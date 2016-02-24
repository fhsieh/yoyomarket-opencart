<?php
class ControllerModuleFlexiReward extends Controller { 
	private $error = array();

	public function index() {  
	//	$this->load->language('module/featured');
		$this->load->language('module/flexi_reward');

		$this->document->setTitle($this->language->get('heading_title'));
		
	//	$this->load->model('setting/setting');
		$this->load->model('extension/module');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {	
			if (!isset($this->request->get['module_id'])) {
				$this->model_extension_module->addModule('flexi_reward', $this->request->post);
			} else {
				$this->model_extension_module->editModule($this->request->get['module_id'], $this->request->post);
			}

			$this->session->data['success'] = $this->language->get('text_flexi_success');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}		
	
	
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_tc_total'] = $this->language->get('text_tc_total');
		$data['text_tc_sub_total'] = $this->language->get('text_tc_sub_total');
		$data['text_support_short_label'] = $this->language->get('text_support_short_label');
		$data['entry_support_label'] = $this->language->get('entry_support_label');

		$data['entry_total_choice'] = $this->language->get('entry_total_choice');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_minca'] = $this->language->get('entry_minca');
		$data['entry_rate'] = $this->language->get('entry_rate');
		$data['entry_flexi_rewardclass'] = $this->language->get('entry_flexi_rewardclass');
		$data['entry_purchase_text'] = $this->language->get('entry_purchase_text');
		
		$data['entry_status'] = $this->language->get('entry_status');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_howp'] = $this->language->get('entry_howp');
        $data['entry_percentage'] = $this->language->get('entry_percentage');
        $data['entry_howp'] = $this->language->get('entry_howp');
        $data['entry_fixed'] = $this->language->get('entry_fixed');
        $data['entry_percentage'] = $this->language->get('entry_percentage');


	
		// Action bonus entry
		$data['text_reg_points'] = $this->language->get('text_reg_points');		
		$data['entry_reg_points'] = $this->language->get('entry_reg_points');		
		$data['entry_reg_points_desc'] = $this->language->get('entry_reg_points_desc');		

		$data['text_news_points'] = $this->language->get('text_news_points');		
		$data['entry_news_points'] = $this->language->get('entry_news_points');		
		$data['entry_news_points_desc'] = $this->language->get('entry_news_points_desc');		

		$data['text_firsto_points'] = $this->language->get('text_firsto_points');		
		$data['entry_firsto_points'] = $this->language->get('entry_firsto_points');		
		$data['entry_firsto_points_desc'] = $this->language->get('entry_firsto_points_desc');		

		$data['text_product_review_points'] = $this->language->get('text_product_review_points');		
		$data['entry_product_review_points'] = $this->language->get('entry_product_review_points');		
		$data['entry_product_review_points_desc'] = $this->language->get('entry_product_review_points_desc');		

		// Quantity bonus
		$data['text_qty_title'] = $this->language->get('text_qty_title');		
		$data['text_qty_description'] = $this->language->get('text_qty_description');		

		$data['text_diff_products'] = $this->language->get('text_diff_products');		
		$data['entry_diff_points'] = $this->language->get('entry_diff_points');		
		$data['entry_diff_points_desc'] = $this->language->get('entry_diff_points_desc');								
		$data['entry_diff_product_num'] = $this->language->get('entry_diff_product_num');		

		$data['text_same_products'] = $this->language->get('text_same_products');		
		$data['entry_same_points'] = $this->language->get('entry_same_points');		
		$data['entry_same_points_desc'] = $this->language->get('entry_same_points_desc');								
		$data['entry_same_product_num'] = $this->language->get('entry_same_product_num');		

		$data['text_total_products'] = $this->language->get('text_total_products');		
		$data['entry_total_points'] = $this->language->get('entry_total_points');		
		$data['entry_total_points_desc'] = $this->language->get('entry_total_points_desc');								
		$data['entry_total_product_num'] = $this->language->get('entry_total_product_num');		
		// Qunatity ranges
		$data['entry_from'] = $this->language->get('entry_from');		
		$data['entry_to'] = $this->language->get('entry_to');								
		$data['entry_points'] = $this->language->get('entry_points');		

		$data['entry_yes'] = $this->language->get('entry_yes');		
		$data['entry_no'] = $this->language->get('entry_no');								
		$data['entry_options'] = $this->language->get('entry_options');		
		

		$data['name'] = $this->language->get('button_save');

		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');
        $data['tab_setting'] = $this->language->get('tab_setting');
                
        $this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['text_lang_file_desc'] = $this->language->get('text_lang_file_desc');	
		

		// Handle errors messages
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

		if (isset($this->error['flexi_minca'])) {
			$data['error_flexi_minca'] = $this->error['flexi_minca'];
		} else {
			$data['error_flexi_minca'] = '';
		}
		
		
		if (isset($this->error['flexi_purchase_rate'])) {
			$data['error_flexi_purchase_rate'] = $this->error['flexi_purchase_rate'];
		} else {
			$data['error_flexi_purchase_rate'] = '';
		}

		if (isset($this->error['flexi_reward_rate'])) {
			$data['error_flexi_reward_rate'] = $this->error['flexi_reward_rate'];
		} else {
			$data['error_flexi_reward_rate'] = '';
		}
	/////////////////////////////////////
	// 	ERR Since v3.0
	////////////////////////////////////.	
		if (isset($this->error['flexi_reg_points'])) {
			$data['error_reg_points'] = $this->error['flexi_reg_points'];
		} else {
			$data['error_reg_points'] = '';
		}
		if (isset($this->error['flexi_reg_points_desc'])) {
			$data['error_reg_points_desc'] = $this->error['flexi_reg_points_desc'];
		} else {
			$data['error_reg_points_desc'] = '';
		}
		if (isset($this->error['flexi_news_points'])) {
			$data['error_news_points'] = $this->error['flexi_news_points'];
		} else {
			$data['error_news_points'] = '';
		}
		if (isset($this->error['flexi_news_points_desc'])) {
			$data['error_news_points_desc'] = $this->error['flexi_news_points_desc'];
		} else {
			$data['error_news_points_desc'] = '';
		}
		if (isset($this->error['flexi_firsto_op'])) {
			$data['error_firsto_op'] = $this->error['flexi_firsto_op'];
		} else {
			$data['error_firsto_op'] = '';
		}
		if (isset($this->error['flexi_firsto_points'])) {
			$data['error_firsto_points'] = $this->error['flexi_firsto_points'];
		} else {
			$data['error_firsto_points'] = '';
		}
		if (isset($this->error['flexi_firsto_points_desc'])) {
			$data['error_firsto_points_desc'] = $this->error['flexi_firsto_points_desc'];
		} else {
			$data['error_firsto_points_desc'] = '';
		}
		if (isset($this->error['flexi_product_review_points'])) {
			$data['error_product_review_points'] = $this->error['flexi_product_review_points'];
		} else {
			$data['error_product_review_points'] = '';
		}
		if (isset($this->error['flexi_product_review_points_desc'])) {
			$data['error_product_review_points_desc'] = $this->error['flexi_product_review_points_desc'];
		} else {
			$data['error_product_review_points_desc'] = '';
		}
		if (isset($this->error['flexi_diff_op'])) {
			$data['error_diff_op'] = $this->error['flexi_diff_op'];
		} else {
			$data['error_diff_op'] = '';
		}	
		if (isset($this->error['flexi_diff_points'])) {
			$data['error_diff_points'] = $this->error['flexi_diff_points'];
		} else {
			$data['error_diff_points'] = '';
		}	
		if (isset($this->error['flexi_diff_points_desc'])) {
			$data['error_diff_points_desc'] = $this->error['flexi_diff_points_desc'];
		} else {
			$data['error_diff_points_desc'] = '';
		}	
		if (isset($this->error['flexi_same_op'])) {
			$data['error_same_op'] = $this->error['flexi_same_op'];
		} else {
			$data['error_same_op'] = '';
		}	
		if (isset($this->error['flexi_same_points'])) {
			$data['error_same_points'] = $this->error['flexi_same_points'];
		} else {
			$data['error_same_points'] = '';
		}	
		if (isset($this->error['flexi_same_points_desc'])) {
			$data['error_same_points_desc'] = $this->error['flexi_same_points_desc'];
		} else {
			$data['error_same_points_desc'] = '';
		}	
		if (isset($this->error['flexi_same_product_num'])) {
			$data['error_same_product_num'] = $this->error['flexi_same_product_num'];
		} else {
			$data['error_same_product_num'] = '';
		}	
		if (isset($this->error['flexi_total_op'])) {
			$data['error_total_op'] = $this->error['flexi_total_op'];
		} else {
			$data['error_total_op'] = '';
		}	
		if (isset($this->error['flexi_total_points'])) {
			$data['error_total_points'] = $this->error['flexi_total_points'];
		} else {
			$data['error_total_points'] = '';
		}	
		if (isset($this->error['flexi_total_points_desc'])) {
			$data['error_total_points_desc'] = $this->error['flexi_total_points_desc'];
		} else {
			$data['error_total_points_desc'] = '';
		}	
		if (isset($this->error['flexi_total_product_num'])) {
			$data['error_total_product_num'] = $this->error['flexi_total_product_num'];
		} else {
			$data['error_total_product_num'] = '';
		}	

	// ERR END  	END  	END -->  Since v3.0
  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home',
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$data['breadcrumbs'][] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=extension/module',
       		'text'      => $this->language->get('text_module'),
      		'separator' => ' :: '
   		);
		
   		$data['breadcrumbs'][] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=module/flexi_reward',
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('module/flexi_reward', 'token=' . $this->session->data['token'], 'SSL')
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('module/flexi_reward', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('module/flexi_reward', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('module/flexi_reward', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
		}

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
		}
	/////////////////////////////////////
	//
	////////////////////////////////////.	
		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}
		
		if (isset($this->request->post['flexi_minca'])) {
			$data['flexi_minca'] = $this->request->post['flexi_minca'];
		} elseif (!empty($module_info)) {
			$data['flexi_minca'] = isset($module_info['flexi_minca']) ? $module_info['flexi_minca'] : 20 ;
		} else {
			$data['flexi_minca'] = '20';
		}

		if (isset($this->request->post['flexi_total_choice'])) {
			$data['flexi_total_choice'] = $this->request->post['flexi_total_choice'];
		} elseif (!empty($module_info['flexi_total_choice'])) {
			$data['flexi_total_choice'] = $module_info['flexi_total_choice'];
		} else {
			$data['flexi_total_choice'] = '0';
		}
		
		if (isset($this->request->post['flexi_reward_rate'])) {
			$data['flexi_reward_rate'] = $this->request->post['flexi_reward_rate'];
		} elseif (!empty($module_info['flexi_reward_rate'])) {
			$data['flexi_reward_rate'] = $module_info['flexi_reward_rate'];
		} else {
			$data['flexi_reward_rate'] = '0.01';
		}
		if (isset($this->request->post['flexi_purchase_rate'])) {
			$data['flexi_purchase_rate'] = $this->request->post['flexi_purchase_rate'];
		} elseif (!empty($module_info['flexi_purchase_rate'])) {
			$data['flexi_purchase_rate'] = $module_info['flexi_purchase_rate'];
		} else {
			$data['flexi_purchase_rate'] = '1';
		}
		if (isset($this->request->post['flexi_reward_status'])) {
			$data['flexi_reward_status'] = $this->request->post['flexi_reward_status'];
		} elseif (!empty($module_info['flexi_reward_status'])) {
			$data['flexi_reward_status'] = $module_info['flexi_reward_status'];
		} else {
			$data['flexi_reward_status'] = '1';
		}
		if (isset($this->request->post['flexi_reward_order_status'])) {
			$data['flexi_reward_order_status'] = $this->request->post['flexi_reward_order_status'];
		} elseif (!empty($module_info)) {
			$data['flexi_reward_order_status'] = $module_info['flexi_reward_order_status'];
		} else {
			$data['flexi_reward_order_status'] = '';
		}
		if (isset($this->request->post['flexi_reward_customer_groups'])) {
			$data['flexi_reward_customer_groups_id'] = $this->request->post['flexi_reward_customer_groups'];
		} elseif (!empty($module_info)) {
			$data['flexi_reward_customer_groups_id'] = $module_info['flexi_reward_customer_groups'];
		} else {
			$data['flexi_reward_customer_groups_id'] = '';
		}
		
	/////////////////////////////////////
	// 	DATA Since v3.0
	////////////////////////////////////.	
		// Reg points
		if (isset($this->request->post['flexi_reg_points'])) {
			$data['flexi_reg_points'] = $this->request->post['flexi_reg_points'];
		} elseif (!empty($module_info['flexi_reg_points'])) {
			$data['flexi_reg_points'] = $module_info['flexi_reg_points'];
		} else {
			$data['flexi_reg_points'] = '0';
		}
		// flexi_reg_points_desc
		if (isset($this->request->post['flexi_reg_points_desc'])) {
			$data['flexi_reg_points_desc'] = $this->request->post['flexi_reg_points_desc'];
		} elseif (!empty($module_info['flexi_reg_points_desc'])) {
			$data['flexi_reg_points_desc'] = $module_info['flexi_reg_points_desc'];
		} else {
			foreach($data['languages'] as $language) {
				$data['flexi_reg_points_desc'][$language['language_id']] = $this->language->get('text_reg_points');
			}
		}
		// flexi_news_points
		if (isset($this->request->post['flexi_news_points'])) {
			$data['flexi_news_points'] = $this->request->post['flexi_news_points'];
		} elseif (!empty($module_info['flexi_news_points'])) {
			$data['flexi_news_points'] = $module_info['flexi_news_points'];
		} else {
			$data['flexi_news_points'] = '0';
		}
		// flexi_news_points_desc
		if (isset($this->request->post['flexi_news_points_desc'])) {
			$data['flexi_news_points_desc'] = $this->request->post['flexi_news_points_desc'];
		} elseif (!empty($module_info['flexi_news_points_desc'])) {
			$data['flexi_news_points_desc'] = $module_info['flexi_news_points_desc'];
		} else {
			foreach($data['languages'] as $language) {
				$data['flexi_news_points_desc'][$language['language_id']] = $this->language->get('text_news_points');
			}
		}
		// flexi_firsto_op
		if (isset($this->request->post['flexi_firsto_op'])) {
			$data['flexi_firsto_op'] = $this->request->post['flexi_firsto_op'];
		} elseif (!empty($module_info)) {
			$data['flexi_firsto_op'] = $module_info['flexi_firsto_op'];
		} else {
			$data['flexi_firsto_op'] = '1';
		}
		// flexi_firsto_points
		if (isset($this->request->post['flexi_firsto_points'])) {
			$data['flexi_firsto_points'] = $this->request->post['flexi_firsto_points'];
		} elseif (!empty($module_info['flexi_firsto_points'])) {
			$data['flexi_firsto_points'] = $module_info['flexi_firsto_points'];
		} else {
			$data['flexi_firsto_points'] = '0';
		}
		// flexi_firsto_points_desc
		if (isset($this->request->post['flexi_firsto_points_desc'])) {
			$data['flexi_firsto_points_desc'] = $this->request->post['flexi_firsto_points_desc'];
		} elseif (!empty($module_info['flexi_firsto_points_desc'])) {
			$data['flexi_firsto_points_desc'] = $module_info['flexi_firsto_points_desc'];
		} else {
			foreach($data['languages'] as $language) {
				$data['flexi_firsto_points_desc'][$language['language_id']] = $this->language->get('text_firsto_points');
			}
		}
		// flexi_product_review_points
		if (isset($this->request->post['flexi_product_review_points'])) {
			$data['flexi_product_review_points'] = $this->request->post['flexi_product_review_points'];
		} elseif (!empty($module_info['flexi_product_review_points'])) {
			$data['flexi_product_review_points'] = $module_info['flexi_product_review_points'];
		} else {
			$data['flexi_product_review_points'] = '0';
		}
		// flexi_product_review_points_desc
		if (isset($this->request->post['flexi_product_review_points_desc'])) {
			$data['flexi_product_review_points_desc'] = $this->request->post['flexi_product_review_points_desc'];
		} elseif (!empty($module_info['flexi_product_review_points_desc'])) {
			$data['flexi_product_review_points_desc'] = $module_info['flexi_product_review_points_desc'];
		} else {
			foreach($data['languages'] as $language) {
				$data['flexi_product_review_points_desc'][$language['language_id']] = $this->language->get('text_product_review_points');
			}
		}
		
		// flexi_diff_op
		if (isset($this->request->post['flexi_diff_op'])) {
			$data['flexi_diff_op'] = $this->request->post['flexi_diff_op'];
		} elseif (!empty($module_info)) {
			$data['flexi_diff_op'] = $module_info['flexi_diff_op'];
		} else {
			$data['flexi_diff_op'] = '1';
		}
		// flexi_diff_points_desc
		if (isset($this->request->post['flexi_diff_points_desc'])) {
			$data['flexi_diff_points_desc'] = $this->request->post['flexi_diff_points_desc'];
		} elseif (!empty($module_info['flexi_diff_points_desc'])) {
			$data['flexi_diff_points_desc'] = $module_info['flexi_diff_points_desc'];
		} else {
			foreach($data['languages'] as $language) {
				$data['flexi_diff_points_desc'][$language['language_id']] = $this->language->get('text_diff_products');
			}
		}
		// flexi_diff_product
		if (isset($this->request->post['flexi_diff_product'])) {
			$data['flexi_diff_product'] = $this->request->post['flexi_diff_product'];
		} elseif (!empty($module_info['flexi_diff_product'])) {
			$data['flexi_diff_product'] = $module_info['flexi_diff_product'];
		} else {
			$data['flexi_diff_product'] = array();
			$data['flexi_diff_product'][0]['from'] = 0;
			$data['flexi_diff_product'][0]['to'] = 0;
			$data['flexi_diff_product'][0]['points'] = 0;
		}

		// flexi_same_op
		if (isset($this->request->post['flexi_same_op'])) {
			$data['flexi_same_op'] = $this->request->post['flexi_same_op'];
		} elseif (!empty($module_info)) {
			$data['flexi_same_op'] = $module_info['flexi_same_op'];
		} else {
			$data['flexi_same_op'] = '1';
		}
		// flexi_same_points_desc
		if (isset($this->request->post['flexi_same_points_desc'])) {
			$data['flexi_same_points_desc'] = $this->request->post['flexi_same_points_desc'];
		} elseif (!empty($module_info['flexi_same_points_desc'])) {
			$data['flexi_same_points_desc'] = $module_info['flexi_same_points_desc'];
		} else {
			foreach($data['languages'] as $language) {
				$data['flexi_same_points_desc'][$language['language_id']] = $this->language->get('text_same_products');
			}
		}
		// flexi_same_product
		if (isset($this->request->post['flexi_same_product'])) {
			$data['flexi_same_product'] = $this->request->post['flexi_same_product'];
		} elseif (!empty($module_info['flexi_same_product'])) {
			$data['flexi_same_product'] = $module_info['flexi_same_product'];
		} else {
			$data['flexi_same_product'] = array();
			$data['flexi_same_product'][0]['from'] = 0;
			$data['flexi_same_product'][0]['to'] = 0;
			$data['flexi_same_product'][0]['points'] = 0;
		}
		// flexi_total_op
		if (isset($this->request->post['flexi_total_op'])) {
			$data['flexi_total_op'] = $this->request->post['flexi_total_op'];
		} elseif (!empty($module_info['flexi_total_op'])) {
			$data['flexi_total_op'] = $module_info['flexi_total_op'];
		} else {
			$data['flexi_total_op'] = '1';
		}
		
		// flexi_total_points_desc
		if (isset($this->request->post['flexi_total_points_desc'])) {
			$data['flexi_total_points_desc'] = $this->request->post['flexi_total_points_desc'];
		} elseif (!empty($module_info['flexi_total_points_desc'])) {
			$data['flexi_total_points_desc'] = $module_info['flexi_total_points_desc'];
		} else {
			foreach($data['languages'] as $language) {
				$data['flexi_total_points_desc'][$language['language_id']] = $this->language->get('text_total_products');
			}
		}
		// flexi_total_product
		if (isset($this->request->post['flexi_total_product'])) {
			$data['flexi_total_product'] = $this->request->post['flexi_total_product'];
		} elseif (!empty($module_info['flexi_total_product'])) {
			$data['flexi_total_product'] = $module_info['flexi_total_product'];
		} else {
			$data['flexi_total_product'] = array();
			$data['flexi_total_product'][0]['from'] = 0;
			$data['flexi_total_product'][0]['to'] = 0;
			$data['flexi_total_product'][0]['points'] = 0;
		}
		// flexi_ignore_opt
		if (isset($this->request->post['flexi_ignore_opt'])) {
			$data['flexi_ignore_opt'] = $this->request->post['flexi_ignore_opt'];
		} elseif (!empty($module_info['flexi_ignore_opt'])) {
			$data['flexi_ignore_opt'] = $module_info['flexi_ignore_opt'];
		} else {
			$data['flexi_ignore_opt'] = '0';
		}
		
	// 	END 	END 	END 	END  --> Since v3.0 
		
		$data['entry_customer_groups'] = $this->language->get('entry_customer_groups');

		$this->load->model('sale/customer_group');
		
		$results = $this->model_sale_customer_group->getCustomerGroups();

		foreach ($results as $result) {
			$data['customer_groups'][] = array(
				'customer_group_id' => $result['customer_group_id'],
				'name'              => $result['name'] . (($result['customer_group_id'] == $this->config->get('config_customer_group_id')) ? $this->language->get('text_default') : ''),
			);
		}



		$data['token'] = $this->session->data['token'];
	
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/flexi_reward.tpl', $data));

	}
		
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/flexi_reward')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['name'] || strlen($this->request->post['name']) < 1) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (!$this->request->post['flexi_purchase_rate'] || !is_numeric($this->request->post['flexi_purchase_rate'])) {
			$this->error['flexi_purchase_rate'] = $this->language->get('error_flexi_purchase_rate');
		}

		if (!$this->request->post['flexi_reward_rate'] || !is_numeric($this->request->post['flexi_reward_rate'])) {
			$this->error['flexi_reward_rate'] = $this->language->get('error_flexi_reward_rate');
		}

		if (!$this->request->post['flexi_minca'] || !is_numeric($this->request->post['flexi_minca'])) {
			$this->error['flexi_minca'] = $this->language->get('error_flexi_minca');
		}
		/*
			Set correct values for the Quantity ranges. Eliminate the option for SQL injection here.
		*/
		$this->setQtyFilters('flexi_diff_product');
		$this->setQtyFilters('flexi_total_product');
		$this->setQtyFilters('flexi_same_product');


		/*
			Set correct value for Action bonuses
		*/
		$this->request->post['flexi_product_review_points'] = $this->setActFilters($this->request->post['flexi_product_review_points']);
		$this->request->post['flexi_firsto_points'] = $this->setActFilters($this->request->post['flexi_firsto_points']);
		$this->request->post['flexi_news_points'] = $this->setActFilters($this->request->post['flexi_news_points']);
		$this->request->post['flexi_reg_points'] = $this->setActFilters($this->request->post['flexi_reg_points']);
		

		// Get all mods and check for duplicate customer group 
		$modules = $this->model_extension_module->getModulesByCode('flexi_reward');
		$ids = array();
		$id = -1;
		if (isset($this->request->get['module_id'])) {
			$id =  $this->request->get['module_id'];
		}
		foreach ($modules as $module) {
			$m = unserialize($module['setting']);
			$ids[$m['flexi_reward_customer_groups']] = $module['module_id'];
			$_name = $this->request->post['flexi_reward_customer_groups'] == $m['flexi_reward_customer_groups'] ? $module['name'] : NULL;
		}
		
		if(isset($ids[$this->request->post['flexi_reward_customer_groups']]) && $ids[$this->request->post['flexi_reward_customer_groups']] != $id) {
			$this->error['warning'] = sprintf($this->language->get('error_customer_group_exist'),$_name);
		}


		return !$this->error;
		
	}
	public function setActFilters($field) {
		$field = (float) $field == (int) $field ? (string)(int) $field : (string)(float) $field;
		return 	$field;
	}

	public function setQtyFilters($arrkey) {
		if (isset($this->request->post[$arrkey])) {
			foreach($this->request->post[$arrkey] as $key => $val) {
				$this->request->post[$arrkey][$key]['points'] = (float) $val['points'] == (int) $val['points'] ? (string)(int) $val['points'] : (string)(float) $val['points'];
	
				if($arrkey == 'flexi_same_product') {
					// If same is less than 2 it is useless to use
					$val['from'] = ($val['from'] < 2) ? '2' : $val['from'] ;
					$this->request->post[$arrkey][$key]['from'] = (string)(int) $val['from'];
				}
				$this->request->post[$arrkey][$key]['to'] = (string)(int) $val['to'];				
			}
		}	else {
			$this->request->post[$arrkey] = array();
			$this->request->post[$arrkey][0]['from'] = (string) 0;
			$this->request->post[$arrkey][0]['to'] = (string) 0;
			$this->request->post[$arrkey][0]['points'] = (string) 0;
		}

	}
}
?>