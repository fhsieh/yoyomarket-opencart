<?php
class ControllerQuickCheckoutShippingMethod extends Controller {
  	public function index() {
		$this->language->load('quickcheckout/checkout');

		$this->load->model('account/address');
		$this->load->model('localisation/country');
		$this->load->model('localisation/zone');

		$shipping_address = array();

		if ($this->customer->isLogged() && isset($this->request->get['address_id'])) {
			// Selected stored address
			$shipping_address = $this->model_account_address->getAddress($this->request->get['address_id']);

			if (isset($this->session->data['guest'])) {
				unset($this->session->data['guest']);
			}
		} elseif (isset($this->request->post['country_id'])) {
			// Selected new address OR is a guest
			if (isset($this->request->post['country_id'])) {
				$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
			} else {
				$country_info = '';
			}

			if (isset($this->request->post['zone_id'])) {
				$zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);
			} else {
				$zone_info = '';
			}

			if ($country_info) {
				$shipping_address['country'] = $country_info['name'];
				$shipping_address['iso_code_2'] = $country_info['iso_code_2'];
				$shipping_address['iso_code_3'] = $country_info['iso_code_3'];
				$shipping_address['address_format'] = $country_info['address_format'];
			} else {
				$shipping_address['country'] = '';
				$shipping_address['iso_code_2'] = '';
				$shipping_address['iso_code_3'] = '';
				$shipping_address['address_format'] = '';
			}

			if ($zone_info) {
				$shipping_address['zone'] = $zone_info['name'];
				$shipping_address['zone_code'] = $zone_info['code'];
			} else {
				$shipping_address['zone'] = '';
				$shipping_address['zone_code'] = '';
			}

			$shipping_address['firstname'] = $this->request->post['firstname'];
			$shipping_address['lastname'] = $this->request->post['lastname'];
			$shipping_address['company'] = $this->request->post['company'];
			$shipping_address['address_1'] = $this->request->post['address_1'];
			$shipping_address['address_2'] = $this->request->post['address_2'];
			$shipping_address['postcode'] = $this->request->post['postcode'];
			$shipping_address['city'] = $this->request->post['city'];
			$shipping_address['country_id'] = $this->request->post['country_id'];
			$shipping_address['zone_id'] = $this->request->post['zone_id'];
		}

		if (!empty($shipping_address)) {
			// Shipping Methods
			$method_data = array();

			$this->load->model('extension/extension');

			$results = $this->model_extension_extension->getExtensions('shipping');

			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('shipping/' . $result['code']);

					$quote = $this->{'model_shipping_' . $result['code']}->getQuote($shipping_address);

					if ($quote) {
						$method_data[$result['code']] = array(
							'title'      => $quote['title'],
							'quote'      => $quote['quote'],
							'sort_order' => $quote['sort_order'],
							'error'      => $quote['error']
						);
					}
				}
			}

			$sort_order = array();

			foreach ($method_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $method_data);

			$this->session->data['shipping_methods'] = $method_data;
		}

		$data['text_shipping_method'] = $this->language->get('text_shipping_method');
		$data['text_estimated_delivery'] = $this->language->get('text_estimated_delivery');
		$data['text_delivery_preference'] = $this->language->get('text_delivery_preference');

		if ($this->config->get('quickcheckout_delivery_time') == '2') {
			$min = $this->config->get('quickcheckout_delivery_min');
			$max = $this->config->get('quickcheckout_delivery_max');
			$today = date('d M Y');

			$min_date = date('d M Y', strtotime($today . ' + ' . $min . ' day'));
			$max_date = date('d M Y', strtotime($today . ' + ' . $max . ' day'));

			$min = 0;
			$max = 0;

			if ($this->config->get('quickcheckout_delivery_unavailable')) {
				$dates = str_replace('"', '', html_entity_decode($this->config->get('quickcheckout_delivery_unavailable'), ENT_QUOTES));
			} else {
				$dates = array();
			}

			foreach (explode(',', $dates) as $unavailable) {
				$unavailable = strtotime($unavailable);

				if ($unavailable >= strtotime($min_date) && $unavailable <= strtotime($max_date)) {
					$max++;
				}

				if ($unavailable == strtotime($min_date)) {
					$min++;
				}
			}

			$min_date = date('d M Y', strtotime($min_date . ' + ' . $min . ' day'));
			$max_date = date('d M Y', strtotime($max_date . ' + ' . $max . ' day'));

			$data['estimated_delivery'] = $min_date . ' - ' . $max_date;
			$data['estimated_delivery_time'] = str_pad($this->config->get('quickcheckout_delivery_min_hour'), 2, '0', STR_PAD_LEFT) . ' 00 - ' . str_pad($this->config->get('quickcheckout_delivery_max_hour'), 2, '0', STR_PAD_LEFT) . ' 00';
		}

		if (empty($this->session->data['shipping_methods'])) {
			$data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['shipping_methods'])) {
			$data['shipping_methods'] = $this->session->data['shipping_methods'];
		} else {
			$data['shipping_methods'] = array();
		}

		if (isset($this->session->data['shipping_method']['code'])) {
			$data['code'] = $this->session->data['shipping_method']['code'];
		} else {
			$data['code'] = '';
		}

		if (isset($this->request->post['delivery_date'])) {
			$data['delivery_date'] = $this->request->post['delivery_date'];
		} elseif (isset($this->session->data['delivery_date'])) {
			$data['delivery_date'] = $this->session->data['delivery_date'];
		} else {
			$data['delivery_date'] = '';
		}

		if (isset($this->request->post['delivery_time'])) {
			$data['delivery_time'] = $this->request->post['delivery_time'];
		} elseif (isset($this->session->data['delivery_time'])) {
			$data['delivery_time'] = $this->session->data['delivery_time'];
		} else {
			$data['delivery_time'] = '';
		}

		// All variables
		$data['logged'] = $this->customer->isLogged();
		$data['debug'] = $this->config->get('quickcheckout_debug');
		$data['shipping'] = $this->config->get('quickcheckout_shipping');
		$data['delivery'] = $this->config->get('quickcheckout_delivery');
		$data['delivery_delivery_time'] = $this->config->get('quickcheckout_delivery_time');
		$data['delivery_required'] = $this->config->get('quickcheckout_delivery_required');
		$data['delivery_times'] = $this->config->get('quickcheckout_delivery_times');
		$data['delivery_unavailable'] = html_entity_decode($this->config->get('quickcheckout_delivery_unavailable'), ENT_QUOTES);
		$data['delivery_days_of_week'] = html_entity_decode($this->config->get('quickcheckout_delivery_days_of_week'), ENT_QUOTES);
		$data['cart'] = $this->config->get('quickcheckout_cart');
		$data['shipping_reload'] = $this->config->get('quickcheckout_shipping_reload');
		$data['language_id'] = $this->config->get('config_language_id');

		if ($this->config->get('quickcheckout_delivery_min')) {
			$data['delivery_min'] = date('d-m-Y', strtotime('+' . $this->config->get('quickcheckout_delivery_min') . ' days'));
		} else {
			$data['delivery_min'] = date('d-m-Y');
		}

		if ($this->config->get('quickcheckout_delivery_max')) {
			$data['delivery_max'] = date('d-m-Y', strtotime('+' . $this->config->get('quickcheckout_delivery_max') . ' days'));
		} else {
			$data['delivery_max'] = date('d-m-Y');
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/quickcheckout/shipping_method.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/quickcheckout/shipping_method.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/quickcheckout/shipping_method.tpl', $data));
		}
  	}

	public function set() {
		$this->load->model('account/address');
		$this->load->model('localisation/country');
		$this->load->model('localisation/zone');

		if ($this->customer->isLogged() && isset($this->request->get['address_id'])) {
			// Selected stored address
			$this->session->data['shipping_address_id'] = $this->request->get['address_id'];

			$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->request->get['address_id']);

			if (isset($this->session->data['guest'])) {
				unset($this->session->data['guest']);
			}
		} elseif (isset($this->request->post['country_id'])) {
			// Selected new address OR is a guest
			if (isset($this->request->post['country_id'])) {
				$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
			} else {
				$country_info = '';
			}

			if (isset($this->request->post['zone_id'])) {
				$zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);
			} else {
				$zone_info = '';
			}

			if ($country_info) {
				$shipping_address['country'] = $country_info['name'];
				$shipping_address['iso_code_2'] = $country_info['iso_code_2'];
				$shipping_address['iso_code_3'] = $country_info['iso_code_3'];
				$shipping_address['address_format'] = $country_info['address_format'];
			} else {
				$shipping_address['country'] = '';
				$shipping_address['iso_code_2'] = '';
				$shipping_address['iso_code_3'] = '';
				$shipping_address['address_format'] = '';
			}

			if ($zone_info) {
				$shipping_address['zone'] = $zone_info['name'];
				$shipping_address['zone_code'] = $zone_info['code'];
			} else {
				$shipping_address['zone'] = '';
				$shipping_address['zone_code'] = '';
			}

			$shipping_address['firstname'] = $this->request->post['firstname'];
			$shipping_address['lastname'] = $this->request->post['lastname'];
			$shipping_address['company'] = $this->request->post['company'];
			$shipping_address['address_1'] = $this->request->post['address_1'];
			$shipping_address['address_2'] = $this->request->post['address_2'];
			$shipping_address['postcode'] = $this->request->post['postcode'];
			$shipping_address['city'] = $this->request->post['city'];
			$shipping_address['country_id'] = $this->request->post['country_id'];
			$shipping_address['zone_id'] = $this->request->post['zone_id'];

			$this->session->data['shipping_address'] = $shipping_address;
		}

		if (isset($this->request->post['delivery_date'])) {
			$this->session->data['delivery_date'] = strip_tags($this->request->post['delivery_date']);
		}

		if (isset($this->request->post['delivery_time'])) {
			$this->session->data['delivery_time'] = strip_tags($this->request->post['delivery_time']);
		}

		if (isset($this->request->post['shipping_method']) && isset($this->session->data['shipping_methods'])) {
			$shipping = explode('.', $this->request->post['shipping_method']);

			if (isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
				$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
			}
		}
	}

	public function validate() {
		$this->language->load('quickcheckout/checkout');
		$this->load->model('account/address');
		$this->load->model('localisation/country');
		$this->load->model('localisation/zone');

		$json = array();

        // Set address
        if ($this->customer->isLogged() && isset($this->request->get['address_id'])) {
			// Selected stored address
			$this->session->data['shipping_address_id'] = $this->request->get['address_id'];

			$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->request->get['address_id']);

			if (isset($this->session->data['guest'])) {
				unset($this->session->data['guest']);
			}
		} elseif (isset($this->request->post['country_id'])) {
			// Selected new address OR is a guest
			if (isset($this->request->post['country_id'])) {
				$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
			} else {
				$country_info = '';
			}

			if (isset($this->request->post['zone_id'])) {
				$zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);
			} else {
				$zone_info = '';
			}

			if ($country_info) {
				$shipping_address['country'] = $country_info['name'];
				$shipping_address['iso_code_2'] = $country_info['iso_code_2'];
				$shipping_address['iso_code_3'] = $country_info['iso_code_3'];
				$shipping_address['address_format'] = $country_info['address_format'];
			} else {
				$shipping_address['country'] = '';
				$shipping_address['iso_code_2'] = '';
				$shipping_address['iso_code_3'] = '';
				$shipping_address['address_format'] = '';
			}

			if ($zone_info) {
				$shipping_address['zone'] = $zone_info['name'];
				$shipping_address['zone_code'] = $zone_info['code'];
			} else {
				$shipping_address['zone'] = '';
				$shipping_address['zone_code'] = '';
			}

			$shipping_address['firstname'] = $this->request->post['firstname'];
			$shipping_address['lastname'] = $this->request->post['lastname'];
			$shipping_address['company'] = $this->request->post['company'];
			$shipping_address['address_1'] = $this->request->post['address_1'];
			$shipping_address['address_2'] = $this->request->post['address_2'];
			$shipping_address['postcode'] = $this->request->post['postcode'];
			$shipping_address['city'] = $this->request->post['city'];
			$shipping_address['country_id'] = $this->request->post['country_id'];
			$shipping_address['zone_id'] = $this->request->post['zone_id'];

			$this->session->data['shipping_address'] = $shipping_address;
		}

		// Validate if shipping is required. If not the customer should not have reached this page.
		if (!$this->cart->hasShipping()) {
			$json['redirect'] = $this->url->link('quickcheckout/checkout', '', 'SSL');
		}

		// Validate if shipping address has been set.
		if (!isset($this->session->data['shipping_address'])) {
			$json['redirect'] = $this->url->link('quickcheckout/checkout', '', 'SSL');
		}

        $shipping_address = $this->session->data['shipping_address'];

		if (!isset($this->request->post['shipping_method'])) {
			$json['error']['warning'] = $this->language->get('error_shipping');
		} else {
			$shipping = explode('.', $this->request->post['shipping_method']);

			if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
				$json['error']['warning'] = $this->language->get('error_shipping');
			}
		}

		if ($this->config->get('quickcheckout_delivery_required')) {
			if (empty($this->request->post['delivery_date'])) {
				$json['error']['warning'] = $this->language->get('error_delivery');
			}
		}

		if (!$json) {
			$shipping = explode('.', $this->request->post['shipping_method']);

			$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];

			$this->session->data['delivery_date'] = strip_tags($this->request->post['delivery_date']);

			if (isset($this->request->post['delivery_time'])) {
				$this->session->data['delivery_time'] = strip_tags($this->request->post['delivery_time']);
			} else {
				$this->session->data['delivery_time'] = '';
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
?>