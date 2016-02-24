<?php
/*Flexi Rewards FRONTEND Model*/

class ModelModuleFlexiReward extends Model {
	var	$modinstances = array();
	var $rel = array('register'=>'text_reg_points', //$this->language->get('text_reg_points'),
							 'newsletter_sign_up'=> 'text_news_points', //$this->language->get('text_news_points'),
			'productreview'=>'text_product_review_points',// $this->language->get('text_product_review_points'),
			'firstorder'=> 'text_firsto_points',//$this->language->get('text_firsto_points'),
   			'diffproduct'=> 'text_diff_products', //$this->language->get('text_diff_products'),
			'sameproduct'=> 'text_same_products',//$this->language->get('text_same_products'),					  
			'totalproduct'=> 'text_total_products'//$this->language->get('text_total_products')
		);
	var $reldb = array('text_reg_points'=>'flexi_reg_points_desc', 
							 'text_news_points'=> 'flexi_news_points_desc', 
			'text_product_review_points'=>'flexi_product_review_points_desc',
			'text_firsto_points'=> 'flexi_firsto_points_desc',
   			'text_diff_products'=> 'flexi_diff_points_desc',
			'text_same_products'=> 'flexi_same_points_desc',
			'text_total_products'=> 'flexi_total_points_desc'
		);

	var $reli = array('register'=>  -1000000 ,
				     'newsletter_sign_up'=> -2000000,
				     'productreview'=> -3000000
	);
	var $rel_trans = array(); // Will get the translations.
	
	/*GGW We added few functions to deliver info replacing the missing model */
	public function getModulesByCode($code) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "module` WHERE `code` = '" . $this->db->escape($code) . "' ORDER BY `name`");
		return $query->rows;
	}	

	public function getModuleInstances($code) {
		$modules = $this->getModulesByCode($code);
		foreach ($modules as $key => $module) {
			$modules[$key]['setting'] = $m = unserialize($module['setting']);
			if($modules[$key]['setting'][$code.'_status'] != 1) unset($modules[$key]);
		}
		$this->modinstances[$code] = $modules;
		return $modules;
	}
	
	public function getFlexi($customer_group = 1) {
		$code = 'flexi_reward';
		$customer_group = $this->customer->getGroupId() ? $this->customer->getGroupId() : (int)$this->config->get('config_customer_group_id');
		$instances = isset($this->modinstances[$code]) ? $this->modinstances[$code] : $this->getModuleInstances($code); 

		$this->load->language('module/flexi_reward');

/*		$this->rel = array('register'=>'text_reg_points', //$this->language->get('text_reg_points'),
							 'newsletter_sign_up'=> 'text_news_points', //$this->language->get('text_news_points'),
							 'productreview'=> 'text_product_review_points' // $this->language->get('text_product_review_points')
		);*/
		foreach ($instances as $module) {
			if($module['setting']['flexi_reward_customer_groups'] == $customer_group) {
				$settings = $module['setting'];
			}
		}
		// Translate
		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();
		// SUGGESTION ::: check if language id is persistent when languages are deleted and added again. It could be better to use lang code like "en".
		$language_id = $languages[$this->session->data['language']]['language_id'];

		foreach($this->rel as $key => $val) {
			$this->rel_trans[$val] = !empty($settings[$this->reldb[$val]][$language_id]) ? $settings[$this->reldb[$val]][$language_id] : $this->language->get($val) ;
		}
		
		return isset($settings) ? $settings : false;
	}
////////////////////////
// v3.0
////////////////////////
	public function addReward($reward_event = '', $data = NULL, $customer_id=false, $customer_group_id=false ) {
		if (!$this->customer->isLogged()) {
			return false;
		}
        if(($flexi_settings = $this->getFlexi()) === false){
			return false;
		}
        if(!array_key_exists($reward_event,$this->rel)){
			return false;
		}
		//Get the points to reward
		switch($reward_event){
			case 'register':
				if($flexi_settings['flexi_reg_points'] == '0') {
					return false;
				}
				$points = $flexi_settings['flexi_reg_points'];
			break;
			case 'newsletter_sign_up':
				if($flexi_settings['flexi_news_points'] == '0') {
					return false;
				}
				if( $this->getTotalCustomerRewardsByOrderId( $this->reli[$reward_event] ) > 0){
					return false;
				}
				$points = $flexi_settings['flexi_news_points'];
			break;
			default:
				$points = 0;
			break;
		}
		
		$this->load->model('account/customer');
		if($customer_id === false || $customer_group_id === false ) {
			$customer_id = $this->customer->getId();
			$customer_group_id = $this->customer->getGroupId(); 
		}
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_reward SET customer_id = '" . (int)$customer_id . "', 
							points = '" . (int)$points . "', order_id='".$this->reli[$reward_event]."', description = '" .$this->db->escape($this->rel[$reward_event]). "', 
							date_added = NOW()");

	/*			$this->load->language('mail/customer');

		$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore($customer_info['store_id']);

			if ($store_info) {
				$store_name = $store_info['name'];
			} else {
				$store_name = $this->config->get('config_name');
			}
*/
	/*		$message  = sprintf($this->language->get('text_reward_received'), $points) . "\n\n";
			$message .= sprintf($this->language->get('text_reward_total'), $this->getRewardTotal($customer_id));

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($customer_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($store_name, ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(sprintf($this->language->get('text_reward_subject'), html_entity_decode($store_name, ENT_QUOTES, 'UTF-8')));
			$mail->setText($message);
			$mail->send();
	//	}
	*/
	}

	public function deleteReward($order_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_reward WHERE order_id = '" . (int)$order_id . "' AND points > 0");
	}

	public function getRewards($customer_id, $start = 0, $limit = 10) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "' ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalRewards($customer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row['total'];
	}

	public function getRewardTotal($customer_id) {
		$query = $this->db->query("SELECT SUM(points) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row['total'];
	}

	public function getTotalCustomerRewardsByOrderId($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_reward WHERE order_id = '" . (int)$order_id . "' AND customer_id = '" . (int) $this->customer->getId() . "'");

		return $query->row['total'];
	}


}
?>