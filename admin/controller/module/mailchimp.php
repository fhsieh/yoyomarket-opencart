<?php
class ControllerModuleMailchimp extends Controller {
	private $error = array();

	public function index() {

		$data['mailchimp_api'] = trim($this->config->get('mailchimp_api'));

		if (empty($data['mailchimp_api'])) {
			$this->session->data['error_apikey'] = $this->language->get('error_apikey');
			$this->response->redirect($this->url->link('mail_chimp/setting', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->load->language('module/mailchimp');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			if (!isset($this->request->get['module_id'])) {
				$this->model_extension_module->addModule('mailchimp', $this->request->post);
			} else {
				$this->model_extension_module->editModule($this->request->get['module_id'], $this->request->post);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['token'] = $this->session->data['token'];

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit']               = $this->language->get('text_edit');
		$data['text_enabled']            = $this->language->get('text_enabled');
		$data['text_disabled']           = $this->language->get('text_disabled');
		$data['text_select']             = $this->language->get('text_select');
		$data['text_show']               = $this->language->get('text_show');
		$data['text_sort']               = $this->language->get('text_sort');
		$data['text_popup_form']         = $this->language->get('text_popup_form');
		$data['entry_name']              = $this->language->get('entry_name');
		$data['entry_title']             = $this->language->get('entry_title');
		$data['entry_description']       = $this->language->get('entry_description');
		$data['entry_success_message']   = $this->language->get('entry_success_message');
		$data['entry_status']            = $this->language->get('entry_status');
		$data['entry_list']              = $this->language->get('entry_list');
		$data['entry_list_fields']       = $this->language->get('entry_list_fields');
		$data['entry_button_text']       = $this->language->get('entry_button_text');
		$data['placeholder_button_text'] = $this->language->get('placeholder_button_text');
		$data['button_save']             = $this->language->get('button_save');
		$data['button_cancel']           = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('module/mailchimp', 'token=' . $this->session->data['token'], 'SSL'),
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('module/mailchimp', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL'),
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('module/mailchimp', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('module/mailchimp', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
		}

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['list_id'])) {
			$data['list_id'] = $this->request->post['list_id'];
		} elseif (!empty($module_info)) {
			$data['list_id'] = $module_info['list_id'];
		} else {
			$data['list_id'] = '';
		}

		if (isset($this->request->post['list_fields'])) {
			$data['list_fields'] = $this->request->post['list_fields'];
		} elseif (!empty($module_info) && isset($module_info['list_fields'])) {
			$data['list_fields'] = $module_info['list_fields'];
		} else {
			$data['list_fields'] = array();
		}

		if (isset($this->request->post['module_description'])) {
			$data['module_description'] = $this->request->post['module_description'];
		} elseif (!empty($module_info)) {
			$data['module_description'] = $module_info['module_description'];
		} else {
			$data['module_description'] = '';
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['mailchimp_list'] = $this->getlist($data['mailchimp_api']);

		if ($data['list_id'] != '') {
			$data['list_details'] = $this->getlistInfo($data['mailchimp_api'], $data['list_id']);
		} else {
			$data['list_details'] = null;
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}

		if (isset($this->request->post['popup'])) {
			$data['popup'] = $this->request->post['popup'];
		} elseif (!empty($module_info)) {
			$data['popup'] = $module_info['popup'];
		} else {
			$data['popup'] = '';
		}

		$data['header']      = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer']      = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/mailchimp.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/mailchimp')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		return !$this->error;
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

	public function getlistInfo($api_key = null, $list_id = null) {
		require_once DIR_SYSTEM . 'library/Drewm/MailChimp.php';

		if ($api_key) {
			$MailChimp = new \Drewm\MailChimp($api_key);
			return $MailChimp->call('lists/merge-vars', array(
				'id' => array($list_id),
			));
		} else {
			$api_key   = $this->request->get['api_key'];
			$MailChimp = new \Drewm\MailChimp($api_key);
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(
				json_encode(
					$MailChimp->call(
						'lists/merge-vars',
						array(
							'id' => array($this->request->get['list_id']),
						)
					)
				)
			);
		}

	}
}