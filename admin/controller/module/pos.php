<?php

define ('POS_VERSION', '1.1.2E');

class ControllerModulePos extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('module/pos');

		$heading_title = $this->language->get('pos_heading_title') . ' V' . POS_VERSION;
		$this->document->setTitle($heading_title);

		$this->load->model('setting/setting');
		$this->load->model('pos/pos');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$data = array();
			foreach ($this->request->post as $key => $value) {
				$data['POS_'.$key] = $value;
			}
			$this->model_setting_setting->editSetting('POS', $data);

			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $heading_title;
		$data['text_edit_pos_settings'] = $this->language->get('text_edit_pos_settings');
		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$data['tab_settings_payment_type'] = $this->language->get('tab_settings_payment_type');
		$data['tab_settings_options'] = $this->language->get('tab_settings_options');
		$data['tab_settings_order'] = $this->language->get('tab_settings_order');
		$data['tab_settings_customer'] = $this->language->get('tab_settings_customer');

		$data['payment_types'] = $this->config->get('POS_POS_payment_types');
		if (empty($data['payment_types'])) {
			// first time run the module, create the default payment types
			$data['payment_types'] = array('cash'=>'Cash', 'credit_card'=>'Credit Card');
			$this->model_setting_setting->editSetting('POS', array('POS_POS_payment_types' => $data['payment_types']));
			$payment_type_enables = array('cash'=>1);
			$this->model_setting_setting->editSetting('POS', array('POS_payment_type_enables' => $payment_type_enables));
		}
		$data['payment_type_enables'] = $this->config->get('POS_payment_type_enables') ? $this->config->get('POS_payment_type_enables') : array();
		$data['text_order_status_setting'] = $this->language->get('text_order_status_setting');
		$data['entry_complete_status'] = $this->language->get('entry_complete_status');
		$data['complete_status_id'] = $this->config->get('POS_complete_status_id') ? $this->config->get('POS_complete_status_id') : 5;
		$data['entry_parking_status'] = $this->language->get('entry_parking_status');
		$data['parking_status_id'] = $this->config->get('POS_parking_status_id') ? $this->config->get('POS_parking_status_id') : 1;
		$data['entry_void_status'] = $this->language->get('entry_void_status');
		$data['void_status_id'] = $this->config->get('POS_void_status_id') ? $this->config->get('POS_void_status_id') : 16;

		$data['text_order_payment_type'] = $this->language->get('text_order_payment_type');
		$data['text_order_payment_eanble'] = $this->language->get('text_order_payment_eanble');
		$data['text_action'] = $this->language->get('text_action');
		$data['text_type_already_exist'] = $this->language->get('text_type_already_exist');
		$data['text_payment_type_setting'] = $this->language->get('text_payment_type_setting');
		$data['text_display_setting'] = $this->language->get('text_display_setting');
		$data['text_display_once_login'] = $this->language->get('text_display_once_login');
		$data['column_exclude'] = $this->language->get('column_exclude');
		$data['text_select_all'] = $this->language->get('text_select_all');
		$data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$data['button_delete'] = $this->language->get('button_delete');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_add_type'] = $this->language->get('button_add_type');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['text_pos_wait'] = $this->language->get('text_pos_wait');
		$data['text_customer_setting'] = $this->language->get('text_customer_setting');
		$data['text_customer_system'] = $this->language->get('text_customer_system');
		$data['text_customer_custom'] = $this->language->get('text_customer_custom');
		$data['text_customer_existing'] = $this->language->get('text_customer_existing');
		$data['text_customer_info'] = $this->language->get('text_customer_info');
		$data['text_address_info'] = $this->language->get('text_address_info');
		$data['text_customer'] = $this->language->get('text_customer');
		$this->language->load('sale/order');
    	$data['entry_firstname'] = $this->language->get('entry_firstname');
    	$data['entry_lastname'] = $this->language->get('entry_lastname');
    	$data['entry_email'] = $this->language->get('entry_email');
    	$data['entry_telephone'] = $this->language->get('entry_telephone');
    	$data['entry_fax'] = $this->language->get('entry_fax');
		$data['entry_address_1'] = $this->language->get('entry_address_1');
		$data['entry_address_2'] = $this->language->get('entry_address_2');
		$data['entry_city'] = $this->language->get('entry_city');
		$data['entry_postcode'] = $this->language->get('entry_postcode');
		$data['entry_zone'] = $this->language->get('entry_zone');
		$data['entry_country'] = $this->language->get('entry_country');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_autocomplete'] = $this->language->get('text_autocomplete');
		$data['text_customer_group'] = $this->language->get('text_customer_group');
		$this->load->model('localisation/country');
		$data['c_countries'] = $this->model_localisation_country->getCountries();
		$this->setDefaultCustomer($data);
		$this->load->model('sale/customer_group');
		$data['c_groups'] = $this->model_sale_customer_group->getCustomerGroups();

		$data['token'] = $this->session->data['token'];

		$this->load->model('user/user');
		$data['users'] = $this->model_user_user->getUsers();
		$this->load->model('user/user_group');
		$data['user_groups'] = $this->model_user_user_group->getUserGroups();

		$excluded_groups = array();
		if ($this->config->get('POS_excluded_groups')) {
			$excluded_groups = $this->config->get('POS_excluded_groups');
		}
		$data['excluded_groups'] = $excluded_groups;

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('pos_heading_title'),
			'href'      => $this->url->link('module/pos', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$data['action'] = $this->url->link('module/pos', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$data['display_once_login'] = $this->config->get('POS_display_once_login');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$data['is_label_user'] = (empty($this->session->data['is_label_user'])) ? false : true;

		$this->response->setOutput($this->load->view('pos/settings.tpl', $data));
	}

	protected function validate() {
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	private function setDefaultCustomer(&$data) {
		// add for Default Customer begin
		$default_country_id = $this->config->get('config_country_id');
		$default_zone_id = $this->config->get('config_zone_id');
		$data['c_id'] = $this->config->get('POS_c_id') ? $this->config->get('POS_c_id') : 0;
		$data['c_group_id'] = $this->config->get('POS_c_group_id') ? $this->config->get('POS_c_group_id') : 1;
		$use_default_general = true;
		$use_default_address = true;
		if ($this->config->get('POS_c_type') == 2 || $this->config->get('POS_c_type') == 3) {
			$data['c_type'] = $this->config->get('POS_c_type');
			if ($this->config->get('POS_c_type') == 2) {
				// use the configuration from settings table
				$data['a_country_id'] = $this->config->get('POS_a_country_id') ? $this->config->get('POS_a_country_id') : $default_country_id;
				$data['a_zone_id'] = $this->config->get('POS_a_zone_id') ? $this->config->get('POS_a_zone_id') : $default_zone_id;
				$data['c_firstname'] = $this->config->get('POS_c_firstname') ? $this->config->get('POS_c_firstname') : 'Instore';
				$data['c_lastname'] = $this->config->get('POS_c_lastname') ? $this->config->get('POS_c_lastname') : 'Dummy';
				$data['c_name'] = $data['c_firstname'] . ' ' . $data['c_lastname'];
				$data['c_email'] = $this->config->get('POS_c_email') ? $this->config->get('POS_c_email') : 'customer@instore.com';
				$data['c_telephone'] = $this->config->get('POS_c_telephone') ? $this->config->get('POS_c_telephone') : '1600';
				$data['c_fax'] = $this->config->get('POS_c_fax') ? $this->config->get('POS_c_fax') : '';
				$data['a_firstname'] = $this->config->get('POS_a_firstname') ? $this->config->get('POS_a_firstname') : 'Instore';
				$data['a_lastname'] = $this->config->get('POS_a_lastname') ? $this->config->get('POS_a_lastname') : 'Dummy';
				$data['a_address_1'] = $this->config->get('POS_a_address_1') ? $this->config->get('POS_a_address_1') : 'customer address';
				$data['a_address_2'] = $this->config->get('POS_a_address_2') ? $this->config->get('POS_a_address_2') : '';
				$data['a_city'] = $this->config->get('POS_a_city') ? $this->config->get('POS_a_city') : 'customer city';
				$data['a_postcode'] = $this->config->get('POS_a_postcode') ? $this->config->get('POS_a_postcode') : '1600';
				$use_default_general = false;
				$use_default_address = false;
			} else {
				// get the first address from customer address
				$this->load->model('sale/customer');
				$c_info = $this->model_sale_customer->getCustomer($data['c_id']);
				if ($c_info) {
					$use_default_general = false;
					$data['c_group_id'] = $c_info['customer_group_id'];
					$data['c_firstname'] = $c_info['firstname'];
					$data['c_lastname'] = $c_info['lastname'];
					$data['c_name'] = $data['c_firstname'] . ' ' . $data['c_lastname'];
					$data['c_email'] = $c_info['email'];
					$data['c_telephone'] = $c_info['telephone'];
					$data['c_fax'] = $c_info['fax'];
					$data['c_address_id'] = $c_info['address_id'];
				}
				$c_addresses = $this->model_sale_customer->getAddresses($data['c_id']);
				$data['c_addresses'] = $c_addresses;
				ksort($c_addresses);
				if (count($c_addresses) > 0) {
					$use_default_address = false;
					foreach ($c_addresses as $c_address) {
						$data['a_country_id'] = $c_address['country_id'];
						$data['a_zone_id'] = $c_address['zone_id'];
						$data['a_firstname'] = $c_address['firstname'];
						$data['a_lastname'] = $c_address['lastname'];
						$data['a_address_1'] = $c_address['address_1'];
						$data['a_address_2'] = $c_address['address_2'];
						$data['a_city'] = $c_address['city'];
						$data['a_postcode'] = $c_address['postcode'];
						break;
					}
				}
			}
		} else {
			$data['c_type'] = 1;
		}

		$data['buildin'] = array();
		$data['buildin']['c_firstname'] = 'Instore';
		$data['buildin']['c_lastname'] = "Dummy";
		$data['buildin']['c_name'] = $data['buildin']['c_firstname'] . ' ' . $data['buildin']['c_lastname'];
		$data['buildin']['c_email'] = 'customer@instore.com';
		$data['buildin']['c_telephone'] = '1600';
		$data['buildin']['c_fax'] = '';
		$data['buildin']['a_country_id'] = $default_country_id;
		$data['buildin']['a_zone_id'] = $default_zone_id;
		$data['buildin']['a_firstname'] = 'Instore';
		$data['buildin']['a_lastname'] = "Dummy";
		$data['buildin']['a_address_1'] = 'customer address';
		$data['buildin']['a_address_2'] = '';
		$data['buildin']['a_city'] = 'customer city';
		$data['buildin']['a_postcode'] = '1600';

		if ($use_default_general) {
			$data['c_firstname'] = 'Instore';
			$data['c_lastname'] = "Dummy";
			$data['c_name'] = $data['c_firstname'] . ' ' . $data['c_lastname'];
			$data['c_email'] = 'customer@instore.com';
			$data['c_telephone'] = '1600';
			$data['c_fax'] = '';
		}
		if ($use_default_address) {
			$data['a_country_id'] = $default_country_id;
			$data['a_zone_id'] = $default_zone_id;
			$data['a_firstname'] = 'Instore';
			$data['a_lastname'] = "Dummy";
			$data['a_address_1'] = 'customer address';
			$data['a_address_2'] = '';
			$data['a_city'] = 'customer city';
			$data['a_postcode'] = '1600';
		}
	}

	public function install() {
		// install modification
		$this->installModifiation();

		// create tables
		$this->load->model('pos/pos');
		$this->model_pos_pos->createModuleTables();

		// copy language file is English not set to default
		$this->copyLangFile();
		// install in store shipping and payment method
		$this->load->model('extension/extension');
		$this->load->model('setting/setting');
		$this->load->model('extension/extension');
		if ($this->user->hasPermission('modify', 'extension/shipping')) {
			$this->model_extension_extension->install('shipping', 'instore');
			$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'shipping/instore');
			$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'shipping/instore');
			$this->model_setting_setting->editSetting('instore', array('instore_geo_zone_id'=>'0', 'instore_status'=>'1', 'instore_sort_order'=>'1'));
		}
		if ($this->user->hasPermission('modify', 'extension/payment')) {
			$this->model_extension_extension->install('payment', 'in_store');
			$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'payment/in_store');
			$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'payment/in_store');
			$this->model_setting_setting->editSetting('in_store', array('in_store_geo_zone_id'=>'0', 'in_store_status'=>'1', 'in_store_sort_order'=>'1'));
		}

		// create new point of sale groups
		$ignore = array(
			'common/home',
			'common/startup',
			'common/login',
			'common/logout',
			'common/forgotten',
			'common/reset',
			'error/not_found',
			'error/permission',
			'common/footer',
			'common/header'
		);

		$data['permission'] = array();
		$data['permission']['access'] = array();
		$data['permission']['modify'] = array();

		$files = glob(DIR_APPLICATION . 'controller/*/*.php');

		foreach ($files as $file) {
			$file_data = explode('/', dirname($file));

			$permission = end($file_data) . '/' . basename($file, '.php');

			if (!in_array($permission, $ignore)) {
				$data['permission']['access'][] = $permission;
				$data['permission']['modify'][] = $permission;
			}
		}

		// create group
		$this->load->model('user/user_group');
		$groups = $this->model_user_user_group->getUserGroups();

		$pos_groups = array('POS');
		foreach ($pos_groups as $pos_group) {
			$pos_group_defined = false;
			if (!empty($groups)) {
				foreach ($groups as $group) {
					if ($group['name'] == $pos_group) {
						$data['name'] = $pos_group;
						$this->model_user_user_group->editUserGroup($group['user_group_id'], $data);
						$pos_group_defined = true;
						break;
					}
				}
			}
			if (!$pos_group_defined) {
				$data['name'] = $pos_group;
				$this->model_user_user_group->addUserGroup($data);
			}
		}
	}

	private function installModifiation() {
		$installed = false;

		// install the modification
		$file = DIR_APPLICATION . 'model/pos/pos.ocmod.xml';

		if (file_exists($file)) {
			$this->load->model('extension/modification');
			$xml = file_get_contents($file);
			if ($xml) {
				try {
					$dom = new DOMDocument('1.0', 'UTF-8');
					$dom->loadXml($xml);

					$attrs = array('name', 'author', 'version', 'link', 'code');
					$modification_data = array('status' => 1);

					foreach ($attrs as $attr) {
						$value = $dom->getElementsByTagName($attr)->item(0);
						$value = $value ? $value->nodeValue : '';
						$modification_data[$attr] = $value;
					}

					$this->db->query("DELETE FROM `" . DB_PREFIX . "modification` WHERE name = '" . $this->db->escape($modification_data['name']) . "'");

					$modification_query = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX. "modification` LIKE 'xml'");
					if($modification_query->num_rows == 0){
						// old version of 2.0, does not have xml in the table
						$modification_data['code'] = $xml;
					} else {
						$modification_data['xml'] = $xml;
					}
					$this->model_extension_modification->addModification($modification_data);
					$installed = true;
				} catch(Exception $exception) {
				}
			}
		}

		return $installed;
	}

	public function uninstall() {
		$this->load->model('extension/extension');
		$this->load->model('setting/setting');
		if ($this->user->hasPermission('modify', 'extension/shipping')) {
			$this->model_extension_extension->uninstall('shipping', 'instore');
			$this->model_setting_setting->deleteSetting('instore');
		}
		if ($this->user->hasPermission('modify', 'extension/payment')) {
			$this->model_extension_extension->uninstall('payment', 'in_store');
			$this->model_setting_setting->deleteSetting('in_store');
		}
		// uninstall the modification, this line is commented out because opencart was not processing the uninstallation properly and there are errors when refresh
		// $this->uninstallModification();
	}

	private function copyLangFile() {
		$supported_languages = array();
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language`");
		foreach ($query->rows as $result) {
			$supported_languages[$result['code']] = $result;
		}
		$directory = $supported_languages[$this->config->get('config_admin_language')]['directory'];
		if ($directory != 'english') {
			copy(DIR_LANGUAGE . 'english/module/pos.php', DIR_LANGUAGE . $directory . '/module/pos.php');
		}
	}

	public function addOrderPayment() {
		$this->load->model('pos/pos');
		$this->language->load('module/pos');
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$order_payment_id = $this->model_pos_pos->addOrderPayment($this->request->post);
			$json = array();
			$json['success'] = $this->language->get('text_cash_success');
			$json['order_payment_id'] = $order_payment_id;
			$this->response->setOutput(json_encode($json));
		}
	}

	public function deleteOrderPayment() {
		$this->load->model('pos/pos');
		if ($this->request->server['REQUEST_METHOD'] == 'GET') {
			$this->model_pos_pos->deleteOrderPayment($this->request->get['order_payment_id']);
			$this->response->setOutput(json_encode(array()));
		}
	}

	public function modifyOrderComment() {
		$this->load->model('pos/pos');
		if ($this->request->server['REQUEST_METHOD'] == 'GET') {
			$this->model_pos_pos->modifyOrderComment($this->request->get);
			$this->response->setOutput(json_encode(array()));
		}
	}

	public function main() {
		$this->language->load('module/pos');
		$this->language->load('sale/order');
		$this->load->model('pos/pos');
		$this->load->model('sale/order');

		$data = array();

		if (!empty($this->request->get['order_id'])) {
			$this->getOrderProducts($this->request->get['order_id'], $data);
		} else {
			$this->getOrderProducts($this->getEmptyOrder(), $data);
		}

		if (!empty($this->request->get['ajax'])) {
			// if it's loading another order, just return order data
			$this->response->setOutput(json_encode($data));
		} else {
			$this->document->setTitle($this->language->get('pos_heading_title'));

			$this->load->model('setting/setting');

			if (isset($this->session->data['pos_user_login'])) {
				unset($this->session->data['pos_user_login']);
				$this->search_for_update();
			}
			$data['user'] = $this->model_pos_pos->get_full_username($this->user->getId(), false);

			$data['text_workmode_sale'] = $this->language->get('text_workmode_sale');
			$data['text_search_placeholder'] = $this->language->get('text_search_placeholder');
			$data['text_order_list'] = $this->language->get('text_order_list');
			$data['text_fetching_orders'] = $this->language->get('text_fetching_orders');
			$data['text_change_order_status'] = $this->language->get('text_change_order_status');
			$data['text_change_customer'] = $this->language->get('text_change_customer');
			$data['text_reset_customer'] = $this->language->get('text_reset_customer');
			$data['text_select_customer'] = $this->language->get('text_select_customer');
			$data['text_add_customer'] = $this->language->get('text_add_customer');
			$data['tab_customer_general'] = $this->language->get('tab_customer_general');
			$data['tab_customer_new_address'] = $this->language->get('tab_customer_new_address');
			$data['text_fetching_customers'] = $this->language->get('text_fetching_customers');
			$data['text_customer_list'] = $this->language->get('text_customer_list');
			$data['column_customer_id'] = $this->language->get('column_customer_id');
			$data['column_customer_name'] = $this->language->get('column_customer_name');
			$data['column_email'] = $this->language->get('column_email');
			$data['column_telephone'] = $this->language->get('column_telephone');
			$data['text_fetching_product_details'] = $this->language->get('text_fetching_product_details');
			$data['text_product_details'] = $this->language->get('text_product_details');
			$data['text_change_quantity'] = $this->language->get('text_change_quantity');
			$data['text_show_totals'] = $this->language->get('text_show_totals');
			$data['text_saving_order_status'] = $this->language->get('text_saving_order_status');
			$data['text_make_payment'] = $this->language->get('text_make_payment');

			$data['text_search_scope'] = $this->language->get('text_search_scope');
			$data['text_search_product_name'] = $this->language->get('text_search_product_name');
			$data['text_search_model_name'] = $this->language->get('text_search_model_name');
			$data['text_search_manufacturer'] = $this->language->get('text_search_manufacturer');
			$data['text_search_model_short'] = $this->language->get('text_search_model_short');
			$data['text_search_manufacturer_short'] = $this->language->get('text_search_manufacturer_short');
			$data['button_set_scope'] = $this->language->get('button_set_scope');

			$data['text_order_id'] = $this->language->get('text_order_id');
			$data['text_invoice_no'] = $this->language->get('text_invoice_no');
			$data['text_invoice_date'] = $this->language->get('text_invoice_date');
			$data['text_store_name'] = $this->language->get('text_store_name');
			$data['text_store_url'] = $this->language->get('text_store_url');
			$data['text_default'] = $this->language->get('text_default');
			$data['text_customer_group'] = $this->language->get('text_customer_group');
			$data['text_total'] = $this->language->get('text_total');
			$data['text_reward'] = $this->language->get('text_reward');
			$data['text_order_status'] = $this->language->get('text_order_status');
			$data['text_comment'] = $this->language->get('text_comment');
			$data['text_order_ready'] = $this->language->get('text_order_ready');
			$data['text_pos_wait'] = $this->language->get('text_pos_wait');
			$data['entry_firstname'] = $this->language->get('entry_firstname');
			$data['entry_lastname'] = $this->language->get('entry_lastname');
			$data['entry_email'] = $this->language->get('entry_email');
			$data['entry_telephone'] = $this->language->get('entry_telephone');
			$data['entry_fax'] = $this->language->get('entry_fax');
			$data['entry_company'] = $this->language->get('entry_company');
			$data['entry_company_id'] = $this->language->get('entry_company_id');
			$data['entry_tax_id'] = $this->language->get('entry_tax_id');
			$data['entry_address_1'] = $this->language->get('entry_address_1');
			$data['entry_address_2'] = $this->language->get('entry_address_2');
			$data['entry_city'] = $this->language->get('entry_city');
			$data['entry_postcode'] = $this->language->get('entry_postcode');
			$data['entry_zone'] = $this->language->get('entry_zone');
			$data['entry_country'] = $this->language->get('entry_country');

			$data['button_save'] = $this->language->get('button_save');
			$data['button_cancel'] = $this->language->get('button_cancel');
			$data['entry_amount'] = $this->language->get('entry_amount');
			$data['text_product'] = $this->language->get('text_product');
			$data['entry_product'] = $this->language->get('entry_product');
			$data['entry_name'] = $this->language->get('entry_name');
			$data['entry_description'] = $this->language->get('entry_description');
			$data['entry_price'] = $this->language->get('entry_price');
			$data['entry_quantity'] = $this->language->get('entry_quantity');
			$data['entry_location'] = $this->language->get('entry_location');
			$data['entry_minimum'] = $this->language->get('entry_minimum');
			$data['entry_thumb'] = $this->language->get('entry_thumb');
			$data['column_attr_name'] = $this->language->get('column_attr_name');
			$data['column_attr_value'] = $this->language->get('column_attr_value');
			$data['entry_options'] = $this->language->get('entry_options');

			if (isset($this->session->data['text_decimal_point']) && isset($this->session->data['text_thousand_point'])) {
				$data['text_decimal_point'] = $this->session->data['text_decimal_point'];
				$data['text_thousand_point'] = $this->session->data['text_thousand_point'];
			} else {
				// get the decimal point and thousand point from the front side language instead of the admin language
				$this->load->model('localisation/language');
				$languages = $this->model_localisation_language->getLanguages();
				$lang_dir = 'english';
				$lang_file = 'english';
				foreach ($languages as $language) {
					if ($language['code'] == $this->config->get('config_language')) {
						$lang_dir = $language['directory'];
						$lang_file = isset($language['filename']) ? $language['filename'] : $language['directory'];
						break;
					}
				}
				include_once (DIR_CATALOG . 'language/' . $lang_dir . '/' . $lang_file . '.php');
				// include_once (DIR_CATALOG . 'language/english/default.php');
				$data['text_decimal_point'] = $_['decimal_point'];
				$data['text_thousand_point'] = $_['thousand_point'];

				$this->session->data['text_decimal_point'] = $_['decimal_point'];
				$this->session->data['text_thousand_point'] = $_['thousand_point'];
			}

			$data['heading_title'] = $this->language->get('pos_heading_title');

			$data['text_terminal'] = $this->language->get('text_terminal');
			$data['text_register_mode'] = $this->language->get('text_register_mode');
			$data['text_date_added'] = $this->language->get('text_date_added');
			$data['text_date_modified'] = $this->language->get('text_date_modified');
			$data['text_customer'] = $this->language->get('text_customer');
			$data['text_product_quantity'] = $this->language->get('text_product_quantity');
			$data['text_items_in_cart']  = $this->language->get('text_items_in_cart');
			$data['text_amount_due']  = $this->language->get('text_amount_due');
			$data['text_change']  = $this->language->get('text_change');
			$data['text_payment_zero_amount']  = $this->language->get('text_payment_zero_amount');
			$data['text_quantity_zero']  = $this->language->get('text_quantity_zero');
			$data['text_comments'] = $this->language->get('text_comments');
			$data['text_order_success'] = $this->language->get('text_order_success');
			$data['text_load_order'] = $this->language->get('text_load_order');
			$data['text_filter_order_list'] = $this->language->get('text_filter_order_list');
			$data['text_load_order_list'] = $this->language->get('text_load_order_list');

			$data['text_product_name'] = $this->language->get('text_product_name');
			$data['text_product_upc'] = $this->language->get('text_product_upc');
			$data['text_no_order_selected'] = $this->language->get('text_no_order_selected');
			$data['text_confirm_delete_order'] = $this->language->get('text_confirm_delete_order');
			$data['text_not_available'] = $this->language->get('text_not_available');
			$data['text_del_payment_confirm'] = $this->language->get('text_del_payment_confirm');
			$data['text_autocomplete'] = $this->language->get('text_autocomplete');
			$data['text_customer_no_address'] = $this->language->get('text_customer_no_address');

			$data['column_payment_type']  = $this->language->get('column_payment_type');
			$data['column_payment_amount']  = $this->language->get('column_payment_amount');
			$data['column_payment_note']  = $this->language->get('column_payment_note');
			$data['column_payment_action']  = $this->language->get('column_payment_action');

			$data['button_add_payment']  = $this->language->get('button_add_payment');

			$data['text_none'] = $this->language->get('text_none');

			$data['text_no_results'] = $this->language->get('text_no_results');
			$data['text_missing'] = $this->language->get('text_missing');
			$data['text_wait'] = $this->language->get('text_wait');

			$data['column_order_id'] = $this->language->get('column_order_id');
			$data['column_customer'] = $this->language->get('column_customer');
			$data['column_status'] = $this->language->get('column_status');
			$data['column_total'] = $this->language->get('column_total');
			$data['column_date_added'] = $this->language->get('column_date_added');
			$data['column_date_modified'] = $this->language->get('column_date_modified');
			$data['column_action'] = $this->language->get('column_action');

			$data['button_invoice'] = $this->language->get('button_invoice');
			$data['button_insert'] = $this->language->get('button_insert');
			$data['button_filter'] = $this->language->get('button_filter');
			$data['entry_option'] = $this->language->get('entry_option');
			$data['error_required'] = $this->language->get('error_required');
			$data['text_park_order']  = $this->language->get('text_park_order');
			$data['text_park_quote']  = $this->language->get('text_park_quote');

			$data['text_order_comment'] = $this->language->get('text_order_comment');
			$data['column_order_total'] = $this->language->get('column_order_total');
			$data['text_print'] = $this->language->get('text_print');
			$data['button_delete'] = $this->language->get('button_delete');
			$data['column_product_options'] = $this->language->get('column_product_options');
			$data['button_ok'] = $this->language->get('button_ok');
			$data['button_add_product'] = $this->language->get('button_add_product');

			// add for Print begin
			$data['print_wait_title'] = $this->language->get('print_wait_title');
			$data['print_wait_message'] = $this->language->get('print_wait_message');
			$data['print_receipt_message'] = $this->language->get('print_receipt_message');
			// add for Print end
			// add for Invoice Print begin
			$data['print_invoice_message'] = $this->language->get('print_invoice_message');
			// add for Invoice Print end
			// add for Browse begin
			$data['text_top_category_id'] = '0';
			$data['text_top_category_name'] = $this->language->get('text_top_category_name');
			// add for Browse end

			$text_week_0 = $this->language->get('text_week_0');
			$text_week_1 = $this->language->get('text_week_1');
			$text_week_2 = $this->language->get('text_week_2');
			$text_week_3 = $this->language->get('text_week_3');
			$text_week_4 = $this->language->get('text_week_4');
			$text_week_5 = $this->language->get('text_week_5');
			$text_week_6 = $this->language->get('text_week_6');
			$data['text_weeks'] = array($text_week_0, $text_week_1, $text_week_2, $text_week_3, $text_week_4, $text_week_5, $text_week_6);

			$text_month_1 = $this->language->get('text_month_1');
			$text_month_2 = $this->language->get('text_month_2');
			$text_month_3 = $this->language->get('text_month_3');
			$text_month_4 = $this->language->get('text_month_4');
			$text_month_5 = $this->language->get('text_month_5');
			$text_month_6 = $this->language->get('text_month_6');
			$text_month_7 = $this->language->get('text_month_7');
			$text_month_8 = $this->language->get('text_month_8');
			$text_month_9 = $this->language->get('text_month_9');
			$text_month_10 = $this->language->get('text_month_10');
			$text_month_11 = $this->language->get('text_month_11');
			$text_month_12 = $this->language->get('text_month_12');
			$data['text_monthes'] = array($text_month_1, $text_month_2, $text_month_3, $text_month_4, $text_month_5, $text_month_6, $text_month_7, $text_month_8, $text_month_9, $text_month_10, $text_month_11, $text_month_12);

			$this->language->load('sale/customer');
			$data['text_enabled'] = $this->language->get('text_enabled');
			$data['text_disabled'] = $this->language->get('text_disabled');
			$data['text_select'] = $this->language->get('text_select');

			$data['entry_password'] = $this->language->get('entry_password');
			$data['entry_confirm'] = $this->language->get('entry_confirm');
			$data['entry_newsletter'] = $this->language->get('entry_newsletter');
			$data['entry_customer_group'] = $this->language->get('entry_customer_group');
			$data['entry_status'] = $this->language->get('entry_status');
			$data['entry_default'] = $this->language->get('entry_default');

			$data['button_add_address'] = $this->language->get('button_add_address');
			$data['button_remove'] = $this->language->get('button_remove');

			$data['tab_general'] = $this->language->get('tab_general');
			$data['tab_address'] = $this->language->get('tab_address');

			if (isset($this->error['warning'])) {
				$data['error_warning'] = $this->error['warning'];
			} else {
				$data['error_warning'] = '';
			}

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => false
			);
			$data['breadcrumbs'][] = array(
				'text'      => $this->language->get('pos_heading_title'),
				'href'      => $this->url->link('module/pos/main', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => ' :: '
			);

			$data['action'] = $this->url->link('module/pos', 'token=' . $this->session->data['token'], 'SSL');

			$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

			$data['text_select'] = $this->language->get('text_select');

			$delete_excluded_groups = array();
			if ($this->config->get('POS_enable_hide_delete') && $this->config->get('POS_delete_excluded_groups')) {
				$delete_excluded_groups = $this->config->get('POS_delete_excluded_groups');
			}

			$data['user_id'] = $this->user->getId();
			$this->load->model('user/user');
			$user = $this->model_user_user->getUser($this->user->getId());
			$user_group_id = 0;
			if ($user) {
				$user_group_id = $user['user_group_id'];
			}

			$data['payment_types'] = $this->config->get('POS_POS_payment_types');
			$payment_type_enables = $this->config->get('POS_payment_type_enables');
			if (empty($payment_type_enables)) {
				$payment_type_enables = array();
			}
			if (empty($data['payment_types'])) {
				// first time run the module, create the default payment types
				$data['payment_types'] = array('cash'=>'Cash', 'credit_card'=>'Credit Card');
				$payment_type_enables = array('cash'=>1);
			}
			// Control the payment type to be displayed as per enablement
			if (!empty($data['payment_types'])) {
				foreach ($data['payment_types'] as $payment_type => $payment_name) {
					if (!(array_key_exists($payment_type, $payment_type_enables) && $payment_type_enables[$payment_type] == 1)) {
						unset($data['payment_types'][$payment_type]);
					}
				}
			}

			if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
				$data['store_url'] = HTTPS_CATALOG;
			} else {
				$data['store_url'] = HTTP_CATALOG;
			}

			$data['full_screen_mode'] = 1;

			$data['text_saving_customer'] = $this->language->get('text_saving_customer');
			// add for Complete Status begin
			$data['complete_status_id'] = $this->config->get('POS_complete_status_id') ? $this->config->get('POS_complete_status_id') : 5;
			$data['parking_status_id'] = $this->config->get('POS_parking_status_id') ? $this->config->get('POS_parking_status_id') : 1;
			$data['void_status_id'] = $this->config->get('POS_void_status_id') ? $this->config->get('POS_void_status_id') : 16;
			// add for Complete Status end

			$data['token'] = $this->session->data['token'];

			$this->load->model('localisation/order_status');
			$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
			$data['order_payment_post_status'] = array();
			foreach ($data['order_statuses'] as $key => $value) {
				if ($value['order_status_id'] == $data['order_status_id']) {
					$data['order_status_name'] = $value['name'];
				}
				if ($value['order_status_id'] == $data['complete_status_id']) {
					$data['order_payment_post_status'][$data['complete_status_id']] = $this->language->get('button_complete_order');
				} elseif ($value['order_status_id'] == $data['parking_status_id']) {
					$data['order_payment_post_status'][$data['parking_status_id']] = $this->language->get('button_park_order');
				} elseif ($value['order_status_id'] == $data['void_status_id']) {
					$data['order_payment_post_status'][$data['void_status_id']] = $this->language->get('button_void_order');
				}
			}
			$data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
			$data['customer_countries'] = $this->model_localisation_country->getCountries();

			if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
				$data['store_admin_url'] = HTTPS_SERVER;
			} else {
				$data['store_admin_url'] = HTTP_SERVER;
			}
			// add for new UI
			$data['text_order_options'] = $this->language->get('text_order_options');
			$data['text_subtotal'] = $this->language->get('text_subtotal');
			$data['text_grandtotal'] = $this->language->get('text_grandtotal');
			$data['text_pay'] = $this->language->get('text_pay');
			$data['button_void_order'] = $this->language->get('button_void_order');
			$data['button_park_order'] = $this->language->get('button_park_order');
			$data['button_complete_order'] = $this->language->get('button_complete_order');
			$data['column_cash_display'] = $this->language->get('column_cash_display');
			$data['column_details_price'] = $this->language->get('column_details_price');
			$data['column_details_model'] = $this->language->get('column_details_model');
			$data['column_details_quantity'] = $this->language->get('column_details_quantity');
			$data['column_details_manufacturer'] = $this->language->get('column_details_manufacturer');
			$data['column_details_sku'] = $this->language->get('column_details_sku');
			$data['column_details_upc'] = $this->language->get('column_details_upc');
			$data['column_details_location'] = $this->language->get('column_details_location');
			$data['column_details_minimum'] = $this->language->get('column_details_minimum');
			$data['column_details_requried'] = $this->language->get('column_details_requried');
			$data['text_yes'] = $this->language->get('text_yes');
			$data['text_no'] = $this->language->get('text_no');

			$data['symbol_left'] = $this->currency->getSymbolLeft();
			$data['symbol_right'] = $this->currency->getSymbolRight();

			$this->load->model('tool/image');
			$data['no_image_url'] = $this->model_tool_image->resize('no_image.jpg', 180, 180);

			// get all config and save it to the local js
			$data['config'] = array();
			$config_query = $this->db->query("SELECT `key` FROM " . DB_PREFIX . "setting WHERE store_id = '0'");
			foreach ($config_query->rows as $row) {
				$key = $row['key'];
				if (substr($key, 0 , 4) == 'POS_') {
					$key = substr($key, 4);
				}
				$data['config'][$key] = $this->config->get($row['key']);
			}

			// generate javascript for variables, that can be used for the order processing
			$var_js_content = '';
			foreach ($data as $key => $value) {
				if (strpos($key, '_') === 0 || $key == 'header' || $key == 'footer' || $key == 'orders') {
					// some special variables, ignore
				} else {
					if (is_array($value)) {
						$var_js_content .= "var " . $key . " = " . json_encode($value) . ";\n";
					} elseif (is_string($value)) {
						$var_js_content .= "var " . $key . " = '" . addslashes($value) . "';\n";
					} elseif (is_int($value) || is_numeric($value) || is_long($value)) {
						$var_js_content .= "var " . $key . " = " . $value . ";\n";
					}
				}
			}
			file_put_contents(DIR_APPLICATION . 'view/javascript/pos/pos_vars.js', $var_js_content);
			// sync the totals from front to the backend
			$this->sync_total_models();

			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('pos/main.tpl', $data));
		}
	}

	private function search_for_update() {
		$post_string = 'version=' . POS_VERSION . '&domain_name=' . $_SERVER['SERVER_NAME'];
		$url = 'http://www.pos4opencart.com/shop/pos_update.php';
		$parts = parse_url($url);

		$fp = @fsockopen($parts['host'], isset($parts['port'])?$parts['port']:80, $errno, $errstr, 30);

		if ($fp) {
			$out = "POST " . $parts['path']. " HTTP/1.1\r\n";
			$out.= "Host: " . $parts['host']. "\r\n";
			$out.= "Content-Type: application/x-www-form-urlencoded\r\n";
			$out.= "Content-Length: ". strlen($post_string) . "\r\n";
			$out.= "Connection: Close\r\n\r\n";
			$out.= $post_string;

			fwrite($fp, $out);
			fclose($fp);
		}
	}

	public function getOrderList() {
		$limit = 8;

 		$this->load->model('pos/pos');
		$data = $this->request->post;
		if (!isset($data['limit'])) {
			$data['limit'] = $limit;
		}
		if (!isset($data['start'])) {
			$data['start'] = 0;
			if (isset($data['page'])) {
				$data['start'] = ((int)$data['page'] - 1) * $data['limit'];
			}
		}

		$order_total = $this->model_pos_pos->getTotalOrders($data);
		$results = $this->model_pos_pos->getOrders($data);

		$json = array();
		$json['orders'] = array();
    	foreach ($results as $result) {
			$json['orders'][] = array(
				'order_id'      => $result['order_id'],
				'customer'      => $result['customer'],
				'status'        => $result['status'],
				'status_id'     => $result['order_status_id'],
				'user_id'		=> $result['user_id'],
				'email'			=> $result['email'],
				'total'         => $this->currency->formatFront($result['total'], $result['currency_code'], $result['currency_value']),
				'date_added'    => $result['date_added'],
				'date_modified' => $result['date_modified']
			);
		}
		$page = (!empty($data['page'])) ? $data['page'] : 1;
		$json['pagination'] = $this->getPagination($order_total, $page, $limit, 'selectOrderPage');

		$this->response->setOutput(json_encode($json));
 	}

	public function deleteOrder() {
		if (isset($this->request->post['order_selected'])) {
			$this->load->model('pos/pos');
			// selected orders to be deleted
			foreach ($this->request->post['order_selected'] as $order_id) {
				$this->model_pos_pos->deleteOrder($order_id);
			}
			$this->getOrderList();
		}
	}

	private function getPagination($total, $page, $limit, $function) {
		$num_links = 10;
		$num_pages = ceil($total / $limit);
		$text = $this->language->get('text_pagination');

		$output = '<ul class="pos-pager">';
		if ($page > 1) {
			$output .= '<li><a onclick="' . $function . '(1);" class="arrow first"></a></li><li><a onclick="' . $function . '(' . ($page - 1) . ');" class="arrow prev"></a></li>';
    	}

		if ($num_pages > 1) {
			if ($num_pages <= $num_links) {
				$start = 1;
				$end = $num_pages;
			} else {
				$start = $page - floor($num_links / 2);
				$end = $page + floor($num_links / 2);

				if ($start < 1) {
					$end += abs($start) + 1;
					$start = 1;
				}

				if ($end > $num_pages) {
					$start -= ($end - $num_pages);
					$end = $num_pages;
				}
			}

			for ($i = $start; $i <= $end; $i++) {
				if ($page == $i) {
					$output .= '<li><a class="active">' . $i . '</a></li>';
				} else {
					$output .= '<li><a onclick="' . $function . '(' . $i . ');">' . $i . '</a></li>';
				}
			}
		}

   		if ($page < $num_pages) {
			$output .= '<li><a onclick="' . $function . '(' . ($page + 1) . ');" class="arrow next"></a></li><li><a onclick="' . $function . '(' . $num_pages . ');" class="arrow last"></a></li>';
		}

		$output .= '</ul>';

		$text = sprintf($text, ($total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($total - $limit)) ? $total : ((($page - 1) * $limit) + $limit), $total, $num_pages);

		return '<span class="count">' . $text . '</span>' . $output;
	}

	private function getOrderIdText($order_id) {
		$order_id_text = ''.$order_id;
		$order_id_len = strlen($order_id_text);
		if ($order_id_len < 7) {
			for ($i = 0; $i < 7-$order_id_len; $i++) {
				$order_id_text = '0'.$order_id_text;
			}
		}
		return $order_id_text;
	}

	private function getOrderProducts($order_id, &$data) {
		// unset the shipping method before load it again
		unset($this->session->data['shipping_method']);

		$order_info = $this->model_pos_pos->getOrder($order_id);
		if (!$order_info) {
			$data['order_error'] = sprintf($this->language->get('order_error'), $order_id);
			return;
		}

		if (empty($this->request->get['ajax'])) {
			// add for Browse begin
			$data['browse_items'] = $this->getCategoryItems(0, $order_info['currency_code'], $order_info['currency_value'], $order_info['customer_group_id']);
			// add for Brose end
		}

		$data['order_id'] = $order_id;
		$data['order_id_text'] = $this->getOrderIdText($order_id);

		$data['store_id'] = $order_info['store_id'];
		$data['invoice'] = $this->url->link('sale/order/invoice', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$order_id, 'SSL');

		if ($order_info['invoice_no']) {
			$data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
		} else {
			$data['invoice_no'] = '';
		}

		$data['store_name'] = $order_info['store_name'];
		$data['store_url'] = $order_info['store_url'];
		$data['firstname'] = $order_info['firstname'];
		$data['lastname'] = $order_info['lastname'];

		if ($order_info['customer_id'] > 0) {
			$data['customer'] = $order_info['customer'];
			$data['customer_id'] = $order_info['customer_id'];
		} else {
			$data['customer'] = $order_info['firstname'].' '.$order_info['lastname'];
			$data['customer_id'] = 0;
		}

		$this->load->model('sale/customer_group');

		$customer_group_info = $this->model_sale_customer_group->getCustomerGroup($order_info['customer_group_id']);
		$data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		$data['customer_group_id'] = $order_info['customer_group_id'];

		if ($customer_group_info) {
			$data['customer_group'] = $customer_group_info['name'];
		} else {
			$data['customer_group'] = '';
		}

		$data['email'] = $order_info['email'];
		$data['telephone'] = $order_info['telephone'];
		$data['fax'] = $order_info['fax'];
		$data['comment'] = $order_info['comment'];
		$data['total'] = $this->currency->formatFront($order_info['total'], $order_info['currency_code'], $order_info['currency_value']);

		if ($order_info['total'] < 0) {
			$data['credit'] = $order_info['total'];
		} else {
			$data['credit'] = 0;
		}

		// use my sql default format yyyy-mm-dd hh:mm:ss across all the module
		// except the invoice, receipt and notifications, which are customer facing presentation
		// all pos user will use the standard date time format
		$data['date_added'] = $order_info['date_added'];
		$data['date_modified'] = $order_info['date_modified'];
		$data['payment_firstname'] = $order_info['payment_firstname'];
		$data['payment_lastname'] = $order_info['payment_lastname'];
		$data['payment_company'] = $order_info['payment_company'];
		$data['payment_address_1'] = $order_info['payment_address_1'];
		$data['payment_address_2'] = $order_info['payment_address_2'];
		$data['payment_city'] = $order_info['payment_city'];
		$data['payment_postcode'] = $order_info['payment_postcode'];
		$data['payment_zone'] = $order_info['payment_zone'];
		$data['payment_zone_code'] = $order_info['payment_zone_code'];
		$data['payment_country'] = $order_info['payment_country'];
		$data['payment_country_id'] = $order_info['payment_country_id'];
		$data['payment_zone_id'] = $order_info['payment_zone_id'];
		$data['shipping_firstname'] = $order_info['shipping_firstname'];
		$data['shipping_lastname'] = $order_info['shipping_lastname'];
		$data['shipping_company'] = $order_info['shipping_company'];
		$data['shipping_address_1'] = $order_info['shipping_address_1'];
		$data['shipping_address_2'] = $order_info['shipping_address_2'];
		$data['shipping_city'] = $order_info['shipping_city'];
		$data['shipping_postcode'] = $order_info['shipping_postcode'];
		$data['shipping_zone'] = $order_info['shipping_zone'];
		$data['shipping_zone_code'] = $order_info['shipping_zone_code'];
		$data['shipping_country'] = $order_info['shipping_country'];
		$data['shipping_country_id'] = $order_info['shipping_country_id'];
		$data['shipping_zone_id'] = $order_info['shipping_zone_id'];
		$data['shipping_code'] = $order_info['shipping_code'];
		$data['shipping_method'] = $order_info['shipping_method'];
		$data['payment_method'] = $order_info['payment_method'];
		$data['payment_code'] = $order_info['payment_code'];

		$this->getCustomer($order_info['customer_id'], $data);
		$zones = array();
		$this->load->model('localisation/country');
		$this->load->model('localisation/zone');
    	$country_info = $this->model_localisation_country->getCountry($order_info['shipping_country_id']);
		if ($country_info) {
			$zones[$order_info['shipping_country_id']] = $this->model_localisation_zone->getZonesByCountryId($order_info['shipping_country_id']);
		}
		if ($order_info['payment_country_id'] != $order_info['shipping_country_id']) {
			$country_info = $this->model_localisation_country->getCountry($order_info['payment_country_id']);
			if ($country_info) {
				$zones[$order_info['payment_country_id']] = $this->model_localisation_zone->getZonesByCountryId($order_info['payment_country_id']);
			}
		}
		$data['zones'] = $zones;
		$data['customer_shipping_zones'] = $this->model_localisation_zone->getZonesByCountryId($data['shipping_country_id']);
		$data['products'] = array();

		$products = $this->model_pos_pos->getOrderProducts($order_id);

		$this->load->model('tool/image');

		$items_in_cart = 0;
		foreach ($products as $product) {
			$option_data = array();

			$options = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);
			$product_total = $product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0);
			$product_total_text = $this->currency->formatFront($product_total, $order_info['currency_code'], $order_info['currency_value']);

			$data['products'][] = array(
				'order_product_id' => $product['order_product_id'],
				'product_id'       => $product['product_id'],
				'name'    	 	   => html_entity_decode($product['name']),
				'model'    		   => $product['model'],
				'image'			   => (!empty($product['image'])) ? $this->model_tool_image->resize($product['image'], 180, 180) : $this->model_tool_image->resize('no_image.jpg', 180, 180),
				'option'   		   => $options,
				'quantity'		   => $product['quantity'],
				'price'			   => $product['price'],
				'subtract'		   => $product['subtract'],
				'price_text'	   => $this->currency->formatFront($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
				'total'			   => $product_total,
				'total_text'       => $product_total_text,
				'tax'			   => $product['tax'],
				'tax_class_id'     => $product['tax_class_id'],
				'shipping'     	   => $product['shipping'],
				'reward'		   => $product['reward'],
				'href'     		   => $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], 'SSL'),
				'selected'		   => isset($this->request->post['selected']) && in_array($product['product_id'], $this->request->post['selected'])
			);

			$items_in_cart += $product['quantity'];
		}
		$data['items_in_cart'] = $items_in_cart;
		$data['currency_code'] = $order_info['currency_code'];
		$data['currency_value'] = $order_info['currency_value'];
		$data['currency_symbol'] = $this->currency->getSymbolLeft($order_info['currency_code']);
		if ($data['currency_symbol'] == '') {
			$data['currency_symbol'] = $this->currency->getSymbolRight($order_info['currency_code']);
		}

		$data['totals'] = $this->model_sale_order->getOrderTotals($order_id);
		foreach ($data['totals'] as $key => $total) {
			$data['totals'][$key]['text'] = $this->currency->formatFront($total['value']);
		}
		// If no total for the current order, use an empty total
		if (empty($data['totals'])) {
			$data['totals'] = array(
				array('code' => 'total',
				'title'      => $this->language->get('text_pos_total'),
				'text'       => $this->currency->formatFront(0),
				'value'      => 0,
				'sort_order' => $this->config->get('total_sort_order'))
			);
		}
		// instead of using the last object in the array, use the total code
		$totalPaymentAmount = 0;
		foreach ($data['totals'] as $order_total_data) {
			if ($order_total_data['code'] == 'total') {
				$totalPaymentAmount = $order_total_data['value'];
				if ($order_info['currency_value']) $totalPaymentAmount = (float)$totalPaymentAmount*$order_info['currency_value'];
				break;
			}
		}

		$totalPaid = 0;
		$data['order_payments'] = array();
		$order_payments = $this->model_pos_pos->retrieveOrderPayments($order_id);
		if ($order_payments) {
			// reverse the order
			$order_payments = array_reverse($order_payments);
			foreach ($order_payments as $order_payment) {
				// update for customer loyalty card begin
				$amount = $order_payment['tendered_amount'];
				$totalPaid += $amount;
				$data['order_payments'][] = array (
					'order_payment_id' => $order_payment['order_payment_id'],
					'payment_type'     => $order_payment['payment_type'],
					'tendered_amount'  => $amount,
					'payment_note'     => ($order_payment['payment_type'] == $this->language->get('text_reward_points')) ? '' : $order_payment['payment_note']
				);
			}
		}

		$data['payment_due_amount'] = $totalPaymentAmount - $totalPaid;
		$data['payment_change'] = 0;
		if ($data['payment_due_amount'] <  0) {
			$data['payment_change'] = 0 - $data['payment_due_amount'];
			$data['payment_due_amount'] = 0;
		}
		$data['payment_due_amount_text'] = $this->currency->formatFront($data['payment_due_amount'], $order_info['currency_code'], 1);
		$data['payment_change_text'] = $this->currency->formatFront($data['payment_change'], $order_info['currency_code'], 1);

		$data['order_status_id'] = $order_info['order_status_id'];
		$data['user_id'] = $order_info['user_id'];
		$data['text_can_not_delete_current_order'] = sprintf($this->language->get('text_can_not_delete_current_order'), $order_id);
	}

	public function getProductDetails() {
		$json = array();

		$product_id = $this->request->get['product_id'];

		if (!empty($this->request->get['product_id'])) {
			$product_id = $this->request->get['product_id'];
			$this->load->model('catalog/product');

			$product_info = $this->model_catalog_product->getProduct($product_id);
			if (!empty($product_info)) {
				$json = $product_info;

				$this->load->model('tool/image');
				if ($product_info['image'] && file_exists(DIR_IMAGE . $product_info['image'])) {
					$json['thumb'] = $this->model_tool_image->resize($product_info['image'], 300, 300);
				} else {
					$json['thumb'] = $this->model_tool_image->resize('no_image.jpg', 300, 300);
				}

				$this->load->model('catalog/manufacturer');
				$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);
				if ($manufacturer_info) {
					$json['manufacturer'] = $manufacturer_info['name'];
				} else {
					$json['manufacturer'] = '';
				}

				// Options
				$this->load->model('catalog/option');
				$product_options = $this->model_catalog_product->getProductOptions($product_id);

				$json['product_options'] = array();
				foreach ($product_options as $product_option) {
					if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
						$product_option_value_data = array();

						foreach ($product_option['product_option_value'] as $product_option_value) {
							$product_option_value_data[] = array(
								'product_option_value_id' => $product_option_value['product_option_value_id'],
								'option_value_id'         => $product_option_value['option_value_id'],
								'quantity'                => $product_option_value['quantity'],
								'subtract'                => $product_option_value['subtract'],
								'price'                   => $product_option_value['price'],
								'price_prefix'            => $product_option_value['price_prefix'],
								'points'                  => $product_option_value['points'],
								'points_prefix'           => $product_option_value['points_prefix'],
								'weight'                  => $product_option_value['weight'],
								'weight_prefix'           => $product_option_value['weight_prefix']
							);
						}

						$json['product_options'][] = array(
							'product_option_id'    => $product_option['product_option_id'],
							'product_option_value' => $product_option_value_data,
							'option_id'            => $product_option['option_id'],
							'name'                 => $product_option['name'],
							'type'                 => $product_option['type'],
							'required'             => $product_option['required']
						);
					} else {
						$json['product_options'][] = array(
							'product_option_id' => $product_option['product_option_id'],
							'option_id'         => $product_option['option_id'],
							'name'              => $product_option['name'],
							'type'              => $product_option['type'],
							'option_value'      => $product_option['value'],
							'required'          => $product_option['required']
						);
					}
				}
				$json['option_values'] = array();
				foreach ($json['product_options'] as $product_option) {
					if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
						if (!isset($json['option_values'][$product_option['option_id']])) {
							$json['option_values'][$product_option['option_id']] = $this->model_catalog_option->getOptionValues($product_option['option_id']);
						}
					}
				}

				$json['product_discounts'] = $this->model_catalog_product->getProductDiscounts($product_id);
				$json['product_specials'] = $this->model_catalog_product->getProductSpecials($product_id);
				$json['product_reward'] = $this->model_catalog_product->getProductRewards($product_id);
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function getCustomerAjax() {
		$data = array();
		$this->getCustomer($this->request->get['customer_id'], $data);
		$this->response->setOutput(json_encode($data));
	}

	private function getCustomer($customer_id, &$data) {
		$data['customer_id'] = $customer_id;

		$this->load->model('sale/customer');
		$customer_info = $this->model_sale_customer->getCustomer($customer_id);

		if (!empty($customer_info)) {
			$data['customer_firstname'] = $customer_info['firstname'];
			$data['customer_lastname'] = $customer_info['lastname'];
      		$data['customer_email'] = $customer_info['email'];
			$data['customer_telephone'] = $customer_info['telephone'];
			$data['customer_fax'] = $customer_info['fax'];
			$data['customer_newsletter'] = $customer_info['newsletter'];
			$data['customer_group_id'] = $customer_info['customer_group_id'];
			$data['customer_status'] = $customer_info['status'];
			$data['customer_addresses'] = $this->model_sale_customer->getAddresses($customer_id);
			$data['customer_address_id'] = $customer_info['address_id'];
			$data['customer_date_added'] = date('Y-m-d', strtotime($customer_info['date_added']));
			$data['hasAddress'] = 1;
			$this->load->model('localisation/zone');
			foreach ($data['customer_addresses'] as $key => $address) {
				if ($customer_info['address_id'] == $address['address_id']) {
					$data['hasAddress'] = 2;
				}
				$data['customer_addresses'][$key]['zones'] = $this->model_localisation_zone->getZonesByCountryId($address['country_id']);
			}
			$data['customer_password'] = '';
			$data['customer_confirm'] = '';
    	}
	}

	private function getStoreId() {
		if (isset($this->request->get['store_id'])) {
			$store_id = $this->request->get['store_id'];
		} else {
			$url_with_port = $this->request->server['SERVER_NAME'] . ':' . $this->request->server['SERVER_PORT'] . $this->request->server['PHP_SELF'];
			$url_without_port = $this->request->server['SERVER_NAME'] . $this->request->server['PHP_SELF'];
			$store_id = 0;
			// get the default store id
			$this->load->model('setting/store');
			$stores = $this->model_setting_store->getStores();
			if (!empty($stores)) {
				foreach ($stores as $store) {
					$store_url = $store['url'];
					$index = strpos($store['url'], '//');
					if (!($index === false)) {
						$store_url = substr($store_url, $index+2);
					}
					if (!(strpos($url_with_port, $store_url) === false) || !(strpos($url_without_port, $store_url) === false)) {
						$store_id = $store['store_id'];
						break;
					}
				}
			}
		}
		return $store_id;
	}

	public function createEmptyOrder() {
		$data = array();

		$data['store_id'] = $this->getStoreId();

		$default_country_id = $this->config->get('POS_a_country_id') ? $this->config->get('POS_a_country_id') : $this->config->get('config_country_id');
		$default_zone_id = $this->config->get('POS_a_zone_id') ? $this->config->get('POS_a_zone_id') : $this->config->get('config_zone_id');
		$data['shipping_country_id'] = $default_country_id;
		$data['shipping_zone_id'] = $default_zone_id;
		$data['payment_country_id'] = $default_country_id;
		$data['payment_zone_id'] = $default_zone_id;
		$data['customer_id'] = 0;
		$default_customer_group_id = $this->config->get('config_customer_group_id');
		$data['customer_group_id'] = $default_customer_group_id ? (int)$default_customer_group_id : 1;
		$data['firstname'] = 'Instore';
		$data['lastname'] = "Dummy";
		$data['email'] = 'customer@instore.com';
		$data['telephone'] = '1600';
		$data['fax'] = '';
		$data['payment_firstname'] = 'Instore';
		$data['payment_lastname'] = "Dummy";
		$data['payment_company'] = '';
		$data['payment_company_id'] = '';
		$data['payment_tax_id'] = '';
		$data['payment_address_1'] = 'customer address';
		$data['payment_address_2'] = '';
		$data['payment_city'] = 'customer city';
		$data['payment_postcode'] = '1600';
		$data['payment_country_id'] = $default_country_id;
		$data['payment_zone_id'] = $default_zone_id;
		$data['payment_method'] = 'In Store';
		$data['payment_code'] = 'in_store';
		$data['shipping_firstname'] = 'Instore';
		$data['shipping_lastname'] = 'Dummy';
		$data['shipping_company'] = '';
		$data['shipping_address_1'] = 'customer address';
		$data['shipping_address_2'] = '';
		$data['shipping_city'] = 'customer city';
		$data['shipping_postcode'] = '1600';
		$data['shipping_country_id'] = $default_country_id;
		$data['shipping_zone_id'] = $default_zone_id;
		$data['shipping_method'] = 'In Store';
		$data['shipping_code'] = 'instore.instore';
		$data['comment'] = '';
		$data['order_status_id'] = 1;
		$data['affiliate_id'] = 0;
		$data['user_id'] = $this->user->getId();
		$data['order_total'] = array(
			array('code' => 'total',
			'title'      => $this->language->get('text_pos_total'),
			'text'       => $this->currency->formatFront(0),
			'value'      => 0,
			'sort_order' => $this->config->get('total_sort_order'))
		);
		$c_data = array();
		$this->setDefaultCustomer($c_data);
		$data['customer_id'] = $c_data['c_id'];
		$data['customer_group_id'] = $c_data['c_group_id'];
		foreach ($c_data as $c_key => $c_value) {
			if (substr($c_key, 0, 2) == 'c_' && isset($data[substr($c_key, 2)])) {
				$data[substr($c_key, 2)] = $c_value;
			} elseif (substr($c_key, 0, 2) == 'a_') {
				if (isset($data['payment_'.substr($c_key, 2)])) {
					$data['payment_'.substr($c_key, 2)] = $c_value;
				}
				if (isset($data['shipping_'.substr($c_key, 2)])) {
					$data['shipping_'.substr($c_key, 2)] = $c_value;
				}
			}
		}

		$this->load->model('pos/pos');
		$order_id = $this->model_pos_pos->addOrder($data);

		return $order_id;
	}

	public function modify_order($data) {
		$this->language->load('module/pos');
		$this->load->model('pos/pos');

		$json = array();
		$json['product_id'] = $data['product_id'];

		$order_id = $data['order_id'];
		$action = $data['action'];

		$is_empty_order = false;
		if ($action == 'insert') {
			// update order creation time if it's an empty order
			$order_product_query = $this->db->query("SELECT order_product_id FROM `" . DB_PREFIX . "order_product` WHERE order_id = '" . (int)$order_id . "' LIMIT 1");
			if ($order_product_query->num_rows == 0) {
				$is_empty_order = true;
			}
			$tax = $this->getTax((float)$data['product']['price'], $data['product']['tax_class_id'], $data['customer_group_id']);
			$this->db->query("INSERT INTO `" . DB_PREFIX . "order_product` SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$data['product_id'] . "', name = '" . $this->db->escape($data['product']['name']) . "', model = '" . $this->db->escape($data['product']['model']) . "', quantity = '" . (int)$data['quantity'] . "', price = '" . (float)$data['product']['price'] . "', total = '" . ((float)$data['product']['price'] * (int)$data['quantity']) . "', tax = '" . $tax . "', reward = '" . (int)$data['product']['points'] . "'");

			$order_product_id = $this->db->getLastId();
			$json['order_product_id'] = $order_product_id;
			$this->db->query("UPDATE `" . DB_PREFIX . "product` SET quantity = (quantity - " . (int)$data['quantity'] . ") WHERE product_id = '" . (int)$data['product_id'] . "' AND subtract = '1'");

			if (isset($data['option'])) {
				foreach ($data['option'] as $order_option) {
					if (!empty($order_option['product_option_value_id'])) {
						$product_option_value_ids = is_array($order_option['product_option_value_id']) ? $order_option['product_option_value_id'] : array($order_option['product_option_value_id'] => array('value'=>$order_option['value']));
						foreach ($product_option_value_ids as $product_option_value_id => $product_option_values) {
							if (!empty($product_option_values['value'])) {
								$this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$order_option['product_option_id'] . "', product_option_value_id = '" . (int)$product_option_value_id . "', name = '" . $this->db->escape($order_option['name']) . "', `value` = '" . $this->db->escape($product_option_values['value']) . "', `type` = '" . $this->db->escape($order_option['type']) . "'");
								// only when product subtract is set to true and, if location based stock is enable, location stock control order is not zero
								$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int)$data['quantity'] . ") WHERE product_option_value_id = '" . (int)$product_option_value_id . "' AND subtract = '1'");
							}
						}
					} else {
						if (!empty($order_option['value'])) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$order_option['product_option_id'] . "', product_option_value_id = '0', name = '" . $this->db->escape($order_option['name']) . "', `value` = '" . $this->db->escape($order_option['value']) . "', `type` = '" . $this->db->escape($order_option['type']) . "'");
						}
					}
				}
			}
			$json['price'] = $data['product']['price'];
			$json['text_price'] = $this->currency->formatFront(($this->config->get('config_tax')) ? $data['product']['price'] + $tax : $data['product']['price']);
			$json['text_total'] = $this->currency->formatFront(((int)$data['quantity']) * ($this->config->get('config_tax') ? $data['product']['price'] + $tax : $data['product']['price']));
		} elseif ($action == 'modify_quantity') {
			$quantity_change = (int)$data['quantity_after'] - (int)$data['quantity_before'];
			$sqlQuery = "UPDATE " . DB_PREFIX . "order_product SET quantity = " . (int)$data['quantity_after'] . ", total = price * " . (int)$data['quantity_after'] . " WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$data['order_product_id'] . "'";
			$this->db->query($sqlQuery);
			$this->db->query("UPDATE `" . DB_PREFIX . "product` SET quantity = (quantity - " . (int)$quantity_change . ") WHERE product_id = '" . (int)$data['product_id'] . "' AND subtract = '1'");

			if (isset($data['order_option'])) {
				foreach ($data['order_option'] as $order_option) {
					$product_option_value_ids = is_array($order_option['product_option_value_id']) ? $order_option['product_option_value_id'] : array($order_option['product_option_value_id'] => $order_option['value']);
					foreach ($product_option_value_ids as $product_option_value_id => $value) {
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int)$quantity_change . ") WHERE product_option_value_id = '" . (int)$product_option_value_id . "' AND subtract = '1'");
					}
				}
			}
		} elseif ($action == 'delete') {
			$this->db->query("UPDATE `" . DB_PREFIX . "product` SET quantity = (quantity + " . (int)$data['quantity'] . ") WHERE product_id = '" . (int)$data['product_id'] . "' AND subtract = '1'");

			if (!empty($data['order_option'])) {
				foreach ($data['order_option'] as $order_option) {
					$product_option_value_ids = is_array($order_option['product_option_value_id']) ? $order_option['product_option_value_id'] : array($order_option['product_option_value_id'] => '');
					foreach ($product_option_value_ids as $product_option_value_id => $value) {
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity + " . (int)$data['quantity'] . ") WHERE product_option_value_id = '" . (int)$product_option_value_id . "' AND subtract = '1'");
					}
				}
			}
			$this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_product_id = '" . (int)$data['order_product_id'] . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$data['order_product_id'] . "'");
		}

		$total_data = $this->recalculate_total($data);
		$json['order_total'] = $total_data['order_total'];
		$total = $total_data['total'];

		$cur_time = time();
		if ($is_empty_order) {
			$update_query = "UPDATE `" . DB_PREFIX . "order` SET total = '" . (float)$total . "', date_added = NOW(), date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'";
			$this->db->query($update_query);
			$json['date_added'] = date('Y-m-d H:i:s', $cur_time);
		} else {
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET total = '" . (float)$total . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");
		}
		$json['date_modified'] = date('Y-m-d H:i:s', $cur_time);

		if (isset($order_product_id)) {
			$json['order_product_id'] = $order_product_id;
		}
		$json['success'] = $this->language->get('text_order_success');

		return $json;
	}

	public function update_total() {
		// only requires to update total
		$this->load->model('pos/pos');
		$this->language->load('module/pos');
		$json = $this->recalculate_total($this->request->post);
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET total = '" . (float)$json['total'] . "', date_modified = NOW() WHERE order_id = '" . (int)$this->request->post['order_id'] . "'");
		$this->response->setOutput(json_encode($json));
	}

	private function recalculate_total($data) {
		$order_id = $data['order_id'];

		// recalculate the total
		$this->load->library('customer');
		$this->customer = new Customer($this->registry);
		$this->load->library('tax');
		$this->tax = new Tax($this->registry);
		$this->load->library('cart');
		$this->cart = new Cart($this->registry);
		$this->customer->logout();

		// put all order product into the cart
		$order_products = $this->model_pos_pos->getOrderProducts($order_id);
		$this->session->data['pos_cart'] = 1;

		$this->cart->add_pos_products($order_products);
		// for shipping method that requires products in the cart
		$this->session->data['pos_products'] = $order_products;
		// then set the tax addresses for calculating taxes for total
		if ($this->cart->hasShipping()) {
			$this->tax->setShippingAddress($data['shipping_country_id'], $data['shipping_zone_id']);
		} else {
			$this->tax->setShippingAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
		}
		$this->tax->setPaymentAddress($data['payment_country_id'], $data['payment_zone_id']);
		$this->tax->setStoreAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));

		$order_total = array();
		$total = 0;
		$sort_order = array();

		$this->load->model('extension/extension');
		$results = $this->model_extension_extension->getInstalled('total');
		foreach ($results as $key => $value) {
			$sort_order[$key] = $this->config->get($value . '_sort_order');
		}

		array_multisort($sort_order, SORT_ASC, $results);

		$taxes = $this->getTaxes($order_id, $order_products, $data['customer_group_id'], false);

		foreach ($results as $result) {
			if ($this->config->get($result . '_status') && !($result == 'shipping' && !empty($this->session->data['shipping_method']['code']) && $this->session->data['shipping_method']['code'] == 'instore.instore')) {
				$this->load->model('total/' . $result);
				$this->{'model_total_' . $result}->getTotal($order_total, $total, $taxes);
			}
		}

		$sort_order = array();

		foreach ($order_total as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
			// reformat the total text as it's not right if default currency is not USD, need to be fixed by Opencart, but fix here temporarily
			$order_total[$key]['text'] = $this->currency->format($value['value'], $data['currency_code'], 1);
			if ($value['code'] == 'sub_total') {
				$order_total[$key]['title'] = $this->language->get('text_pos_sub_total');
			}
			if ($value['code'] == 'total') {
				$order_total[$key]['title'] = $this->language->get('text_pos_total');
			}
		}

		array_multisort($sort_order, SORT_ASC, $order_total);

		$this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "'");

		foreach ($order_total as $order_total_data) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($order_total_data['code']) . "', title = '" . $this->db->escape($order_total_data['title']) . "', `value` = '" . (float)$order_total_data['value'] . "', sort_order = '" . (int)$order_total_data['sort_order'] . "'");
		}

		// clean up
		$this->cart->clear();
		$this->customer->logout();
		return array('order_total' => $order_total, 'total' => $total);
	}

	public function saveOrderStatus() {
		$order_id = $this->request->post['order_id'];
		$order_status_id = $this->request->post['order_status_id'];
		$this->load->model('pos/pos');
		$this->model_pos_pos->saveOrderStatus($order_id, $order_status_id);

		$this->language->load('module/pos');
		$json['success'] = $this->language->get('text_order_success');
		$this->response->setOutput(json_encode($json));
	}

	public function saveCustomer() {
		$json = $this->request->post;

		$customer_id = (int)$this->request->post['customer_id'];
		$data = array();
		if ($customer_id > 0 || $customer_id == -1) {
			$json['hasAddress'] = 1;
			if ($this->user->isLogged() && $this->user->hasPermission('modify', 'sale/customer')) {
				$data['customer_id'] = $customer_id;
				$data['customer_group_id'] = $this->request->post['customer_group_id'];
				$data['safe'] = 0;
				$data['address_id'] = 0;
				$keys = array_keys($this->request->post);
				foreach ($keys as $key) {
					$value = $this->request->post[$key];
					if ($key == 'customer_addresses') {
						foreach ($value as $address) {
							if (isset($address['default']) && $address['default']) {
								$json['hasAddress'] = 2;
								$data['address_id'] = $address['address_id'];
								break;
							}
						}
					}
					if (strpos($key, 'customer_') === 0) {
						$dataKey = substr($key, 9);
						$data[$dataKey] = $value;
					}
				}

				$this->load->model('pos/pos');
				if ($customer_id > 0) {
					$this->model_pos_pos->editCustomer($customer_id, $data);
				} else {
					$customer_id = $this->model_pos_pos->addCustomer($data);
				}
			} else {
				$json['error']['warning'] = $this->language->get('error_permission');
			}
		} else {
			$keys = array_keys($this->request->post);
			foreach ($keys as $key) {
				$value = $this->request->post[$key];
				if (strpos($key, 'customer_') === 0) {
					$dataKey = substr($key, 9);
					$data[$dataKey] = $value;
				}
			}
		}

		$order_id = (!empty($this->request->get['order_id'])) ? $this->request->get['order_id'] : false;
		$pos_return_id = (!empty($this->request->get['pos_return_id'])) ? $this->request->get['pos_return_id'] : false;
		$customer_group_id = (int)$this->request->post['customer_group_id'];
		$this->saveCustomerInfo($order_id, $pos_return_id, $data, $customer_id, $customer_group_id, $json);

		$this->response->setOutput(json_encode($json));
	}

	private function saveCustomerInfo($order_id, $pos_return_id, $customer, $customer_id, $customer_group_id, &$json) {
		$customer_sql = "";
		if ($customer_id > 0 || $customer_id == -1) {
			$json['hasAddress'] = 1;

			$this->load->model('sale/customer');
			$customer_addresses = $this->model_sale_customer->getAddresses($customer_id);
			$customer_info = $this->model_sale_customer->getCustomer($customer_id);

			foreach ($customer_addresses as $address) {
				if ($customer_info['address_id'] == $address['address_id']) {
					if (!empty($this->request->get['order_id'])) {
					// update the order shipping address and payment address
						$customer_sql .= ", payment_firstname = '" . $this->db->escape($address['firstname']) . "', payment_lastname = '" . $this->db->escape($address['lastname']) . "', payment_company = '" . $this->db->escape($address['company']) . "', payment_address_1 = '" . $this->db->escape($address['address_1']) . "', payment_address_2 = '" . $this->db->escape($address['address_2']) . "', payment_city = '" . $this->db->escape($address['city']) . "', payment_postcode = '" . $this->db->escape($address['postcode']) . "', payment_country = '" . $this->db->escape($address['country']) . "', payment_country_id = '" . (int)$address['country_id'] . "', payment_zone = '" . $this->db->escape($address['zone']) . "', payment_zone_id = '" . (int)$address['zone_id'] . "', shipping_firstname = '" . $this->db->escape($address['firstname']) . "', shipping_lastname = '" . $this->db->escape($address['lastname']) . "',  shipping_company = '" . $this->db->escape($address['company']) . "', shipping_address_1 = '" . $this->db->escape($address['address_1']) . "', shipping_address_2 = '" . $this->db->escape($address['address_2']) . "', shipping_city = '" . $this->db->escape($address['city']) . "', shipping_postcode = '" . $this->db->escape($address['postcode']) . "', shipping_country = '" . $this->db->escape($address['country']) . "', shipping_country_id = '" . (int)$address['country_id'] . "', shipping_zone = '" . $this->db->escape($address['zone']) . "', shipping_zone_id = '" . (int)$address['zone_id'] . "'";
					}
					$json['hasAddress'] = 2;
					$json['customer_address_id'] = $address['address_id'];
					$json['country_id'] = $address['country_id'];
					$json['zone_id'] = $address['zone_id'];
					break;
				}
			}
			if ($json['hasAddress'] == 1 && !empty($this->request->get['order_id'])) {
				$customer_sql .= ", payment_firstname = '', payment_lastname = '', payment_company = '', payment_address_1 = '', payment_address_2 = '', payment_city = '', payment_postcode = '', payment_country = '', payment_country_id = '', payment_zone = '', payment_zone_id = '', shipping_firstname = '', shipping_lastname = '',  shipping_company = '', shipping_address_1 = '', shipping_address_2 = '', shipping_city = '', shipping_postcode = '', shipping_country = '', shipping_country_id = '', shipping_zone = '', shipping_zone_id = ''";
			}
			try {
				//$this->model_sale_customer->approve($data['customer_id']);
			} catch (Exception $e) {
			}
			// add for Add Customer end
			// add for Edit order address begin
			$json['customer_addresses'] = $customer_addresses;
			// add for Edit order address end
		}

		$json['customer_id'] = $customer_id;
		if (!empty($order_id)) {
			$store_id = $this->getStoreId();
			$sql = "UPDATE `" . DB_PREFIX . "order` SET store_id = '" . $store_id . "', customer_group_id = '" . $customer_group_id . "', firstname = '" . $this->db->escape($customer['firstname']) ."', lastname = '" . $this->db->escape($customer['lastname']) . "', email = '" . $this->db->escape($customer['email']) . "', telephone = '" . $this->db->escape($customer['telephone']) . "', fax = '" . $this->db->escape($customer['fax']) . "', date_modified = NOW()";
			if ($customer_id > 0 || $customer_id == -1) {
				$sql .= $customer_sql;
			}
			$sql .= ", customer_id = '" . $customer_id . "' WHERE order_id = '" . (int)$order_id . "'";
			$this->db->query($sql);

			$this->language->load('module/pos');
			$json['success'] = $this->language->get('text_order_success');
		}
	}

	public function invoice() {
		$this->load->language('sale/order');
		$this->load->language('module/pos');

		$data['title'] = $this->language->get('text_invoice');

		if ($this->request->server['HTTPS']) {
			$data['base'] = HTTPS_SERVER;
		} else {
			$data['base'] = HTTP_SERVER;
		}

		$data['direction'] = $this->language->get('direction');
		$data['lang'] = $this->language->get('code');

		$data['text_invoice'] = $this->language->get('text_invoice');
		$data['text_order_detail'] = $this->language->get('text_order_detail');
		$data['text_order_id'] = $this->language->get('text_order_id');
		$data['text_invoice_no'] = $this->language->get('text_invoice_no');
		$data['text_invoice_date'] = $this->language->get('text_invoice_date');
		$data['text_date_added'] = $this->language->get('text_date_added');
		$data['text_telephone'] = $this->language->get('text_telephone');
		$data['text_fax'] = $this->language->get('text_fax');
		$data['text_email'] = $this->language->get('text_email');
		$data['text_website'] = $this->language->get('text_website');
		$data['text_to'] = $this->language->get('text_to');
		$data['text_ship_to'] = $this->language->get('text_ship_to');
		$data['text_payment_method'] = $this->language->get('text_payment_method');
		$data['text_shipping_method'] = $this->language->get('text_shipping_method');

		$data['column_product'] = $this->language->get('column_product');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_comment'] = $this->language->get('column_comment');

        $data['entry_footer'] = $this->language->get('entry_footer');

		$this->load->model('sale/order');

		$this->load->model('setting/setting');

		$data['order'] = array();

		$order_id = $this->request->get['order_id'];
		$order_info = $this->model_sale_order->getOrder($order_id);

		if ($order_info) {
			$store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);

			if ($store_info) {
				$store_address = $store_info['config_address'];
				$store_email = $store_info['config_email'];
				$store_telephone = $store_info['config_telephone'];
				$store_fax = $store_info['config_fax'];
			} else {
				$store_address = $this->config->get('config_address');
				$store_email = $this->config->get('config_email');
				$store_telephone = $this->config->get('config_telephone');
				$store_fax = $this->config->get('config_fax');
			}

			if ($order_info['invoice_no']) {
				$invoice_no = $order_info['invoice_prefix'] . $order_info['invoice_no'];
			} else {
				$invoice_no = '';
			}

			if ($order_info['payment_address_format']) {
				$format = $order_info['payment_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}

			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);

			$replace = array(
				'firstname' => $order_info['payment_firstname'],
				'lastname'  => $order_info['payment_lastname'],
				'company'   => $order_info['payment_company'],
				'address_1' => $order_info['payment_address_1'],
				'address_2' => $order_info['payment_address_2'],
				'city'      => $order_info['payment_city'],
				'postcode'  => $order_info['payment_postcode'],
				'zone'      => $order_info['payment_zone'],
				'zone_code' => $order_info['payment_zone_code'],
				'country'   => $order_info['payment_country']
			);

			$payment_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			if ($order_info['shipping_address_format']) {
				$format = $order_info['shipping_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}

			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);

			$replace = array(
				'firstname' => $order_info['shipping_firstname'],
				'lastname'  => $order_info['shipping_lastname'],
				'company'   => $order_info['shipping_company'],
				'address_1' => $order_info['shipping_address_1'],
				'address_2' => $order_info['shipping_address_2'],
				'city'      => $order_info['shipping_city'],
				'postcode'  => $order_info['shipping_postcode'],
				'zone'      => $order_info['shipping_zone'],
				'zone_code' => $order_info['shipping_zone_code'],
				'country'   => $order_info['shipping_country']
			);

			$shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			$this->load->model('tool/upload');

			$product_data = array();

			$products = $this->model_sale_order->getOrderProducts($order_id);

			foreach ($products as $product) {
				$option_data = array();

				$options = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);

				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$value = $upload_info['name'];
						} else {
							$value = '';
						}
					}

					$option_data[] = array(
						'name'  => $option['name'],
						'value' => $value
					);
				}

				$product_data[] = array(
					'name'     => $product['name'],
					'model'    => $product['model'],
					'option'   => $option_data,
					'quantity' => $product['quantity'],
					'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
					'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
				);
			}

			$total_data = array();

			$totals = $this->model_sale_order->getOrderTotals($order_id);

			foreach ($totals as $total) {
				$total_data[] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
				);
			}

			$data['order'] = array(
				'order_id'	         => $order_id,
				'invoice_no'         => $invoice_no,
				'date_added'         => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
				'store_name'         => $order_info['store_name'],
				'store_url'          => rtrim($order_info['store_url'], '/'),
				'store_address'      => nl2br($store_address),
				'store_email'        => $store_email,
				'store_telephone'    => $store_telephone,
				'store_fax'          => $store_fax,
				'email'              => $order_info['email'],
				'telephone'          => $order_info['telephone'],
				'shipping_address'   => $shipping_address,
				'shipping_method'    => $order_info['shipping_method'],
				'payment_address'    => $payment_address,
				'payment_method'     => $order_info['payment_method'],
				'product'            => $product_data,
				'total'              => $total_data,
				'comment'            => nl2br($order_info['comment'])
			);
		}

		$this->response->setOutput($this->load->view('pos/order_invoice.tpl', $data));
	}
	// add for Browse begin
	private function getCategoryItems($parent_category_id, $currency_code, $currency_value, $customer_group_id) {
		// get the direct sub-category and product in the given category
		$this->load->model('pos/pos');
		$sub_categories = $this->model_pos_pos->getSubCategories($parent_category_id);
		$products = $this->model_pos_pos->getProducts($parent_category_id);

		$this->language->load('module/pos');
		$this->load->model('tool/image');
		$this->load->model('catalog/product');
		$browse_items = array();
		foreach ($sub_categories as $sub_category) {
			$category_items = $this->model_pos_pos->getTotalSubItems($sub_category['category_id']);
			$browse_items[] = array('type' => 'C',
								'name' => $sub_category['name'],
								'total_items' => $category_items,
								'image' => !empty($sub_category['image']) ? $this->model_tool_image->resize($sub_category['image'], 180, 180) : $this->model_tool_image->resize('no_image.jpg', 180, 180),
								'parent_category_id' => $parent_category_id,
								'category_id' => $sub_category['category_id']);
		}
		foreach ($products as $product) {
			$quantity = $product['quantity'];
			$price = $product['price'];
			$product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product['product_id'] . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");

			if ($product_special_query->num_rows) {
				$price = $product_special_query->row['price'];
			}
			// calculate price with tax
			$price_after_tax = ($this->config->get('config_tax')) ? $this->calculateTax($price, $product['tax_class_id'], true, $customer_group_id) : $price;
			$tax = $price_after_tax - $price;

			// Reward Points
			$product_reward_query = $this->db->query("SELECT points FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product['product_id'] . "' AND customer_group_id = '" . (int)$customer_group_id . "'");

			if ($product_reward_query->num_rows) {
				$reward = $product_reward_query->row['points'];
			} else {
				$reward = 0;
			}

			$has_sn = 0;

			$browse_items[] = array('type' => 'P',
								'name' => $product['name'],
								'image' => (!empty($product['image'])) ? $this->model_tool_image->resize($product['image'], 180, 180) : $this->model_tool_image->resize('no_image.jpg', 180, 180),
								'price_text' => $this->currency->formatFront($price_after_tax, $currency_code, $currency_value),
								'stock' => $quantity, // . ' ' . $this->language->get('text_remaining'),
								'hasOptions' => $product['options'] ? '1' : '0',
								'has_sn' => $has_sn,
								'price' => $price,
								'subtract' => $product['subtract'],
								'tax_class_id' => $product['tax_class_id'],
								'shipping'  => $product['shipping'],
								'tax' => $tax,
								'points' => $product['points'],
								'reward_points' => $reward,
								'model' => $product['model'],
								'description' => $product['description'],
								'manufacturer' => $product['m_name'],
								'upc' => $product['upc'],
								'sku' => $product['sku'],
								'ean' => $product['ean'],
								'mpn' => $product['mpn'],
								'manufacturer' => $product['m_name'],
								'parent_category_id' => $parent_category_id,
								'product_discounts' => $this->model_catalog_product->getProductDiscounts($product['product_id']),
								'product_specials' => $this->model_catalog_product->getProductSpecials($product['product_id']),
								'product_id' => $product['product_id']);
		}

		return $browse_items;
	}

  	public function calculateTax($value, $tax_class_id, $calculate = true, $customer_group_id) {
		if ($tax_class_id && $calculate) {
			$amount = $this->getTax($value, $tax_class_id, $customer_group_id);

			return $value + $amount;
		} else {
      		return $value;
    	}
  	}

	public function getPriceFromPriceWithTax($price, $tax_class_id, $customer_group_id) {
		$cal_price = $price;
		if ($this->config->get('config_tax')) {
			// the changed price is with tax according to the settings
			// get all tax rates
			$base = 100;
			$tax_rates = $this->getRates($base, $tax_class_id, $customer_group_id);
			$rate_p = 0;
			foreach ($tax_rates as $tax_rate) {
				if ($tax_rate['type'] == 'F') {
					// fixed amount rate
					$cal_price -= $tax_rate['rate'];
				} elseif ($tax_rate['type'] == 'P') {
					// percentage rate
					$rate_p += $tax_rate['rate'];
				}
			}
			$cal_price = $cal_price / (1+((float)$rate_p)/100);
		}
		return $cal_price;
	}

  	public function getTax($value, $tax_class_id, $customer_group_id) {
		$amount = 0;

		$tax_rates = $this->getRates($value, $tax_class_id, $customer_group_id);

		foreach ($tax_rates as $tax_rate) {
			$amount += $tax_rate['amount'];
		}

		return $amount;
  	}

    public function getRates($value, $tax_class_id, $customer_group_id, $discount=0) {
		$tax_rates = $this->getTaxRates($tax_class_id, $customer_group_id);

		$tax_rate_data = array();
		foreach ($tax_rates as $tax_rate) {
			if (isset($tax_rate_data[$tax_rate['tax_rate_id']])) {
				$amount = $tax_rate_data[$tax_rate['tax_rate_id']]['amount'];
			} else {
				$amount = 0;
			}

			if ($tax_rate['type'] == 'F') {
				if (!$discount) {
					// change for pos_discount, as the discount will not incur the fix amount tax
					$amount += $tax_rate['rate'];
				}
			} elseif ($tax_rate['type'] == 'P') {
				$amount += ($value / 100 * $tax_rate['rate']);
			}

			$tax_rate_data[$tax_rate['tax_rate_id']] = array(
				'tax_rate_id' => $tax_rate['tax_rate_id'],
				'name'        => $tax_rate['name'],
				'rate'        => $tax_rate['rate'],
				'type'        => $tax_rate['type'],
				'amount'      => $amount
			);
		}
		return $tax_rate_data;
	}

	private function getTaxRates($tax_class_id, $customer_group_id) {
		$tax_rates = array();

		// use the default country id and zone id for POS
		$country_id = $this->config->get('config_country_id');
		$zone_id = $this->config->get('config_zone_id');

		$tax_query = $this->db->query("SELECT tr2.tax_rate_id, tr2.name, tr2.rate, tr2.type, tr1.priority FROM " . DB_PREFIX . "tax_rule tr1 LEFT JOIN " . DB_PREFIX . "tax_rate tr2 ON (tr1.tax_rate_id = tr2.tax_rate_id) INNER JOIN " . DB_PREFIX . "tax_rate_to_customer_group tr2cg ON (tr2.tax_rate_id = tr2cg.tax_rate_id) LEFT JOIN " . DB_PREFIX . "zone_to_geo_zone z2gz ON (tr2.geo_zone_id = z2gz.geo_zone_id) LEFT JOIN " . DB_PREFIX . "geo_zone gz ON (tr2.geo_zone_id = gz.geo_zone_id) WHERE tr1.tax_class_id = '" . (int)$tax_class_id . "' AND tr1.based = 'shipping' AND tr2cg.customer_group_id = '" . (int)$customer_group_id . "' AND z2gz.country_id = '" . (int)$country_id . "' AND (z2gz.zone_id = '0' OR z2gz.zone_id = '" . (int)$zone_id . "') ORDER BY tr1.priority ASC");

		foreach ($tax_query->rows as $result) {
			$tax_rates[$result['tax_rate_id']] = array(
				'tax_rate_id' => $result['tax_rate_id'],
				'name'        => $result['name'],
				'rate'        => $result['rate'],
				'type'        => $result['type'],
				'priority'    => $result['priority']
			);
		}

		$tax_query = $this->db->query("SELECT tr2.tax_rate_id, tr2.name, tr2.rate, tr2.type, tr1.priority FROM " . DB_PREFIX . "tax_rule tr1 LEFT JOIN " . DB_PREFIX . "tax_rate tr2 ON (tr1.tax_rate_id = tr2.tax_rate_id) INNER JOIN " . DB_PREFIX . "tax_rate_to_customer_group tr2cg ON (tr2.tax_rate_id = tr2cg.tax_rate_id) LEFT JOIN " . DB_PREFIX . "zone_to_geo_zone z2gz ON (tr2.geo_zone_id = z2gz.geo_zone_id) LEFT JOIN " . DB_PREFIX . "geo_zone gz ON (tr2.geo_zone_id = gz.geo_zone_id) WHERE tr1.tax_class_id = '" . (int)$tax_class_id . "' AND tr1.based = 'payment' AND tr2cg.customer_group_id = '" . (int)$customer_group_id . "' AND z2gz.country_id = '" . (int)$country_id . "' AND (z2gz.zone_id = '0' OR z2gz.zone_id = '" . (int)$zone_id . "') ORDER BY tr1.priority ASC");

		foreach ($tax_query->rows as $result) {
			$tax_rates[$result['tax_rate_id']] = array(
				'tax_rate_id' => $result['tax_rate_id'],
				'name'        => $result['name'],
				'rate'        => $result['rate'],
				'type'        => $result['type'],
				'priority'    => $result['priority']
			);
		}

		$tax_query = $this->db->query("SELECT tr2.tax_rate_id, tr2.name, tr2.rate, tr2.type, tr1.priority FROM " . DB_PREFIX . "tax_rule tr1 LEFT JOIN " . DB_PREFIX . "tax_rate tr2 ON (tr1.tax_rate_id = tr2.tax_rate_id) INNER JOIN " . DB_PREFIX . "tax_rate_to_customer_group tr2cg ON (tr2.tax_rate_id = tr2cg.tax_rate_id) LEFT JOIN " . DB_PREFIX . "zone_to_geo_zone z2gz ON (tr2.geo_zone_id = z2gz.geo_zone_id) LEFT JOIN " . DB_PREFIX . "geo_zone gz ON (tr2.geo_zone_id = gz.geo_zone_id) WHERE tr1.tax_class_id = '" . (int)$tax_class_id . "' AND tr1.based = 'store' AND tr2cg.customer_group_id = '" . (int)$customer_group_id . "' AND z2gz.country_id = '" . (int)$country_id . "' AND (z2gz.zone_id = '0' OR z2gz.zone_id = '" . (int)$zone_id . "') ORDER BY tr1.priority ASC");

		foreach ($tax_query->rows as $result) {
			$tax_rates[$result['tax_rate_id']] = array(
				'tax_rate_id' => $result['tax_rate_id'],
				'name'        => $result['name'],
				'rate'        => $result['rate'],
				'type'        => $result['type'],
				'priority'    => $result['priority']
			);
		}

		return $tax_rates;
	}

	public function getCategoryItemsAjax() {
		$parent_category_id = 0;
		if (isset($this->request->post['category_id'])) {
			$parent_category_id = $this->request->post['category_id'];
		}

		$json = array();
		$customer_group_id = $this->config->get('config_customer_group_id');
		if (isset($this->request->post['customer_group_id'])) {
			$customer_group_id = $this->request->post['customer_group_id'];
		}
		$json['browse_items'] = $this->getCategoryItems($parent_category_id, $this->request->post['currency_code'], $this->request->post['currency_value'], $customer_group_id);
		// the above step already has model pos/pos include
		if (version_compare(VERSION, '1.5.5', '<')) {
			$category_path = $this->model_pos_pos->getCategoryFullPathOld($parent_category_id);
		} else {
			$category_path = $this->model_pos_pos->getCategoryFullPath($parent_category_id);
			if ($category_path) {
				$category_path = $category_path['name'];
			}
		}
		$json['path'] = array();
		$this->load->model('tool/image');
		if ($category_path) {
			$pathes = explode('!|||!', $category_path);
			$json['path'] = array();
			foreach ($pathes as $path) {
				$names = explode('|||', $path);
				$json['path'][] = array('id' => $names[0], 'name' => $names[1], 'image' => (!empty($names[2]) ? $this->model_tool_image->resize($names[2], 180, 180) : $this->model_tool_image->resize('no_image.jpg', 180, 180)));
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function getProductOptions() {
		$json = array();
		$option_data = array();

		$this->load->model('catalog/product');
		$this->load->model('catalog/option');
		$product_options = $this->model_catalog_product->getProductOptions($this->request->get['product_id']);

		foreach ($product_options as $product_option) {
			$option_info = $this->model_catalog_option->getOption($product_option['option_id']);

			if ($option_info) {
				if ($option_info['type'] == 'select' || $option_info['type'] == 'radio' || $option_info['type'] == 'checkbox' || $option_info['type'] == 'image') {
					$option_value_data = array();

					foreach ($product_option['product_option_value'] as $product_option_value) {
						$option_value_name = '';
						if (version_compare(VERSION, '1.5.5', '<')) {
							$option_value_name = $product_option_value['name'];
						} else {
							$option_value_info = $this->model_catalog_option->getOptionValue($product_option_value['option_value_id']);
							if ($option_value_info) {
								$option_value_name = $option_value_info['name'];
							}
						}

						$option_value_data[] = array(
							'product_option_value_id' => $product_option_value['product_option_value_id'],
							'option_value_id'         => $product_option_value['option_value_id'],
							'name'                    => $option_value_name,
							'price'                   => (float)$product_option_value['price'] ? $this->currency->formatFront($product_option_value['price'], $this->config->get('config_currency')) : false,
							'price_prefix'            => $product_option_value['price_prefix']
						);
					}

					$option_data[] = array(
						'product_option_id' => $product_option['product_option_id'],
						'option_id'         => $product_option['option_id'],
						'name'              => $option_info['name'],
						'type'              => $option_info['type'],
						'option_value'      => $option_value_data,
						'required'          => $product_option['required']
					);
				} elseif ($option_info['type'] != 'file') {
					$option_data[] = array(
						'product_option_id' => $product_option['product_option_id'],
						'option_id'         => $product_option['option_id'],
						'name'              => $option_info['name'],
						'type'              => $option_info['type'],
						'option_value'      => $product_option['value'],
						'required'          => $product_option['required']
					);
				}
			}
		}

		$json['option_data'] = $option_data;
		$this->response->setOutput(json_encode($json));
	}
	// add for Browse end
	public function image() {
		if (isset($this->request->get['image'])) {
			$filename = $this->request->get['image'];
			if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) {
				return;
			}

			$old_image = $filename;
			$new_image = 'cache/' . $filename;

			if (!file_exists(DIR_IMAGE . $new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime(DIR_IMAGE . $new_image))) {
				$path = '';

				$directories = explode('/', dirname(str_replace('../', '', $new_image)));

				foreach ($directories as $directory) {
					$path = $path . '/' . $directory;

					if (!file_exists(DIR_IMAGE . $path)) {
						@mkdir(DIR_IMAGE . $path, 0777);
					}
				}

				copy(DIR_IMAGE . $old_image, DIR_IMAGE . $new_image);
			}

			if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
				$this->response->setOutput(HTTPS_CATALOG . 'image/' . $new_image);
			} else {
				$this->response->setOutput(HTTP_CATALOG . 'image/' . $new_image);
			}
		}
	}

	private function getEmptyOrder() {
		$order_id = 0;
		// find the order with the maximum id and with no order product added
		$max_order_id_query = $this->db->query("SELECT order_id, user_id FROM `" . DB_PREFIX . "order` WHERE user_id IS NOT NULL AND user_id <> '-1' AND user_id <> '-2' ORDER BY order_id DESC LIMIT 1");
		if ($max_order_id_query->row && $max_order_id_query->row['user_id'] == $this->user->getId()) {
			$order_products_query = $this->db->query("SELECT order_product_id FROM `" .DB_PREFIX . "order_product` WHERE order_id = '" . $max_order_id_query->row['order_id'] . "' LIMIT 1");
			if ($order_products_query->num_rows == 0) {
				// no order product for this order, consider it as an empty order
				$order_id = $max_order_id_query->row['order_id'];
			}
		}
		if (!$order_id) {
			// the last order is not an empty order, create a new one
			$order_id = $this->createEmptyOrder();
		} else {
			// it's an order, reset status to initial status for order
			$order_status_id = 1;
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . $order_status_id . "' WHERE order_id = '" . $order_id . "'");
		}
		return $order_id;
	}

	public function autocomplete_product() {
		$json = array();

		if (isset($this->request->post['filter_name'])) {
			$this->load->model('pos/pos');
			$this->load->model('tool/image');
			$this->load->model('catalog/product');

			$filter_name = $this->request->post['filter_name'];

			if (!empty($this->request->post['filter_scopes'])) {
				$filter_scopes = $this->request->post['filter_scopes'];
			} else {
				$filter_scopes = array('name');
			}

			if (isset($this->request->post['limit'])) {
				$limit = $this->request->post['limit'];
			} else {
				$limit = 20;
			}

			$data = array(
				'filter_name'   => $filter_name,
				'filter_scopes' => $filter_scopes,
				'start'         => 0,
				'limit'         => $limit
			);

			if (isset($this->request->post['customer_group_id'])) {
				$customer_group_id = $this->request->post['customer_group_id'];
			} else {
				$customer_group_id = $this->config->get('config_customer_group_id');
			}
			$results = $this->model_pos_pos->getProductsForBrowse($data);

			foreach ($results as $result) {
				$quantity = $result['quantity'];
				$price = $result['price'];
				$product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$result['product_id'] . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");

				if ($product_special_query->num_rows) {
					$price = $product_special_query->row['price'];
				}
				// calculate price with tax
				$price_after_tax = ($this->config->get('config_tax')) ? $this->calculateTax($price, $result['tax_class_id'], true, $customer_group_id) : $price;
				$tax = $price_after_tax - $price;

				// Reward Points
				$product_reward_query = $this->db->query("SELECT points FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$result['product_id'] . "' AND customer_group_id = '" . (int)$customer_group_id . "'");

				if ($product_reward_query->num_rows) {
					$reward = $product_reward_query->row['points'];
				} else {
					$reward = 0;
				}

				$has_sn = 0;

				$category_id = 0;
				$category_query = $this->db->query("SELECT category_id FROM `" . DB_PREFIX . "product_to_category` WHERE product_id = '" . (int)$result['product_id'] . "'");
				if ($category_query->row) {
					$category_id = $category_query->row['category_id'];
				}

				$json[] = array('type' => 'P',
								'name' => $result['name'],
								'image' => (!empty($result['image'])) ? $this->model_tool_image->resize($result['image'], 180, 180) : $this->model_tool_image->resize('no_image.jpg', 180, 180),
								'price_text' => $this->currency->formatFront($price_after_tax),
								'stock' => $quantity,
								'hasOptions' => $result['options'] ? '1' : '0',
								'parent_category_id' => $category_id,
								'has_sn' => $has_sn,
								'price' => $price,
								'subtract' => $result['subtract'],
								'tax' => $tax,
								'tax_class_id' => $result['tax_class_id'],
								'points' => $result['points'],
								'reward_points'=> $reward,
								'model' => $result['model'],
								'description' => $result['description'],
								'manufacturer' => $result['m_name'],
								'upc' => $result['upc'],
								'sku' => $result['sku'],
								'ean' => $result['ean'],
								'mpn' => $result['mpn'],
								'product_discounts' => $this->model_catalog_product->getProductDiscounts($result['product_id']),
								'product_specials' => $this->model_catalog_product->getProductSpecials($result['product_id']),
								'product_id' => $result['product_id']);
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function check_and_save_order() {
		$json = array();

		$action = $this->request->post['action'];
		// check stock first
		$has_stock = true;
		if ($action == 'insert' || $action == 'modify_quantity') {
			// check stock for insert and modify quantity only
			if ($action == 'insert') {
				$quantity = $this->request->post['quantity'];
			} else {
				$quantity = (int)$this->request->post['quantity_after'] - (int)$this->request->post['quantity_before'];
			}
			if ($quantity > 0) {
				if (!empty($this->request->post['option'])) {
					// has option, check the option stock first
					foreach ($this->request->post['option'] as $option) {
						if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'image' || $option['type'] == 'checkbox') {
							if (!empty($option['product_option_value_id'])) {
								$product_option_value_ids = is_array($option['product_option_value_id']) ? $option['product_option_value_id'] : array($option['product_option_value_id'] => '');
								foreach ($product_option_value_ids as $product_option_value_id => $value) {
									$option_value_query = $this->db->query("SELECT subtract, quantity FROM `" . DB_PREFIX . "product_option_value` WHERE product_option_value_id = '" . (int)$product_option_value_id . "'");
									if ($option_value_query->row['subtract'] && $option_value_query->row['quantity'] < $quantity) {
										$has_stock = false;
										break;
									}
								}
							}
						}
					}
				}
				// check the product level stock as well
				$product_stock_query = $this->db->query("SELECT subtract, quantity FROM `" . DB_PREFIX . "product` WHERE product_id = '" . (int)$this->request->post['product_id'] . "'");
				if ($product_stock_query->row['subtract'] && $product_stock_query->row['quantity'] < $quantity) {
					$has_stock = false;
				}
			}
		}

		// indicates we are going to access cart from pos
		$data = $this->request->post;

		$product_name = '';
		$product_info = $this->db->query("SELECT p.model, p.price, p.tax_class_id, p.points, p.subtract, pd.name FROM `" . DB_PREFIX . "product` p LEFT JOIN `" . DB_PREFIX . "product_description` pd ON p.product_id = pd.product_id WHERE p.product_id = '" . (int)$this->request->post['product_id'] . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'")->row;
		if ($product_info) {
			$product_name = $product_info['name'];
			$product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$this->request->post['product_id'] . "' AND customer_group_id = '" . (int)$this->request->post['customer_group_id'] . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");

			if ($product_special_query->num_rows) {
				$product_info['price'] = $product_special_query->row['price'];
			}
			if (!empty($this->request->post['option'])) {
				$product_info['price'] = $this->getOptionPrice($product_info['price'], $this->request->post['option']);
			} elseif (!empty($this->request->post['order_option'])) {
				$product_info['price'] = $this->getOptionPrice($product_info['price'], $this->request->post['order_option']);
			}
		}
		if (!$has_stock) {
			$this->language->load('module/pos');
			// no enough stock, return error message
			$product_option_value_ids = '';
			if (!empty($this->request->post['option'])) {
				$option_desc = '';
				$option_size = count($this->request->post['option']);
				$option_index = 0;
				foreach ($this->request->post['option'] as $option) {
					if (!empty($option['value'])) {
						if (!empty($option['product_option_value_id']) && is_array($option['product_option_value_id'])) {
							$option_desc .= $option['name'] . '=';
							foreach ($option['product_option_value_id'] as $pov_id => $option_values) {
								$product_option_value_ids .= $pov_id . ',';
								if (!empty($option_values['value'])) {
									$option_desc .= $option_values['value'] . ';';
								}
							}
						} else {
							$option_desc .= $option['name'] . '=' . $option['value'];
						}
						if ($option_index < $option_size-1) {
							$option_desc .= ', ';
						}
					}
					$option_index ++;
				}
				$json['error']['stock'] = sprintf($this->language->get('error_stock_option'), $product_name, $option_desc);
			} else {
				$product_option_value_ids = '0';
				$json['error']['stock'] = sprintf($this->language->get('error_stock'), $product_name);
			}
		}

//		if ($has_stock)  {
			// modify order
			// get product information
			$data['product'] = $product_info;
			$save_json = $this->modify_order($data);
			if (!empty($json['error'])) {
				$save_json['error'] = $json['error'];
			}
//		}

		$this->response->setOutput(json_encode($save_json));
	}

	private function getOptionPrice($base_price, $options) {
		$order_product_price = $base_price;
		foreach ($options as $option) {
			// for select-like options
			if (!empty($option['price']) && !empty($option['price_prefix'])) {
				if ($option['price_prefix'] == '+') {
					$order_product_price += (float)$option['price'];
				} else {
					$order_product_price -= (float)$option['price'];
				}
			}
			// for checkbox options
			if (!empty($option['product_option_value_id']) && is_array($option['product_option_value_id'])) {
				foreach ($option['product_option_value_id'] as $product_option_value_id => $product_option_values) {
					if (!empty($product_option_values['value'])&& !empty($product_option_values['price']) && !empty($product_option_values['price_prefix'])) {
						if ($product_option_values['price_prefix'] == '+') {
							$order_product_price += (float)$product_option_values['price'];
						} else {
							$order_product_price -= (float)$product_option_values['price'];
						}
					}
				}
			}
		}
		return $order_product_price;
	}


	private function getTaxes($order_id, $order_products, $customer_group_id=0, $discount_total=0) {
		$this->load->model('sale/order');
		$order_customer_group_id = $customer_group_id;
		if (!$order_customer_group_id) {
			$order_query = $this->db->query("SELECT customer_group_id FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "'");
			$order_customer_group_id = $order_query->row['customer_group_id'];
		}

		$tax_data = array();

		foreach ($order_products as $product) {
			if ($product['tax_class_id']) {
				if (!empty($product['discount'])) {
					// product level discount
					$tax_rates = $this->getRates($product['discount']['discounted_price'], $product['tax_class_id'], $order_customer_group_id);
				} else {
					$tax_rates = $this->getRates($product['price'], $product['tax_class_id'], $order_customer_group_id);
				}

				foreach ($tax_rates as $tax_rate) {
					if (!isset($tax_data[$tax_rate['tax_rate_id']])) {
						$tax_data[$tax_rate['tax_rate_id']] = ($tax_rate['amount'] * $product['quantity']);
					} else {
						$tax_data[$tax_rate['tax_rate_id']] += ($tax_rate['amount'] * $product['quantity']);
					}
				}
			}
		}

		return $tax_data;
  	}

	private function sync_total_models() {
		// synchronise the total models from catalog to admin
		$admin_model_root = DIR_APPLICATION . 'model/total';
		$catalog_model_root = DIR_CATALOG . 'model/total';
		$admin_totals = $this->get_totals_files($admin_model_root, '');
		$catalog_totals = $this->get_totals_files($catalog_model_root, '');
		$this->sync_files($catalog_totals, $catalog_model_root, $admin_totals, $admin_model_root);
	}
	private function get_totals_files($dir, $relative_dir) {
		$result = array();
		$cdir = scandir($dir);
		foreach ($cdir as $value) {
			if (!in_array($value,array(".",".."))) {
				$filename = $dir . DIRECTORY_SEPARATOR . $value;
				if (is_dir($filename)) {
					$result[$relative_dir . DIRECTORY_SEPARATOR . $value] = $this->get_totals_files($filename, $relative_dir . DIRECTORY_SEPARATOR . $value);
				} else {
					$result[$relative_dir . DIRECTORY_SEPARATOR . $value] = filemtime($filename);
				}
			}
		}
		return $result;
	}
	private function sync_files($arr1, $parent1, $arr2, $parent2) {
		foreach ($arr1 as $key => $value) {
			if (array_key_exists($key, $arr2)) {
				if (is_dir($parent1.$key)) {
					$this->sync_files($value, $parent1, $arr2[$key], $parent2);
				} elseif ($value > $arr2[$key]) {
					// catalog is the latest, copy it over
					copy($parent1.$key, $parent2.$key);
				}
			} else {
				if (is_dir($parent1.$key)) {
					// create the directory
					mkdir($parent2.$key);
					$this->mk_tree($parent1.$key, $parent2.$key);
				} else {
					// no file exits, copy it over
					copy($parent1.$key, $parent2.$key);
				}
			}
		}
		// remove the directories / files that not in the first directory
		foreach ($arr2 as $key => $value) {
			if (!array_key_exists($key, $arr1)) {
				$this->del_tree($parent2.$key);
			}
		}
	}
	private function mk_tree($source, $destination) {
		$files = array_diff(scandir($source), array('.','..'));
		foreach ($files as $file) {
			if (is_dir("$source/$file")) {
				mkdir("$destination/$file");
				$this->mk_tree("$source/$file", "$destination/$file");
			} else {
				copy("$source/$file", "$destination/$file");
			}
		}
	}
	private function del_tree($dir) {
		if (is_dir($dir)) {
			$files = array_diff(scandir($dir), array('.','..'));
			foreach ($files as $file) {
				(is_dir("$dir/$file")) ? $this->del_tree("$dir/$file") : unlink("$dir/$file");
			}
			rmdir($dir);
		} else {
			unlink($dir);
		}
	}

	public function get_default_customer_ajax() {
		$this->response->setOutput(json_encode($this->get_default_customer()));
	}

	private function get_default_customer() {
		$c_data = array();
		$this->setDefaultCustomer($c_data);

		$json = array();
		$json['customer_id'] = $c_data['c_id'];
		$json['customer_group_id'] = $c_data['c_group_id'];
		foreach ($c_data as $c_key => $c_value) {
			if (substr($c_key, 0, 2) == 'c_') {
				$json['customer_'.substr($c_key, 2)] = $c_value;
			}
		}
		if (!empty($json['customer_addresses'])) {
			$this->load->model('localisation/zone');
			foreach ($json['customer_addresses'] as $key => $customer_address) {
				$json['customer_addresses'][$key]['zones'] = $this->model_localisation_zone->getZonesByCountryId($customer_address['country_id']);
			}
		}
		return $json;
	}

	public function getCustomerList() {
		$limit = 8;

 		$this->load->model('pos/pos');

		$data = $this->request->post;
		if (!isset($data['limit'])) {
			$data['limit'] = $limit;
		}
		if (!isset($data['start'])) {
			$data['start'] = 0;
			if (isset($data['page'])) {
				$data['start'] = ((int)$data['page'] - 1) * $data['limit'];
			}
		}

		$customer_total = $this->model_pos_pos->getTotalCustomers($data);
		$results = $this->model_pos_pos->getCustomers($data);

		$json = array();
		$json['customers'] = array();
    	foreach ($results as $result) {
			$json['customers'][] = array(
				'customer_id'  => $result['customer_id'],
				'name'         => $result['name'],
				'email'        => $result['email'],
				'telephone'    => $result['telephone'],
				'date_added'   => date('Y-m-d', strtotime($result['date_added']))
			);
		}
		$page = (!empty($data['page'])) ? $data['page'] : 1;
		$json['pagination'] = $this->getPagination($customer_total, $page, $limit, 'selectCustomerPage');

		$this->response->setOutput(json_encode($json));
 	}

	public function saveOrderComment() {
		$order_id = $this->request->post['order_id'];
		$comment = $this->request->post['comment'];
		$this->load->model('pos/pos');
		$this->model_pos_pos->saveOrderComment($order_id, $comment);

		$this->language->load('module/pos');
		$json['success'] = $this->language->get('text_order_success');
		$this->response->setOutput(json_encode($json));
	}

	public function getPaymentsDetails() {
		$json = array();

		$this->load->model('pos/pos');
		$data['filter_order_id'] = $this->request->get['order_id'];
		$payments = $this->model_pos_pos->getOrderPayments($data);
		$json['payments'] = array();
		if (!empty($payments)) {
			foreach ($payments as $payment) {
				$json['payments'][] = array('payment_type' => $payment['payment_type'],
											'tendered_amount' => $payment['tendered_amount'],
											'payment_note' => $payment['payment_note'],
											'payment_time' => $payment['payment_time']);
			}
		}

		$this->response->setOutput(json_encode($json));
	}
	private function resize($filename, $width, $height) {
		if ($this->request->server['HTTPS']) {
			$local_filename = str_replace(HTTPS_CATALOG . '/image/', DIR_IMAGE, $filename);
		} else {
			$local_filename = str_replace(HTTP_CATALOG . '/image/', DIR_IMAGE, $filename);
		}

		if (!is_file($local_filename)) {
			return;
		}

		$path_parts = pathinfo($local_filename);

		$old_image = $local_filename;
		$new_image = DIR_APPLICATION . 'view/image/pos/cache/' . $path_parts['filename'] . '-' . $width . 'x' . $height . '.' . $path_parts['extension'];

		if (!is_file($new_image) || (filectime($old_image) > filectime($new_image))) {
			list($width_orig, $height_orig) = getimagesize($old_image);

			if ($width_orig != $width || $height_orig != $height) {
				$image = new Image($old_image);
				$image->resize($width, $height);
				$image->save($new_image);
			} else {
				copy($old_image, $new_image);
			}
		}

		return 'view/image/pos/cache/' . $path_parts['filename'] . '-' . $width . 'x' . $height . '.' . $path_parts['extension'];
	}
}
?>