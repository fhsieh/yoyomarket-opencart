<?php
class ControllerModuleHbOosnm extends Controller {
	public function index() {
		$this->load->language('module/hb_oosnm');

		$data['hb_oosn_name_enable']	= $this->config->get('hb_oosn_name_enable');
		$data['hb_oosn_mobile_enable']	= $this->config->get('hb_oosn_mobile_enable');
		$data['hb_oosn_animation'] = $this->config->get('hb_oosn_animation');
		$data['hb_oosn_css'] = $this->config->get('hb_oosn_css');

        $data['text_title'] = $this->language->get('text_title'); // yym_custom
        $data['notify_submit'] = $this->language->get('button_notify_submit'); // yym_custom
		$data['notify_button'] = $this->language->get('button_notify_button');
		$data['oosn_info_text'] = $this->language->get('oosn_info_text');
		$data['oosn_text_email'] = $this->language->get('oosn_text_email');
		$data['oosn_text_email_plh'] = $this->language->get('oosn_text_email_plh');
		$data['oosn_text_name'] = $this->language->get('oosn_text_name');
		$data['oosn_text_name_plh'] = $this->language->get('oosn_text_name_plh');
		$data['oosn_text_phone'] = $this->language->get('oosn_text_phone');
		$data['oosn_text_phone_plh'] = $this->language->get('oosn_text_phone_plh');

		if ($this->customer->isLogged()){
			$data['email'] = $this->customer->getEmail();
			$data['fname'] = $this->customer->getFirstName();
			$data['phone'] = $this->customer->getTelephone();
		}else {
			$data['email'] = $data['fname'] =  $data['phone'] = '';
		}



		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/hb_oosnm.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/hb_oosnm.tpl', $data);
		} else {
			return $this->load->view('default/template/module/hb_oosnm.tpl', $data);
		}
	}
}