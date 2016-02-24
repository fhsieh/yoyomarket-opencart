<?php  
class ControllerQuickCheckoutTerms extends Controller {
  	public function index() {
		$this->language->load('quickcheckout/checkout');
		
		$data['button_continue'] = $this->language->get('button_continue');
		$data['text_loading'] = $this->language->get('text_loading');
		
		if ($this->config->get('config_checkout_id')) {
			$this->load->model('catalog/information');
			
			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));
			
			if ($information_info) {
				$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_checkout_id'), 'SSL'), $information_info['title'], $information_info['title']);
			} else {
				$data['text_agree'] = '';
			}
		} else {
			$data['text_agree'] = '';
		}
		
		if (isset($this->session->data['agree'])) { 
			$data['agree'] = $this->session->data['agree'];
		} else {
			$data['agree'] = '';
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/quickcheckout/terms.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/quickcheckout/terms.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/quickcheckout/terms.tpl', $data));
		}
	}
	
	public function validate() {
		$this->language->load('quickcheckout/checkout');
		
		$json = array();
		
		if ($this->config->get('config_checkout_id')) {
			$this->load->model('catalog/information');
				
			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));
				
			if ($information_info && !isset($this->request->post['agree'])) {
				$json['error']['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
			}
		}
		
		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');
		}
		
		// Validate minimum quantity requirements.
		$products = $this->cart->getProducts();
		
		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$json['redirect'] = $this->url->link('checkout/cart');

				break;
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}