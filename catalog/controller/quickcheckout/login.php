<?php  
class ControllerQuickCheckoutLogin extends Controller { 
	public function index() {
		$this->language->load('quickcheckout/checkout');
		
		$data['text_new_customer'] = $this->language->get('text_new_customer');
		$data['text_returning_customer'] = $this->language->get('text_returning_customer');
		$data['text_checkout'] = $this->language->get('text_checkout');
		$data['text_register'] = $this->language->get('text_register');
		$data['text_guest'] = $this->language->get('text_guest');
		$data['text_i_am_returning_customer'] = $this->language->get('text_i_am_returning_customer');
		$data['text_register_account'] = $this->language->get('text_register_account');
		$data['text_forgotten'] = $this->language->get('text_forgotten');
		$data['text_loading'] = $this->language->get('text_loading');
 
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_password'] = $this->language->get('entry_password');
		
		$data['button_login'] = $this->language->get('button_login');
		
		$data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/quickcheckout/login.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/quickcheckout/login.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/quickcheckout/login.tpl', $data));
		}
	}
	
	public function validate() {
		$this->language->load('quickcheckout/checkout');
		
		$json = array();
		
		if ($this->customer->isLogged()) {
			$json['redirect'] = $this->url->link('quickcheckout/checkout', '', 'SSL');			
		}
		
		if (!$json) {
			$this->load->model('account/customer');
			
			// Check how many login attempts have been made.
			$login_info = $this->model_account_customer->getLoginAttempts($this->request->post['email']);
					
			if ($login_info && ($login_info['total'] > $this->config->get('config_login_attempts')) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
				$json['error']['warning'] = $this->language->get('error_attempts');
			}
			
			// Check if customer has been approved.
			$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

			if ($customer_info && !$customer_info['approved']) {
				$json['error']['warning'] = $this->language->get('error_approved');
			}
			
			if (!$json) {
				if (!$this->customer->login($this->request->post['email'], $this->request->post['password'])) {
					$json['error']['warning'] = $this->language->get('error_login');
					
					$this->model_account_customer->addLoginAttempt($this->request->post['email']);
				} else {
					$this->model_account_customer->deleteLoginAttempts($this->request->post['email']);
					
					// Add to activity log
					$this->load->model('account/activity');

					$activity_data = array(
						'customer_id' => $this->customer->getId(),
						'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
					);

					$this->model_account_activity->addActivity('login', $activity_data);
				}
			}
		}
		
		if (!$json) {
			$json['redirect'] = $this->url->link('quickcheckout/checkout', '', 'SSL');
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));		
	}
}