<?php
class ControllerQuickCheckoutCheckout extends Controller {
	public function index() {
		if ($this->config->get('quickcheckout_load_screen')) {
			$this->document->addScript('catalog/view/javascript/jquery/quickcheckout/quickcheckout.block.js');
		}

		if ($this->config->get('quickcheckout_countdown')) {
			$this->document->addScript('catalog/view/javascript/jquery/quickcheckout/quickcheckout.countdown.js');
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/css/quickcheckout.css')) {
			$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/css/quickcheckout.css');
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/quickcheckout.css');
		}

		if ($this->config->get('quickcheckout_layout') == '1') {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/css/quickcheckout_one.css')) {
				$data['stylesheet'] = 'catalog/view/theme/' . $this->config->get('config_template') . '/css/quickcheckout_one.css';
			} else {
				$data['stylesheet'] = 'catalog/view/theme/default/stylesheet/quickcheckout_one.css';
			}
		} elseif ($this->config->get('quickcheckout_layout') == '2') {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/css/quickcheckout_two.css')) {
				$data['stylesheet'] = 'catalog/view/theme/' . $this->config->get('config_template') . '/css/quickcheckout_two.css';
			} else {
				$data['stylesheet'] = 'catalog/view/theme/default/stylesheet/quickcheckout_two.css';
			}
		} else {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/css/quickcheckout_three.css')) {
				$data['stylesheet'] = 'catalog/view/theme/' . $this->config->get('config_template') . '/css/quickcheckout_three.css';
			} else {
				$data['stylesheet'] = 'catalog/view/theme/default/stylesheet/quickcheckout_three.css';
			}
		}

		if ($this->config->get('quickcheckout_responsive')) {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/css/quickcheckout_mobile.css')) {
				$data['mobile_stylesheet'] = 'catalog/view/theme/' . $this->config->get('config_template') . '/css/quickcheckout_mobile.css';
			} else {
				$data['mobile_stylesheet'] = 'catalog/view/theme/default/stylesheet/quickcheckout_mobile.css';
			}
		} else {
			$data['mobile_stylesheet'] = '';
		}

		if (!$this->config->get('quickcheckout' . base64_decode('X29yZGVyX2lk'))) {
			$this->response->redirect($this->url->link('checkout/cart'));
		}

		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

		// Required by klarna
		if ($this->config->get('klarna_account') || $this->config->get('klarna_invoice')) {
			$this->document->addScript('http://cdn.klarna.com/public/kitt/toc/v1.0/js/klarna.terms.min.js');
		}

		if (!$this->config->get('quickcheckout_debug') || !isset($this->request->get['debug'])) {
			if (!$this->config->get('quickcheckout_status')) {
				$this->response->redirect($this->url->link('checkout/checkout'));
			}
		}

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
	  		$this->response->redirect($this->url->link('checkout/cart'));
    	}

		// Validate minimum quantity requirements.
		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$this->response->redirect($this->url->link('checkout/cart'));
			}
		}

		$this->language->load('quickcheckout/checkout');

		if ($this->cart->getTotal() < $this->config->get('quickcheckout_minimum_order')) {
			$this->session->data['error'] = sprintf($this->language->get('error_minimum_order'), $this->currency->format($this->config->get('quickcheckout_minimum_order')));

			$this->response->redirect($this->url->link('checkout/cart'));
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

      	$data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home')
      	);

      	$data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_cart'),
			'href'      => $this->url->link('checkout/cart')
      	);

      	$data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('quickcheckout/checkout', '', 'SSL')
      	);

	    $data['heading_title'] = $this->language->get('heading_title');

		$data['text_checkout_option'] = $this->language->get('text_checkout_option');
		$data['text_checkout_account'] = $this->language->get('text_checkout_account');
		$data['text_checkout_payment_address'] = $this->language->get('text_checkout_payment_address');
		$data['text_checkout_shipping_address'] = $this->language->get('text_checkout_shipping_address');
		$data['text_checkout_shipping_method'] = $this->language->get('text_checkout_shipping_method');
		$data['text_checkout_payment_method'] = $this->language->get('text_checkout_payment_method');
		$data['text_checkout_confirm'] = $this->language->get('text_checkout_confirm');
		$data['text_modify'] = $this->language->get('text_modify');
		$data['text_or'] = $this->language->get('text_or');
		$data['text_please_wait'] = $this->language->get('text_please_wait');

		$data['logged'] = $this->customer->isLogged();
		$data['shipping_required'] = $this->cart->hasShipping();

		if ($this->config->get('quickcheckout_html_header')) {
			$html_header = $this->config->get('quickcheckout_html_header');

			if (!empty($html_header[$this->config->get('config_language_id')])) {
				$data['html_header'] = html_entity_decode($html_header[$this->config->get('config_language_id')]);
			} else {
				$data['html_header'] = '';
			}
		} else {
			$data['html_header'] = '';
		}

		if ($this->config->get('quickcheckout_html_footer')) {
			$html_footer = $this->config->get('quickcheckout_html_footer');

			if (!empty($html_footer[$this->config->get('config_language_id')])) {
				$data['html_footer'] = html_entity_decode($html_footer[$this->config->get('config_language_id')]);
			} else {
				$data['html_footer'] = '';
			}
		} else {
			$data['html_footer'] = '';
		}

		$data['countdown_end'] = false;

		if ($this->config->get('quickcheckout_countdown')) {
			$data['timezone'] = substr(date('O'), 0, 3);

			$text = $this->config->get('quickcheckout_countdown_text');

			if (!empty($text[$this->config->get('config_language_id')])) {
				if ($this->config->get('quickcheckout_countdown_start')) {
					$time = date('d M Y') . ' ' . $this->config->get('quickcheckout_countdown_time') . ':00';

					if (time() > strtotime($time)) {
						$data['countdown_end'] = date('d M Y H:i:s', (strtotime($time) + 86400));
					} else {
						$data['countdown_end'] = $time;
					}
				} else {
					if (time() >= strtotime($this->config->get('quickcheckout_countdown_date_start')) && time() <= strtotime($this->config->get('quickcheckout_countdown_date_end'))) {
						$data['countdown_end'] = $this->config->get('quickcheckout_countdown_date_end');
					}
				}

				$text = explode('{timer}', html_entity_decode($text[$this->config->get('config_language_id')]));

				$data['countdown_before'] = $text[0];

				if (isset($text[1])) {
					$data['countdown_after'] = $text[1];
				} else {
					$data['countdown_after'] = '';
				}

				$data['countdown_timer'] = '{dn} {dl} {hnn} {hl} {mnn} {ml} {snn} {sl}';
			}
		}

		// All variables
		$data['load_screen'] = $this->config->get('quickcheckout_load_screen');
		$data['edit_cart'] = $this->config->get('quickcheckout_edit_cart');
		$data['highlight_error'] = $this->config->get('quickcheckout_highlight_error');
		$data['text_error'] = $this->config->get('quickcheckout_text_error');
		$data['layout'] = $this->config->get('quickcheckout_layout');
		$data['slide_effect'] = $this->config->get('quickcheckout_slide_effect');
		$data['debug'] = $this->config->get('quickcheckout_debug');
		$data['auto_submit'] = $this->config->get('quickcheckout_auto_submit');
		$data['coupon_module'] = $this->config->get('quickcheckout_coupon');
		$data['voucher_module'] = $this->config->get('quickcheckout_voucher');
		$data['reward_module'] = $this->config->get('quickcheckout_reward');
		$data['cart_module'] = $this->config->get('quickcheckout_cart');
		$data['payment_module'] = $this->config->get('quickcheckout_payment_module');
		$data['shipping_module'] = $this->config->get('quickcheckout_shipping_module');
		$data['login_module'] = $this->config->get('quickcheckout_login_module');
		$data['countdown'] = $this->config->get('quickcheckout_countdown');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/quickcheckout/checkout.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/quickcheckout/checkout.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/quickcheckout/checkout.tpl', $data));
		}
  	}

	public function country() {
		$json = array();

		$this->load->model('localisation/country');

    	$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function customfield() {
		$json = array();

		$this->load->model('account/custom_field');

		// Customer Group
		if (isset($this->request->get['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->get['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $this->request->get['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

		foreach ($custom_fields as $custom_field) {
			$json[] = array(
				'custom_field_id' => $custom_field['custom_field_id'],
				'required'        => $custom_field['required']
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}