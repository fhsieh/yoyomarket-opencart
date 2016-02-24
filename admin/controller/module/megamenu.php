<?php
/* 
Version: 1.0
Author: Artur Sułkowski
Website: http://artursulkowski.pl
*/

class ControllerModuleMegaMenu extends Controller {
	
	private $error = array(); 
	
	public function index() {  
	
		//Load the language file for this module
		$this->language->load('module/megamenu');

		//Set the title from the language file $_['heading_title'] string
		$this->document->setTitle('MegaMenu'); 
		
		//Load the settings model. You can also add any other models you want to load here.
		$this->load->model('setting/setting');
		
		// Dodawanie plików css i js do <head>
		$this->document->addStyle('view/stylesheet/megamenu.css');
		$this->document->addScript('view/javascript/jquery/jquery.nestable.js');
		
		// Ładowanie modelu megamenu
		$this->load->model('menu/megamenu');
		
		// Instalacja megamenu w bazie danych
		$this->model_menu_megamenu->install();
		
		// Multilanguage
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		$data['language_id'] = 0;
		foreach($data['languages'] as $value) {
			if($value['code'] == $this->config->get('config_language')) {
				$data['language_id'] = $value['language_id'];
			}
		}
		
		// Usuwanie menu
		if(isset($_GET['delete'])) {
			if($this->validate()){
				if($this->model_menu_megamenu->deleteMenu(intval($_GET['delete']))) {
					$this->session->data['success'] = 'This menu has been properly removed from the database.';
				} else {
					$this->session->data['error_warning'] = $this->model_menu_megamenu->displayError();
				}
			} else {
				$this->session->data['error_warning'] = $this->language->get('error_permission');
			}
			
			$this->response->redirect($this->url->link('module/megamenu', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		// Dodawanie menu
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			if(isset($_POST['button-create'])) {
				if($this->validate()) {
					$error = false;
					$lang_id = $data['language_id'];
					if($this->request->post['name'][$lang_id] == '') $error = true;
					if($error == true) {
						$this->session->data['error_warning'] = $this->language->get('text_warning');
					} else {
						$this->model_menu_megamenu->addMenu($this->request->post);
						$this->session->data['success'] = $this->language->get('text_success');
					}
				} else {
					$this->session->data['error_warning'] = $this->language->get('error_permission');
				}
				
				$this->response->redirect($this->url->link('module/megamenu', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}
		
		// Zapisywanie menu
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			if(isset($_POST['button-edit'])) {
				if($this->validate()) {
					$error = false;
					$lang_id = $data['language_id'];
					if($this->request->post['name'][$lang_id] == '') $error = true;
					if($error == true) {
						$this->session->data['error_warning'] = $this->language->get('text_warning');
					} else {
						$this->model_menu_megamenu->saveMenu($this->request->post);
						$this->session->data['success'] = $this->language->get('text_success');
					}
				} else {
					$this->session->data['error_warning'] = $this->language->get('error_permission');
				}
				
				$this->response->redirect($this->url->link('module/megamenu', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}
		
		// Generowanie menu z lewej strony
		$data['nestable_list'] = $this->model_menu_megamenu->generate_nestable_list($data['language_id']);
				
		// Zapisywanie ustawień
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if(isset($_POST['button-save'])){
				$megamenu = array();
				if(isset($this->request->post['search_bar'])) {
					$search_bar = 1;
				} else {
					$search_bar = 0;
				}
				if(!isset($this->request->post['layout_id'])) $this->request->post['layout_id'] = 100;
				if(!isset($this->request->post['position'])) $this->request->post['position'] = 'menu';
				if(!isset($this->request->post['status'])) $this->request->post['status'] = 1;
				if(!isset($this->request->post['sort_order'])) $this->request->post['layout_id'] = 0;
				if(!isset($this->request->post['orientation'])) $this->request->post['orientation'] = 0;
				if(!isset($this->request->post['navigation_text'])) $this->request->post['navigation_text'] = 0;
				if(!isset($this->request->post['home_text'])) $this->request->post['home_text'] = 0;
				if(!isset($this->request->post['full_width'])) $this->request->post['full_width'] = 0;
				if(!isset($this->request->post['home_item'])) $this->request->post['home_item'] = 0;
				if(!isset($this->request->post['animation'])) $this->request->post['animation'] = 'slide';
				if(!isset($this->request->post['animation_time'])) $this->request->post['animation_time'] = 500;
				if(!isset($this->request->post['status_cache'])) $this->request->post['status_cache'] = 0;
				if(!isset($this->request->post['cache_time'])) $this->request->post['cache_time'] = 1;
				$megamenu['megamenu_module'] = array(
					array(
						'layout_id'  => $this->request->post['layout_id'],
						'position'   => $this->request->post['position'],
						'status'     => $this->request->post['status'],
						'sort_order' => intval($this->request->post['sort_order']),
						'orientation' =>  $this->request->post['orientation'],
						'search_bar' => $search_bar,
						'navigation_text' => $this->request->post['navigation_text'],
						'home_text'  => $this->request->post['home_text'],
						'full_width' => $this->request->post['full_width'],
						'home_item'  => $this->request->post['home_item'],
						'animation'  => $this->request->post['animation'],
						'animation_time'  => intval($this->request->post['animation_time']),
						'status_cache'  => intval($this->request->post['status_cache']),
						'cache_time'  => intval($this->request->post['cache_time'])
					)
				);
				$this->model_setting_setting->editSetting('megamenu', $megamenu);
				$this->session->data['success'] = $this->language->get('text_success');
				
				$this->response->redirect($this->url->link('module/megamenu', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}
		
		// Zapisywanie kolejności linków
		if (isset($_GET['jsonstring'])) {
			if($this->validate()){
				$jsonstring = $_GET['jsonstring'];
				$jsonDecoded = json_decode(html_entity_decode($jsonstring));
				
				function parseJsonArray($jsonArray, $parentID = 0) {
					$return = array();
					foreach ($jsonArray as $subArray) {
						$returnSubSubArray = array();
						if (isset($subArray->children)) {
							$returnSubSubArray = parseJsonArray($subArray->children, $subArray->id);
						}
						$return[] = array('id' => $subArray->id, 'parentID' => $parentID);
						$return = array_merge($return, $returnSubSubArray);
					}
				
					return $return;
				}
				
				
				$readbleArray = parseJsonArray($jsonDecoded);
								
				foreach ($readbleArray as $key => $value) {
					if (is_array($value)) {
						$this->model_menu_megamenu->save_rang($value['parentID'], $value['id'], $key);
					}	
				}

				die("The list was updated ".date("y-m-d H:i:s")."!");
				
			} else {
				die($this->language->get('error_permission'));
			}
		}
		
		// Pobranie ustawień
		if($this->config->get('megamenu_module') != '') {
			$ustawienia = $this->config->get('megamenu_module');
			$data['layout_id'] = $ustawienia[0]['layout_id'];
			$data['status'] = $ustawienia[0]['status'];
			$data['position'] = $ustawienia[0]['position'];
			$data['orientation'] = $ustawienia[0]['orientation'];
			$data['search_bar'] = $ustawienia[0]['search_bar'];
			$data['sort_order'] = $ustawienia[0]['sort_order'];
			$data['navigation_text'] = $ustawienia[0]['navigation_text'];
			$data['home_text'] = $ustawienia[0]['home_text'];
			$data['full_width'] = $ustawienia[0]['full_width'];
			$data['home_item'] = $ustawienia[0]['home_item'];
			$data['animation'] = $ustawienia[0]['animation'];
			$data['animation_time'] = $ustawienia[0]['animation_time'];
			if(isset($ustawienia[0]['status_cache'])) {
				$data['status_cache'] = $ustawienia[0]['status_cache'];
				$data['cache_time'] = $ustawienia[0]['cache_time'];
			} else {
				$data['status_cache'] = '0';
				$data['cache_time'] = '1';
			}
		} else {
			$data['layout_id'] = 100;
			$data['status'] = 1;
			$data['orientation'] = 0;
			$data['position'] = 'menu';   
			$data['search_bar'] = 0;
			$data['sort_order'] = 0;
			$data['full_width'] = 0;
			$data['home_item'] = 'icon';
			$data['animation'] = 'slide';
			$data['animation_time'] = '500';
			$data['status_cache'] = '0';
			$data['cache_time'] = '1';
		}
		
		// Dodawanie menu
		$data['action_type'] = 'basic';
		if(isset($_GET['action'])) {
			if($_GET['action'] == 'create') {
				$data['action_type'] = 'create';
				$data['name'] = '';
				$data['description'] = '';
				$data['icon'] = '';
				$data['link'] = '';
				$data['new_window'] = '';
				$data['status'] = '';
				$data['position'] = '';
				$data['submenu_width'] = '100%';
				$data['display_submenu'] = '';
				$data['content_width'] = '4';
				$data['content_type'] = '0';
				$data['content'] = array(
					'html' => array(
							'text' => array()
						),
					'product' => array(
							'id' => '',
							'name' => '',
							'width' => '400',
							'height' => '400'
						),
					'categories' => array(
							'categories' => array(),
							'columns' => '',
							'submenu' => '',
							'submenu_columns' => ''
						)
				);
				$data['list_categories'] = false;
			}
		}
		
		// Edycja menu
		if(isset($_GET['edit'])) {
			$data['action_type'] = 'edit';
			$dane = $this->model_menu_megamenu->getMenu(intval($_GET['edit']));
			if($dane) {
				$data['name'] = $dane['name'];
				$data['description'] = $dane['description'];
				$data['icon'] = $dane['icon'];
				$data['link'] = $dane['link'];
				$data['new_window'] = $dane['new_window'];
				$data['status'] = $dane['status'];
				$data['position'] = $dane['position'];
				$data['submenu_width'] = $dane['submenu_width'];
				$data['display_submenu'] = $dane['display_submenu'];
				$data['content_width'] = $dane['content_width'];
				$data['content_type'] = $dane['content_type'];
				$data['content'] = $dane['content'];
				$data['list_categories'] = $this->model_menu_megamenu->getCategories($dane['content']['categories']['categories']);
			} else {
				$this->session->data['error_warning'] = 'This menu does not exist!';
				$this->response->redirect($this->url->link('module/megamenu', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}
		
		// Layouts
		$this->load->model('design/layout');
		$data['layouts'] = $this->model_design_layout->getLayouts();
		
		//This creates an error message. The error['warning'] variable is set by the call to function validate() in this controller (below)
		if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];
			unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
		    unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		$data['action'] = $this->url->link('module/megamenu', 'token=' . $this->session->data['token'], 'SSL');
		
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
			'text' => 'MegaMenu',
			'href' => $this->url->link('module/megamenu', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		// No image
		$this->load->model('tool/image');
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
				
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
				
		$this->response->setOutput($this->load->view('module/megamenu.tpl', $data));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/megamenu')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
	
}

?>