<?php
/* 
Template Name: Stowear
Version: 1.0
Author: Artur Sułkowski
Website: http://artursulkowski.pl
*/

class ControllerModuleStowear extends Controller {
	
	private $error = array(); 
	
	public function index() {   
	
		//Load the language file for this module
		$this->language->load('module/stowear');

		//Set the title from the language file $_['heading_title'] string
		$this->document->setTitle('Stowear Theme Options');
		
		//Load the settings model. You can also add any other models you want to load here.
		$this->load->model('setting/setting');
		
		// Multilanguage
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		// Konfiguracja zmiennych
		$config_data = array(
			'main_layout',
			'top_bar_layout',
			'header_layout',
			'slideshow_layout',
			'content_layout',
			'custom_footer_layout',
			'footer_layout',
			'breadcrumb_layout',
			
			'page_width',
			'max_width',
			
			'responsive_design',
			
			'add_to_compare_text',
			'add_to_wishlist_text',
			'checkout_text',
			'continue_shopping_text',
			'confirmation_text',
			'home_text',
			'welcome_text',
			'more_details_text',
			'quickview_text',
			'sale_text',
			'ourbrands_text',
			'login_text',
			'register_text',
			
			'header_type',
			'fixed_header',
			'fixed_menu',
			'quick_search_autosuggest',
			
			'product_per_pow',
			'product_per_pow2',
			'product_scroll_latest',
			'product_scroll_featured',
			'product_scroll_bestsellers',
			'product_scroll_specials',
			'product_scroll_related',
			'quick_view',
			'display_text_sale',
			'type_sale',
			'product_image_effect',
			'display_add_to_compare',
			'display_add_to_wishlist',
			'display_add_to_cart',
			'display_rating',
			'default_list_grid',
			'refine_search_style',
			'refine_image_width',
			'refine_image_height',
			'refine_search_number',
			'product_image_zoom',
			'product_image_size',
			'position_image_additional',
			'product_social_share',
			
			'custom_block',
			
			'customfooter',
			
			'colors_status',
			'body_text_color',
			'body_headlines_color',
			'body_links_color',
			'body_links_hover_color',
			'price_text_color',
			'price_new_text_color',
			'price_old_text_color',
			'body_background_color',
			'body_fixed_content_background_color',
			'dropdown_text_color',
			'dropdown_border_color',
			'dropdown_background_color',
			'dropdown_item_hover_background_color',
			'input_text_color',
			'input_border_color',
			'input_focus_border_color',
			'input_background_color',
			'table_border_color',
			'top_bar_text_color',
			'top_bar_border_color',
			'top_bar_background_color',
			'top_links_color',
			'search_input_text_color',
			'search_input_border_color',
			'search_input_focus_border_color',
			'search_input_background_color',
			'cart_icon',
			'price_in_cart_color',
			'top_background_gradient_top',
			'top_background_gradient_bottom',
			'menu_main_links_color',
			'menu_main_links_hover_color',
			'menu_main_links_hover_border_bottom',
			'menu_icon_home_color',
			'menu_background_gradient_top',
			'menu_background_gradient_bottom',
			'menu_icon_plus_minus_color',
			'submenu_text_color',
			'submenu_link_color',
			'submenu_link_hover_color',
			'submenu_border_color',
			'submenu_background_color',
			'button_text_color',
			'button_background_color',
			'button_hover_text_color',
			'button_hover_background_color',
			'second_button_text_color',
			'second_button_border_color',
			'second_button_background_color',
			'second_button_hover_text_color',
			'second_button_hover_border_color',
			'second_button_hover_background_color',
			'carousel_button_background',
			'carousel_button_hover_background',
			'carousel_bullet_background',
			'carousel_bullet_active_background',
			'slider_button_background',
			'slider_bullet_background',
			'slider_bullet_active_background',
			'product_grid_button_text_color',
			'product_grid_button_background_color',
			'product_grid_button_hover_text_color',
			'product_grid_button_hover_background_color',
			'product_list_button_text_color',
			'product_list_button_border_color',
			'product_list_button_background_color',
			'product_list_button_hover_text_color',
			'product_list_button_hover_border_color',
			'product_list_button_hover_background_color',
			'sale_color_text',
			'sale_background_color',
			'rating_icon_background_color',
			'rating_icon_active_background_color',
			'custom_block_border_color',
			'box_categories_border_color',
			'box_categories_links_active_color',
			'product_filter_icon_color',
			'product_filter_icon_hover_color',
			'tab_link_color',
			'tab_link_active_color',
			'tab_link_active_border_color',
			'pagination_link_border_color',
			'pagination_link_active_border_color',
			'customfooter_text_color',
			'customfooter_headlines_color',
			'customfooter_icon_phone_background_color',
			'customfooter_icon_mail_background_color',
			'customfooter_icon_skype_background_color',
			'customfooter_background_color',
			'footer_text_color',
			'footer_headlines_color',
			'footer_background_color',
			
			'background_status',
			'body_background',
			'body_background_background',
			'body_background_subtle_patterns',
			'body_background_position',
			'body_background_repeat',
			'body_background_attachment',
			'header_background',
			'header_background_background',
			'header_background_subtle_patterns',
			'header_background_position',
			'header_background_repeat',
			'header_background_attachment',
			'customfooter_background',
			'customfooter_background_background',
			'customfooter_background_subtle_patterns',
			'customfooter_background_position',
			'customfooter_background_repeat',
			'customfooter_background_attachment',
			'content_headlines_background',
			'content_headlines_background_background',
			'footer_headlines_background',
			'footer_headlines_background_background',
						
			'font_status',
			'categories_bar',
			'categories_bar_weight',
			'categories_bar_px',
			'headlines',
			'headlines_weight',
			'headlines_px',
			'headlines_transform',
			'footer_headlines',
			'footer_headlines_weight',
			'footer_headlines_px',
			'footer_headlines_transform',
			'body_font',
			'body_font_px',
			'body_font_weight',
			'body_font_smaller_px',
			'page_name',
			'page_name_weight',
			'page_name_px',
			'page_name_transform',
			'button_font',
			'button_font_weight',
			'button_font_px',
			'button_font_transform',
			'custom_price',
			'custom_price_weight',
			'custom_price_px',
			'custom_price_px_medium',
			'custom_price_px_small',
			'custom_price_px_old_price',
			
			'product_image_zoom',
			
			'product_scroll_latest',
			'product_scroll_featured',
			'product_scroll_bestsellers',
			'product_scroll_specials',
			'product_scroll_related',
			
			'custom_code_css_status',
			'custom_code_css',
			'custom_code_javascript_status',
			'custom_code_js',
			
			'refine_image_width',
			'refine_image_height',
			
			'payment_status',
			'payment',
			
			'widget_facebook_status',
			'widget_facebook_id',
			'widget_facebook_position',
			'widget_twitter_status',
			'widget_twitter_id',
			'widget_twitter_user_name',
			'widget_twitter_position',
			'widget_twitter_limit',
			'widget_custom_status',
			'widget_custom_content',
			'widget_custom_position',
			
			'compressor_code_status'
		);
		
		foreach ($config_data as $conf) {
			$data[$conf] = false;
		}

		// Funkcja do usuwania katalogu
		function removeDir($path) { 
			$dir = new DirectoryIterator($path); 
			foreach ($dir as $fileinfo) { 
				if ($fileinfo->isFile() || $fileinfo->isLink()) { 
					unlink($fileinfo->getPathName()); 
				} elseif (!$fileinfo->isDot() && $fileinfo->isDir()) { 
					removeDir($fileinfo->getPathName()); 
				} 
			} 
			rmdir($path); 
		}
		
  		// stowear MUTLI STORE
  		
			if (isset($this->request->post['store_id'])) {
				$data['store_id'] = $this->request->post['store_id'];
			} else {
				$data['store_id'] = $this->config->get('d_store_id');
			}

			$data['stores'] = array();
			
			$this->load->model('setting/store');
			
			$results = $this->model_setting_store->getStores();
			
			$data['stores'][] = array(
				'name' => 'Default',
				'href' => '',
				'store_id' => 0
			);
				
			foreach ($results as $result) {
				$data['stores'][] = array(
					'name' => $result['name'],
					'href' => $result['url'],
					'store_id' => $result['store_id']
				);
			}		
			
			
			if(isset($_GET['store_id'])) {
				$data['store_id'] = $_GET['store_id'];
			} else {
				if (isset($_GET['submit'])) {
					$data['store_id'] = $data['store_id'];
				} else {
					if (isset($this->request->post['store_id'])) {
						$data['store_id'] = $this->request->post['store_id'];
					} else {
						$data['store_id'] = 0;
					}
				}
			}
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
				$data['array'] = array(
					'd_store_id' => $this->request->post['store_id']
				);
				$this->model_setting_setting->editSetting('d_id_store', $data['array']);	
			}
			
		// END MULTISTORE
		
		// Pobieranie informacji, która skórka jest włączona	
		$data['setting_skin'] = $this->model_setting_setting->getSetting('stowear_skin', $data['store_id']);
		
		// Nadanie nazw sklepom 
		if($data['store_id'] == 0) {
			$data['edit_skin_store'] = 'default';
		} else {
			$data['edit_skin_store'] = $data['store_id'];
		}
		
		// Aktywna skórka
		if(isset($data['setting_skin']['stowear_skin'])) {
			$data['active_skin'] = $data['setting_skin']['stowear_skin'];
		} else {
			$data['active_skin'] = 'default';
		}
		
		if(!file_exists(DIR_CATALOG . 'view/theme/stowear/skins/store_'.$data['edit_skin_store'].'/'.$data['active_skin'].'')) {
			$data['active_skin'] = false;
		}
		
		// Tworzenie listy skórek
		if(file_exists(DIR_CATALOG . 'view/theme/stowear/skins/store_'.$data['edit_skin_store'].'/')) {
			$data['skins'] = array();
			$dir = opendir(DIR_CATALOG . 'view/theme/stowear/skins/store_'.$data['edit_skin_store'].'/');
			while(false !== ($file = readdir($dir))) {
				if(is_dir(DIR_CATALOG . 'view/theme/stowear/skins/store_'.$data['edit_skin_store'].'/'.$file) && $file != '.' && $file != '..')  {
					$data['skins'][] = $file;
				}
			}
		}
		
		// Edycja skórki - sprawdzanie jaki szablon jest edytowany
		if(isset($data['setting_skin']['stowear_skin'])) {
			$data['active_skin_edit'] = $data['setting_skin']['stowear_skin'];
		} else {
			$data['active_skin_edit'] = 'default';
		}
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if(isset($_POST['button-edit']) || isset($_POST['button-delete'])){
				$data['active_skin_edit'] = $this->request->post['skin'];
			}
		}
		
		if(isset($this->request->post['save_skin']) && !isset($_POST['button-edit']) && !isset($_POST['button-delete'])) {
			$data['active_skin_edit'] = $this->request->post['save_skin'];
		}
		
		if(isset($_GET['skin_edit'])) {
			$data['active_skin_edit'] = $_GET['skin_edit'];
		}
			
		// Zmiana skórki
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if(isset($_POST['button-active'])){
				$save_stowear_skin = array(
					'stowear_skin' => $this->request->post['skin']
				);
				$this->model_setting_setting->editSetting('stowear_skin', $save_stowear_skin, $this->request->post['store_id']);	
				$this->session->data['success'] = $this->language->get('text_success');
				$this->response->redirect($this->url->link('module/stowear&submit=true', 'token=' . $this->session->data['token'], 'SSL'));
            }
		}
		
		// Dodawanie skórki
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if(isset($_POST['add-skin'])){
				if(is_writable(DIR_CATALOG . 'view/theme/stowear/skins/') && (is_writable(DIR_CATALOG . 'view/theme/stowear/skins/store_'.$data['edit_skin_store'].'/') || !file_exists(DIR_CATALOG . 'view/theme/stowear/skins/store_'.$data['edit_skin_store'].'/'))) {
					// Sprawdzanie czy istnieje folder store_ 
					if(file_exists(DIR_CATALOG . 'view/theme/stowear/skins/store_'.$data['edit_skin_store'].'/') && filetype(DIR_CATALOG . 'view/theme/stowear/skins/store_'.$data['edit_skin_store'].'/') == 'dir') {
					} else {
						mkdir(DIR_CATALOG . 'view/theme/stowear/skins/store_'.$data['edit_skin_store'].'/', 0777);
					}
					
					// Dodawanie pliku z ustawieniami
					if($this->request->post['add-skin-name'] != '') {	
						if(!file_exists(DIR_CATALOG . 'view/theme/stowear/skins/store_'.$data['edit_skin_store'].'/'.$this->request->post['add-skin-name'].'/')) {
							mkdir(DIR_CATALOG . 'view/theme/stowear/skins/store_'.$data['edit_skin_store'].'/'.$this->request->post['add-skin-name'].'/', 0777);
							file_put_contents(DIR_CATALOG . 'view/theme/stowear/skins/store_'.$data['edit_skin_store'].'/'.$this->request->post['add-skin-name'].'/settings.json', json_encode($config_data));
							mkdir(DIR_CATALOG . 'view/theme/stowear/skins/store_'.$data['edit_skin_store'].'/'.$this->request->post['add-skin-name'].'/js/', 0777);
							file_put_contents(DIR_CATALOG . 'view/theme/stowear/skins/store_'.$data['edit_skin_store'].'/'.$this->request->post['add-skin-name'].'/js/custom_code.js', ' ');
							mkdir(DIR_CATALOG . 'view/theme/stowear/skins/store_'.$data['edit_skin_store'].'/'.$this->request->post['add-skin-name'].'/css/', 0777);
							file_put_contents(DIR_CATALOG . 'view/theme/stowear/skins/store_'.$data['edit_skin_store'].'/'.$this->request->post['add-skin-name'].'/css/custom_code.css', ' ');
							$this->session->data['success'] = $this->language->get('text_success');
							$this->response->redirect($this->url->link('module/stowear&submit=true', 'token=' . $this->session->data['token'], 'SSL'));
						}
					}  
				}

				$this->session->data['error_warning'] = 'You need to set CHMOD 777 for all folder and subfolder in catalog/view/theme/stowear/skins!';
				$this->response->redirect($this->url->link('module/stowear&submit=true', 'token=' . $this->session->data['token'], 'SSL'));
		    }
		}
		
		// Zapisywanie skórki
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if(isset($_POST['button-save'])){
				if(is_writable(DIR_CATALOG . 'view/theme/stowear/skins') && is_writable(DIR_CATALOG . 'view/theme/stowear/skins/store_'.$data['edit_skin_store']) && is_writable(DIR_CATALOG . 'view/theme/stowear/skins/store_'.$data['edit_skin_store'].'/'.$data['active_skin_edit'])) {
					// Sprawdzanie czy istnieje skórka
					if(file_exists(DIR_CATALOG . 'view/theme/stowear/skins/store_'.$data['edit_skin_store'].'/') && filetype(DIR_CATALOG . 'view/theme/stowear/skins/store_'.$data['edit_skin_store'].'/') == 'dir' && file_exists(DIR_CATALOG . 'view/theme/stowear/skins/store_'.$data['edit_skin_store'].'/'.$data['active_skin_edit'].'')) {
						// Zapisywanie ustawien
						file_put_contents(DIR_CATALOG . 'view/theme/stowear/skins/store_'.$data['edit_skin_store'].'/'.$data['active_skin_edit'].'/settings.json', json_encode($this->request->post));  
						
						// Custom js
						file_put_contents(DIR_CATALOG . 'view/theme/stowear/skins/store_'.$data['edit_skin_store'].'/'.$data['active_skin_edit'].'/js/custom_code.js', $this->request->post['custom_code_js']);  
						
						// Custom css
						file_put_contents(DIR_CATALOG . 'view/theme/stowear/skins/store_'.$data['edit_skin_store'].'/'.$data['active_skin_edit'].'/css/custom_code.css', $this->request->post['custom_code_css']);  
						
						// Informacja o zapisaniu ustawien
						$this->session->data['success'] = $this->language->get('text_success');
						$this->response->redirect($this->url->link('module/stowear&submit=true&skin_edit=' . $data['active_skin_edit'] . '', 'token=' . $this->session->data['token'], 'SSL'));
					}
				}
				
				// Jezeli nie istnieje katalog skórki to pojawia się komunikat o błedzie
				$this->session->data['error_warning'] = 'You need to set CHMOD 777 for all folder and subfolder in catalog/view/theme/stowear/skins!';
				$this->response->redirect($this->url->link('module/stowear&submit=true', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}
		
		// Usuwanie skórki
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if(isset($_POST['button-delete'])){
				if(is_writable(DIR_CATALOG . 'view/theme/stowear/skins')) {
					// Sprawdzanie czy istnieje skórka
					if(file_exists(DIR_CATALOG . 'view/theme/stowear/skins/store_'.$data['edit_skin_store'].'/') && filetype(DIR_CATALOG . 'view/theme/stowear/skins/store_'.$data['edit_skin_store'].'/') == 'dir' && file_exists(DIR_CATALOG . 'view/theme/stowear/skins/store_'.$data['edit_skin_store'].'/'.$data['active_skin_edit'].'')) {
						// Sprawdzanie czy skórka jest ustawiona jako aktywna
						if($data['active_skin_edit'] != $data['active_skin']) {
							removeDir(DIR_CATALOG . 'view/theme/stowear/skins/store_'.$data['edit_skin_store'].'/'.$data['active_skin_edit'].'');
							
							// Informacja o usunięciu skórki
							$this->session->data['success'] = $this->language->get('text_success');
							$this->response->redirect($this->url->link('module/stowear&submit=true', 'token=' . $this->session->data['token'], 'SSL'));
						}
					}
				} else {
					$this->session->data['error_warning'] = 'You need to set CHMOD 777 for all folder and subfolder in catalog/view/theme/stowear/skins!';
					$this->response->redirect($this->url->link('module/stowear&submit=true', 'token=' . $this->session->data['token'], 'SSL'));
				}
				
				// Jezeli nie usunięto szablonu to pojawia sie błąd
				$this->session->data['error_warning'] = $this->language->get('text_warning2');
				$this->response->redirect($this->url->link('module/stowear&submit=true', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}
		
		// Pobieranie ustawień szablonu
		if(file_exists(DIR_CATALOG . 'view/theme/stowear/skins/store_'.$data['edit_skin_store'].'/'.$data['active_skin_edit'].'/settings.json')) {
			$template = json_decode(file_get_contents(DIR_CATALOG . 'view/theme/stowear/skins/store_'.$data['edit_skin_store'].'/'.$data['active_skin_edit'].'/settings.json'), true);
			if(isset($template)) {
				foreach ($template as $option => $value) { 
					if($option != 'store_id') {
						$data[$option] = $value;
					}
				}
			}
		}
				
		// Pobieranie ustawień szablon --> custom code js
		if(file_exists(DIR_CATALOG . 'view/theme/stowear/skins/store_'.$data['edit_skin_store'].'/'.$data['active_skin_edit'].'/js/custom_code.js')) {
			$data['custom_code_js'] = file_get_contents(DIR_CATALOG . 'view/theme/stowear/skins/store_'.$data['edit_skin_store'].'/'.$data['active_skin_edit'].'/js/custom_code.js');
		}
		
		// Pobieranie ustawień szablon --> custom code css
		if(file_exists(DIR_CATALOG . 'view/theme/stowear/skins/store_'.$data['edit_skin_store'].'/'.$data['active_skin_edit'].'/css/custom_code.css')) {
			$data['custom_code_css'] = file_get_contents(DIR_CATALOG . 'view/theme/stowear/skins/store_'.$data['edit_skin_store'].'/'.$data['active_skin_edit'].'/css/custom_code.css');
		}
		
		$data['text_image_manager'] = 'Image manager';
		$data['token'] = $this->session->data['token'];		
		
		$text_strings = array('heading_title');
		
		foreach ($text_strings as $text) {
			$data[$text] = $this->language->get($text);
		}
		
		
		// Instalacja przykładowych danych
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {	
			// Custom Module
			if(isset($_POST['install_carousel_item'])){
				$language_id = 2;
				foreach($data['languages'] as $language) {
					if($language['language_id'] != 1) {
						$language_id = $language['language_id'];
					}
				}
				
				$output = array();
				$output["carousel_item_module"] = array (
				  1 => 
				  array (
				    'heading' => 
				    array (
				      $language_id => 'Testimonials',
				      1 => 'Testimonials',
				    ),
				    'items' => 
				    array (
				      1 => 
				      array (
				        $language_id => 
				        array (
				          'html' => '&lt;div class=&quot;testimonial&quot;&gt;
				 &lt;img src=&quot;image/catalog/avatar.png&quot; align=&quot;left&quot; alt=&quot;Avatar&quot;&gt;
				
				 &lt;div class=&quot;author&quot;&gt; 
				  &lt;span&gt;John Doe&lt;/span&gt;
				  &lt;p&gt;Themeforest comment&lt;/p&gt;
				 &lt;/div&gt;
				
				 &lt;div class=&quot;description&quot;&gt;
				   “ This template is great. If anyone is looking for a nice and quick to set
				up a template, should choose it. ”
				 &lt;/div&gt;
				&lt;/div&gt;',
				        ),
				        1 => 
				        array (
				          'html' => '&lt;div class=&quot;testimonial&quot;&gt;
				 &lt;img src=&quot;image/catalog/avatar.png&quot; align=&quot;left&quot; alt=&quot;Avatar&quot;&gt;
				
				 &lt;div class=&quot;author&quot;&gt; 
				  &lt;span&gt;John Doe&lt;/span&gt;
				  &lt;p&gt;Themeforest comment&lt;/p&gt;
				 &lt;/div&gt;
				
				 &lt;div class=&quot;description&quot;&gt;
				   “ This template is great. If anyone is looking for a nice and quick to set
				up a template, should choose it. ”
				 &lt;/div&gt;
				&lt;/div&gt;',
				        ),
				      ),
				      2 => 
				      array (
				        $language_id => 
				        array (
				          'html' => '&lt;div class=&quot;testimonial&quot;&gt;
				 &lt;img src=&quot;image/catalog/avatar.png&quot; align=&quot;left&quot; alt=&quot;Avatar&quot;&gt;
				
				 &lt;div class=&quot;author&quot;&gt; 
				  &lt;span&gt;John Doe&lt;/span&gt;
				  &lt;p&gt;Themeforest comment&lt;/p&gt;
				 &lt;/div&gt;
				
				 &lt;div class=&quot;description&quot;&gt;
				   “ This template is great. If anyone is looking for a nice and quick to set
				up a template, should choose it. ”
				 &lt;/div&gt;
				&lt;/div&gt;',
				        ),
				        1 => 
				        array (
				          'html' => '&lt;div class=&quot;testimonial&quot;&gt;
				 &lt;img src=&quot;image/catalog/avatar.png&quot; align=&quot;left&quot; alt=&quot;Avatar&quot;&gt;
				
				 &lt;div class=&quot;author&quot;&gt; 
				  &lt;span&gt;John Doe&lt;/span&gt;
				  &lt;p&gt;Themeforest comment&lt;/p&gt;
				 &lt;/div&gt;
				
				 &lt;div class=&quot;description&quot;&gt;
				   “ This template is great. If anyone is looking for a nice and quick to set
				up a template, should choose it. ”
				 &lt;/div&gt;
				&lt;/div&gt;',
				        ),
				      ),
				    ),
				    'layout_id' => '1',
				    'position' => 'content_bottom',
				    'status' => '1',
				    'sort_order' => '',
				  ),
				);
				
				$this->model_setting_setting->editSetting( "carousel_item", $output );	
				$this->session->data['success'] = $this->language->get('text_success');
				$this->response->redirect($this->url->link('module/stowear', 'token=' . $this->session->data['token'], 'SSL'));
			}
			
			// Custom Module
			if(isset($_POST['install_custom_module'])){
				$language_id = 2;
				foreach($data['languages'] as $language) {
					if($language['language_id'] != 1) {
						$language_id = $language['language_id'];
					}
				}
				
				$output = array();
				$output["custom_module_module"] = array (
				  1 => 
				  array (
				    'type' => '2',
				    'block_heading' => 
				    array (
				      $language_id => '',
				      1 => '',
				    ),
				    'block_content' => 
				    array (
				      $language_id => '',
				      1 => '',
				    ),
				    'html' => 
				    array (
				      $language_id => '&lt;div class=&quot;row banners hidden-xs&quot;&gt;
				   &lt;div class=&quot;col-sm-4&quot;&gt;&lt;a href=&quot;#&quot;&gt;&lt;img src=&quot;image/catalog/banner-01.png&quot; alt=&quot;Image&quot;&gt;&lt;/a&gt;&lt;/div&gt;
				   &lt;div class=&quot;col-sm-4&quot;&gt;&lt;a href=&quot;#&quot;&gt;&lt;img src=&quot;image/catalog/banner-02.png&quot; alt=&quot;Image&quot;&gt;&lt;/a&gt;&lt;/div&gt;
				   &lt;div class=&quot;col-sm-4&quot;&gt;&lt;a href=&quot;#&quot;&gt;&lt;img src=&quot;image/catalog/banner-03.png&quot; alt=&quot;Image&quot;&gt;&lt;/a&gt;&lt;/div&gt;
				&lt;/div&gt;',
				      1 => '&lt;div class=&quot;row banners hidden-xs&quot;&gt;
				   &lt;div class=&quot;col-sm-4&quot;&gt;&lt;a href=&quot;#&quot;&gt;&lt;img src=&quot;image/catalog/banner-01.png&quot; alt=&quot;Image&quot;&gt;&lt;/a&gt;&lt;/div&gt;
				   &lt;div class=&quot;col-sm-4&quot;&gt;&lt;a href=&quot;#&quot;&gt;&lt;img src=&quot;image/catalog/banner-02.png&quot; alt=&quot;Image&quot;&gt;&lt;/a&gt;&lt;/div&gt;
				   &lt;div class=&quot;col-sm-4&quot;&gt;&lt;a href=&quot;#&quot;&gt;&lt;img src=&quot;image/catalog/banner-03.png&quot; alt=&quot;Image&quot;&gt;&lt;/a&gt;&lt;/div&gt;
				&lt;/div&gt;',
				    ),
				    'layout_id' => '1',
				    'position' => 'preface_fullwidth',
				    'status' => '1',
				    'sort_order' => '0',
				  ),
				  2 => 
				  array (
				    'type' => '2',
				    'block_heading' => 
				    array (
				      $language_id => '',
				      1 => '',
				    ),
				    'block_content' => 
				    array (
				      $language_id => '',
				      1 => '',
				    ),
				    'html' => 
				    array (
				      $language_id => '&lt;div class=&quot;row banners hidden-xs&quot; style=&quot;padding-top: 15px&quot;&gt;
				   &lt;div class=&quot;col-sm-6&quot;&gt;&lt;a href=&quot;#&quot;&gt;&lt;img src=&quot;image/catalog/banner-04.png&quot; alt=&quot;Image&quot;&gt;&lt;/a&gt;&lt;/div&gt;
				   &lt;div class=&quot;col-sm-6&quot;&gt;&lt;a href=&quot;#&quot;&gt;&lt;img src=&quot;image/catalog/banner-05.png&quot; alt=&quot;Image&quot;&gt;&lt;/a&gt;&lt;/div&gt;
				&lt;/div&gt;',
				      1 => '&lt;div class=&quot;row banners hidden-xs&quot; style=&quot;padding-top: 15px&quot;&gt;
				   &lt;div class=&quot;col-sm-6&quot;&gt;&lt;a href=&quot;#&quot;&gt;&lt;img src=&quot;image/catalog/banner-04.png&quot; alt=&quot;Image&quot;&gt;&lt;/a&gt;&lt;/div&gt;
				   &lt;div class=&quot;col-sm-6&quot;&gt;&lt;a href=&quot;#&quot;&gt;&lt;img src=&quot;image/catalog/banner-05.png&quot; alt=&quot;Image&quot;&gt;&lt;/a&gt;&lt;/div&gt;
				&lt;/div&gt;',
				    ),
				    'layout_id' => '1',
				    'position' => 'preface_fullwidth',
				    'status' => '1',
				    'sort_order' => '4',
				  ),
				  3 => 
				  array (
				    'type' => '2',
				    'block_heading' => 
				    array (
				      $language_id => '',
				      1 => '',
				    ),
				    'block_content' => 
				    array (
				      $language_id => '',
				      1 => '',
				    ),
				    'html' => 
				    array (
				      $language_id => '&lt;div class=&quot;row banners hidden-xs&quot; style=&quot;margin-top: 20px&quot;&gt;
				   &lt;div class=&quot;col-sm-12&quot;&gt;&lt;a href=&quot;#&quot;&gt;&lt;img src=&quot;image/catalog/banner-category.png&quot; alt=&quot;Image&quot;&gt;&lt;/a&gt;&lt;/div&gt;
				&lt;/div&gt;',
				      1 => '&lt;div class=&quot;row banners hidden-xs&quot; style=&quot;margin-top: 20px&quot;&gt;
				   &lt;div class=&quot;col-sm-12&quot;&gt;&lt;a href=&quot;#&quot;&gt;&lt;img src=&quot;image/catalog/banner-category.png&quot; alt=&quot;Image&quot;&gt;&lt;/a&gt;&lt;/div&gt;
				&lt;/div&gt;',
				    ),
				    'layout_id' => '3',
				    'position' => 'column_left',
				    'status' => '1',
				    'sort_order' => '6',
				  ),
				);
				
				$this->model_setting_setting->editSetting( "custom_module", $output );	
				$this->session->data['success'] = $this->language->get('text_success');
				$this->response->redirect($this->url->link('module/stowear', 'token=' . $this->session->data['token'], 'SSL'));
			}
			
			// Camera slider
			if(isset($_POST['install_camera_slider'])){
				$output = array();
				$output["camera_slider_module"] = array (
				  0 => 
				  array (
				    'slider_id' => '1',
				    'layout_id' => '1',
				    'position' => 'slideshow',
				    'sort_order' => '',
				    'status' => '1',
				  ),
				); 
				$this->model_setting_setting->editSetting( "camera_slider", $output );	
				
				include '../data_sample/stowear/camera_slider_query.php'; 
				
				$this->session->data['success'] = $this->language->get('text_success');
				$this->response->redirect($this->url->link('module/stowear', 'token=' . $this->session->data['token'], 'SSL'));
			}
			
			// Megamenu
			if(isset($_POST['install_megamenu'])){
				$output = array();
				$output["megamenu_module"] = array (
				  0 => 
				  array (
				    'layout_id' => '99999',
				    'position' => 'menu',
				    'status' => '1',
				    'sort_order' => 0,
				    'orientation' => '0',
				    'search_bar' => 0,
				    'navigation_text' => 
				    array (
				      1 => '',
				    ),
				    'home_text' => 
				    array (
				      1 => '',
				    ),
				    'full_width' => '1',
				    'home_item' => 'icon',
				    'animation' => 'none',
				    'animation_time' => 350,
				    'status_cache' => 0,
				    'cache_time' => 1,
				  ),
				); 
				$this->model_setting_setting->editSetting( "megamenu", $output );	
						
				include '../data_sample/stowear/megamenu_query.php'; 
				
				$this->session->data['success'] = $this->language->get('text_success');
				$this->response->redirect($this->url->link('module/stowear', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}
		
		//This creates an error message. The error['warning'] variable is set by the call to function validate() in this controller (below)
		if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];
			unset($this->session->data['error_warning']);
 		} elseif(isset($this->error['warning'])) {
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

		$data['action'] = $this->url->link('module/stowear', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
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
			'text' => 'Stowear Theme Options',
			'href' => $this->url->link('module/stowear', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		// No image
		$this->load->model('tool/image');
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
				
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
				
		$this->response->setOutput($this->load->view('module/stowear.tpl', $data));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/stowear')) {
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