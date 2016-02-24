<?php
class ControllerMailChimpSetting extends Controller {
	private $objcreatesend;
	private $error = array();

	public function index() {
		$this->language->load('mail_chimp/setting');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		$this->load->model('sale/customer_group');

		$data['customer_groups'] = array_merge(
			array(
				0 => array(
					'customer_group_id' => 0,
					'name'              => 'Guest',
				),
			),
			$this->model_sale_customer_group->getCustomerGroups()
		);

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			$this->model_setting_setting->editSetting('mailchimp', $this->request->post);

			$mailchimp_api_key = trim($this->request->post['mailchimp_api']);

			if (strlen($mailchimp_api_key) > 1) {
				$lists = $this->getlist(trim($mailchimp_api_key));

				require_once DIR_SYSTEM . 'library/Drewm/MailChimp.php';
				$MailChimp = new \Drewm\MailChimp($mailchimp_api_key);

				$webhook_key = 'WEBBYMAILCHIMP'; /* Change as needed */

				if (intval($lists['total']) > 0) {
					foreach ($lists['data'] as $list_item) {
						$result = $MailChimp->call('lists/webhook-del', array(
							'id'  => $list_item['id'],
							'url' => HTTPS_CATALOG . 'index.php?route=mail_chimp/webhook&key=' . $webhook_key,
						));

						$result = $MailChimp->call('lists/webhook-add', array(
							'id'  => $list_item['id'],
							'url' => HTTPS_CATALOG . 'index.php?route=mail_chimp/webhook&key=' . $webhook_key,
						));
					}
				}

			}

		 	$this->load->model('extension/event');
			if (intval($this->request->post['mailchimp_ecommerce360']) == 1) {
				$this->model_extension_event->deleteEvent('mailchimp');
				$this->model_extension_event->addEvent('mailchimp', 'post.order.history.add', 'module/mailchimp/after_order_add');
				$this->model_extension_event->addEvent('mailchimp', 'post.order.edit', 'module/mailchimp/after_order_edit');
			} else {
				$this->model_extension_event->deleteEvent('mailchimp');
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('mail_chimp/setting', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_select']             = $this->language->get('text_select');
		$data['text_api']                = $this->language->get('text_api');
		$data['text_list']               = $this->language->get('text_list');
		$data['text_none']               = $this->language->get('text_none');
		$data['text_apikey_help']        = $this->language->get('text_apikey_help');
		$data['text_apikey_placeholder'] = $this->language->get('text_apikey_placeholder');
		$data['text_btn_load_list']      = $this->language->get('text_btn_load_list');
		$data['text_ec360_help']         = $this->language->get('text_ec360_help');
		$data['entry_name']              = $this->language->get('entry_name');
		$data['entry_api_key']           = $this->language->get('entry_api_key');
		$data['entry_ecommerce360']      = $this->language->get('entry_ecommerce360');
		$data['entry_customer_group']    = $this->language->get('entry_customer_group');
		$data['entry_list_id']           = $this->language->get('entry_list_id');
		$data['button_save']             = $this->language->get('button_save');
		$data['button_cancel']           = $this->language->get('button_cancel');
		$data['text_autosync']			 = $this->language->get('text_autosync');
		$data['text_autosync_notice']	 = $this->language->get('text_autosync_notice');
		$data['text_autosync_btn']		 = $this->language->get('text_autosync_btn');
		$data['tab_setting']		 = $this->language->get('tab_setting');
		$data['tab_help']		 = $this->language->get('tab_help');
		$data['tab_new_and_updates']		 = $this->language->get('tab_new_and_updates');
		$data['text_autosync_note']      = $this->language->get('text_autosync_note');
		$data['text_loading']            = $this->language->get('text_loading');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['mc_api'])) {
			$data['error_api'] = $this->error['mc_api'];
		} else {
			$data['error_api'] = '';
		}

		foreach ($data['customer_groups'] as $customer_groups) {
			$mc_list_key = 'mc_list_' . $customer_groups['customer_group_id'];
			if (isset($this->error[$mc_list_key])) {
				$data['error_' . $mc_list_key] = $this->error[$mc_list_key];
			}

		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false,
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('mail_chimp/setting', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: ',
		);

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}


		$data['action'] = $this->url->link('mail_chimp/setting', 'token=' . $this->session->data['token'], 'SSL');
		$data['autosynclink'] = $this->url->link('mail_chimp/setting/autosync', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('mail_chimp/setting', 'token=' . $this->session->data['token'], 'SSL');

		$data['token'] = $this->session->data['token'];

		$mc_config = $this->model_setting_setting->getSetting('mailchimp');

		if (isset($this->request->post['mailchimp_api'])) {
			$data['mailchimp_api'] = trim($this->request->post['mailchimp_api']);
		} else if (isset($mc_config['mailchimp_api'])) {
			$data['mailchimp_api'] = trim($mc_config['mailchimp_api']);
		} else {
			$data['mailchimp_api'] = '';
		}

		if (isset($this->request->post['mailchimp_ecommerce360'])) {
			$data['mailchimp_ecommerce360'] = trim($this->request->post['mailchimp_ecommerce360']);
		} else if (isset($mc_config['mailchimp_ecommerce360'])) {
			$data['mailchimp_ecommerce360'] = trim($mc_config['mailchimp_ecommerce360']);
		} else {
			$data['mailchimp_ecommerce360'] = '';
		}

		foreach ($data['customer_groups'] as $customer_groups) {
			$mc_list_key = 'mailchimp_list_' . $customer_groups['customer_group_id'];
			if (isset($this->request->post[$mc_list_key])) {
				$data[$mc_list_key] = $this->request->post[$mc_list_key];
			} else if (isset($mc_config[$mc_list_key])) {
				$data[$mc_list_key] = $mc_config[$mc_list_key];
			} else {
				$data[$mc_list_key] = '';
			}
		}

		$data['header']      = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer']      = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('mail_chimp/setting.tpl', $data));

	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'mail_chimp/setting')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['mailchimp_api']) {
			$this->error['mc_api'] = $this->language->get('error_api');
		}

		$data['customer_groups'] = array_merge(
			array(
				0 => array(
					'customer_group_id' => 0,
					'name'              => 'Guest',
				),
			),
			$this->model_sale_customer_group->getCustomerGroups()
		);

		foreach ($data['customer_groups'] as $customer_groups) {
			$mc_list_key = 'mailchimp_list_' . $customer_groups['customer_group_id'];
			if (!isset($this->request->post[$mc_list_key])) {
				$this->error[$mc_list_key] = $this->language->get('error_list_id');
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	public function template() {
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = HTTPS_CATALOG;
		} else {
			$server = HTTP_CATALOG;
		}

		if (file_exists(DIR_IMAGE . 'templates/' . basename($this->request->get['template']) . '.png')) {
			$image = $server . 'image/templates/' . basename($this->request->get['template']) . '.png';
		} else {
			$image = $server . 'image/no_image.jpg';
		}

		$this->response->setOutput('<img src="' . $image . '" alt="" title="" style="border: 1px solid #EEEEEE;" />');
	}

	public function getlist($api_key = null) {
		require_once DIR_SYSTEM . 'library/Drewm/MailChimp.php';

		if ($api_key) {
			$MailChimp = new \Drewm\MailChimp($api_key);
			return $MailChimp->call('lists/list');
		} else {
			$api_key   = $this->request->get['api_key'];
			$MailChimp = new \Drewm\MailChimp($api_key);
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($MailChimp->call('lists/list')));
		}
	}

	public function autosync(){
		$this->language->load('mail_chimp/setting');
		$this->load->model('sale/customer');
		$customers = $this->db->query("SELECT `firstname`,`lastname`,`email`,`customer_group_id`,`newsletter` FROM " . DB_PREFIX . "customer");
		$customer_group_name = '';
		$total_customer = 0;
		$total_customer_add = 0;
		$total_customer_del = 0;
		foreach ($customers->rows as $customer) {
			if(intval($customer['newsletter']) == 1){
				$this->model_sale_customer->subscribeMailChimp(
					$customer['email'], 
					$customer['firstname'], 
					$customer['lastname'], 
					$customer['customer_group_id']
				);
				$total_customer_add++;
			} else {
				$this->model_sale_customer->unsubscribeMailChimp(
					$customer['email'], 
					$customer['customer_group_id']
				);
				$total_customer_del++;
			}

			$total_customer++;
		}

		$this->session->data['success'] = sprintf($this->language->get('text_sync_success'), $total_customer, $total_customer_add,$total_customer_del) ;
		$this->response->redirect($this->url->link('mail_chimp/setting', 'token=' . $this->session->data['token'], 'SSL'));
	}
}
?>