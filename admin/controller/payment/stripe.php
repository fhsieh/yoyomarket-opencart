<?php
//==============================================================================
// Stripe Payment Gateway v210.2
// 
// Author: Clear Thinking, LLC
// E-mail: johnathan@getclearthinking.com
// Website: http://www.getclearthinking.com
// 
// All code within this file is copyright Clear Thinking, LLC.
// You may not copy or reuse code within this file without written permission.
//==============================================================================

class ControllerPaymentStripe extends Controller { 
	private $type = 'payment';
	private $name = 'stripe';
	
	public function index() {
		$data = array(
			'type'				=> $this->type,
			'name'				=> $this->name,
			'autobackup'		=> false,
			'save_type'			=> 'keepediting',
			'token'				=> $this->session->data['token'],
			'permission'		=> $this->user->hasPermission('modify', $this->type . '/' . $this->name),
			'exit'				=> $this->url->link('extension/' . $this->type . '&token=' . $this->session->data['token'], '', 'SSL'),
		);
		
		$this->loadSettings($data);
		
		// extension-specific
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "stripe_customer` (
				`customer_id` int(11) NOT NULL,
				`stripe_customer_id` varchar(18) NOT NULL,
				`transaction_mode` varchar(4) NOT NULL DEFAULT 'live',
				PRIMARY KEY (`customer_id`, `stripe_customer_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci
		");
		
		$old_customers_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `" . (version_compare(VERSION, '2.0.1') < 0 ? 'group' : 'code') . "` = 'stripe_permanent' AND `key` = 'stripe_customers'");
		if ($old_customers_query->num_rows) {
			$stripe_customer_tokens = unserialize($old_customers_query->row['value']);
			foreach ($stripe_customer_tokens as $opencart_id => $stripe_id) {
				$this->db->query("INSERT IGNORE INTO " . DB_PREFIX . "stripe_customer SET customer_id = " . (int)$opencart_id . ", stripe_customer_id = '" . $this->db->escape($stripe_id) . "'");
			}
			$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `" . (version_compare(VERSION, '2.0.1') < 0 ? 'group' : 'code') . "` = 'stripe_permanent'");
		}
		// end
		
		// Convert old settings
		$old_settings_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `key` = '" . $this->name . "_data'");
		if ($old_settings_query->num_rows) {
			$old_settings = unserialize($old_settings_query->row['value']);
			foreach ($old_settings as $key => $value) {
				if (is_array($value)) {
					if (is_int(key($value))) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `" . (version_compare(VERSION, '2.0.1') < 0 ? 'group' : 'code') . "` = '" . $this->name . "', `key` = '" . $this->name . "_" . $key . "', `value` = '" . implode(';', $value) . "'");
					} else {
						foreach ($value as $value_key => $value_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `" . (version_compare(VERSION, '2.0.1') < 0 ? 'group' : 'code') . "` = '" . $this->name . "', `key` = '" . $this->name . "_" . $key . "_" . $value_key . "', `value` = '" . $value_value . "'");
						}
					}
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `" . (version_compare(VERSION, '2.0.1') < 0 ? 'group' : 'code') . "` = '" . $this->name . "', `key` = '" . $this->name . "_" . $key . "', `value` = '" . $value . "'");
				}
			}
			$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `key` = '" . $this->name . "_data'");
		}
		
		//------------------------------------------------------------------------------
		// Data Arrays
		//------------------------------------------------------------------------------
		$this->load->model('localisation/language');
		$data['language_array'] = array($this->config->get('config_language') => '');
		$data['language_flags'] = array();
		foreach ($this->model_localisation_language->getLanguages() as $language) {
			$data['language_array'][$language['code']] = $language['name'];
			$data['language_flags'][$language['code']] = $language['image'];
		}
		
		$data['order_status_array'] = array(0 => $data['text_ignore']);
		$this->load->model('localisation/order_status');
		foreach ($this->model_localisation_order_status->getOrderStatuses() as $order_status) {
			$data['order_status_array'][$order_status['order_status_id']] = $order_status['name'];
		}
		
		$data['customer_group_array'] = array(0 => $data['text_guests']);
		$this->load->model((version_compare(VERSION, '2.1', '<') ? 'sale' : 'customer') . '/customer_group');
		foreach ($this->{'model_' . (version_compare(VERSION, '2.1', '<') ? 'sale' : 'customer') . '_customer_group'}->getCustomerGroups() as $customer_group) {
			$data['customer_group_array'][$customer_group['customer_group_id']] = $customer_group['name'];
		}
		
		$data['geo_zone_array'] = array(0 => $data['text_everywhere_else']);
		$this->load->model('localisation/geo_zone');
		foreach ($this->model_localisation_geo_zone->getGeoZones() as $geo_zone) {
			$data['geo_zone_array'][$geo_zone['geo_zone_id']] = $geo_zone['name'];
		}
		
		$data['store_array'] = array(0 => $this->config->get('config_name'));
		$store_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store ORDER BY name");
		foreach ($store_query->rows as $store) {
			$data['store_array'][$store['store_id']] = $store['name'];
		}
		
		$data['currency_array'] = array($this->config->get('config_currency') => '');
		$this->load->model('localisation/currency');
		foreach ($this->model_localisation_currency->getCurrencies() as $currency) {
			$data['currency_array'][$currency['code']] = $currency['code'];
		}
		
		// Get subscription products
		$data['subscription_products'] = array();
		
		if (!empty($data['saved']['subscriptions']) &&
			!empty($data['saved']['transaction_mode']) &&
			!empty($data['saved'][$data['saved']['transaction_mode'].'_secret_key'])
		) {
			$plan_response = $this->curlRequest('plans', array('count' => 100));
			
			if (!empty($plan_response['error'])) {
				$this->log->write('STRIPE ERROR: ' . $plan_response['error']['message']);
			} else {
				foreach ($plan_response['data'] as $plan) {
					$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id AND pd.language_id = " . (int)$this->config->get('config_language_id') . ") WHERE p.location = '" . $this->db->escape($plan['id']) . "'");
					foreach ($product_query->rows as $product) {
						$data['subscription_products'][] = array(
							'product_id'	=> $product['product_id'],
							'name'			=> $product['name'],
							'price'			=> $this->currency->format($product['price']),
							'location'		=> $product['location'],
							'plan'			=> $plan['name'],
							'interval'		=> $plan['interval_count'] . ' ' . $plan['interval'] . ($plan['interval_count'] > 1 ? 's' : ''),
							'charge'		=> $this->currency->format($plan['amount'] / (strtoupper($plan['currency']) == 'JPY' ? 1 : 100), strtoupper($plan['currency']), 1)
						);
					}
				}
			}
		}
		
		//------------------------------------------------------------------------------
		// Extensions Settings
		//------------------------------------------------------------------------------
		$data['settings'] = array();
		$data['settings'][] = array(
			'type'		=> 'tabs',
			'tabs'		=> array('extension_settings', 'order_statuses', 'restrictions', 'stripe_settings', 'stripe_checkout', 'subscription_products'),
		);
		$data['settings'][] = array(
			'key'		=> 'extension_settings',
			'type'		=> 'heading',
		);
		$data['settings'][] = array(
			'key'		=> 'status',
			'type'		=> 'select',
			'options'	=> array(1 => $data['text_enabled'], 0 => $data['text_disabled']),
			'default'	=> 1,
		);
		$data['settings'][] = array(
			'key'		=> 'sort_order',
			'type'		=> 'text',
			'default'	=> '1',
			'attributes'=> array('style' => 'width: 50px !important'),
		);
		$data['settings'][] = array(
			'key'		=> 'title',
			'type'		=> 'multilingual_text',
			'default'	=> 'Credit / Debit Card',
		);
		$data['settings'][] = array(
			'key'		=> 'button_text',
			'type'		=> 'multilingual_text',
			'default'	=> 'Confirm Order',
		);
		$data['settings'][] = array(
			'key'		=> 'button_class',
			'type'		=> 'text',
			'default'	=> (version_compare(VERSION, '2.0') < 0) ? 'button' : 'btn btn-primary',
		);
		$data['settings'][] = array(
			'key'		=> 'button_styling',
			'type'		=> 'text',
		);
		
		//------------------------------------------------------------------------------
		// Order Statuses
		//------------------------------------------------------------------------------
		$data['settings'][] = array(
			'key'		=> 'order_statuses',
			'type'		=> 'tab',
		);
		$data['settings'][] = array(
			'type'		=> 'html',
			'content'	=> '<div class="text-info text-center" style="padding-bottom: 5px">' . $data['help_order_statuses'] . '</div>',
		);
		$data['settings'][] = array(
			'key'		=> 'order_statuses',
			'type'		=> 'heading',
		);
		
		$complete_status_id = $this->config->get('config_complete_status');
		$data['settings'][] = array(
			'key'		=> 'success_status_id',
			'type'		=> 'select',
			'options'	=> $data['order_status_array'],
			'default'	=> is_array($complete_status_id) ? reset($complete_status_id) : $complete_status_id,
		);
		
		$data['settings'][] = array(
			'key'		=> 'street_status_id',
			'type'		=> 'select',
			'options'	=> $data['order_status_array'],
		);
		$data['settings'][] = array(
			'key'		=> 'zip_status_id',
			'type'		=> 'select',
			'options'	=> $data['order_status_array'],
		);
		$data['settings'][] = array(
			'key'		=> 'cvc_status_id',
			'type'		=> 'select',
			'options'	=> $data['order_status_array'],
		);
		$data['settings'][] = array(
			'key'		=> 'refund_status_id',
			'type'		=> 'select',
			'options'	=> $data['order_status_array'],
		);
		$data['settings'][] = array(
			'key'		=> 'partial_status_id',
			'type'		=> 'select',
			'options'	=> $data['order_status_array'],
		);
		
		//------------------------------------------------------------------------------
		// Restrictions
		//------------------------------------------------------------------------------
		$data['settings'][] = array(
			'key'		=> 'restrictions',
			'type'		=> 'tab',
		);
		$data['settings'][] = array(
			'type'		=> 'html',
			'content'	=> '<div class="text-info text-center" style="padding-bottom: 5px">' . $data['help_restrictions'] . '</div>',
		);
		$data['settings'][] = array(
			'key'		=> 'restrictions',
			'type'		=> 'heading',
		);
		$data['settings'][] = array(
			'key'		=> 'min_total',
			'type'		=> 'text',
			'attributes'=> array('style' => 'width: 50px !important'),
			'default'	=> '0.50',
		);
		$data['settings'][] = array(
			'key'		=> 'max_total',
			'type'		=> 'text',
			'attributes'=> array('style' => 'width: 50px !important'),
		);
		$data['settings'][] = array(
			'key'		=> 'stores',
			'type'		=> 'checkboxes',
			'options'	=> $data['store_array'],
			'default'	=> array_keys($data['store_array']),
		);
		$data['settings'][] = array(
			'key'		=> 'geo_zones',
			'type'		=> 'checkboxes',
			'options'	=> $data['geo_zone_array'],
			'default'	=> array_keys($data['geo_zone_array']),
		);
		$data['settings'][] = array(
			'key'		=> 'customer_groups',
			'type'		=> 'checkboxes',
			'options'	=> $data['customer_group_array'],
			'default'	=> array_keys($data['customer_group_array']),
		);

		//------------------------------------------------------------------------------
		// Stripe Settings
		//------------------------------------------------------------------------------
		$data['settings'][] = array(
			'key'		=> 'stripe_settings',
			'type'		=> 'tab',
		);
		$data['settings'][] = array(
			'type'		=> 'html',
			'content'	=> '<div class="text-info text-center" style="padding-bottom: 5px">' . $data['help_stripe_settings'] . '</div>',
		);
		$data['settings'][] = array(
			'key'		=> 'stripe_settings',
			'type'		=> 'heading',
		);
		$data['settings'][] = array(
			'key'		=> 'test_secret_key',
			'type'		=> 'text',
			'attributes'=> array('onchange' => '$(this).val($(this).val().trim())', 'style' => 'width: 350px !important'),
		);
		$data['settings'][] = array(
			'key'		=> 'test_publishable_key',
			'type'		=> 'text',
			'attributes'=> array('onchange' => '$(this).val($(this).val().trim())', 'style' => 'width: 350px !important'),
		);
		$data['settings'][] = array(
			'key'		=> 'live_secret_key',
			'type'		=> 'text',
			'attributes'=> array('onchange' => '$(this).val($(this).val().trim())', 'style' => 'width: 350px !important'),
		);
		$data['settings'][] = array(
			'key'		=> 'live_publishable_key',
			'type'		=> 'text',
			'attributes'=> array('onchange' => '$(this).val($(this).val().trim())', 'style' => 'width: 350px !important'),
		);
		unset($data['saved']['webhook_url']);
		$data['settings'][] = array(
			'key'		=> 'webhook_url',
			'type'		=> 'text',
			'default'	=> str_replace('http:', 'https:', HTTP_CATALOG) . 'index.php?route=' . $this->type . '/' . $this->name . '/webhook&key=' . $this->config->get('config_encryption'),
			'attributes'=> array('readonly' => 'readonly', 'onclick' => 'this.select()', 'style' => 'background: #EEE; cursor: pointer; font-family: monospace; width: 100% !important;'),
		);
		$data['settings'][] = array(
			'key'		=> 'transaction_mode',
			'type'		=> 'select',
			'options'	=> array('test' => $data['text_test'], 'live' => $data['text_live']),
		);
		$data['settings'][] = array(
			'key'		=> 'charge_mode',
			'type'		=> 'select',
			'options'	=> array('authorize' => $data['text_authorize'], 'capture' => $data['text_capture'], 'fraud' => $data['text_fraud_authorize']),
			'default'	=> 'capture',
		);
		$data['settings'][] = array(
			'key'		=> 'send_customer_data',
			'type'		=> 'select',
			'options'	=> array('never' => $data['text_never'], 'choice' => $data['text_customers_choice'], 'always' => $data['text_always']),
		);
		$data['settings'][] = array(
			'key'		=> 'allow_stored_cards',
			'type'		=> 'select',
			'options'	=> array(0 => $data['text_no'], 1 => $data['text_yes']),
		);
		$data['settings'][] = array(
			'key'		=> 'transaction_description',
			'type'		=> 'text',
			'default'	=> '[store]: Order #[order_id] ([amount], [email])',
		);
		
		// Currency Settings
		$data['settings'][] = array(
			'key'		=> 'currency_settings',
			'type'		=> 'heading',
		);
		$data['settings'][] = array(
			'type'		=> 'html',
			'content'	=> '<div class="text-info text-center" style="padding-bottom: 15px">' . $data['help_currency_settings'] . '</div>',
		);
		foreach ($data['currency_array'] as $code => $title) {
			$data['settings'][] = array(
				'key'		=> 'currencies_' . $code,
				'title'		=> str_replace('[currency]', $code, $data['entry_currencies']),
				'type'		=> 'select',
				'options'	=> array_merge(array(0 => $data['text_currency_disabled']), $data['currency_array']),
				'default'	=> $this->config->get('config_currency'),
			);
		}
		
		
		//------------------------------------------------------------------------------
		// Stripe Checkout
		//------------------------------------------------------------------------------
		$data['settings'][] = array(
			'key'		=> 'stripe_checkout',
			'type'		=> 'tab',
		);
		$data['settings'][] = array(
			'type'		=> 'html',
			'content'	=> '<div class="text-info text-center" style="padding-bottom: 5px">' . $data['help_stripe_checkout'] . '</div>',
		);
		$data['settings'][] = array(
			'key'		=> 'stripe_checkout',
			'type'		=> 'heading',
		);
		$data['settings'][] = array(
			'key'		=> 'use_checkout',
			'type'		=> 'select',
			'options'	=> array(0 => $data['text_no'], 1 => $data['text_yes']),
		);
		$data['settings'][] = array(
			'key'		=> 'checkout_remember_me',
			'type'		=> 'select',
			'options'	=> array(1 => $data['text_yes'], 0 => $data['text_no']),
			'default'	=> 1,
		);
		$data['settings'][] = array(
			'key'		=> 'checkout_bitcoin',
			'type'		=> 'select',
			'options'	=> array(1 => $data['text_yes'], 0 => $data['text_no']),
			'default'	=> 0,
		);
		$data['settings'][] = array(
			'key'		=> 'checkout_billing',
			'type'		=> 'select',
			'options'	=> array(1 => $data['text_yes'], 0 => $data['text_no']),
			'default'	=> 1,
		);
		$data['settings'][] = array(
			'key'		=> 'checkout_shipping',
			'type'		=> 'select',
			'options'	=> array(1 => $data['text_yes'], 0 => $data['text_no']),
			'default'	=> 0,
		);
		$data['settings'][] = array(
			'key'		=> 'checkout_image',
			'type'		=> 'text',
			'before'	=> HTTP_CATALOG . 'image/',
			'default'	=> (version_compare(VERSION, '2.0') < 0) ? 'no_image.jpg' : 'no_image.png',
		);
		$data['settings'][] = array(
			'key'		=> 'checkout_title',
			'type'		=> 'multilingual_text',
			'default'	=> '[store]',
		);
		$data['settings'][] = array(
			'key'		=> 'checkout_description',
			'type'		=> 'multilingual_text',
			'default'	=> 'Order #[order_id] ([amount])',
		);
		$data['settings'][] = array(
			'key'		=> 'checkout_button',
			'type'		=> 'multilingual_text',
			'default'	=> 'Pay [amount]',
		);
		$data['settings'][] = array(
			'key'		=> 'quick_checkout',
			'type'		=> 'html',
			'content'	=> '
				<div class="well" style="padding: 10px">
					You can add a "quick checkout" feature to your store by placing the Stripe Checkout button on other pages besides the checkout confirm page. The customer can enter their e-mail, payment address, shipping address, and credit card details all through the pop-up, and an order will be properly created in OpenCart.<br /><br /><strong>You must only use this on SSL-enabled (https) pages.</strong> To see an example of how to do this on the cart page, <a href="#quick-checkout-modal" data-toggle="modal">click here</a>.
					<div id="quick-checkout-modal" class="modal fade" style="text-align: left">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<a class="close" data-dismiss="modal">&times;</a>
									<h4 class="modal-title">Quick Checkout Example</h4>
								</div>
								<div class="modal-body">
									In the default theme, you would use these edits to add a Quick Checkout button in place of the regular "Checkout" button on the cart page:<br /><br />
									<pre style="white-space: pre-line">
									IN:
									/catalog/controller/checkout/cart.php

									AFTER:
									public function index() {
										
									ADD:
									' . (version_compare(VERSION, '2.0') < 0 ? '$this->data[\'quick_checkout_button\'] = $this->getChild(\'payment/stripe/embed\');' : '$data[\'quick_checkout_button\'] = $this->load->controller(\'payment/stripe/embed\');') . '
									</pre>
									<br />
									<pre style="white-space: pre-line">
									IN:
									/catalog/view/theme/default/template/checkout/cart.tpl

									REPLACE THE CODE BLOCK:
									&lt;div class="buttons"&gt;
									   ...
									&lt;/div&gt;

									WITH:
									&lt;?php echo $quick_checkout_button; ?&gt;
									</pre>
								</div>
								<div class="modal-footer">
									<a href="#" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			',
		);
				
		//------------------------------------------------------------------------------
		// Subscription Products
		//------------------------------------------------------------------------------
		$data['settings'][] = array(
			'key'		=> 'subscription_products',
			'type'		=> 'tab',
		);
		$data['settings'][] = array(
			'type'		=> 'html',
			'content'	=> '<div class="text-info text-center" style="padding-bottom: 5px">' . $data['help_subscription_products'] . '</div>',
		);
		$data['settings'][] = array(
			'key'		=> 'subscription_products',
			'type'		=> 'heading',
		);
		$data['settings'][] = array(
			'key'		=> 'subscriptions',
			'type'		=> 'select',
			'options'	=> array(1 => $data['text_yes'], 0 => $data['text_no']),
			'default'	=> 0,
		);
		$data['settings'][] = array(
			'key'		=> 'prevent_guests',
			'type'		=> 'select',
			'options'	=> array(1 => $data['text_yes'], 0 => $data['text_no']),
			'default'	=> 0,
		);
		
		$subscription_products_table = '
			<div class="form-group">
				<label class="control-label col-sm-3">' . str_replace('[transaction_mode]', ucwords(isset($data['saved']['transaction_mode']) ? $data['saved']['transaction_mode'] : 'test'), $data['entry_current_subscriptions']) . '</label>
				<div class="col-sm-9">
					<table class="table table-stripe table-bordered">
						<thead>
							<tr>
								<td colspan="3" style="text-align: center">' . $data['text_thead_opencart'] . '</td>
								<td colspan="3" style="text-align: center">' . $data['text_thead_stripe'] . '</td>
							</tr>
							<tr>
								<td class="left">' . $data['text_product_name'] . '</td>
								<td class="left">' . $data['text_product_price'] . '</td>
								<td class="left">' . $data['text_location_plan_id'] . '</td>
								<td class="left">' . $data['text_plan_name'] . '</td>
								<td class="left">' . $data['text_plan_interval'] . '</td>
								<td class="left">' . $data['text_plan_charge'] . '</td>
							</tr>
						</thead>
		';
		if (empty($data['subscription_products'])) {
			$subscription_products_table .= '
				<tr><td class="center" colspan="6">' . $data['text_no_subscription_products'] . '</td></tr>
				<tr><td class="center" colspan="6">' . $data['text_create_one_by_entering'] . '</td></tr>
			';
		}
		foreach ($data['subscription_products'] as $product) {
			$highlight = ($product['price'] == $product['charge']) ? '' : 'style="background: #FDD"';
			$subscription_products_table .= '
				<tr>
					<td class="left"><a target="_blank" href="index.php?route=catalog/product/' . (version_compare(VERSION, '2.0') < 0 ? 'update' : 'edit') . '&amp;product_id=' . $product['product_id'] . '&amp;token=' . $data['token'] . '">' . $product['name'] . '</a></td>
					<td class="left" ' . $highlight . '>' . $product['price'] . '</td>
					<td class="left">' . $product['location'] . '</td>
					<td class="left">' . $product['plan'] . '</td>
					<td class="left">' . $product['interval'] . '</td>
					<td class="left" ' . $highlight . '>' . $product['charge'] . '</td>
				</tr>
			';
		}
		$subscription_products_table .= '</table>';
		
		$data['settings'][] = array(
			'type'		=> 'html',
			'content'	=> $subscription_products_table,
		);
		
		//------------------------------------------------------------------------------
		// end settings
		//------------------------------------------------------------------------------
		
		$this->document->setTitle($data['heading_title']);
		
		if (version_compare(VERSION, '2.0') < 0) {
			$this->data = $data;
			$this->template = $this->type . '/' . $this->name . '.tpl';
			$this->children = array(
				'common/header',	
				'common/footer',
			);
			$this->response->setOutput($this->render());
		} else {
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			$this->response->setOutput($this->load->view($this->type . '/' . $this->name . '.tpl', $data));
		}
	}
	
	//==============================================================================
	// Setting functions
	//==============================================================================
	private $encryption_key = '';
	private $columns = 7;
	
	private function getTableRowNumbers($data, $table, $sorting) {
		$groups = array();
		$rules = array();
		
		foreach ($data['saved'] as $key => $setting) {
			if (preg_match('/' . $table . '_(\d+)_' . $sorting . '/', $key, $matches)) {
				$groups[$setting][] = $matches[1];
			}
			if (preg_match('/' . $table . '_(\d+)_rule_(\d+)_type/', $key, $matches)) {
				$rules[$matches[1]][] = $matches[2];
			}
		}
		
		if (empty($groups)) {
			$groups = array('' => array('1'));
		}
		ksort($groups, SORT_STRING);
		
		$rows = array();
		foreach ($groups as $group) {
			foreach ($group as $num) {
				$rows[$num] = (empty($rules[$num])) ? array() : $rules[$num];
			}
		}
		
		return $rows;
	}
	
	public function loadSettings(&$data) {
		$backup_type = (empty($data)) ? 'manual' : 'auto';
		if ($backup_type == 'manual' && !$this->user->hasPermission('modify', $this->type . '/' . $this->name)) {
			return;
		}
		
		// Load saved settings
		$data['saved'] = array();
		$settings_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `" . (version_compare(VERSION, '2.0.1') < 0 ? 'group' : 'code') . "` = '" . $this->db->escape($this->name) . "' ORDER BY `key` ASC");
		
		foreach ($settings_query->rows as $setting) {
			$key = str_replace($this->name . '_', '', $setting['key']);
			$value = $setting['value'];
			if ($setting['serialized']) {
				$value = (version_compare(VERSION, '2.1', '<')) ? unserialize($setting['value']) : json_decode($setting['value'], true);
			}
			
			$data['saved'][$key] = $value;
			
			if (is_array($value)) {
				foreach ($value as $num => $value_array) {
					foreach ($value_array as $k => $v) {
						$data['saved'][$key . '_' . $num . '_' . $k] = $v;
					}
				}
			}
		}
		
		// Load language and check max_input _vars
		$data = array_merge($data, $this->load->language($this->type . '/' . $this->name));
		
		if (ini_get('max_input_vars') && ((ini_get('max_input_vars') - count($data['saved'])) < 50)) {
			$data['warning'] = $data['standard_warning'];
		}
		
		// Set save type
		if (!empty($data['saved']['autosave'])) {
			$data['save_type'] = 'auto';
		}
		
		// Skip auto-backup if not needed
		if ($backup_type == 'auto' && empty($data['autobackup'])) {
			return;
		}
		
		// Create settings auto-backup file
		$manual_filepath = DIR_LOGS . $this->name . '_backup' . $this->encryption_key . '.txt';
		$auto_filepath = DIR_LOGS . $this->name . '_autobackup' . $this->encryption_key . '.txt';
		$filepath = ($backup_type == 'auto') ? $auto_filepath : $manual_filepath;
		if (file_exists($filepath)) unlink($filepath);
		
		if ($this->columns == 3) {
			file_put_contents($filepath, 'EXTENSION	SETTING	VALUE' . "\n", FILE_APPEND|LOCK_EX);
		} elseif ($this->columns == 5) {
			file_put_contents($filepath, 'EXTENSION	SETTING	NUMBER	SUB-SETTING	VALUE' . "\n", FILE_APPEND|LOCK_EX);
		} else {
			file_put_contents($filepath, 'EXTENSION	SETTING	NUMBER	SUB-SETTING	SUB-NUMBER	SUB-SUB-SETTING	VALUE' . "\n", FILE_APPEND|LOCK_EX);
		}
		
		foreach ($data['saved'] as $key => $value) {
			if (is_array($value)) continue;
			
			$parts = explode('|', preg_replace(array('/_(\d+)_/', '/_(\d+)/'), array('|$1|', '|$1'), $key));
			
			$line = $this->name . "\t" . $parts[0] . "\t";
			for ($i = 1; $i < $this->columns - 2; $i++) {
				$line .= (isset($parts[$i]) ? $parts[$i] : '') . "\t";
			}
			$line .= str_replace(array("\t", "\n"), array('    ', '\n'), $value) . "\n";
			
			file_put_contents($filepath, $line, FILE_APPEND|LOCK_EX);
		}
		
		$data['autobackup_time'] = date('Y-M-d @ g:i a');
		$data['backup_time'] = (file_exists($manual_filepath)) ? date('Y-M-d @ g:i a', filemtime($manual_filepath)) : '';
		
		if ($backup_type == 'manual') {
			echo $data['autobackup_time'];
		}
	}
	
	public function saveSettings() {
		if (!$this->user->hasPermission('modify', $this->type . '/' . $this->name)) {
			echo 'PermissionError';
			return;
		}
		
		if ($this->request->get['saving'] == 'manual') {
			$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `" . (version_compare(VERSION, '2.0.1') < 0 ? 'group' : 'code') . "` = '" . $this->db->escape($this->name) . "' AND `key` != '" . $this->db->escape($this->name . '_module') . "'");
		}
		
		$modules = array();
		foreach ($this->request->post as $key => $value) {
			if (strpos($key, 'module_') === 0) {
				$parts = explode('_', $key, 3);
				$modules[$parts[1]][$parts[2]] = $value;
			} else {
				if ($this->request->get['saving'] == 'auto') {
					$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `" . (version_compare(VERSION, '2.0.1') < 0 ? 'group' : 'code') . "` = '" . $this->db->escape($this->name) . "' AND `key` = '" . $this->db->escape($this->name . '_' . $key) . "'");
				}
				$this->db->query("
					INSERT INTO " . DB_PREFIX . "setting SET
					`store_id` = 0,
					`" . (version_compare(VERSION, '2.0.1') < 0 ? 'group' : 'code') . "` = '" . $this->db->escape($this->name) . "',
					`key` = '" . $this->db->escape($this->name . '_' . $key) . "',
					`value` = '" . $this->db->escape(stripslashes(is_array($value) ? implode(';', $value) : $value)) . "',
					`serialized` = 0
				");
			}
		}
		
		if (version_compare(VERSION, '2.0.1') < 0) {
			$module_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `" . (version_compare(VERSION, '2.0.1') < 0 ? 'group' : 'code') . "` = '" . $this->db->escape($this->name) . "'AND `key` = '" . $this->db->escape($this->name . '_module') . "'");
			if ($module_query->num_rows) {
				foreach (unserialize($module_query->row['value']) as $key => $value) {
					foreach ($value as $k => $v) {
						if (!isset($modules[$key][$k])) $modules[$key][$k] = $v;
					}
				}
			}
			
			if (isset($modules[0])) {
				$index = 1;
				while (isset($modules[$index])) {
					$index++;
				}
				$modules[$index] = $modules[0];
				unset($modules[0]);
			}
			
			$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `" . (version_compare(VERSION, '2.0.1') < 0 ? 'group' : 'code') . "` = '" . $this->db->escape($this->name) . "'AND `key` = '" . $this->db->escape($this->name . '_module') . "'");
			$this->db->query("
				INSERT INTO " . DB_PREFIX . "setting SET
				`store_id` = 0,
				`" . (version_compare(VERSION, '2.0.1') < 0 ? 'group' : 'code') . "` = '" . $this->db->escape($this->name) . "',
				`key` = '" . $this->db->escape($this->name . '_module') . "',
				`value` = '" . $this->db->escape(serialize($modules)) . "',
				`serialized` = 1
			");
		} else {
			foreach ($modules as $module_id => $module) {
				$module_settings = (version_compare(VERSION, '2.1', '<')) ? serialize($module) : json_encode($module);
				if ($module_id) {
					$this->db->query("
						UPDATE " . DB_PREFIX . "module SET
						`name` = '" . $this->db->escape($module['name']) . "',
						`code` = '" . $this->db->escape($this->name) . "',
						`setting` = '" . $this->db->escape($module_settings) . "'
						WHERE module_id = " . (int)$module_id . "
					");
				} else {
					$this->db->query("
						INSERT INTO " . DB_PREFIX . "module SET
						`name` = '" . $this->db->escape($module['name']) . "',
						`code` = '" . $this->db->escape($this->name) . "',
						`setting` = '" . $this->db->escape($module_settings) . "'
					");
				}
			}
		}
	}
	
	public function deleteSetting() {
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `" . (version_compare(VERSION, '2.0.1') < 0 ? 'group' : 'code') . "` = '" . $this->db->escape($this->name) . "' AND `key` = '" . $this->db->escape($this->name . '_' . str_replace('[]', '', $this->request->get['setting'])) . "'");
	}
	
	//==============================================================================
	// Custom functions
	//==============================================================================
	private function curlRequest($api, $data = array(), $type = 'GET') {
		if ($type == 'POST') {
			$curl = curl_init('https://api.stripe.com/v1/' . $api);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
		} else {
			$curl = curl_init('https://api.stripe.com/v1/' . $api . '?' . http_build_query($data));
		}
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Stripe-Version: 2015-09-08'));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_USERPWD, $this->config->get($this->name . '_' . $this->config->get($this->name . '_transaction_mode') . '_secret_key') . ':');
		$response = json_decode(curl_exec($curl), true);
		
		if (curl_error($curl)) {
			$response = array('error' => array('message' => 'CURL ERROR #' . curl_errno($curl) . ': ' . curl_error($curl)));
			$this->log->write('STRIPE CURL ERROR #' . curl_errno($curl) . ': ' . curl_error($curl));	
		} elseif (empty($response)) {
			$response = array('error' => array('message' => 'CURL ERROR: Empty Gateway Response'));
			$this->log->write('STRIPE CURL ERROR: Empty Gateway Response');
		}
		curl_close($curl);
		
		return $response;
	}
	
	public function capture() {
		$capture_response = $this->curlRequest('charges/' . $this->request->get['charge_id'] . '/capture', array(), 'POST');
		if (!empty($capture_response['error'])) {
			$this->log->write('STRIPE ERROR: ' . $capture_response['error']['message']);
			echo 'Error: ' . $capture_response['error']['message'];
		}
		if (empty($capture_response['error']) || strpos($capture_response['error']['message'], 'has already been captured')) {
			$this->db->query("UPDATE " . DB_PREFIX . "order_history SET `comment` = REPLACE(`comment`, '<span>No &nbsp;</span> <a onclick=\"capture($(this), \'" . $this->db->escape($this->request->get['charge_id']) . "\')\">(Capture)</a>', 'Yes') WHERE `comment` LIKE '%capture($(this), \'" . $this->db->escape($this->request->get['charge_id']) . "\')%'");
		}
	}
	
	public function refund() {
		$refund_response = $this->curlRequest('charges/' . $this->request->get['charge_id'] . '/refund', array('amount' => (int)($this->request->get['amount'] * 100)), 'POST');
		if (!empty($refund_response['error'])) {
			$this->log->write('STRIPE ERROR: ' . $refund_response['error']['message']);
			echo 'Error: ' . $refund_response['error']['message'];
		}
	}
}
?>