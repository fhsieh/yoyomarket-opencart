<?php
/* 
Version: 1.0
Author: Artur Sułkowski
Website: http://artursulkowski.pl
*/

class ControllerModuleCameraSlider extends Controller {
	private $error = array(); 
	
	public function slider() {
		$this->language->load('module/camera_slider');
		
		$this->document->setTitle('Camera slider');
		
		// Dodawanie plików css i js do <head>
		$this->document->addStyle('view/stylesheet/camera_slider.css');
		
		// Ładowanie modelu Camera slider
		$this->load->model('slider/camera_slider');
		
		// Pobieranie ustawień slidera
		if(isset($_GET['slider_id'])) {
			$data = $this->model_slider_camera_slider->getData(intval($_GET['slider_id']));
			if($data == false) { 
				$this->response->redirect($this->url->link('module/camera_slider/sliders', 'token=' . $this->session->data['token'], 'SSL'));
			}
			$data['slider_height'] = $data['settings']['slider_height'];
			$data['slider_width'] = $data['settings']['slider_width'];
			$data['layout_type'] = $data['settings']['layout_type'];
			$data['slider_name'] = $data['name'];
			if(is_array($data['content'])) {
				$data['sliders'] = $data['content'];
			} else {
				$data['sliders'] = array();
			}
			$data['slider_id'] = $_GET['slider_id'];
		} else {
			$data['slider_height'] = '465';
			$data['slider_width'] = '1122';
			$data['layout_type'] = false;
			$data['slider_name'] = 'New slider';
			$data['sliders'] = array();
		}
		
		// Dodawanie slideru
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if(isset($_POST['button-add'])) {
				if($this->model_slider_camera_slider->addSlider($this->request->post)) {
					$this->session->data['success'] = $this->language->get('text_success');
				} else {
					$this->session->data['error_warning'] = $this->model_slider_camera_slider->displayError();
				}
				$this->response->redirect($this->url->link('module/camera_slider/sliders', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}
		
		// Zapisywanie slideru
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if(isset($_POST['button-save'])) {
				if($this->model_slider_camera_slider->saveSlider($this->request->post)) {
					$this->session->data['success'] = $this->language->get('text_success');
				} else {
					$this->session->data['error_warning'] = $this->model_slider_camera_slider->displayError();
				}
				$this->response->redirect($this->url->link('module/camera_slider/sliders', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}
		
		// Usuwanie slideru
		if(isset($_GET['slider_id']) && isset($_GET['delete'])) {
			if($this->validate()){
				if($this->model_slider_camera_slider->deleteSlider(intval($_GET['slider_id']))) {
					$this->session->data['success'] = 'This slider has been properly removed from the database.';
				} else {
					$this->session->data['error_warning'] = $this->model_slider_camera_slider->displayError();
				}
			} else {
				$this->session->data['error_warning'] = $this->language->get('error_permission');
			}
			$this->response->redirect($this->url->link('module/camera_slider/sliders', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		// Wyświetlanie powiadomień
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];
		    unset($this->session->data['error_warning']);
		}
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
		    unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		$data['action'] = $this->url->link('module/camera_slider/slider', 'token=' . $this->session->data['token'], 'SSL');
		$data['token'] = $this->session->data['token'];
		
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
			'text' => 'Camera slider',
			'href' => $this->url->link('module/camera_slider', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		// No image
		$this->load->model('tool/image');
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/camera_slider/slider.tpl', $data));
		
	}
	
	public function sliders() {
		$this->language->load('module/camera_slider');

		$this->document->setTitle('Camera slider');
		
		$this->load->model('setting/setting');
		
		// Dodawanie plików css i js do <head>
		$this->document->addStyle('view/stylesheet/camera_slider.css');
		
		// Ładowanie modelu Camera slider
		$this->load->model('slider/camera_slider');

		// Pobranie sliderów
		$data['sliders'] = $this->model_slider_camera_slider->getSliders();
		
		// Wyświetlanie powiadomień
 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];
		    unset($this->session->data['error_warning']);
		}
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
		    unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		$data['action'] = $this->url->link('module/camera_slider/sliders', 'token=' . $this->session->data['token'], 'SSL');
		$data['placement'] = $this->url->link('module/camera_slider', 'token=' . $this->session->data['token'], 'SSL');		
		$data['existing'] = $this->url->link('module/camera_slider/sliders', 'token=' . $this->session->data['token'], 'SSL');	
		$data['link_slider'] = $this->url->link('module/camera_slider/slider', 'token=' . $this->session->data['token'], 'SSL');	
		$data['token'] = $this->session->data['token'];

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
			'text' => 'Camera slider',
			'href' => $this->url->link('module/camera_slider', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/camera_slider/sliders.tpl', $data));
		
	}
	 
	public function index() {   
		$this->language->load('module/camera_slider');

		$this->document->setTitle('Camera slider');
		
		$this->load->model('setting/setting');
		
		// Dodawanie plików css i js do <head>
		$this->document->addStyle('view/stylesheet/camera_slider.css');
		
		// Ładowanie modelu Camera slider
		$this->load->model('slider/camera_slider');
		
		// Instalacja Camera slider w bazie danych
		$this->model_slider_camera_slider->install();
		
		// Pobranie sliderów
		$data['sliders'] = $this->model_slider_camera_slider->getSliders();
		
		// Zapisywanie modułu		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('camera_slider', $this->request->post);		
			
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->response->redirect($this->url->link('module/camera_slider', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		// Wyświetlanie powiadomień
 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];
		    unset($this->session->data['error_warning']);
		}
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
		    unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		$data['action'] = $this->url->link('module/camera_slider', 'token=' . $this->session->data['token'], 'SSL');
		$data['placement'] = $this->url->link('module/camera_slider', 'token=' . $this->session->data['token'], 'SSL');		
		$data['existing'] = $this->url->link('module/camera_slider/sliders', 'token=' . $this->session->data['token'], 'SSL');		
		$data['token'] = $this->session->data['token'];
	
		// Ładowanie listy modułów
		$data['modules'] = array();
		
		if (isset($this->request->post['camera_slider_module'])) {
			$data['modules'] = $this->request->post['camera_slider_module'];
		} elseif ($this->config->get('camera_slider_module')) { 
			$data['modules'] = $this->config->get('camera_slider_module');
		}	
		
		// Load model layout		
		$this->load->model('design/layout');
		$data['layouts'] = $this->model_design_layout->getLayouts();
		
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
			'text' => 'Camera slider',
			'href' => $this->url->link('module/camera_slider', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/camera_slider/placement.tpl', $data));
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/camera_slider')) {
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