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
	private $embed = false;
	
    public function embed() {
		$this->embed = true;
		if (version_compare(VERSION, '2.0', '<')) {
			$this->index();
		} else {
			return $this->index();
		}
    }
	
	public function index() {
		$data['type'] = $this->type;
		$data['name'] = $this->name;
		$data['embed'] = $this->embed;
		
		$data['settings'] = $settings = $this->getSettings();
		$data['language'] = $this->session->data['language'];
		$data['currency'] = $this->session->data['currency'];
		
		$data = array_merge($data, $this->load->language($this->type . '/' . $this->name));
		
		$this->load->model('checkout/order');
		if (isset($this->session->data['order_id'])) {
			$data['order_info'] = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		} else {
			$order_totals_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = 'total' ORDER BY `code` ASC");
			$order_totals = $order_totals_query->rows;
			
			$sort_order = array();
			foreach ($order_totals as $key => $value) {
				$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
			}
			array_multisort($sort_order, SORT_ASC, $order_totals);
			
			$total_data = array();
			$order_total = 0;
			$taxes = $this->cart->getTaxes();
			
			foreach ($order_totals as $ot) {
				if (!$this->config->get($ot['code'] . '_status')) continue;
				$this->load->model('total/' . $ot['code']);
				$this->{'model_total_' . $ot['code']}->getTotal($total_data, $order_total, $taxes);
			}
			
			$data['order_info'] = array(
				'order_id'	=> '',
				'total'		=> $order_total,
				'email'		=> $this->customer->getEmail(),
				'comment'	=> '',
			);
		}
		
		$data['customer'] = array();
		$data['logged_in'] = $this->customer->isLogged();
		
		if ($this->customer->isLogged()) {
			$customer_id_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "stripe_customer WHERE customer_id = " . (int)$this->customer->getId() . " AND transaction_mode = '" . $this->db->escape($settings['transaction_mode']) . "'");
			if ($customer_id_query->num_rows) {
				$customer_response = $this->curlRequest('customers/' . $customer_id_query->row['stripe_customer_id'], array('expand' => array('' => 'default_source')));
				if (!empty($customer_response['deleted'])) {
					$this->db->query("DELETE FROM " . DB_PREFIX . "stripe_customer WHERE stripe_customer_id = '" . $this->db->escape($customer_id_query->row['stripe_customer_id']) . "'");
				} elseif (!empty($customer_response['error'])) {
					$this->log->write(strtoupper($this->name) . ' ERROR: ' . $customer_response['error']['message']);
				} elseif ($data['settings']['allow_stored_cards']) {
					$data['customer'] = $customer_response;
				}
			}
		}
		
		$data['no_shipping_method'] = empty($this->session->data['shipping_method']);
		
		$data['checkout_image'] = (!empty($settings['checkout_image'])) ? HTTPS_SERVER . 'image/' . $settings['checkout_image'] : '';
		$data['checkout_title'] = (!empty($settings['checkout_title_' . $data['language']])) ? $this->replaceShortcodes($settings['checkout_title_' . $data['language']], $data['order_info']) : $this->config->get('config_name');
		$data['checkout_description'] = (!empty($settings['checkout_description_' . $data['language']])) ? $this->replaceShortcodes($settings['checkout_description_' . $data['language']], $data['order_info']) : '';
		$data['checkout_amount'] = round(($data['currency'] == 'JPY' ? 1 : 100) * $this->currency->convert($data['order_info']['total'], $this->config->get('config_currency'), $data['currency']));
		$data['checkout_button'] = (!empty($settings['checkout_button_' . $data['language']])) ? $settings['checkout_button_' . $data['language']] : '';
		
		$template = (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/' . $this->type . '/' . $this->name . '.tpl')) ? $this->config->get('config_template') : 'default';
		$template_file = $template . '/template/' . $this->type . '/' . $this->name . '.tpl';
		
		if (version_compare(VERSION, '2.0', '<')) {
			$this->data = $data;
			$this->template = $template_file;
			$this->render();
		} else {
			return $this->load->view($template_file, $data);
		}
	}
	
	public function send() {
		$settings = $this->getSettings();
		
		$language_data = $this->load->language('total/total');
		$language_data = array_merge($language_data, $this->load->language($this->type . '/' . $this->name));
		
		$this->load->model('checkout/order');
		
		// Create order if necessary
		if (!empty($this->request->post['embed'])) {
			$data = array();
			
			$data['customer_id'] = (int)$this->customer->getId();
			$data['email'] = $this->request->post['email'];
			
			if ($settings['checkout_billing']) {
				$payment_name = explode(' ', $this->request->post['addresses']['billing_name'], 2);
				$payment_country = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE iso_code_2 = '" . $this->db->escape($this->request->post['addresses']['billing_address_country_code']) . "'");
				$payment_zone = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE `name` = '" . $this->db->escape($this->request->post['addresses']['billing_address_state']) . "' AND country_id = " . (int)$payment_country->row['country_id']);
				
				$data['firstname'] = (isset($payment_name[0])) ? $payment_name[0] : '';
				$data['lastname'] = (isset($payment_name[1])) ? $payment_name[1] : '';
				
				$data['payment_firstname'] = (isset($payment_name[0])) ? $payment_name[0] : '';
				$data['payment_lastname'] = (isset($payment_name[1])) ? $payment_name[1] : '';
				$data['payment_company'] = '';
				$data['payment_company_id'] = '';
				$data['payment_tax_id'] = '';
				$data['payment_address_1'] = $this->request->post['addresses']['billing_address_line1'];
				$data['payment_address_2'] = '';
				$data['payment_city'] = $this->request->post['addresses']['billing_address_city'];
				$data['payment_postcode'] = $this->request->post['addresses']['billing_address_zip'];
				$data['payment_zone'] = $this->request->post['addresses']['billing_address_state'];
				$data['payment_zone_id'] = (isset($payment_zone->row['zone_id'])) ? $payment_zone->row['zone_id'] : '';
				$data['payment_country'] = $this->request->post['addresses']['billing_address_country'];
				$data['payment_country_id'] = (isset($payment_country->row['country_id'])) ? $payment_country->row['country_id'] : '';
			}
			
			if ($settings['checkout_shipping']) {
				if (isset($this->session->data['country_id'])) {
					$shipping_quote = array(
						'country_id'	=> $this->session->data['country_id'],
						'zone_id'		=> $this->session->data['zone_id'],
						'postcode'		=> $this->session->data['postcode'],
					);
				} elseif (isset($this->session->data['guest']['shipping']['country_id'])) {
					$shipping_quote = array(
						'country_id'	=> $this->session->data['guest']['shipping']['country_id'],
						'zone_id'		=> $this->session->data['guest']['shipping']['zone_id'],
						'postcode'		=> $this->session->data['guest']['shipping']['postcode'],
					);
				} elseif (isset($this->session->data['shipping_country_id'])) {
					$shipping_quote = array(
						'country_id'	=> $this->session->data['shipping_country_id'],
						'zone_id'		=> $this->session->data['shipping_zone_id'],
						'postcode'		=> $this->session->data['shipping_postcode'],
					);
				} else {
					$shipping_quote = array(
						'country_id'	=> $this->session->data['shipping_address']['country_id'],
						'zone_id'		=> $this->session->data['shipping_address']['zone_id'],
						'postcode'		=> $this->session->data['shipping_address']['postcode'],
					);
				}
				
				$shipping_name = explode(' ', $this->request->post['addresses']['shipping_name'], 2);
				$shipping_country = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE iso_code_2 = '" . $this->db->escape($this->request->post['addresses']['shipping_address_country_code']) . "'");
				$shipping_zone = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE `code` = '" . $this->db->escape($this->request->post['addresses']['shipping_address_state']) . "' AND country_id = " . (int)$shipping_country->row['country_id']);
				
				if ($shipping_quote['country_id'] != $shipping_country->row['country_id'] || 
					$shipping_quote['zone_id'] != $shipping_zone->row['zone_id'] || 
					$shipping_quote['postcode'] != $this->request->post['addresses']['shipping_address_zip']
				) {
					$this->response->setOutput(json_encode(array('error' => $language_data['error_shipping_mismatch'])));
					return;
				}
				
				$data['shipping_firstname'] = (isset($shipping_name[0])) ? $shipping_name[0] : '';
				$data['shipping_lastname'] = (isset($shipping_name[1])) ? $shipping_name[1] : '';
				$data['shipping_company'] = '';
				$data['shipping_company_id'] = '';
				$data['shipping_tax_id'] = '';
				$data['shipping_address_1'] = $this->request->post['addresses']['shipping_address_line1'];
				$data['shipping_address_2'] = '';
				$data['shipping_city'] = $this->request->post['addresses']['shipping_address_city'];
				$data['shipping_postcode'] = $this->request->post['addresses']['shipping_address_zip'];
				$data['shipping_zone'] = $this->request->post['addresses']['shipping_address_state'];
				$data['shipping_zone_id'] = (isset($shipping_zone->row['zone_id'])) ? $shipping_zone->row['zone_id'] : '';
				$data['shipping_country'] = $this->request->post['addresses']['shipping_address_country'];
				$data['shipping_country_id'] = (isset($shipping_country->row['country_id'])) ? $shipping_country->row['country_id'] : '';
			}
			
			$this->load->model($this->type . '/' . $this->name);
			$this->session->data['order_id'] = $this->{'model_'.$this->type.'_'.$this->name}->createOrder($data);
		}
		
		$order_id = $this->session->data['order_id'];
		$order_info = $this->model_checkout_order->getOrder($order_id);
		$data = (!empty($this->request->post['token'])) ? array('source' => $this->request->post['token']) : array();
		
		// Check for subscription plan products
		$customer_id = $this->customer->getId();
		$plans = array();
		
		if (!empty($settings['subscriptions'])) {
			foreach ($this->cart->getProducts() as $product) {
				$product_query = $this->db->query("SELECT location FROM " . DB_PREFIX . "product WHERE product_id = " . (int)$product['product_id']);
				if (empty($product_query->row['location'])) continue;
				
				$plan_response = $this->curlRequest('plans/' . $product_query->row['location']);
				
				if (empty($plan_response['error'])) {
					$plan_tax_rate = $this->tax->getTax($product['total'], $product['tax_class_id']) / $product['total'];
					$plans[] = array(
						'cost'			=> $plan_response['amount'] / 100,
						'taxed_cost'	=> $plan_response['amount'] / 100 * (1 + $plan_tax_rate),
						'tax_percent'	=> $plan_tax_rate * 100,
						'id'			=> $plan_response['id'],
						'name'			=> $plan_response['name'],
						'quantity'		=> $product['quantity'],
					);
					if ($settings['prevent_guests'] && !$customer_id) {
						$this->response->setOutput(json_encode(array('error' => $language_data['error_customer_required'])));
						return;
					}
				}
			}
		}
		
		// Create or update customer
		if (!empty($plans) || !empty($this->request->post['store_card']) || $settings['send_customer_data'] == 'always' || empty($data)) {
			$stripe_customer_id = '';
			if ($this->customer->isLogged()) {
				$customer_id_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "stripe_customer WHERE customer_id = " . (int)$this->customer->getId() . " AND transaction_mode = '" . $this->db->escape($settings['transaction_mode']) . "'");
				if ($customer_id_query->num_rows) {
					$stripe_customer_id = $customer_id_query->row['stripe_customer_id'];
				}
			}
			
			$data['description'] = $order_info['firstname'] . ' ' . $order_info['lastname'] . ' (' . 'customer_id: ' . $order_info['customer_id'] . ')';
			$data['email'] = $order_info['email'];
			
			$customer_response = $this->curlRequest('customers' . ($stripe_customer_id ? '/' . $stripe_customer_id : ''), $data, 'POST');
			
			if (empty($customer_response['error'])) {
				$data = array('customer' => $customer_response['id']);
				if (!$stripe_customer_id) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "stripe_customer SET customer_id = " . (int)$this->customer->getId() . ", stripe_customer_id = '" . $this->db->escape($customer_response['id']) . "', transaction_mode = '" . $this->db->escape($settings['transaction_mode']) . "'");
				}
			} else {
				$this->response->setOutput(json_encode(array('error' => $customer_response['error']['message'])));
				return;
			}
		}
		
		// Subscribe customer to plan
		if (!empty($plans)) {
			foreach ($plans as $plan) {
				$current_quantity = 0;
				
				if (!empty($customer_response['subscriptions'])) {
				 	foreach ($customer_response['subscriptions']['data'] as $subscription) {
						if ($subscription['id'] == $plan['id']) $current_quantity += $subscription['quantity'];
					}
				}
				
				$subscription_data = array(
					'plan'			=> $plan['id'],
					'quantity'		=> $current_quantity + $plan['quantity'],
					'tax_percent'	=> $plan['tax_percent'],
				);
				
				if (isset($this->session->data['coupon'])) {
					$coupon_response = $this->curlRequest('coupons/' . $this->session->data['coupon']);
					if (empty($coupon_response['error'])) {
						$subscription_data['coupon'] = $coupon_response['id'];
					}
				}
				
				$subscription_response = $this->curlRequest('customers/' . $data['customer'] . '/subscriptions', $subscription_data, 'POST');
				
				if (empty($subscription_response['error'])) {
					$order_info['total'] -= $plan['taxed_cost'];
					if (!empty($subscription_response['trial_period_days'])) {
						$this->db->query("UPDATE `" . DB_PREFIX . "order` SET total = " . (float)$order_info['total'] . " WHERE order_id = " . (int)$order_info['order_id']);
						$this->db->query("UPDATE " . DB_PREFIX . "order_total SET text = '" . $this->db->escape($this->currency->format($order_info['total'])) . "', value = " . (float)$order_info['total'] . " WHERE order_id = " . (int)$order_info['order_id'] . " AND title = '" . $this->db->escape($language_data['text_total']) . "'");
						$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = " . (int)$order_info['order_id'] . ", code = 'total', title = '" . $this->db->escape($language_data['text_to_be_charged_separately']) . "', text = '" . $this->db->escape($this->currency->format(-$plan['taxed_cost'])) . "', value = " . (float)-$plan['taxed_cost'] . ", sort_order = " . ((int)$this->config->get('total_sort_order')-1));
					}
				} else {
					$this->response->setOutput(json_encode(array('error' => $subscription_response['error']['message'])));
					return;
				}
			}
		}
		
		// Charge card
		$order_status_id = $settings['success_status_id'];
		if ($order_info['total'] > 0) {
			$data['amount'] = round(($settings['currencies_' . $this->session->data['currency']] == 'JPY' ? 1 : 100) * $this->currency->convert($order_info['total'], $this->config->get('config_currency'), $settings['currencies_' . $this->session->data['currency']]));
			
			$data['capture'] = ($settings['charge_mode'] == 'authorize') ? 'false' : 'true';
			
			if ($settings['charge_mode'] == 'fraud') {
				if (version_compare(VERSION, '2.0.3', '<')) {
					if ($this->config->get('config_fraud_detection')) {
						$this->load->model('checkout/fraud');
						if ($this->model_checkout_fraud->getFraudScore($order_info) > $this->config->get('config_fraud_score')) {
							$data['capture'] = 'false';
						}
					}
				} else {
					$this->load->model('account/customer');
					$customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);
					
					if (empty($customer_info['safe'])) {
						$this->load->model('extension/extension');
						foreach ($this->model_extension_extension->getExtensions('fraud') as $extension) {
							if (!$this->config->get($extension['code'] . '_status')) continue;
							
							$this->load->model('fraud/' . $extension['code']);
							$this->{'model_fraud_' . $extension['code']}->check($order_info);
							
							$fraud_query = $this->db->query("SELECT * FROM " . DB_PREFIX . $extension['code'] . " WHERE order_id = " . (int)$order_info['order_id']);
							if ($fraud_query->row[($extension['code'] == 'maxmind') ? 'risk_score' : 'fraudlabspro_score'] > $this->config->get($extension['code'] . '_score')) {
								$data['capture'] = 'false';
							}
						}
					}
				}
			}
			
			$data['currency'] = $settings['currencies_' . $this->session->data['currency']];
			$data['description'] = $this->replaceShortcodes($settings['transaction_description'], $order_info);
			
			$data['metadata']['Store'] = $this->config->get('config_name');
			$data['metadata']['Order ID'] = $order_info['order_id'];
			$data['metadata']['Customer Info'] = $order_info['firstname'] . ' ' . $order_info['lastname'] . ', ' . $order_info['email'] . ', ' . $order_info['telephone'] . ', customer_id: ' . $order_info['customer_id'];
			$data['metadata']['Products'] = $this->replaceShortcodes('[products]', $order_info);
			if (!empty($order_info['shipping_method'])) {
				$data['metadata']['Shipping Method'] = $order_info['shipping_method'] . ' (' . $this->currency->format($this->session->data['shipping_method']['cost']) . ')';
				$data['metadata']['Shipping Address'] = $order_info['shipping_firstname'] . ' ' . $order_info['shipping_lastname'] . ($order_info['shipping_company'] ? ', ' . $order_info['shipping_company'] : '');
				$data['metadata']['Shipping Address'] .= ', ' . $order_info['shipping_address_1'] . ($order_info['shipping_address_2'] ? ', ' . $order_info['shipping_address_2'] : '');
				$data['metadata']['Shipping Address'] .= ', ' . $order_info['shipping_city'] . ', ' . $order_info['shipping_zone'] . ', ' . $order_info['shipping_postcode'] . ', ' . $order_info['shipping_country'];
			}
			$data['metadata']['Order Comment'] = $order_info['comment'];
			$data['metadata']['IP Address'] = $order_info['ip'];
			foreach ($data['metadata'] as &$metadata) {
				if (strlen($metadata) > 197) {
					$metadata = substr($metadata, 0, 197) . '...';
				}
			}
			
			$charge_response = $this->curlRequest('charges', $data, 'POST');
			
			if (empty($charge_response['error'])) {
				if (isset($charge_response['source']['address_line1_check']) && $charge_response['source']['address_line1_check'] == 'fail' && $settings['street_status_id'])	$order_status_id = $settings['street_status_id'];
				if (isset($charge_response['source']['address_zip_check']) && $charge_response['source']['address_zip_check'] == 'fail' && $settings['zip_status_id'])			$order_status_id = $settings['zip_status_id'];
				if (isset($charge_response['source']['cvc_check']) && $charge_response['source']['cvc_check'] == 'fail' && $settings['cvc_status_id'])							$order_status_id = $settings['cvc_status_id'];
			} else {
				$this->response->setOutput(json_encode(array('error' => $charge_response['error']['message'])));
				return;
			}
		}
		
		// Confirm order
		$strong = '<strong style="display: inline-block; width: 135px; padding: 2px 5px">';
		
		$comment = '';
		if (!empty($plans)) {
			foreach ($plans as $plan) {
				$comment .= $strong . 'Subscribed to Plan:</strong>' . $plan['name'] . '<br />';
				$comment .= $strong . 'Subscription Charge:</strong>' . $this->currency->format($plan['cost'], strtoupper($subscription_response['plan']['currency']), 1);
				if ($plan['taxed_cost'] != $plan['cost']) {
					$comment .= ' (Including Tax: ' . $this->currency->format($plan['taxed_cost'], strtoupper($subscription_response['plan']['currency']), 1) . ')';
				}
				$comment .= '<br />';
			}
		}
		if (!empty($charge_response)) {
			$charge_amount = $charge_response['amount'] / (strtoupper($charge_response['currency']) == 'JPY' ? 1 : 100);
			$comment .= '<script type="text/javascript" src="view/javascript/stripe.js"></script>';
			$comment .= $strong . 'Stripe Charge ID:</strong>' . $charge_response['id'] . '<br />';
			$comment .= $strong . 'Charge Amount:</strong>' . $this->currency->format($charge_amount, strtoupper($charge_response['currency']), 1) . '<br />';
			$comment .= $strong . 'Captured:</strong>' . (!empty($charge_response['captured']) ? 'Yes' : '<span>No &nbsp;</span> <a onclick="capture($(this), \'' . $charge_response['id'] . '\')">(Capture)</a>') . '<br />';
			
			// Credit/Debit Card fields
			if (isset($charge_response['source']['name']))					$comment .= $strong . 'Card Name:</strong>' . $charge_response['source']['name'] . '<br />';
			if (isset($charge_response['source']['last4']))					$comment .= $strong . 'Card Number:</strong>**** **** **** ' . $charge_response['source']['last4'] . '<br />';
			if (isset($charge_response['source']['fingerprint']))			$comment .= $strong . 'Card Fingerprint:</strong>' . $charge_response['source']['fingerprint'] . '<br />';
			if (isset($charge_response['source']['exp_month']))				$comment .= $strong . 'Card Expiry:</strong>' . $charge_response['source']['exp_month'] . ' / ' . $charge_response['source']['exp_year'] . '<br />';
			if (isset($charge_response['source']['brand']))					$comment .= $strong . 'Card Type:</strong>' . $charge_response['source']['brand'] . '<br />';
			if (isset($charge_response['source']['address_line1']))			$comment .= $strong . 'Card Address:</strong>' . $charge_response['source']['address_line1'] . '<br />';
			if (isset($charge_response['source']['address_line2']))			$comment .= (!empty($charge_response['source']['address_line2'])) ? $strong . '&nbsp;</strong>' . $charge_response['source']['address_line2'] . '<br />' : '';
			if (isset($charge_response['source']['address_city']))			$comment .= $strong . '&nbsp;</strong>' . $charge_response['source']['address_city'] . ', ' . $charge_response['source']['address_state'] . ' ' . $charge_response['source']['address_zip'] . '<br />';
			if (isset($charge_response['source']['address_country']))		$comment .= $strong . '&nbsp;</strong>' . $charge_response['source']['address_country'] . '<br />';
			if (isset($charge_response['source']['country']))				$comment .= $strong . 'Origin:</strong>' . $charge_response['source']['country'] . ' <img src="view/image/flags/' . strtolower($charge_response['source']['country']) . '.png" /><br />';
			if (isset($charge_response['source']['cvc_check']))				$comment .= $strong . 'CVC Check:</strong>' . $charge_response['source']['cvc_check'] . '<br />';
			if (isset($charge_response['source']['address_line1_check']))	$comment .= $strong . 'Street Check:</strong>' . $charge_response['source']['address_line1_check'] . '<br />';
			if (isset($charge_response['source']['address_zip_check']))		$comment .= $strong . 'Zip Check:</strong>' . $charge_response['source']['address_zip_check'] . '<br />';
			
			// Bitcoin fields
			if (isset($charge_response['source']['email']))					$comment .= $strong . 'Bitcoin E-mail:</strong>' . $charge_response['source']['email'] . '<br />';
			
			$comment .= $strong . 'Refund:</strong><a onclick="refund($(this), ' . ($charge_response['amount'] / 100) . ', \'' . $charge_response['id'] . '\', ' . (version_compare(VERSION, '1.5.0', '<') ? 0 : 1) . ')">(Refund)</a>';
		}
		
		$error_display = $this->config->get('config_error_display');
		$this->config->set('config_error_display', 0);
		register_shutdown_function(array($this, 'logFatalErrors'));
		
		if (version_compare(VERSION, '2.0') < 0) {
			$this->model_checkout_order->confirm($order_id, $order_status_id);
			$this->model_checkout_order->update($order_id, $order_status_id, $comment, false);
		} else {
			$this->model_checkout_order->addOrderHistory($order_id, $order_status_id, $comment, false);
		}
		
		$this->config->set('config_error_display', $error_display);
		$this->response->setOutput(json_encode(array('success' => $this->url->link('checkout/success', '', 'SSL'))));
	}
	
	public function logFatalErrors() {
		$error = error_get_last();
		if ($error['type'] === E_ERROR) { 
			$this->log->write('STRIPE PAYMENT GATEWAY: Order confirmation could not be completed due to the following fatal error:');
			$this->log->write('PHP Fatal Error:  ' . $error['message'] . ' in ' . $error['file'] . ' on line ' . $error['line']);
		} 
	}
	
	//==============================================================================
	// Private functions
	//==============================================================================
	private function getSettings() {
		$settings = array();
		$settings_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `" . (version_compare(VERSION, '2.0.1', '<') ? 'group' : 'code') . "` = '" . $this->db->escape($this->name) . "' ORDER BY `key` ASC");
		
		foreach ($settings_query->rows as $setting) {
			$value = $setting['value'];
			if ($setting['serialized']) {
				$value = (version_compare(VERSION, '2.1', '<')) ? unserialize($setting['value']) : json_decode($setting['value'], true);
			}
			$split_key = preg_split('/_(\d+)_?/', str_replace($this->name . '_', '', $setting['key']), -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
			
				if (count($split_key) == 1)	$settings[$split_key[0]] = $value;
			elseif (count($split_key) == 2)	$settings[$split_key[0]][$split_key[1]] = $value;
			elseif (count($split_key) == 3)	$settings[$split_key[0]][$split_key[1]][$split_key[2]] = $value;
			elseif (count($split_key) == 4)	$settings[$split_key[0]][$split_key[1]][$split_key[2]][$split_key[3]] = $value;
			else 							$settings[$split_key[0]][$split_key[1]][$split_key[2]][$split_key[3]][$split_key[4]] = $value;
		}
		
		return $settings;
	}
	
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
			$response = array('error' => array('message' => 'CURL ERROR: ' . curl_errno($curl) . '::' . curl_error($curl)));
			$this->log->write('STRIPE CURL ERROR: ' . curl_errno($curl) . '::' . curl_error($curl));	
		} elseif (empty($response)) {
			$response = array('error' => array('message' => 'CURL ERROR: Empty Gateway Response'));
			$this->log->write('STRIPE CURL ERROR: Empty Gateway Response');
		}
		curl_close($curl);
		
		return $response;
	}
	
	private function replaceShortcodes($text, $order_info) {
		$product_names = array();
		foreach ($this->cart->getProducts() as $product) {
			$options = array();
			foreach ($product['option'] as $option) {
				$options[] = $option['name'] . ': ' . (version_compare(VERSION, '2.0', '<') ? $option['option_value'] : $option['value']);
			}
			$product_names[] = $product['name'] . ($options ? ' (' . implode(', ', $options) . ')' : '');
		}
		
		$replace = array(
			'[store]',
			'[order_id]',
			'[amount]',
			'[email]',
			'[comment]',
			'[products]'
		);
		$with = array(
			$this->config->get('config_name'),
			$order_info['order_id'],
			$this->currency->format($order_info['total']),
			$order_info['email'],
			$order_info['comment'],
			implode(', ', $product_names)
		);
		
		return str_replace($replace, $with, $text);
	}
	
	//==============================================================================
	// Public functions
	//==============================================================================
	public function webhook() {
		$settings = $this->getSettings();
		
		$event = @json_decode(file_get_contents('php://input'));
		if (empty($event->type)) return;
		
		if (!isset($this->request->get['key']) || $this->request->get['key'] != $this->config->get('config_encryption')) {
			echo 'Wrong key';
			$this->log->write('STRIPE WEBHOOK ERROR: webhook URL key ' . $this->request->get['key'] . ' does not match the encryption key ' . $this->config->get('config_encryption'));
			return;
		}
		
		$this->load->model('checkout/order');
		
		if ($event->type == 'charge.refunded') {
			
			$order_history_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_history WHERE `comment` LIKE '%" . $this->db->escape($event->data->object->id) . "%' ORDER BY order_history_id DESC");
			if (!$order_history_query->num_rows) return;
			
			$refund = array_pop($event->data->object->refunds);
			$refund_currency = strtoupper($refund->currency);
			
			$strong = '<strong style="display: inline-block; width: 140px; padding: 3px">';
			$comment = $strong . 'Stripe Event:</strong>' . $event->type . '<br />';
			$comment .= $strong . 'Refund Amount:</strong>' . $this->currency->format($refund->amount / ($refund_currency == 'JPY' ? 1 : 100), $refund_currency, 1) . '<br />';
			$comment .= $strong . 'Total Amount Refunded:</strong>' . $this->currency->format($event->data->object->amount_refunded / ($refund_currency == 'JPY' ? 1 : 100), $refund_currency, 1);
			
			$order_id = $order_history_query->row['order_id'];
			$order_info = $this->model_checkout_order->getOrder($order_id);
			$refund_type = ($event->data->object->amount_refunded == $event->data->object->amount) ? 'refund' : 'partial';
			$order_status_id = ($settings[$refund_type . '_status_id']) ? $settings[$refund_type . '_status_id'] : $order_history_query->row['order_status_id'];
			
			if (version_compare(VERSION, '2.0', '<')) {
				$this->model_checkout_order->update($order_id, $order_status_id, $comment, false);
			} else {
				$this->model_checkout_order->addOrderHistory($order_id, $order_status_id, $comment, false);
			}
			
		} elseif ($event->type == 'invoice.payment_succeeded') {
			
			if (empty($settings['subscriptions'])) return;
			
			$customer_response = $this->curlRequest('customers/' . $event->data->object->customer, array('expand' => array('' => 'default_source')));
			if (!empty($customer_response['deleted']) || !empty($customer_response['error'])) {
				$this->log->write('STRIPE WEBHOOK ERROR: ' . $customer_response['error']['message']);
				return;
			}
			
			$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "stripe_customer sc ON (c.customer_id = sc.customer_id) WHERE sc.stripe_customer_id = '" . $this->db->escape($customer_response['id']) . "' AND sc.transaction_mode = '" . $this->db->escape($settings['transaction_mode']) . "'");
			if ($settings['prevent_guests'] && !$customer_query->num_rows) {
				$this->log->write('STRIPE WEBHOOK ERROR: customer with stripe_customer_id ' . $customer_response['id'] . ' does not exist in OpenCart, and guests are currently prevented from using subscriptions');
				return;
			}
			$customer = $customer_query->row;
			
			$now_query = $this->db->query("SELECT NOW()");
			$last_order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE email = '" . $this->db->escape($customer_response['email']) . "' ORDER BY date_added DESC");
			if ($last_order_query->num_rows && (strtotime($now_query->row['NOW()']) - strtotime($last_order_query->row['date_added'])) < 600) {
				// Customer's last order is within 10 minutes, so it most likely was an immediate subscription and is already shown on their last order
				return;
			}
			
			$data = array();
			
			$name = (isset($customer_response['default_source']['name'])) ? explode(' ', $customer_response['default_source']['name'], 2) : array();
			$firstname = (isset($name[0])) ? $name[0] : '';
			$lastname = (isset($name[1])) ? $name[1] : '';
			
			$data['customer_id'] = (isset($customer['customer_id'])) ? $customer['customer_id'] : 0;
			$data['firstname'] = $firstname;
			$data['lastname'] = $lastname;
			$data['email'] = $customer_response['email'];
			
			$plan_name = '';
			$product_data = array();
			$total_data = array();
			$subtotal = 0;
			$shipping = false;
			
			foreach ($event->data->object->lines->data as $line) {
				$line_currency = strtoupper($line->currency);
				$line_decimal_factor = ($line_currency == 'JPY') ? 1 : 100;
				
				if (empty($line->plan)) {
					$total_data[] = array(
						'code'			=> 'total',
						'title'			=> $line->description,
						'text'			=> $this->currency->format($line->amount / $line_decimal_factor, $line_currency, 1),
						'value'			=> $line->amount / $line_decimal_factor,
						'sort_order'	=> 2
					);
				} else {
					$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id AND pd.language_id = " . (int)$this->config->get('config_language_id') . ") WHERE p.location = '" . $this->db->escape($line->plan->id) . "'");
					if (!$product_query->num_rows) continue;
					foreach ($product_query->rows as $product) {
						$plan_name = $line->plan->name;
						$subtotal += $line->amount / $line_decimal_factor;
						$shipping = ($shipping || $product['shipping']);
						$product_data[] = array(
							'product_id'	=> $product['product_id'],
							'name'			=> $product['name'],
							'model'			=> $product['model'],
							'option'		=> array(),
							'download'		=> array(),
							'quantity'		=> $line->quantity,
							'subtract'		=> $product['subtract'],
							'price'			=> ($line->amount / $line_decimal_factor / $line->quantity),
							'total'			=> $line->amount / $line_decimal_factor,
							'tax'			=> $this->tax->getTax($line->amount / $line_decimal_factor, $product['tax_class_id']),
							'reward'		=> isset($product['reward']) ? $product['reward'] : 0
						);
					}
				}
			}
			
			$country = (isset($customer_response['default_source']['address_country'])) ? $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE `name` = '" . $this->db->escape($customer_response['default_source']['address_country']) . "'") : '';
			$country_id = (isset($country->row['country_id'])) ? $country->row['country_id'] : 0;
			
			$zone = (isset($customer_response['default_source']['address_state'])) ? $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE `name` = '" . $this->db->escape($customer_response['default_source']['address_state']) . "' AND country_id = " . (int)$country_id) : '';
			$zone_id = (isset($zone->row['zone_id'])) ? $zone->row['zone_id'] : 0;
			
			$data['payment_firstname'] = $firstname;
			$data['payment_lastname'] = $lastname;
			$data['payment_company'] = '';
			$data['payment_company_id'] = '';
			$data['payment_tax_id'] = '';
			$data['payment_address_1'] = $customer_response['default_source']['address_line1'];
			$data['payment_address_2'] = $customer_response['default_source']['address_line2'];
			$data['payment_city'] = $customer_response['default_source']['address_city'];
			$data['payment_postcode'] = $customer_response['default_source']['address_zip'];
			$data['payment_zone'] = $customer_response['default_source']['address_state'];
			$data['payment_zone_id'] = $zone_id;
			$data['payment_country'] = $customer_response['default_source']['address_country'];
			$data['payment_country_id'] = $country_id;
			
			if ($shipping) {
				$data['shipping_firstname'] = $firstname;
				$data['shipping_lastname'] = $lastname;
				$data['shipping_company'] = '';
				$data['shipping_company_id'] = '';
				$data['shipping_tax_id'] = '';
				$data['shipping_address_1'] = $customer_response['default_source']['address_line1'];
				$data['shipping_address_2'] = $customer_response['default_source']['address_line2'];
				$data['shipping_city'] = $customer_response['default_source']['address_city'];
				$data['shipping_postcode'] = $customer_response['default_source']['address_zip'];
				$data['shipping_zone'] = $customer_response['default_source']['address_state'];
				$data['shipping_zone_id'] = $zone_id;
				$data['shipping_country'] = $customer_response['default_source']['address_country'];
				$data['shipping_country_id'] = $country_id;
			}
			
			$decimal_factor = ($event->data->object->currency == 'jpy') ? 1 : 100;
			
			$total_data[] = array(
				'code'			=> 'sub_total',
				'title'			=> 'Sub-Total',
				'text'			=> $this->currency->format($subtotal, $event->data->object->currency, 1),
				'value'			=> $subtotal,
				'sort_order'	=> 1
			);
			if (!empty($event->data->object->tax)) {
				$total_data[] = array(
					'code'			=> 'tax',
					'title'			=> 'Tax',
					'text'			=> $this->currency->format($event->data->object->tax / $decimal_factor, 1),
					'value'			=> $event->data->object->tax / $decimal_factor,
					'sort_order'	=> 2
				);
			}
			$total_data[] = array(
				'code'			=> 'total',
				'title'			=> 'Total',
				'text'			=> $this->currency->format($event->data->object->total / $decimal_factor, 1),
				'value'			=> $event->data->object->total / $decimal_factor,
				'sort_order'	=> 3
			);
			
			$data['products'] = $product_data;
			$data['totals'] = $total_data;
			$data['total'] = $event->data->object->total / $decimal_factor;
			
			$this->load->model($this->type . '/' . $this->name);
			$order_id = $this->{'model_'.$this->type.'_'.$this->name}->createOrder($data);
			$order_status_id = $settings['success_status_id'];
			
			$strong = '<strong style="display: inline-block; width: 140px; padding: 3px">';
			$comment = $strong . 'Charged for Plan:</strong>' . $plan_name . '<br />';
			if (!empty($event->data->object->charge)) {
				$comment .= $strong . 'Stripe Charge ID:</strong>' . $event->data->object->charge . '<br />';
			}
			
			$error_display = $this->config->get('config_error_display');
			$this->config->set('config_error_display', 0);
			register_shutdown_function(array($this, 'logFatalErrors'));
			
			if (version_compare(VERSION, '2.0') < 0) {
				$this->model_checkout_order->confirm($order_id, $order_status_id);
				$this->model_checkout_order->update($order_id, $order_status_id, $comment, false);
			} else {
				$this->model_checkout_order->addOrderHistory($order_id, $order_status_id, $comment, false);
			}
			
			$this->config->set('config_error_display', $error_display);
		}
		
	}
}
?>