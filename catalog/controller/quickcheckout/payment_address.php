<?php
class ControllerQuickCheckoutPaymentAddress extends Controller {
	public function index() {
		$this->language->load('quickcheckout/checkout');

		$data['text_address_existing'] = $this->language->get('text_address_existing');
		$data['text_address_new'] = $this->language->get('text_address_new');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['entry_firstname'] = $this->language->get('entry_firstname');
		$data['entry_lastname'] = $this->language->get('entry_lastname');
		$data['entry_company'] = $this->language->get('entry_company');
		$data['entry_address_1'] = $this->language->get('entry_address_1');
		$data['entry_address_2'] = $this->language->get('entry_address_2');
		$data['entry_postcode'] = $this->language->get('entry_postcode');
		$data['entry_city'] = $this->language->get('entry_city');
		$data['entry_country'] = $this->language->get('entry_country');
		$data['entry_zone'] = $this->language->get('entry_zone');
		
		$data['button_upload'] = $this->language->get('button_upload');

		if (isset($this->session->data['payment_address']['address_id'])) {
			$data['address_id'] = $this->session->data['payment_address']['address_id'];
		} else {
			$data['address_id'] = $this->customer->getAddressId();
		}

		$data['addresses'] = array();

		$this->load->model('account/address');

		$data['addresses'] = $this->model_account_address->getAddresses();

		if (isset($this->session->data['payment_address']['country_id'])) {
			$data['country_id'] = $this->session->data['payment_address']['country_id'];
		} elseif (isset($this->session->data['shipping_address']['country_id'])) {
			$data['country_id'] = $this->session->data['shipping_address']['country_id'];
		} else {
			$country = $this->config->get('quickcheckout_field_country');

			$data['country_id'] = $country['default'];
		}

		if (isset($this->session->data['payment_address']['zone_id'])) {
			$data['zone_id'] = $this->session->data['payment_address']['zone_id'];
		} elseif (isset($this->session->data['shipping_address']['zone_id'])) {
			$data['zone_id'] = $this->session->data['shipping_address']['zone_id'];
		} else {
			$zone = $this->config->get('quickcheckout_field_zone');

			$data['zone_id'] = isset($zone['default']) ? $zone['default'] : 0;
		}

		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();
		
		// Custom Fields
		$this->load->model('account/custom_field');

		$data['custom_fields'] = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));

		if (isset($this->session->data['payment_address']['custom_field'])) {
			$data['payment_address_custom_field'] = $this->session->data['payment_address']['custom_field'];
		} else {
			$data['payment_address_custom_field'] = array();
		}

		// Fields
		$fields = array(
			'firstname',
			'lastname',
			'company',
			'address_1',
			'address_2',
			'city',
			'postcode',
			'country',
			'zone'
		);

		// All variables
		$data['debug'] = $this->config->get('quickcheckout_debug');
		$data['country_reload'] = $this->config->get('quickcheckout_country_reload');

		$sort_order = array();

		foreach ($fields as $key => $field) {
			$field_data = $this->config->get('quickcheckout_field_' . $field);

			$data['field_' . $field] = $field_data;

			$sort_order[$key] = $field_data['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $fields);

		$data['fields'] = $fields;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/quickcheckout/payment_address.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/quickcheckout/payment_address.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/quickcheckout/payment_address.tpl', $data));
		}
  	}

	public function validate() {
		$this->language->load('quickcheckout/checkout');

		$json = array();

		// Validate if customer is logged in.
		if (!$this->customer->isLogged()) {
			$json['redirect'] = $this->url->link('quickcheckout/checkout', '', 'SSL');
		}

		if (!$json) {
			if (isset($this->request->post['payment_address']) && $this->request->post['payment_address'] == 'existing') {
				$this->load->model('account/address');

				if (empty($this->request->post['address_id'])) {
					$json['error']['warning'] = $this->language->get('error_address');
				} elseif (!in_array($this->request->post['address_id'], array_keys($this->model_account_address->getAddresses()))) {
					$json['error']['warning'] = $this->language->get('error_address');
				}

				if (!$json) {
					// Default Payment Address
					$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->request->post['address_id']);
				}
			}

			if ($this->request->post['payment_address'] == 'new') {
				$firstname = $this->config->get('quickcheckout_field_firstname');

				if (!empty($firstname['required'])) {
					if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
						$json['error']['firstname'] = $this->language->get('error_firstname');
					}
				}

				$lastname = $this->config->get('quickcheckout_field_lastname');

				if (!empty($lastname['required'])) {
					if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
						$json['error']['lastname'] = $this->language->get('error_lastname');
					}
				}

				$address_1 = $this->config->get('quickcheckout_field_address_1');

				if (!empty($address_1['required'])) {
					if ((utf8_strlen($this->request->post['address_1']) < 3) || (utf8_strlen($this->request->post['address_1']) > 64)) {
						$json['error']['address_1'] = $this->language->get('error_address_1');
					}
				}

				$address_2 = $this->config->get('quickcheckout_field_address_2');

				if (!empty($address_2['required'])) {
					if ((utf8_strlen($this->request->post['address_2']) < 3) || (utf8_strlen($this->request->post['address_2']) > 64)) {
						$json['error']['address_2'] = $this->language->get('error_address_2');
					}
				}

				$company = $this->config->get('quickcheckout_field_company');

				if (!empty($company['required'])) {
					if ((utf8_strlen($this->request->post['company']) < 3) || (utf8_strlen($this->request->post['company']) > 64)) {
						$json['error']['company'] = $this->language->get('error_company');
					}
				}

				$city = $this->config->get('quickcheckout_field_city');

				if (!empty($city['required'])) {
					if ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 128)) {
						$json['error']['city'] = $this->language->get('error_city');
					}
				}

				$this->load->model('localisation/country');

				$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

				if ($country_info) {
					if ($country_info['postcode_required'] && (utf8_strlen($this->request->post['postcode']) < 2) || (utf8_strlen($this->request->post['postcode']) > 10)) {
						$json['error']['postcode'] = $this->language->get('error_postcode');
					}
				}

				$country = $this->config->get('quickcheckout_field_country');

				if (!empty($country['required'])) {
					if ($this->request->post['country_id'] == '') {
						$json['error']['country'] = $this->language->get('error_country');
					}
				}

				$zone = $this->config->get('quickcheckout_field_zone');

				if (!empty($zone['required'])) {
					if ($this->request->post['zone_id'] == '') {
						$json['error']['zone'] = $this->language->get('error_zone');
					}
				}
				
				// Custom field validation
				$this->load->model('account/custom_field');

				$custom_fields = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));

				foreach ($custom_fields as $custom_field) {
					if ($custom_field['location'] == 'address' && $custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {
						$json['error']['custom_field' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
					}
				}

				if (!$json) {
					// Default Payment Address
					$this->load->model('account/address');
					
					$address_id = $this->model_account_address->addAddress($this->request->post);

					$this->session->data['payment_address'] = $this->model_account_address->getAddress($address_id);

					$this->load->model('account/activity');

					$activity_data = array(
						'customer_id' => $this->customer->getId(),
						'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
					);

					$this->model_account_activity->addActivity('address_add', $activity_data);
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}