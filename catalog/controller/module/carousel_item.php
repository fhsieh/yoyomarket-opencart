<?php  
/* 
Version: 1.0
Author: Artur Sułkowski
Website: http://artursulkowski.pl
*/

class ControllerModuleCarouselItem extends Controller {
	public function index($setting) {
		if(isset($setting['heading'][$this->config->get('config_language_id')])) {
			$data['block_heading'] = html_entity_decode($setting['heading'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
		} else {
			$data['block_heading'] = 'You must set block heading in the module Carousel Item!';
		}
		$data['carousel_item'] = array();
		$lang_id = $this->config->get('config_language_id');
		
		foreach($setting['items'] as $item) {
			if(isset($item[$lang_id]['html'])) {
				$data['carousel_item'][]['content'] = html_entity_decode($item[$lang_id]['html'], ENT_QUOTES, 'UTF-8');
			} else {
				$data['carousel_item'][]['content'] = 'You must set block content in the module Carousel Item!';
			}
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/carousel_item.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/carousel_item.tpl', $data);
		} else {
			return $this->load->view('default/template/module/carousel_item.tpl', $data);
		}
	}
}
?>