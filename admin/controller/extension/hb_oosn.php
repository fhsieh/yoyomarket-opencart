<?php
class ControllerExtensionHbOosn extends Controller {

	private $error = array();

	public function index() {
		$this->load->language('extension/hb_oosn');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		$this->load->model('setting/hb_oosn');

		if ($this->config->get('hb_oosn_installed') <> 1){
			$this->response->redirect($this->url->link('extension/hb_oosn/install', 'token=' . $this->session->data['token'], 'SSL'));
		}

		//Save the settings if the user has submitted the admin form (ie if someone has pressed save).
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('hb_oosn', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/hb_oosn', 'token=' . $this->session->data['token'], 'SSL'));
		}

		if(isset($this->request->get['delete'])){
            $delete = $this->request->get['delete'];
			$this->model_setting_hb_oosn->deleteRecords($delete);
			$this->session->data['success'] = $this->language->get('text_reset_success');
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data = array(
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);

		if (isset($this->request->get['filteroption'])) {
    		$filteroption = $this->request->get['filteroption'];
            $data['current_report'] = $this->language->get('tile_'.$filteroption);
		}else {
			$filteroption = 'all';
    	    $data['current_report'] = $this->language->get('tile_all');
		}
		$reports_total = $this->model_setting_hb_oosn->getTotalReports($filteroption);

		$data['products'] = array();

		$results = $this->model_setting_hb_oosn->getReports($data,$filteroption);

		foreach ($results as $result) {

			$data['products'][] = array(
				'product_id'    => $result['product_id'],
				'name'   => $result['name'],
				'selected_option'   => ($result['selected_option'] == '0')?'NA':$result['selected_option'],
				'all_selected_option'   => ($result['all_selected_option'] == '0')?'NA':$result['all_selected_option'],
				'email'   => $result['email'],
				'fname'   => $result['fname'],
				'phone'   => $result['phone'],
				'language_code'   => $result['language_code'],
				'enquiry_date'  => date("d M Y g:i A", strtotime($result['enquiry_date'])),
				'notify_date'  => (!empty($result['notified_date']))? date("d M Y g:i A", strtotime($result['notified_date'])) : '',
                'product_link' => $this->url->link('catalog/product/edit&product_id='.$result['product_id'], 'token=' . $this->session->data['token'], 'SSL')
			);
		}

		$pagination = new Pagination();
		$pagination->total = $reports_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/hb_oosn', 'token=' . $this->session->data['token'] . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
		$limit = $this->config->get('config_limit_admin');

		$data['results'] = sprintf($this->language->get('text_pagination'), ($pagination->total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($pagination->total - $limit)) ? $pagination->total : ((($page - 1) * $limit) + $limit), $pagination->total, ceil($pagination->total / $limit));

		$data['demands'] = $this->model_setting_hb_oosn->getDemandedList();

		$text_strings = array(
				'heading_title',
				'button_save',
				'button_cancel',
				'tab_records',
				'tab_demand',
				'tab_email',
				'tab_setting',
				'tile_all','tile_awaiting','tile_archive',
				'header_product',
				'header_others',
				'header_sitemaps',
				'header_allstore',
				'text_no_results',
				'column_product_id',
				'column_product_name',
				'column_email',
				'column_name',
				'column_phone',
				'column_option',
				'column_all_option',
				'column_language',
				'column_enquiry_date',
				'column_notify_date',
				'text_notify_customers',
				'text_total_alert',
				'text_total_responded',
				'text_show_all_reports',
				'text_reset_all',
				'text_customers_awaiting_notification',
				'text_number_of_products_demanded',
				'text_show_awaiting_reports',
				'text_reset_awaiting',
				'text_archive_records',
				'text_customers_notified',
				'text_show_archive_reports',
				'text_reset_archive',
				'text_reports',
				'text_current_report_all',
				'text_current_report_awaiting',
				'text_current_report_archive',
				'text_product_in_demand',
				'column_count',
				'entry_text',
				'entry_success_msg',
				'entry_store_subject',
				'entry_store_body',
				'entry_customer_subject',
				'entry_customer_body',
				'email_to_store',
				'email_to_customer',
				'text_header_common',
				'text_header_form',
				'entry_enable_name',
				'entry_enable_mobile',
				'entry_enable_sms',
				'entry_animation',
				'text_success',
				'text_product_qty',
				'text_product_stock_status',
				'text_header_admin',
				'text_header_customer',
				'entry_subject',
				'entry_body',
				'sms_body',
				'text_admin_tab',
				'text_customer_tab',
				'text_header_condition',
				'text_header_email',
				'text_header_sms',
				'entry_css',
				'entry_store_codes',
				'entry_sms_codes',
				'entry_customer_codes',
				'text_product_image_size',
				'text_mobile_validation',
				'text_header_installation',
				'installation'
		);

		foreach ($text_strings as $text) {
			$data[$text] = $this->language->get($text);
		}

		//This creates an error message. The error['warning'] variable is set by the call to function validate() in this controller (below)
 		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

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
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/hb_oosn', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$data['action'] = $this->url->link('extension/hb_oosn', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL');
		$data['uninstall'] = $this->url->link('extension/hb_oosn/uninstall', 'token=' . $this->session->data['token'], 'SSL');

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();

		$this->load->model('localisation/stock_status');
		$data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();

		foreach ($data['languages'] as $result){
	 		$language_id = $result['language_id'];
			$code = md5($result['code']);
			$data['hb_oosn_admin_email_subject_'.$code] =  $this->config->get('hb_oosn_admin_email_subject_'.$code);
			$data['hb_oosn_admin_email_body_'.$code] =  $this->config->get('hb_oosn_admin_email_body_'.$code);
			$data['hb_oosn_customer_email_subject_'.$language_id] =  $this->config->get('hb_oosn_customer_email_subject_'.$language_id);
			$data['hb_oosn_customer_email_body_'.$language_id] =  $this->config->get('hb_oosn_customer_email_body_'.$language_id);
			$data['hb_oosn_product_image_h_'.$language_id] =  $this->config->get('hb_oosn_product_image_h_'.$language_id);
			$data['hb_oosn_product_image_w_'.$language_id] =  $this->config->get('hb_oosn_product_image_w_'.$language_id);
			$data['hb_oosn_customer_sms_body_'.$language_id] =  $this->config->get('hb_oosn_customer_sms_body_'.$language_id);

		}
		$data['hb_oosn_installed'] = $this->config->get('hb_oosn_installed');
		$data['hb_oosn_name_enable'] = $this->config->get('hb_oosn_name_enable');
		$data['hb_oosn_mobile_enable'] = $this->config->get('hb_oosn_mobile_enable');
		$data['hb_oosn_sms_enable'] = $this->config->get('hb_oosn_sms_enable');
		$data['hb_oosn_animation'] = $this->config->get('hb_oosn_animation');
		$data['hb_oosn_css'] = $this->config->get('hb_oosn_css');
		$data['hb_oosn_product_qty'] = $this->config->get('hb_oosn_product_qty');
		$data['hb_oosn_stock_status'] = $this->config->get('hb_oosn_stock_status');
		$data['hb_oosn_mobile_validation'] = $this->config->get('hb_oosn_mobile_validation');

		$data['total_alert'] = $this->model_setting_hb_oosn->getTotalAlert();
        $data['total_responded'] = $this->model_setting_hb_oosn->getTotalResponded();
        $data['customer_notified'] = $this->model_setting_hb_oosn->getCustomerNotified();
        $data['awaiting_notification'] = $this->model_setting_hb_oosn->getAwaitingNotification();
        $data['product_requested'] = $this->model_setting_hb_oosn->getTotalRequested();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/hb_oosn.tpl', $data));

	}

	public function manualrun(){
        $url = HTTPS_CATALOG."index.php?route=product/product_oosn/autonotify";

        // yym_custom: manual run using curl instead of file_get_contents
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        // $returnedoutput = file_get_contents($url);

        // yym_custom: error handling for maintenance mode
        if ($output == 'done') {
          $json['success'] = '<div class="alert alert-info">Success! Notification emails sent.</div>';
        } else {
          $json['success'] = '<div class="alert alert-danger">Error: Could not send mail. Check maintenance mode.</div>';
        }

		$this->response->setOutput(json_encode($json));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/hb_oosn')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

		public function install(){
			$this->load->model('setting/hb_oosn');
			$this->model_setting_hb_oosn->install();
			$data['success'] = 'This extension has been installed successfully';
			$this->response->redirect($this->url->link('extension/hb_oosn', 'token=' . $this->session->data['token'], 'SSL'));
		}

		public function uninstall(){
				$this->load->model('setting/hb_oosn');
				$this->model_setting_hb_oosn->uninstall();
				$data['success'] = 'This extension is uninstalled successfully';
				$this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
		}



}
?>