<?php
define('EXTENSION_NAME',            'Product Quick Edit Plus');
define('EXTENSION_VERSION',         '1.4.0');
define('EXTENSION_ID',              '15274');
define('EXTENSION_COMPATIBILITY',   'OpenCart 2.0.0.x, 2.0.1.x, 2.0.2.x and 2.0.3.x');
define('EXTENSION_STORE_URL',       'http://www.opencart.com/index.php?route=extension/extension/info&extension_id=' . EXTENSION_ID);
define('EXTENSION_PURCHASE_URL',    'http://www.opencart.com/index.php?route=extension/purchase&extension_id=' . EXTENSION_ID);
define('EXTENSION_SUPPORT_EMAIL',   'support@opencart.ee');
define('EXTENSION_SUPPORT_FORUM',   'http://forum.opencart.com/viewtopic.php?f=123&t=116963');
define('OTHER_EXTENSIONS',          'http://www.opencart.com/index.php?route=extension/extension&filter_username=bull5-i');

class ControllerModuleAdminQuickEdit extends Controller {
	protected $error = array();
	protected $alert = array(
		'error'     => array(),
		'warning'   => array(),
		'success'   => array(),
		'info'      => array()
	);

	private $defaults = array(
		'aqe_installed'                             => 1,
		'aqe_installed_version'                     => EXTENSION_VERSION,
		'aqe_status'                                => 0,
		'aqe_display_in_menu_as'                    => 0,
		'aqe_alternate_row_colour'                  => 0,
		'aqe_row_hover_highlighting'                => 0,
		'aqe_highlight_status'                      => 0,
		'aqe_highlight_filtered_columns'            => 0,
		'aqe_highlight_actions'                     => 0,
		'aqe_row_hover_highlighting'                => 0,
		'aqe_quick_edit_on'                         => 'click',
		'aqe_list_view_image_width'                 => 40,
		'aqe_list_view_image_height'                => 40,
		'aqe_filter_sub_category'                   => 0,
		'aqe_multilingual_seo'                      => 0,
		'aqe_items_per_page'                        => 25,
		'aqe_server_side_caching'                   => 0,
		'aqe_client_side_caching'                   => 1,
		'aqe_cache_size'                            => 1000,
		'aqe_debug_mode'                            => 0,
		'aqe_services'                              => "W10=",
	);

	private $column_defaults = array(
		'aqe_catalog_products'      => array(
			'selector'          => array('display' => 1, 'editable' => 0, 'index' =>   0, 'align' => 'text-center', 'type' =>           '', 'sort' => ''                , 'rel' => array()),
			'id'                => array('display' => 0, 'editable' => 0, 'index' =>   5, 'align' =>   'text-left', 'type' =>           '', 'sort' => 'p.product_id'    , 'rel' => array()),
			'image'             => array('display' => 1, 'editable' => 1, 'index' =>  10, 'align' => 'text-center', 'type' =>   'image_qe', 'sort' => ''                , 'rel' => array()),
			'category'          => array('display' => 0, 'editable' => 1, 'index' =>  20, 'align' =>   'text-left', 'type' =>     'cat_qe', 'sort' => ''                , 'rel' => array()),
			'manufacturer'      => array('display' => 0, 'editable' => 1, 'index' =>  30, 'align' =>   'text-left', 'type' => 'manufac_qe', 'sort' => 'm.name'          , 'rel' => array()),
			'name'              => array('display' => 1, 'editable' => 1, 'index' =>  40, 'align' =>   'text-left', 'type' =>    'name_qe', 'sort' => 'pd.name'         , 'rel' => array()),
			'tag'               => array('display' => 0, 'editable' => 1, 'index' =>  50, 'align' =>   'text-left', 'type' =>     'tag_qe', 'sort' => ''                , 'rel' => array()),
			'model'             => array('display' => 1, 'editable' => 1, 'index' =>  60, 'align' =>   'text-left', 'type' =>         'qe', 'sort' => 'p.model'         , 'rel' => array()),
			'price'             => array('display' => 1, 'editable' => 1, 'index' =>  70, 'align' =>  'text-right', 'type' =>   'price_qe', 'sort' => 'p.price'         , 'rel' => array()),
			'quantity'          => array('display' => 1, 'editable' => 1, 'index' =>  80, 'align' =>  'text-right', 'type' =>     'qty_qe', 'sort' => 'p.quantity'      , 'rel' => array()),
			'sku'               => array('display' => 0, 'editable' => 1, 'index' =>  90, 'align' =>   'text-left', 'type' =>         'qe', 'sort' => 'p.sku'           , 'rel' => array()),
			'upc'               => array('display' => 0, 'editable' => 1, 'index' => 100, 'align' =>   'text-left', 'type' =>         'qe', 'sort' => 'p.upc'           , 'rel' => array()),
			'ean'               => array('display' => 0, 'editable' => 1, 'index' => 110, 'align' =>   'text-left', 'type' =>         'qe', 'sort' => 'p.ean'           , 'rel' => array()),
			'jan'               => array('display' => 0, 'editable' => 1, 'index' => 120, 'align' =>   'text-left', 'type' =>         'qe', 'sort' => 'p.jan'           , 'rel' => array()),
			'isbn'              => array('display' => 0, 'editable' => 1, 'index' => 130, 'align' =>   'text-left', 'type' =>         'qe', 'sort' => 'p.isbn'          , 'rel' => array()),
			'mpn'               => array('display' => 0, 'editable' => 1, 'index' => 140, 'align' =>   'text-left', 'type' =>         'qe', 'sort' => 'p.mpn'           , 'rel' => array()),
			'location'          => array('display' => 0, 'editable' => 1, 'index' => 150, 'align' =>   'text-left', 'type' =>         'qe', 'sort' => 'p.location'      , 'rel' => array()),
			'seo'               => array('display' => 0, 'editable' => 1, 'index' => 160, 'align' =>   'text-left', 'type' =>     'seo_qe', 'sort' => 'seo'             , 'rel' => array()),
			'tax_class'         => array('display' => 0, 'editable' => 1, 'index' => 170, 'align' =>   'text-left', 'type' => 'tax_cls_qe', 'sort' => 'tc.title'        , 'rel' => array()),
			'minimum'           => array('display' => 0, 'editable' => 1, 'index' => 180, 'align' =>  'text-right', 'type' =>         'qe', 'sort' => 'p.minimum'       , 'rel' => array()),
			'subtract'          => array('display' => 0, 'editable' => 1, 'index' => 190, 'align' => 'text-center', 'type' =>  'yes_no_qe', 'sort' => 'p.subtract'      , 'rel' => array()),
			'stock_status'      => array('display' => 0, 'editable' => 1, 'index' => 200, 'align' =>   'text-left', 'type' =>   'stock_qe', 'sort' => 'ss.name'         , 'rel' => array()),
			'shipping'          => array('display' => 0, 'editable' => 1, 'index' => 210, 'align' => 'text-center', 'type' =>  'yes_no_qe', 'sort' => 'p.shipping'      , 'rel' => array()),
			'date_added'        => array('display' => 0, 'editable' => 1, 'index' => 215, 'align' =>   'text-left', 'type' =>'datetime_qe', 'sort' => 'p.date_added'    , 'rel' => array()),
			'date_available'    => array('display' => 0, 'editable' => 1, 'index' => 220, 'align' =>   'text-left', 'type' =>    'date_qe', 'sort' => 'p.date_available', 'rel' => array()),
			'date_modified'     => array('display' => 0, 'editable' => 0, 'index' => 230, 'align' =>   'text-left', 'type' =>'datetime_qe', 'sort' => 'p.date_modified' , 'rel' => array()),
			'length'            => array('display' => 0, 'editable' => 1, 'index' => 240, 'align' =>   'text-left', 'type' =>         'qe', 'sort' => 'p.length'        , 'rel' => array()),
			'width'             => array('display' => 0, 'editable' => 1, 'index' => 250, 'align' =>  'text-right', 'type' =>         'qe', 'sort' => 'p.width'         , 'rel' => array()),
			'height'            => array('display' => 0, 'editable' => 1, 'index' => 260, 'align' =>  'text-right', 'type' =>         'qe', 'sort' => 'p.height'        , 'rel' => array()),
			'weight'            => array('display' => 0, 'editable' => 1, 'index' => 270, 'align' =>  'text-right', 'type' =>         'qe', 'sort' => 'p.weight'        , 'rel' => array()),
			'length_class'      => array('display' => 0, 'editable' => 1, 'index' => 280, 'align' =>   'text-left', 'type' =>  'length_qe', 'sort' => 'lc.title'        , 'rel' => array()),
			'weight_class'      => array('display' => 0, 'editable' => 1, 'index' => 290, 'align' =>   'text-left', 'type' =>  'weight_qe', 'sort' => 'wc.title'        , 'rel' => array()),
			'points'            => array('display' => 0, 'editable' => 1, 'index' => 300, 'align' =>  'text-right', 'type' =>         'qe', 'sort' => 'p.points'        , 'rel' => array()),
			'filter'            => array('display' => 0, 'editable' => 1, 'index' => 310, 'align' =>   'text-left', 'type' =>  'filter_qe', 'sort' => ''                , 'rel' => array('filters')),
			'download'          => array('display' => 0, 'editable' => 1, 'index' => 320, 'align' =>   'text-left', 'type' =>      'dl_qe', 'sort' => ''                , 'rel' => array()),
			'store'             => array('display' => 0, 'editable' => 1, 'index' => 330, 'align' =>   'text-left', 'type' =>   'store_qe', 'sort' => ''                , 'rel' => array()),
			'sort_order'        => array('display' => 1, 'editable' => 1, 'index' => 340, 'align' =>  'text-right', 'type' =>         'qe', 'sort' => 'p.sort_order'    , 'rel' => array()),
			'status'            => array('display' => 1, 'editable' => 1, 'index' => 350, 'align' => 'text-center', 'type' =>  'status_qe', 'sort' => 'p.status'        , 'rel' => array()),
			'viewed'            => array('display' => 0, 'editable' => 1, 'index' => 360, 'align' =>  'text-right', 'type' =>         'qe', 'sort' => 'p.viewed'        , 'rel' => array()),
			'view_in_store'     => array('display' => 0, 'editable' => 0, 'index' => 370, 'align' =>   'text-left', 'type' =>           '', 'sort' => ''                , 'rel' => array()),
			'action'            => array('display' => 1, 'editable' => 0, 'index' => 380, 'align' =>  'text-right', 'type' =>           '', 'sort' => ''                , 'rel' => array()),
		),
		'aqe_catalog_products_actions' => array(
			'attributes'        => array('display' => 0, 'index' =>  0, 'short' => 'attr',  'type' =>    'attr_qe', 'class' =>            '', 'rel' => array()),
			'discounts'         => array('display' => 0, 'index' =>  1, 'short' => 'dscnt', 'type' =>   'dscnt_qe', 'class' =>            '', 'rel' => array()),
			'images'            => array('display' => 0, 'index' =>  2, 'short' => 'img',   'type' =>  'images_qe', 'class' =>            '', 'rel' => array()),
			'filters'           => array('display' => 0, 'index' =>  3, 'short' => 'fltr',  'type' => 'filters_qe', 'class' =>            '', 'rel' => array('filter')),
			'options'           => array('display' => 0, 'index' =>  4, 'short' => 'opts',  'type' =>  'option_qe', 'class' =>            '', 'rel' => array()),
			'recurrings'        => array('display' => 0, 'index' =>  5, 'short' => 'rec',   'type' =>   'recur_qe', 'class' =>            '', 'rel' => array()),
			'related'           => array('display' => 0, 'index' =>  6, 'short' => 'rel',   'type' => 'related_qe', 'class' =>            '', 'rel' => array()),
			'specials'          => array('display' => 0, 'index' =>  7, 'short' => 'spcl',  'type' => 'special_qe', 'class' =>            '', 'rel' => array('price')),
			'descriptions'      => array('display' => 0, 'index' =>  8, 'short' => 'desc',  'type' =>   'descr_qe', 'class' =>            '', 'rel' => array()),
			'view'              => array('display' => 1, 'index' =>  9, 'short' => 'vw',    'type' =>       'view', 'class' =>            '', 'rel' => array()),
			'edit'              => array('display' => 1, 'index' => 10, 'short' => 'ed',    'type' =>       'edit', 'class' => 'btn-primary', 'rel' => array()),
		)
	);

	private static $language_texts = array(
		// Texts
		'text_enabled', 'text_disabled', 'text_toggle_navigation', 'text_more_options', 'text_legal_notice', 'text_license', 'text_extension_information',
		'text_terms', 'text_license_text', 'text_support_subject', 'text_faq', 'text_saving', 'text_upgrading', 'text_loading', 'text_canceling',
		'text_catalog_products', 'text_catalog_products_qe', 'text_replace_old', 'text_add_as_new',
		// Tabs
		'tab_settings', 'tab_support', 'tab_about', 'tab_general', 'tab_faq', 'tab_services', 'tab_changelog', 'tab_extension',
		// Buttons
		'button_save', 'button_apply', 'button_cancel', 'button_close', 'button_upgrade', 'button_refresh',
		// Entries
		'entry_extension_status', 'entry_installed_version', 'entry_extension_name', 'entry_extension_compatibility', 'entry_extension_store_url',
		'entry_copyright_notice', 'entry_display_in_menu_as',
		// Errors
		'error_ajax_request'
	);

	public function __construct($registry) {
		parent::__construct($registry);
		$this->load->helper('aqe');

		$this->load->language('module/admin_quick_edit');
	}

	public function index() {
		$this->document->addStyle('view/stylesheet/aqe/css/module.min.css');

		$this->document->addScript('view/javascript/aqe/module.min.js');

		$this->document->setTitle($this->language->get('extension_name'));

		$this->load->model('setting/setting');

		$ajax_request = isset($this->request->server['HTTP_X_REQUESTED_WITH']) && !empty($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

		if ($this->request->server['REQUEST_METHOD'] == 'POST' && !$ajax_request && $this->validateForm($this->request->post)) {
			$original_settings = $this->model_setting_setting->getSetting('aqe');

			foreach ($this->defaults as $setting => $default) {
				$value = $this->config->get($setting);
				if ($value === null) {
					$original_settings[$setting] = $default;
				}
			}

			foreach ($this->column_defaults as $page => $columns) {
				$page_conf = $this->config->get($page);

				if ($page_conf === null) {
					$page_conf = $value;
				}

				foreach ($columns as $column => $attributes) {
					if (!isset($page_conf[$column])) {
						$page_conf[$column] = $attributes;
					} else {
						foreach ($attributes as $key => $value) {
							if (!isset($page_conf[$column][$key])) {
								$page_conf[$column][$key] = $value;
							} else {
								switch ($key) {
									case 'display':
									case 'index':
										break;
									default:
										$page_conf[$column][$key] = $value;
										break;
								}
							}
						}

						foreach (array_diff(array_keys($page_conf[$column]), array_keys($columns[$column])) as $key) {
							unset($page_conf[$column]);
						}
					}
				}

				foreach (array_diff(array_keys($page_conf), array_keys($columns)) as $key) {
					unset($page_conf[$key]);
				}

				$this->request->post[$page] = $page_conf;
			}

			$settings = array_merge($original_settings, $this->request->post);
			$settings['aqe_installed_version'] = $original_settings['aqe_installed_version'];

			$this->model_setting_setting->editSetting('aqe', $settings);

			$this->session->data['success'] = $this->language->get('text_success_update');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		} else if ($this->request->server['REQUEST_METHOD'] == 'POST' && $ajax_request) {
			$response = array();

			if ($this->validateForm($this->request->post)) {
				$original_settings = $this->model_setting_setting->getSetting('aqe');

				foreach ($this->defaults as $setting => $default) {
					$value = $this->config->get($setting);
					if ($value === null) {
						$original_settings[$setting] = $default;
					}
				}

				foreach ($this->column_defaults as $page => $columns) {
					$page_conf = $this->config->get($page);

					if ($page_conf === null) {
						$page_conf = $value;
					}

					foreach ($columns as $column => $attributes) {
						if (!isset($page_conf[$column])) {
							$page_conf[$column] = $attributes;
						} else {
							foreach ($attributes as $key => $value) {
								if (!isset($page_conf[$column][$key])) {
									$page_conf[$column][$key] = $value;
								} else {
									switch ($key) {
										case 'display':
										case 'index':
											break;
										default:
											$page_conf[$column][$key] = $value;
											break;
									}
								}
							}

							foreach (array_diff(array_keys($page_conf[$column]), array_keys($columns[$column])) as $key) {
								unset($page_conf[$column][$key]);
							}
						}
					}

					foreach (array_diff(array_keys($page_conf), array_keys($columns)) as $key) {
						unset($page_conf[$key]);
					}

					$this->request->post[$page] = $page_conf;
				}

				$settings = array_merge($original_settings, $this->request->post);
				$settings['aqe_installed_version'] = $original_settings['aqe_installed_version'];

				if ((int)$original_settings['aqe_status'] != (int)$this->request->post['aqe_status'] || (int)$original_settings['aqe_display_in_menu_as'] != (int)$this->request->post['aqe_display_in_menu_as']) {
					$response['reload'] = true;
					$this->session->data['success'] = $this->language->get('text_success_update');
				}

				$this->model_setting_setting->editSetting('aqe', $settings);

				$this->alert['success']['updated'] = $this->language->get('text_success_update');
			} else {
				if (!$this->checkVersion()) {
					$response['reload'] = true;
				}
			}

			$response = array_merge($response, array("errors" => $this->error), array("alerts" => $this->alert));

			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_enc($response, JSON_UNESCAPED_SLASHES));
			return;
		}

		$this->checkPrerequisites();

		$this->checkVersion();

		$data['heading_title'] = $this->language->get('extension_name');
		$data['text_other_extensions'] = sprintf($this->language->get('text_other_extensions'), OTHER_EXTENSIONS);

		foreach (self::$language_texts as $text) {
			$data[$text] = $this->language->get($text);
		}

		$data['ext_name'] = EXTENSION_NAME;
		$data['ext_version'] = EXTENSION_VERSION;
		$data['ext_id'] = EXTENSION_ID;
		$data['ext_compatibility'] = EXTENSION_COMPATIBILITY;
		$data['ext_store_url'] = EXTENSION_STORE_URL;
		$data['ext_purchase_url'] = EXTENSION_PURCHASE_URL;
		$data['ext_support_email'] = EXTENSION_SUPPORT_EMAIL;
		$data['ext_support_forum'] = EXTENSION_SUPPORT_FORUM;
		$data['other_extensions_url'] = OTHER_EXTENSIONS;

		$data['alert_icon'] = function($type) {
			$icon = "";
			switch ($type) {
				case 'error':
					$icon = "fa-times-circle";
					break;
				case 'warning':
					$icon = "fa-exclamation-triangle";
					break;
				case 'success':
					$icon = "fa-check-circle";
					break;
				case 'info':
					$icon = "fa-info-circle";
					break;
				default:
					break;
			}
			return $icon;
		};

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
			'active'    => false
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
			'active'    => false
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('extension_name'),
			'href'      => $this->url->link('module/admin_quick_edit', 'token=' . $this->session->data['token'], 'SSL'),
			'active'    => true
		);

		$data['save'] = $this->url->link('module/admin_quick_edit', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		$data['upgrade'] = $this->url->link('module/admin_quick_edit/upgrade', 'token=' . $this->session->data['token'], 'SSL');
		$data['extension_installer'] = $this->url->link('extension/installer', 'token=' . $this->session->data['token'], 'SSL');
		$data['services'] = html_entity_decode($this->url->link('module/admin_quick_edit/services', 'token=' . $this->session->data['token'], 'SSL'));

		$data['update_pending'] = !$this->checkVersion();

		if (!$data['update_pending']) {
			$this->updateEventHooks();
		}

		$data['ssl'] = (
				(int)$this->config->get('config_secure') ||
				isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ||
				!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ||
				!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on'
			) ? 's' : '';

		# Loop through all settings for the post/config values
		foreach (array_keys($this->defaults) as $setting) {
			if (isset($this->request->post[$setting])) {
				$data[$setting] = $this->request->post[$setting];
			} else {
				$data[$setting] = $this->config->get($setting);
				if ($data[$setting] === null) {
					if (!isset($this->alert['warning']['unsaved']) && $this->checkVersion())  {
						$this->alert['warning']['unsaved'] = $this->language->get('error_unsaved_settings');
					}
					if (isset($this->defaults[$setting])) {
						$data[$setting] = $this->defaults[$setting];
					}
				}
			}
		}

		// Check for multilingual SEO keywords
		$column = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "url_alias` LIKE '%language_id'");
		if ($column->num_rows && isset($column->row['Field'])) {
			$multilingual_seo = $column->row['Field'];
		} else {
			$multilingual_seo = false;
		}

		if ($data['aqe_multilingual_seo'] != $multilingual_seo) {
			$data['aqe_multilingual_seo'] = $multilingual_seo;
			if (!isset($this->alert['warning']['unsaved']) && $this->checkVersion())  {
				$this->alert['warning']['unsaved'] = $this->language->get('error_unsaved_settings');
			}
		}

		$data['installed_version'] = $this->installedVersion();

		foreach ($this->column_defaults as $page => $columns) {
			$conf = $this->config->get($page);
			if (!is_array($conf)) {
				if (!isset($this->alert['warning']['unsaved']) && $this->checkVersion())  {
					$this->alert['warning']['unsaved'] = $this->language->get('error_unsaved_settings');
				}
				$conf = $columns;
			}

			foreach ($columns as $column => $attributes) {
				if (!isset($conf[$column])) {
					if (!isset($this->alert['warning']['unsaved']) && $this->checkVersion())  {
						$this->alert['warning']['unsaved'] = $this->language->get('error_unsaved_settings');
					}
					$conf[$column] = $attributes;
				}

				foreach ($attributes as $key => $value) {
					if (!isset($conf[$column][$key])) {
						if (!isset($this->alert['warning']['unsaved']) && $this->checkVersion())  {
							$this->alert['warning']['unsaved'] = $this->language->get('error_unsaved_settings');
						}
						$conf[$column][$key] = $value;
					}
					switch ($key) {
						case 'display':
						case 'index':
							break;
						default:
							if ($conf[$column][$key] != $value) {
								if (!isset($this->alert['warning']['unsaved']) && $this->checkVersion())  {
									$this->alert['warning']['unsaved'] = $this->language->get('error_unsaved_settings');
								}
							}
							break;
					}
				}

				if (array_diff(array_keys($conf[$column]), array_keys($columns[$column])) && !isset($this->alert['warning']['unsaved']) && $this->checkVersion()) {
					$this->alert['warning']['unsaved'] = $this->language->get('error_unsaved_settings');
				}
			}

			if (array_diff(array_keys($conf), array_keys($columns)) && !isset($this->alert['warning']['unsaved']) && $this->checkVersion()) {
				$this->alert['warning']['unsaved'] = $this->language->get('error_unsaved_settings');
			}
		}

		if (isset($this->session->data['error'])) {
			$this->error = $this->session->data['error'];

			unset($this->session->data['error']);
		}

		if (isset($this->error['warning'])) {
			$this->alert['warning']['warning'] = $this->error['warning'];
		}

		if (isset($this->error['error'])) {
			$this->alert['error']['error'] = $this->error['error'];
		}

		if (isset($this->session->data['success'])) {
			$this->alert['success']['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		}

		$data['errors'] = $this->error;

		$data['token'] = $this->session->data['token'];

		$data['alerts'] = $this->alert;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/admin_quick_edit.tpl', $data));
	}

	public function install() {
		$this->registerEventHooks();

		$this->load->model('setting/setting');
		$this->model_setting_setting->editSetting('aqe', array_merge($this->defaults, $this->column_defaults));
	}

	public function uninstall() {
		$this->removeEventHooks();

		$this->load->model('setting/setting');
		$this->model_setting_setting->deleteSetting('aqe');
	}

	public function upgrade() {
		$ajax_request = isset($this->request->server['HTTP_X_REQUESTED_WITH']) && !empty($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

		$response = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateUpgrade()) {
			$this->load->model('setting/setting');

			$settings = array();

			// Go over all settings, add new values and remove old ones
			foreach ($this->defaults as $setting => $default) {
				$value = $this->config->get($setting);
				if ($value === null) {
					$settings[$setting] = $default;
				} else {
					$settings[$setting] = $value;
				}
			}

			foreach ($this->column_defaults as $page => $columns) {
				$setting = array();

				$conf = $this->config->get($page);

				if ($conf === null || !is_array($conf)) {
					$conf = $columns;
				}

				foreach ($columns as $column => $values) {
					$setting[$column] = array();

					foreach ($values as $key => $value) {
						if (!isset($conf[$column][$key])) {
							$setting[$column][$key] = $value;
						} else {
							$setting[$column][$key] = $conf[$column][$key];
						}
					}
				}

				$settings[$page] = $setting;
			}

			$settings['aqe_installed_version'] = EXTENSION_VERSION;

			$this->model_setting_setting->editSetting('aqe', $settings);

			$this->updateEventHooks();

			$this->session->data['success'] = sprintf($this->language->get('text_success_upgrade'), EXTENSION_VERSION);
			$this->alert['success']['upgrade'] = sprintf($this->language->get('text_success_upgrade'), EXTENSION_VERSION);

			$response['success'] = true;
			$response['reload'] = true;
		}

		$response = array_merge($response, array("errors" => $this->error), array("alerts" => $this->alert));

		if (!$ajax_request) {
			$this->session->data['errors'] = $this->error;
			$this->session->data['alerts'] = $this->alert;
			$this->response->redirect($this->url->link('module/admin_quick_edit', 'token=' . $this->session->data['token'], 'SSL'));
		} else {
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_enc($response, JSON_UNESCAPED_SLASHES));
			return;
		}
	}

	public function services() {
		$services = base64_decode($this->config->get('aqe_services'));
		$response = json_decode($services, true);
		$force = isset($this->request->get['force']) && (int)$this->request->get['force'];

		if ($response && isset($response['expires']) && $response['expires'] >= strtotime("now") && !$force) {
			$response['cached'] = true;
		} else {
			$url = "http://www.opencart.ee/services/?eid=" . EXTENSION_ID . "&info=true&general=true";
			$hostname = (!empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : '' ;

			if (function_exists('curl_init')) {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
				curl_setopt($ch, CURLOPT_TIMEOUT, 60);
				curl_setopt($ch, CURLOPT_USERAGENT, base64_encode("curl " . EXTENSION_ID));
				curl_setopt($ch, CURLOPT_REFERER, $hostname);
				$json = curl_exec($ch);
			} else {
				$json = false;
			}

			if ($json !== false) {
				$this->load->model('setting/setting');
				$settings = $this->model_setting_setting->getSetting('aqe');
				$settings['aqe_services'] = base64_encode($json);
				$this->model_setting_setting->editSetting('aqe', $settings);
				$response = json_decode($json, true);
			} else {
				$response = array();
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_enc($response, JSON_UNESCAPED_SLASHES));
	}

	private function registerEventHooks() {
		if (version_compare(VERSION, '2.0.1', '<')) {
			$this->load->model('tool/event');
			$this->model_tool_event->addEvent('pqe.product.add',                'post.admin.add.product',          'catalog/product_ext/clear_products_cache');
			$this->model_tool_event->addEvent('pqe.product.delete',             'post.admin.delete.product',       'catalog/product_ext/clear_products_cache');
			$this->model_tool_event->addEvent('pqe.manufacturer.add',           'post.admin.add.manufacturer',     'catalog/product_ext/clear_manufacturers_cache');
			$this->model_tool_event->addEvent('pqe.manufacturer.edit',          'post.admin.edit.manufacturer',    'catalog/product_ext/clear_manufacturers_cache');
			$this->model_tool_event->addEvent('pqe.manufacturer.delete',        'post.admin.delete.manufacturer',  'catalog/product_ext/clear_manufacturers_cache');
			$this->model_tool_event->addEvent('pqe.download.add',               'post.admin.add.download',         'catalog/product_ext/clear_downloads_cache');
			$this->model_tool_event->addEvent('pqe.download.edit',              'post.admin.edit.download',        'catalog/product_ext/clear_downloads_cache');
			$this->model_tool_event->addEvent('pqe.download.delete',            'post.admin.delete.download',      'catalog/product_ext/clear_downloads_cache');
			$this->model_tool_event->addEvent('pqe.filter.add',                 'post.admin.add.filter',           'catalog/product_ext/clear_filters_cache');
			$this->model_tool_event->addEvent('pqe.filter.edit',                'post.admin.edit.filter',          'catalog/product_ext/clear_filters_cache');
			$this->model_tool_event->addEvent('pqe.filter.delete',              'post.admin.delete.filter',        'catalog/product_ext/clear_filters_cache');
		} else {
			$this->load->model('extension/event');
			$this->model_extension_event->addEvent('pqe.product.add',           'post.admin.product.add',          'catalog/product_ext/clear_products_cache');
			$this->model_extension_event->addEvent('pqe.product.delete',        'post.admin.product.delete',       'catalog/product_ext/clear_products_cache');
			$this->model_extension_event->addEvent('pqe.manufacturer.add',      'post.admin.manufacturer.add',     'catalog/product_ext/clear_manufacturers_cache');
			$this->model_extension_event->addEvent('pqe.manufacturer.edit',     'post.admin.manufacturer.edit',    'catalog/product_ext/clear_manufacturers_cache');
			$this->model_extension_event->addEvent('pqe.manufacturer.delete',   'post.admin.manufacturer.delete',  'catalog/product_ext/clear_manufacturers_cache');
			$this->model_extension_event->addEvent('pqe.download.add',          'post.admin.download.add',         'catalog/product_ext/clear_downloads_cache');
			$this->model_extension_event->addEvent('pqe.download.edit',         'post.admin.download.edit',        'catalog/product_ext/clear_downloads_cache');
			$this->model_extension_event->addEvent('pqe.download.delete',       'post.admin.download.delete',      'catalog/product_ext/clear_downloads_cache');
			$this->model_extension_event->addEvent('pqe.filter.add',            'post.admin.filter.add',           'catalog/product_ext/clear_filters_cache');
			$this->model_extension_event->addEvent('pqe.filter.edit',           'post.admin.filter.edit',          'catalog/product_ext/clear_filters_cache');
			$this->model_extension_event->addEvent('pqe.filter.delete',         'post.admin.filter.delete',        'catalog/product_ext/clear_filters_cache');
		}
	}

	private function removeEventHooks() {
		if (version_compare(VERSION, '2.0.1', '<')) {
			$this->load->model('tool/event');
			$this->model_tool_event->deleteEvent('pqe.product.add');
			$this->model_tool_event->deleteEvent('pqe.product.delete');
			$this->model_tool_event->deleteEvent('pqe.manufacturer.add');
			$this->model_tool_event->deleteEvent('pqe.manufacturer.edit');
			$this->model_tool_event->deleteEvent('pqe.manufacturer.delete');
			$this->model_tool_event->deleteEvent('pqe.download.add');
			$this->model_tool_event->deleteEvent('pqe.download.edit');
			$this->model_tool_event->deleteEvent('pqe.download.delete');
			$this->model_tool_event->deleteEvent('pqe.filter.add');
			$this->model_tool_event->deleteEvent('pqe.filter.edit');
			$this->model_tool_event->deleteEvent('pqe.filter.delete');
		} else {
			$this->load->model('extension/event');
			$this->model_extension_event->deleteEvent('pqe.product.add');
			$this->model_extension_event->deleteEvent('pqe.product.delete');
			$this->model_extension_event->deleteEvent('pqe.manufacturer.add');
			$this->model_extension_event->deleteEvent('pqe.manufacturer.edit');
			$this->model_extension_event->deleteEvent('pqe.manufacturer.delete');
			$this->model_extension_event->deleteEvent('pqe.download.add');
			$this->model_extension_event->deleteEvent('pqe.download.edit');
			$this->model_extension_event->deleteEvent('pqe.download.delete');
			$this->model_extension_event->deleteEvent('pqe.filter.add');
			$this->model_extension_event->deleteEvent('pqe.filter.edit');
			$this->model_extension_event->deleteEvent('pqe.filter.delete');
		}
	}

	private function updateEventHooks() {
		if (version_compare(VERSION, '2.0.1', '<')) {
			$this->load->model('tool/event');
		} else {
			$this->load->model('extension/event');
		}

		if (version_compare(VERSION, '2.0.1', '<')) {
			$event_hooks = array(
				'pqe.product.add'           => array('trigger' => 'post.admin.add.product',           'action' => 'catalog/product_ext/clear_products_cache'),
				'pqe.product.delete'        => array('trigger' => 'post.admin.delete.product',        'action' => 'catalog/product_ext/clear_products_cache'),
				'pqe.manufacturer.add'      => array('trigger' => 'post.admin.add.manufacturer',      'action' => 'catalog/product_ext/clear_manufacturers_cache'),
				'pqe.manufacturer.edit'     => array('trigger' => 'post.admin.edit.manufacturer',     'action' => 'catalog/product_ext/clear_manufacturers_cache'),
				'pqe.manufacturer.delete'   => array('trigger' => 'post.admin.delete.manufacturer',   'action' => 'catalog/product_ext/clear_manufacturers_cache'),
				'pqe.download.add'          => array('trigger' => 'post.admin.add.download',          'action' => 'catalog/product_ext/clear_downloads_cache'),
				'pqe.download.edit'         => array('trigger' => 'post.admin.edit.download',         'action' => 'catalog/product_ext/clear_downloads_cache'),
				'pqe.download.delete'       => array('trigger' => 'post.admin.delete.download',       'action' => 'catalog/product_ext/clear_downloads_cache'),
				'pqe.filter.add'            => array('trigger' => 'post.admin.add.filter',            'action' => 'catalog/product_ext/clear_filters_cache'),
				'pqe.filter.edit'           => array('trigger' => 'post.admin.edit.filter',           'action' => 'catalog/product_ext/clear_filters_cache'),
				'pqe.filter.delete'         => array('trigger' => 'post.admin.delete.filter',         'action' => 'catalog/product_ext/clear_filters_cache'),
			);
		} else {
			$event_hooks = array(
				'pqe.product.add'           => array('trigger' => 'post.admin.product.add',           'action' => 'catalog/product_ext/clear_products_cache'),
				'pqe.product.delete'        => array('trigger' => 'post.admin.product.delete',        'action' => 'catalog/product_ext/clear_products_cache'),
				'pqe.manufacturer.add'      => array('trigger' => 'post.admin.manufacturer.add',      'action' => 'catalog/product_ext/clear_manufacturers_cache'),
				'pqe.manufacturer.edit'     => array('trigger' => 'post.admin.manufacturer.edit',     'action' => 'catalog/product_ext/clear_manufacturers_cache'),
				'pqe.manufacturer.delete'   => array('trigger' => 'post.admin.manufacturer.delete',   'action' => 'catalog/product_ext/clear_manufacturers_cache'),
				'pqe.download.add'          => array('trigger' => 'post.admin.download.add',          'action' => 'catalog/product_ext/clear_downloads_cache'),
				'pqe.download.edit'         => array('trigger' => 'post.admin.download.edit',         'action' => 'catalog/product_ext/clear_downloads_cache'),
				'pqe.download.delete'       => array('trigger' => 'post.admin.download.delete',       'action' => 'catalog/product_ext/clear_downloads_cache'),
				'pqe.filter.add'            => array('trigger' => 'post.admin.filter.add',            'action' => 'catalog/product_ext/clear_filters_cache'),
				'pqe.filter.edit'           => array('trigger' => 'post.admin.filter.edit',           'action' => 'catalog/product_ext/clear_filters_cache'),
				'pqe.filter.delete'         => array('trigger' => 'post.admin.filter.delete',         'action' => 'catalog/product_ext/clear_filters_cache'),
			);
		}

		foreach ($event_hooks as $code => $hook) {
			if (version_compare(VERSION, '2.0.1', '<')) {
				$event = $this->model_tool_event->getEvent($code);
			} else {
				$event = $this->model_extension_event->getEvent($code);
			}

			if (!$event || $event['trigger'] != $hook['trigger'] || $event['action'] != $hook['action']) {
				if (version_compare(VERSION, '2.0.1', '<')) {
					$this->model_tool_event->addEvent($code, $hook['trigger'], $hook['action']);
				} else {
					$this->model_extension_event->addEvent($code, $hook['trigger'], $hook['action']);
				}

				if (empty($this->alert['success']['hooks_updated'])) {
					$this->alert['success']['hooks_updated'] = $this->language->get('text_success_hooks_update');
				}
			}
		}

		// Delete old triggers
		$query = $this->db->query("SELECT `code` FROM " . DB_PREFIX . "event WHERE `code` LIKE 'pqe.%'");
		$events = array_keys($event_hooks);

		foreach ($query->rows as $row) {
			if (!in_array($row['code'], $events)) {
				if (version_compare(VERSION, '2.0.1', '<')) {
					$this->model_tool_event->deleteEvent($row['code']);
				} else {
					$this->model_extension_event->deleteEvent($row['code']);
				}

				if (empty($this->alert['success']['hooks_updated'])) {
					$this->alert['success']['hooks_updated'] = $this->language->get('text_success_hooks_update');
				}
			}
		}
	}

	private function checkPrerequisites() {
		$errors = false;

		if (!class_exists('VQMod')) {
			$errors = true;
			$this->alert['error']['vqmod'] = $this->language->get('error_vqmod');
		}

		return !$errors;
	}

	private function checkVersion() {
		$errors = false;

		$installed_version = $this->installedVersion();

		if ($installed_version != EXTENSION_VERSION) {
			$errors = true;
			$this->alert['info']['version'] = sprintf($this->language->get('error_version'), EXTENSION_VERSION);
		}

		return !$errors;
	}

	private function validate() {
		$errors = false;

		if (!$this->user->hasPermission('modify', 'module/admin_quick_edit')) {
			$errors = true;
			$this->alert['error']['permission'] = $this->language->get('error_permission');
		}

		if (!$errors) {
			return $this->checkVersion() && $this->checkPrerequisites();
		} else {
			return false;
		}
	}

	private function validateForm(&$data) {
		$errors = false;

		if ($errors) {
			$errors = true;
			$this->alert['warning']['warning'] = $this->language->get('error_warning');
		}

		if (!$errors) {
			return $this->validate();
		} else {
			return false;
		}
	}

	private function validateUpgrade() {
		$errors = false;

		if (!$this->user->hasPermission('modify', 'module/admin_quick_edit')) {
			$errors = true;
			$this->alert['error']['permission'] = $this->language->get('error_permission');
		}

		return !$errors;
	}

	private function installedVersion() {
		$installed_version = $this->config->get('aqe_installed_version');
		return $installed_version ? $installed_version : '1.0.0';
	}
}
