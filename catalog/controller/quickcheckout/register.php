<?php
class ControllerQuickCheckoutRegister extends Controller {
  	public function index() {
		$this->language->load('quickcheckout/checkout');

		$data['text_your_password'] = $this->language->get('text_your_password');

		$data['entry_newsletter'] = sprintf($this->language->get('entry_newsletter'), $this->config->get('config_name'));
		$data['entry_password'] = $this->language->get('entry_password');
		$data['entry_confirm'] = $this->language->get('entry_confirm');

		if ($this->config->get('config_account_id')) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

			if ($information_info) {
				$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_account_id'), 'SSL'), $information_info['title'], $information_info['title']);
			} else {
				$data['text_agree'] = '';
			}
		} else {
			$data['text_agree'] = '';
		}

		// All variables
		$data['field_newsletter'] = $this->config->get('quickcheckout_field_newsletter');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/quickcheckout/register.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/quickcheckout/register.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/quickcheckout/register.tpl', $data));
		}
  	}

	public function validate() {
		$this->language->load('quickcheckout/checkout');

		$this->load->model('account/customer');

		$json = array();

		// Validate if customer is already logged out.
		if ($this->customer->isLogged()) {
			$json['redirect'] = $this->url->link('quickcheckout/checkout', '', 'SSL');
		}

		if (!$json) {
			/*$firstname = $this->config->get('quickcheckout_field_firstname');

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

			$email = $this->config->get('quickcheckout_field_email');

			if (!empty($email['required'])) {
				if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
					$json['error']['email'] = $this->language->get('error_email');
				}
			}

			$telephone = $this->config->get('quickcheckout_field_telephone');

			if (!empty($telephone['required'])) {
				if ((utf8_strlen($this->request->post['telephone']) < 6) || (utf8_strlen($this->request->post['telephone']) > 32)) {
					$json['error']['telephone'] = $this->language->get('error_telephone');
				}
			}

			$fax = $this->config->get('quickcheckout_field_fax');

			if (!empty($fax['required'])) {
				if ((utf8_strlen($this->request->post['fax']) < 3) || (utf8_strlen($this->request->post['fax']) > 32)) {
					$json['error']['fax'] = $this->language->get('error_fax');
				}
			}

			$company = $this->config->get('quickcheckout_field_company');

			if (!empty($company['required'])) {
				if ((utf8_strlen($this->request->post['company']) < 3) || (utf8_strlen($this->request->post['company']) > 32)) {
					$json['error']['company'] = $this->language->get('error_company');
				}
			}

			$address_1 = $this->config->get('quickcheckout_field_address_1');

			if (!empty($address_1['required'])) {
				if ((utf8_strlen($this->request->post['address_1']) < 3) || (utf8_strlen($this->request->post['address_1']) > 128)) {
					$json['error']['address_1'] = $this->language->get('error_address_1');
				}
			}

			$address_2 = $this->config->get('quickcheckout_field_address_2');

			if (!empty($address_2['required'])) {
				if ((utf8_strlen($this->request->post['address_2']) < 3) || (utf8_strlen($this->request->post['address_2']) > 128)) {
					$json['error']['address_2'] = $this->language->get('error_address_2');
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
			
			// Customer Group
			if (isset($this->request->post['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->post['customer_group_id'], $this->config->get('config_customer_group_display'))) {
				$customer_group_id = $this->request->post['customer_group_id'];
			} else {
				$customer_group_id = $this->config->get('config_customer_group_id');
			}

			// Custom field validation
			$this->load->model('account/custom_field');

			$custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {
					$json['error']['custom_field' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
				}
			}*/
			
			if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
				$json['error']['warning'] = $this->language->get('error_exists');
			}

			if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
				$json['error']['password'] = $this->language->get('error_password');
			}

			if ($this->request->post['confirm'] != $this->request->post['password']) {
				$json['error']['confirm'] = $this->language->get('error_confirm');
			}

			if ($this->config->get('config_account_id')) {
				$this->load->model('catalog/information');

				$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

				if ($information_info && !isset($this->request->post['agree'])) {
					$json['error']['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
				}
			}
		}

		if (!$json) {
			$customer_id = $this->model_account_customer->addCustomer($this->request->post);
			
			// Clear any previous login attempts for unregistered accounts.
			$this->model_account_customer->deleteLoginAttempts($this->request->post['email']);

			$this->session->data['account'] = 'register';
			
			if (isset($this->request->post['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->post['customer_group_id'], $this->config->get('config_customer_group_display'))) {
				$customer_group_id = $this->request->post['customer_group_id'];
			} else {
				$customer_group_id = $this->config->get('config_customer_group_id');
			}

			$this->load->model('account/customer_group');

			$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

			if ($customer_group_info && !$customer_group_info['approval']) {
				$this->customer->login($this->request->post['email'], $this->request->post['password']);

				// Default Payment Address
				$this->load->model('account/address');

				$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());

				if (!empty($this->request->post['shipping_address'])) {
					$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
				}
			} else {
				$json['redirect'] = $this->url->link('account/success');
			}

			unset($this->session->data['guest']);

			// Add to activity log
			$this->load->model('account/activity');

			$activity_data = array(
				'customer_id' => $customer_id,
				'name'        => $this->request->post['firstname'] . ' ' . $this->request->post['lastname']
			);

			$this->model_account_activity->addActivity('register', $activity_data);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}