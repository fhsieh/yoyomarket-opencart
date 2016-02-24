<?php
/* 
Version: 1.0
Author: Artur Sułkowski
Website: http://artursulkowski.pl
*/

class ControllerModuleFilterProduct extends Controller {
	private $error = array(); 
	 
	public function index() {   
		$this->language->load('module/filter_product');

		$this->document->setTitle('Filter product');
		
		$this->load->model('setting/setting');
		$this->load->model('catalog/product');
		$this->load->model('catalog/category');
				
		// Dodawanie plików css i js do <head>
		$this->document->addStyle('view/stylesheet/filter_product.css');
		
		// Zapisywanie modułu		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('filter_product', $this->request->post);		
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->response->redirect($this->url->link('module/filter_product', 'token=' . $this->session->data['token'], 'SSL'));			
		}
		
		// Wyświetlanie powiadomień
 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
		    unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		$data['action'] = $this->url->link('module/filter_product', 'token=' . $this->session->data['token'], 'SSL');
				
		$data['token'] = $this->session->data['token'];
	
		// Ładowanie listy modułów
		$data['modules'] = array();
		
		if (isset($this->request->post['filter_product_module'])) {
			$data['modules'] = $this->request->post['filter_product_module'];
		} elseif ($this->config->get('filter_product_module')) { 
			$data['modules'] = $this->config->get('filter_product_module');
		}	
		
		// Layouts
		$this->load->model('design/layout');
		$data['layouts'] = $this->model_design_layout->getLayouts();
		
		// Languages
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		$data['breadcrumbs'] = array();
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		$data['breadcrumbs'][] = array(
			'text' => 'Modules',
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);
				
		$data['breadcrumbs'][] = array(
			'text' => 'Filter product',
			'href' => $this->url->link('module/filter_product', 'token=' . $this->session->data['token'], 'SSL')
		);
				
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
				
		$this->response->setOutput($this->load->view('module/filter_product.tpl', $data));
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/filter_product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>