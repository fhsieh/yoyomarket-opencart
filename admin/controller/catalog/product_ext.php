<?php
class ControllerCatalogProductExt extends Controller {
	protected $start_time = 0;
	protected $error = array();
	protected $alert = array(
		'error'     => array(),
		'warning'   => array(),
		'success'   => array(),
		'info'      => array()
	);

	private static $language_texts = array(
		'heading_title', 'code',
		// Texts
		'text_list', 'text_settings', 'text_search', 'text_items_per_page', 'text_choose_columns', 'text_other_settings', 'text_all', 'text_custom',
		'text_yes', 'text_no', 'text_single_click', 'text_double_click', 'text_toggle_navigation', 'text_toggle_dropdown', 'text_action', 'text_column',
		'text_display', 'text_editable', 'text_confirm_delete', 'text_are_you_sure', 'text_enabled', 'text_disabled', 'text_filter', 'text_clear_filter',
		'text_clear_search', 'text_none', 'text_special_off', 'text_special_active', 'text_special_expired', 'text_special_future', 'text_sort_ascending',
		'text_sort_descending', 'text_first_page', 'text_previous_page', 'text_next_page', 'text_last_page', 'text_empty_table', 'text_showing_info',
		'text_showing_info_empty', 'text_showing_info_filtered', 'text_loading_records', 'text_no_matching_records', 'text_autocomplete', 'text_select',
		'text_searching', 'text_loading_more_results', 'text_no_matches_found', 'text_input_too_short', 'text_input_too_long', 'text_selection_too_big',
		'text_image_manager', 'text_browse', 'text_clear', 'text_batch_edit', 'text_clear_cache', 'text_saving', 'text_copying', 'text_deleting',
		'text_no_records_found', 'text_request_handled',
		// Buttons
		'button_add', 'button_copy', 'button_cancel', 'button_delete', 'button_save', 'button_close', 'button_accept',
		// Help texts
		'help_items_per_page', 'help_row_hover_highlighting', 'help_highlight_status', 'help_highlight_filtered_columns', 'help_highlight_actions',
		'help_filter_sub_category', 'help_server_side_caching', 'help_client_side_caching', 'help_client_side_cache_size',
		// Entries
		'entry_products_per_page', 'entry_alternate_row_colour', 'entry_row_hover_highlighting', 'entry_highlight_status',
		'entry_highlight_filtered_columns', 'entry_highlight_actions', 'entry_quick_edit_on', 'entry_list_view_image_size', 'entry_filter_sub_category',
		'entry_server_side_caching', 'entry_client_side_caching', 'entry_client_side_cache_size', 'entry_debug_mode', 'entry_columns', 'entry_actions',
		// Errors
		'error_ajax_request', 'error_items_per_page', 'error_image_width', 'error_image_height', 'error_cache_size',
 	);

	public function __construct($registry) {
		parent::__construct($registry);
		global $execution_start_time;
		$this->start_time = $execution_start_time;
		$this->load->helper('aqe');

		if (!$this->config->get('aqe_installed') || !$this->config->get('aqe_status')) {
			$url = $this->urlParams();
			$this->response->redirect($this->url->link('catalog/product', $url, 'SSL'));
		}
	}

	public function index() {
		$this->getBase();
	}

	public function delete() {
		$this->action('delete');
	}

	public function copy() {
		$this->action('copy');
	}

	private function action($action) {
		$ajax_request = isset($this->request->server['HTTP_X_REQUESTED_WITH']) && !empty($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

		if ($ajax_request) {
			$this->load->language('catalog/product_ext');

			$this->load->model('catalog/product');

			$response = array();

			if (isset($this->request->post['selected']) && $this->validateAction($action)) {
				foreach ((array)$this->request->post['selected'] as $product_id) {
					switch ($action) {
						case 'copy':
							$this->model_catalog_product->copyProduct($product_id);
							break;
						case 'delete':
							$this->model_catalog_product->deleteProduct($product_id);
							break;
					}
				}

				$response['reset'] = true;
				$this->alert['success']['done'] = sprintf($this->language->get('text_success_' . $action), count((array)$this->request->post['selected']));
			}

			$response = array_merge($response, array("errors" => $this->error), array("alerts" => $this->alert));

			$response['query_count'] = DB::$query_count;
			$response['page_time'] = microtime(true) - $this->start_time;

			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_enc($response, JSON_UNESCAPED_SLASHES));
			return;
		} else {
			$url = $this->urlParams();
			$this->response->redirect($this->url->link('catalog/product_ext', $url, 'SSL'));
		}
	}

	protected function getBase() {
		if (isset($this->session->data['errors'])) {
			$this->error = array_merge($this->error, (array)$this->session->data['errors']);

			unset($this->session->data['errors']);
		}

		if (isset($this->session->data['alerts'])) {
			$this->alert = array_merge($this->alert, (array)$this->session->data['alerts']);

			unset($this->session->data['alerts']);
		}

		if (isset($this->request->get['search'])) {
			$search = html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8');
		} else {
			$search = '';
		}

		$this->load->language('catalog/product_ext');

		$this->load->model('catalog/product');
		$this->load->model('catalog/product_ext');

		$this->document->addStyle('view/stylesheet/aqe/css/catalog.min.css');

		$this->document->addScript('view/javascript/aqe/catalog.min.js');

		$this->document->setTitle($this->language->get('heading_title'));

		foreach (self::$language_texts as $text) {
			$data[$text] = $this->language->get($text);
		}

		$items_per_page = $this->config->get('aqe_items_per_page');
		$data['items_per_page'] = ($items_per_page) ? $items_per_page : $this->config->get('config_limit_admin');

		$data['aqe_server_side_caching'] = $this->config->get('aqe_server_side_caching');
		$data['aqe_client_side_caching'] = $this->config->get('aqe_client_side_caching');
		$data['aqe_cache_size'] = $this->config->get('aqe_cache_size');
		$data['aqe_alternate_row_colour'] = $this->config->get('aqe_alternate_row_colour');
		$data['aqe_row_hover_highlighting'] = $this->config->get('aqe_row_hover_highlighting');
		$data['aqe_highlight_status'] = $this->config->get('aqe_highlight_status');
		$data['aqe_highlight_filtered_columns'] = $this->config->get('aqe_highlight_filtered_columns');
		$data['aqe_highlight_actions'] = $this->config->get('aqe_highlight_actions');
		$data['aqe_quick_edit_on'] = $this->config->get('aqe_quick_edit_on');
		$data['aqe_list_view_image_width'] = $this->config->get('aqe_list_view_image_width');
		$data['aqe_list_view_image_height'] = $this->config->get('aqe_list_view_image_height');
		$data['aqe_filter_sub_category'] = $this->config->get('aqe_filter_sub_category');
		$data['aqe_debug_mode'] = $this->config->get('aqe_debug_mode');

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

		$url = $this->urlParams();

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
			'active'    => false
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/product_ext', 'token=' . $this->session->data['token'], 'SSL'),
			'active'    => true
		);

		$data['add'] = $this->url->link('catalog/product/add', $url, 'SSL');
		$data['copy'] = $this->url->link('catalog/product_ext/copy', $url, 'SSL');
		$data['delete'] = $this->url->link('catalog/product_ext/delete', $url, 'SSL');
		$data['source'] = html_entity_decode($this->url->link('catalog/product_ext/data', $url, 'SSL'), ENT_QUOTES, 'UTF-8');
		$data['load'] = html_entity_decode($this->url->link('catalog/product_ext/load', 'token=' . $this->session->data['token'], 'SSL'), ENT_QUOTES, 'UTF-8');
		$data['settings'] = $this->url->link('catalog/product_ext/settings', $url, 'SSL');
		$data['clear_cache'] = $this->url->link('catalog/product_ext/clear_cache', $url, 'SSL');
		$data['update'] = html_entity_decode($this->url->link('catalog/product_ext/update', 'token=' . $this->session->data['token'], 'SSL'), ENT_QUOTES, 'UTF-8');
		$data['reload'] = html_entity_decode($this->url->link('catalog/product_ext/reload', 'token=' . $this->session->data['token'], 'SSL'), ENT_QUOTES, 'UTF-8');
		$data['filter'] = html_entity_decode($this->url->link('catalog/product_ext/filter', '', 'SSL'), ENT_QUOTES, 'UTF-8');

		$data['clear_dt_cache'] = isset($this->session->data['success']);

		$this->load->model('setting/store');

		$multistore = $this->model_setting_store->getTotalStores();

		$actions = $this->config->get('aqe_catalog_products_actions');

		foreach ($actions as $action => $attr) {
			$actions[$action]['name'] = $this->language->get('action_' . $action);
		}
		uasort($actions, 'column_sort');
		$data['product_actions'] = $actions;

		$columns = $this->config->get('aqe_catalog_products');

		foreach ($columns as $column => $attr) {
			$columns[$column]['name'] = $this->language->get('column_' . $column);

			if ($column == 'view_in_store' && !$multistore) {
				unset($columns[$column]);
			}
		}

		uasort($columns, 'column_sort');
		$data['product_columns'] = $columns;

		$columns = array_filter($columns, 'column_display');
		$displayed_actions = array_keys(array_filter($actions, 'column_display'));

		$displayed_columns = array_keys($columns);
		$related_columns = array_merge(array_map(function($v) { return $v['rel']; }, $columns), array_map(function($v) { return $v['rel']; }, $actions));
		$column_classes = array();
		$type_classes = array();
		$non_sortable = array();

		if (!is_array($columns)) {
			$displayed_columns = array('selector', 'image', 'name', 'model', 'price', 'quantity', 'status', 'action');
			$columns = array();
		} else {
			foreach($columns as $column => $attr) {
				if (empty($attr['sort'])) {
					$non_sortable[] = 'col_' . $column;
				}

				if (!empty($attr['type']) && !in_array($attr['type'], $type_classes)) {
					$type_classes[] = $attr['type'];
				}

				if (!empty($attr['align'])) {
					if (!empty($attr['type']) && $attr['editable']) {
						$column_classes[] = $attr['align'] . ' ' . $attr['type'];
					} else {
						$column_classes[] = $attr['align'];
					}
				} else {
					if (!empty($attr['type'])) {
						$column_classes[] = $attr['type'];
					} else {
						$column_classes[] = null;
					}
				}
			}
		}

		// foreach($columns as $column => $attr) {
		//     $columns[$column]['name'] = $this->language->get('column_' . $column);
		// }

		$data['multilingual_seo'] = $this->config->get('aqe_multilingual_seo');
		$data['columns'] = $displayed_columns;
		$data['actions'] = $displayed_actions;
		$data['related'] = $related_columns;
		$data['column_info'] = $columns;
		$data['non_sortable_columns'] = json_enc($non_sortable);
		$data['column_classes'] = $column_classes;
		$data['types'] = $type_classes;
		$data['typeahead'] = array();
		$data['active_filters'] = array();

		if (!$displayed_columns) {
			$this->alert['info']['select_columns'] = $this->language->get('text_select_columns');
		}

		foreach (array('name', 'model', 'sku', 'upc', 'ean', 'jan', 'isbn', 'mpn', 'location', 'seo') as $column) {
			if (in_array($column, $displayed_columns)) {
				$url = $this->urlParams();
				$data['typeahead'][$column] = array(
					'remote' => html_entity_decode($this->url->link('catalog/product_ext/filter', 'type=' . $column . '&query=%QUERY' . $url, 'SSL'), ENT_QUOTES, 'UTF-8')
				);
			}
		}

		if (in_array('category', $displayed_columns)) {
			$this->load->model('catalog/category');
			$data['categories'] = false;
			$total_categories = $this->cache->get('category.total');

			if (isset($this->request->get['filter_category'])) {
				if ($this->request->get['filter_category'] == '*') {
					$data['active_filters']['category'] = array(
						"id"    => '*',
						"value" => html_entity_decode($this->language->get('text_none'))
					);
				} else {
					$cat =  $this->model_catalog_category->getCategory($this->request->get['filter_category']);
					if ($cat) {
						$data['active_filters']['category'] = array(
							"id"    => $this->request->get['filter_category'],
							"value" => html_entity_decode($cat['name'])
						);
					}
				}
			}

			if ($total_categories === false) {
				$total_categories = (int)$this->model_catalog_category->getTotalCategories();
				$this->cache->set('category.total', $total_categories);
			}

			if ($total_categories < TA_LOCAL) {
				$categories = $this->cache->get('category.all');

				if ($categories === false) {
					$categories = $this->model_catalog_category->getCategories(array());
					$this->cache->set('category.all', $categories);
				}

				$data['categories'] = $categories;
			} else if ($total_categories < TA_PREFETCH) {
				$url = $this->urlParams();
				$data['typeahead']['category'] = array(
					'prefetch' => html_entity_decode($this->url->link('catalog/product_ext/filter', 'type=category' . $url, 'SSL'), ENT_QUOTES, 'UTF-8'),
					'remote' => html_entity_decode($this->url->link('catalog/product_ext/filter', 'type=category&query=%QUERY' . $url, 'SSL'), ENT_QUOTES, 'UTF-8')
				);
			} else {
				$url = $this->urlParams();
				$data['typeahead']['category'] = array(
					'remote' => html_entity_decode($this->url->link('catalog/product_ext/filter', 'type=category&query=%QUERY' . $url, 'SSL'), ENT_QUOTES, 'UTF-8')
				);
			}

			if ($total_categories < TA_LOCAL) {
				$cat_select = array();
				foreach ($data['categories'] as $cat) {
					$cat_select[] = array('id' => (int)$cat['category_id'], 'text' => html_entity_decode($cat['name'], ENT_QUOTES, 'UTF-8'));
				}
				$data['category_select'] = json_enc($cat_select);
			} else {
				$data['category_select'] = false;
			}
		}

		if (in_array('store', $displayed_columns)) {
			$this->load->model('setting/store');
			$_stores = $this->model_setting_store->getStores(array());

			$stores = array(
				'0' => array(
					'store_id'  => 0,
					'name'      => $this->config->get('config_name'),
					'url'       => HTTP_CATALOG
				)
			);

			foreach ($_stores as $store) {
				$stores[$store['store_id']] = array(
					'store_id'  => $store['store_id'],
					'name'      => $store['name'],
					'url'       => $store['url']
				);
			}

			$data['stores'] = $stores;

			$st_select = array();
			foreach ($stores as $st) {
				$st_select[] = array('id' => (int)$st['store_id'], 'text' => html_entity_decode($st['name'], ENT_QUOTES, 'UTF-8'));
			}
			$data['store_select'] = json_enc($st_select);
		}

		if (in_array('manufacturer', $displayed_columns)) {
			$this->load->model('catalog/manufacturer');

			$data['manufacturers'] = false;
			$total_manufacturers = $this->cache->get('manufacturers.total');

			if (isset($this->request->get['filter_manufacturer'])) {
				if ($this->request->get['filter_manufacturer'] == '*') {
					$data['active_filters']['manufacturer'] = array(
						"id"    => '*',
						"value" => html_entity_decode($this->language->get('text_none'))
					);
				} else {
					$man =  $this->model_catalog_manufacturer->getManufacturer($this->request->get['filter_manufacturer']);
					if ($man) {
						$data['active_filters']['manufacturer'] = array(
							"id"    => $this->request->get['filter_manufacturer'],
							"value" => html_entity_decode($man['name'])
						);
					}
				}
			}

			if ($total_manufacturers === false) {
				$total_manufacturers = (int)$this->model_catalog_manufacturer->getTotalManufacturers();
				$this->cache->set('manufacturers.total', $total_manufacturers);
			}

			if ($total_manufacturers < TA_LOCAL) {
				$manufacturers = $this->cache->get('manufacturers.all');

				if ($manufacturers === false) {
					$manufacturers = $this->model_catalog_manufacturer->getManufacturers(array());
					$this->cache->set('manufacturers.all', $manufacturers);
				}

				$data['manufacturers'] = (array)$manufacturers;
			} else if ($total_manufacturers < TA_PREFETCH) {
				$url = $this->urlParams();
				$data['typeahead']['manufacturer'] = array(
					'prefetch' => html_entity_decode($this->url->link('catalog/product_ext/filter', 'type=manufacturer' . $url, 'SSL'), ENT_QUOTES, 'UTF-8'),
					'remote' => html_entity_decode($this->url->link('catalog/product_ext/filter', 'type=manufacturer&query=%QUERY' . $url, 'SSL'), ENT_QUOTES, 'UTF-8')
				);
			} else {
				$url = $this->urlParams();
				$data['typeahead']['manufacturer'] = array(
					'remote' => html_entity_decode($this->url->link('catalog/product_ext/filter', 'type=manufacturer&query=%QUERY' . $url, 'SSL'), ENT_QUOTES, 'UTF-8')
				);
			}

			if ($total_manufacturers < TA_LOCAL) {
				$m_select = array();
				foreach ($data['manufacturers'] as $m) {
					$m_select[] = array('value' => (int)$m['manufacturer_id'], 'text' => $m['name']);
				}
				$data['manufacturer_select'] = json_enc($m_select);
			} else {
				$data['manufacturer_select'] = false;
			}
		}

		if (in_array('download', $displayed_columns)) {
			$this->load->model('catalog/download');

			$data['downloads'] = false;
			$total_downloads = $this->cache->get('downloads.total');

			if (isset($this->request->get['filter_download'])) {
				if ($this->request->get['filter_download'] == '*') {
					$data['active_filters']['download'] = array(
						"id"    => '*',
						"value" => html_entity_decode($this->language->get('text_none'))
					);
				} else {
					$dl =  $this->model_catalog_download->getDownload($this->request->get['filter_download']);
					if ($dl) {
						$data['active_filters']['download'] = array(
							"id"    => $this->request->get['filter_download'],
							"value" => html_entity_decode($dl['name'])
						);
					}
				}
			}

			if ($total_downloads === false) {
				$total_downloads = (int)$this->model_catalog_download->getTotalDownloads();
				$this->cache->set('downloads.total', $total_downloads);
			}

			if ($total_downloads < TA_LOCAL) {
				$downloads = $this->cache->get('downloads.all');

				if ($downloads === false) {
					$downloads = $this->model_catalog_download->getDownloads(array());
					$this->cache->set('downloads.all', $downloads);
				}

				$data['downloads'] = $downloads;
			} else if ($total_downloads < TA_PREFETCH) {
				$url = $this->urlParams();
				$data['typeahead']['download'] = array(
					'prefetch' => html_entity_decode($this->url->link('catalog/product_ext/filter', 'type=download' . $url, 'SSL'), ENT_QUOTES, 'UTF-8'),
					'remote' => html_entity_decode($this->url->link('catalog/product_ext/filter', 'type=download&query=%QUERY' . $url, 'SSL'), ENT_QUOTES, 'UTF-8')
				);
			} else {
				$url = $this->urlParams();
				$data['typeahead']['download'] = array(
					'remote' => html_entity_decode($this->url->link('catalog/product_ext/filter', 'type=download&query=%QUERY' . $url, 'SSL'), ENT_QUOTES, 'UTF-8')
				);
			}

			if ($total_downloads < TA_LOCAL) {
				$dl_select = array();
				foreach ($data['downloads'] as $dl) {
					$dl_select[] = array('id' => (int)$dl['download_id'], 'text' => $dl['name']);
				}
				$data['download_select'] = json_enc($dl_select);
			} else {
				$data['download_select'] = false;
			}
		}

		if (in_array('filter', $displayed_columns)) {
			$this->load->model('catalog/filter');

			$data['filters'] = false;
			$total_filters = $this->cache->get('filters.total');

			if (isset($this->request->get['filter_filter'])) {
				if ($this->request->get['filter_filter'] == '*') {
					$data['active_filters']['filter'] = array(
						"id"    => '*',
						"value" => html_entity_decode($this->language->get('text_none'))
					);
				} else {
					$fltr =  $this->model_catalog_filter->getFilter($this->request->get['filter_filter']);
					if ($fltr) {
						$data['active_filters']['filter'] = array(
							"id"    => $this->request->get['filter_filter'],
							"value" => html_entity_decode($fltr['name'])
						);
					}
				}
			}

			if ($total_filters === false) {
				$total_filters = (int)$this->model_catalog_filter->getTotalFilters();
				$this->cache->set('filters.total', $total_filters);
			}

			if ($total_filters < TA_LOCAL) {
				$filters = $this->cache->get('filters.all');

				if ($filters === false) {
					$filters = $this->model_catalog_filter->getFiltersByGroup();
					$this->cache->set('filters.all', $filters);
				}

				$data['filters'] = $filters;
			} else if ($total_filters < TA_PREFETCH) {
				$url = $this->urlParams();
				$data['typeahead']['filter'] = array(
					'prefetch' => html_entity_decode($this->url->link('catalog/product_ext/filter', 'type=filter' . $url, 'SSL'), ENT_QUOTES, 'UTF-8'),
					'remote' => html_entity_decode($this->url->link('catalog/product_ext/filter', 'type=filter&query=%QUERY' . $url, 'SSL'), ENT_QUOTES, 'UTF-8')
				);
			} else {
				$url = $this->urlParams();
				$data['typeahead']['filter'] = array(
					'remote' => html_entity_decode($this->url->link('catalog/product_ext/filter', 'type=filter&query=%QUERY' . $url, 'SSL'), ENT_QUOTES, 'UTF-8')
				);
			}

			if ($total_filters < TA_LOCAL) {
				$f_select = array();
				foreach ($data['filters'] as $fg) {
					$group = array('text' => $fg['name'], 'children' => array());
					foreach ($fg['filters'] as $f) {
						$group['children'][] = array(
							'id'    => (int)$f['filter_id'],
							'text'  => strip_tags(html_entity_decode($fg['name'] . ' &gt; ' . $f['name'], ENT_QUOTES, 'UTF-8'))
						);
					}
					$f_select[] = $group;
				}
				$data['filter_select'] = json_enc($f_select);
			} else {
				$data['filter_select'] = false;
			}
		}

		if (in_array('tax_class', $displayed_columns)) {
			$this->load->model('localisation/tax_class');
			$tax_classes = $this->model_localisation_tax_class->getTaxClasses(array());

			$data['tax_classes'] = $tax_classes;

			$tc_select = array();
			$tc_select[] = array('value' => 0, 'text' => $this->language->get('text_none'));
			foreach ($tax_classes as $tc) {
				$tc_select[] = array('value' => (int)$tc['tax_class_id'], 'text' => $tc['title']);
			}
			$data['tax_class_select'] = json_enc($tc_select);
		}

		if (in_array('stock_status', $displayed_columns)) {
			$this->load->model('localisation/stock_status');
			$stock_statuses = $this->model_localisation_stock_status->getStockStatuses(array());

			$data['stock_statuses'] = $stock_statuses;

			$ss_select = array();
			foreach ($stock_statuses as $ss) {
				$ss_select[] = array('value' => (int)$ss['stock_status_id'], 'text' => $ss['name']);
			}
			$data['stock_status_select'] = json_enc($ss_select);
		}

		if (in_array('length_class', $displayed_columns)) {
			$this->load->model('localisation/length_class');
			$length_classes = $this->model_localisation_length_class->getLengthClasses(array());

			$data['length_classes'] = $length_classes;

			$lc_select = array();
			foreach ($length_classes as $lc) {
				$lc_select[] = array('value' => (int)$lc['length_class_id'], 'text' => $lc['title']);
			}
			$data['length_class_select'] = json_enc($lc_select);
		}

		if (in_array('weight_class', $displayed_columns)) {
			$this->load->model('localisation/weight_class');
			$weight_classes = $this->model_localisation_weight_class->getWeightClasses(array());

			$data['weight_classes'] = $weight_classes;

			$wc_select = array();
			foreach ($weight_classes as $wc) {
				$wc_select[] = array('value' => (int)$wc['weight_class_id'], 'text' => $wc['title']);
			}
			$data['weight_class_select'] = json_enc($wc_select);
		}

		if (in_array('image', $displayed_columns)) {
			$this->load->model('tool/image');

			$w = (int)$this->config->get('aqe_list_view_image_width');
			$h = (int)$this->config->get('aqe_list_view_image_height');

			$data['no_image'] = $this->model_tool_image->resize('no_image.png', $w, $h);
			$data['list_view_image_width'] = $w;
			$data['list_view_image_height'] = $h;
		}

		if (isset($this->session->data['error'])) {
			$this->error = $this->session->data['error'];

			unset($this->session->data['error']);
		}

		if (isset($this->error['warning'])) {
			$this->alert['warning']['warning'] = $this->error['warning'];
			unset($this->error['warning']);
		}

		if (isset($this->error['error'])) {
			$this->alert['error']['error'] = $this->error['error'];
			unset($this->error['error']);
		}

		if (isset($this->session->data['success'])) {
			$this->alert['success']['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		}

		$data['search'] = $search;

		$data['errors'] = $this->error;

		$data['token'] = $this->session->data['token'];

		$data['alerts'] = $this->alert;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$data['text_page_created'] = sprintf($this->language->get('text_page_created'), microtime(true) - $this->start_time, DB::$query_count);

		$this->response->setOutput($this->load->view('catalog/product_ext_list.tpl', $data));
	}

	public function settings() {
		$this->load->language('catalog/product_ext');

		$ajax_request = isset($this->request->server['HTTP_X_REQUESTED_WITH']) && !empty($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

		if ($ajax_request && $this->request->server['REQUEST_METHOD'] == 'POST') {
			$response = array(
				"values"    => array(),
				"reset"     => false
			);

			if ($this->validateSettings($this->request->post)) {
				$this->load->model('setting/setting');

				$settings = $this->model_setting_setting->getSetting('aqe');

				if (isset($this->request->post['aqe_items_per_page'])) {
					$settings['aqe_items_per_page'] = $this->request->post['aqe_items_per_page'];
					if ($settings['aqe_items_per_page'] != $this->request->post['aqe_items_per_page']) {
						$response['reset'] = true;
					}
				}

				if (isset($this->request->post['aqe_server_side_caching'])) {
					if ($settings['aqe_server_side_caching'] != $this->request->post['aqe_server_side_caching']) {
						$response['reload'] = true;
					}
					$settings['aqe_server_side_caching'] = $this->request->post['aqe_server_side_caching'];
				}

				if (isset($this->request->post['aqe_client_side_caching'])) {
					if ($settings['aqe_client_side_caching'] != $this->request->post['aqe_client_side_caching']) {
						$response['reload'] = true;
					}
					$settings['aqe_client_side_caching'] = $this->request->post['aqe_client_side_caching'];
				}

				if (isset($this->request->post['aqe_cache_size'])) {
					if ($settings['aqe_cache_size'] !=  $this->request->post['aqe_cache_size']) {
						$response['reload'] = true;
					}
					$settings['aqe_cache_size'] = $this->request->post['aqe_cache_size'];
				}

				if (isset($this->request->post['aqe_alternate_row_colour'])) {
					$settings['aqe_alternate_row_colour'] = $this->request->post['aqe_alternate_row_colour'];
				}

				if (isset($this->request->post['aqe_row_hover_highlighting'])) {
					$settings['aqe_row_hover_highlighting'] = $this->request->post['aqe_row_hover_highlighting'];
				}

				if (isset($this->request->post['aqe_highlight_status'])) {
					$settings['aqe_highlight_status'] = $this->request->post['aqe_highlight_status'];
				}

				if (isset($this->request->post['aqe_highlight_filtered_columns'])) {
					$settings['aqe_highlight_filtered_columns'] = $this->request->post['aqe_highlight_filtered_columns'];
				}

				if (isset($this->request->post['aqe_highlight_actions'])) {
					$settings['aqe_highlight_actions'] = $this->request->post['aqe_highlight_actions'];
				}

				if (isset($this->request->post['aqe_quick_edit_on'])) {
					if ($settings['aqe_quick_edit_on'] !=  $this->request->post['aqe_quick_edit_on']) {
						$response['reload'] = true;
					}
					$settings['aqe_quick_edit_on'] = $this->request->post['aqe_quick_edit_on'];
				}

				if (isset($this->request->post['aqe_list_view_image_width'])) {
					$settings['aqe_list_view_image_width'] = $this->request->post['aqe_list_view_image_width'];
				}

				if (isset($this->request->post['aqe_list_view_image_height'])) {
					$settings['aqe_list_view_image_height'] = $this->request->post['aqe_list_view_image_height'];
				}

				if (isset($this->request->post['aqe_filter_sub_category'])) {
					if ($settings['aqe_filter_sub_category'] !=  $this->request->post['aqe_filter_sub_category']) {
						$response['reset'] = true;
					}
					$settings['aqe_filter_sub_category'] = $this->request->post['aqe_filter_sub_category'];
				}

				if (isset($this->request->post['aqe_debug_mode'])) {
					$settings['aqe_debug_mode'] = $this->request->post['aqe_debug_mode'];
				}

				// Loop through columns
				if (isset($this->request->post['index']['columns'])) {
					foreach ($settings['aqe_catalog_products'] as $column => $attr) {
						$display = (isset($this->request->post['display']['columns'][$column])) ? true : false;
						if ($settings['aqe_catalog_products'][$column]['display'] != $display) {
							$response['reload'] = true;
						}
						$settings['aqe_catalog_products'][$column]['display'] = $display;

						if (isset($this->request->post['index']['columns'][$column])) {
							if ($settings['aqe_catalog_products'][$column]['index'] != $this->request->post['index']['columns'][$column]) {
								$response['reload'] = true;
							}
							$settings['aqe_catalog_products'][$column]['index'] = $this->request->post['index']['columns'][$column];
						}
					}
				}

				// Loop through actions
				if (isset($this->request->post['index']['actions'])) {
					foreach ($settings['aqe_catalog_products_actions'] as $action => $attr) {
						$display = (isset($this->request->post['display']['actions'][$action])) ? true : false;
						if ($settings['aqe_catalog_products_actions'][$action]['display'] != $display) {
							$response['reload'] = true;
						}
						$settings['aqe_catalog_products_actions'][$action]['display'] = $display;

						if (isset($this->request->post['index']['actions'][$action])) {
							if ($settings['aqe_catalog_products_actions'][$action]['index'] != $this->request->post['index']['actions'][$action]) {
								$response['reload'] = true;
							}
							$settings['aqe_catalog_products_actions'][$action]['index'] = $this->request->post['index']['actions'][$action];
						}
					}
				}

				$this->model_setting_setting->editSetting('aqe', $settings);

				if (!empty($response['reload'])) {
					$this->session->data['success'] = $this->language->get('text_setting_updated');
				}
				$this->alert['success']['updated'] = $this->language->get('text_setting_updated');
			}

			$response = array_merge($response, array("errors" => $this->error), array("alerts" => $this->alert));

			$response['query_count'] = DB::$query_count;
			$response['page_time'] = microtime(true) - $this->start_time;

			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_enc($response, JSON_UNESCAPED_SLASHES));
			return;
		}

		$this->response->redirect($this->url->link('catalog/product_ext', $this->urlParams(), 'SSL'));
	}

	public function data() {
		$ajax_request = isset($this->request->server['HTTP_X_REQUESTED_WITH']) && !empty($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

		if ($ajax_request) {
			$this->load->language('catalog/product_ext');
			$this->load->model('catalog/product_ext');

			$all_columns = $this->config->get('aqe_catalog_products');

			uasort($all_columns, 'column_sort');
			$columns = array_filter($all_columns, 'column_display');

			$actions = $this->config->get('aqe_catalog_products_actions');

			uasort($actions, 'column_sort');
			$actions = array_filter($actions, 'column_display');

			$displayed_columns = array_keys($columns);
			$displayed_actions = array_keys($actions);

			$filter_data = array(
				'search'    => '',
				'filter'    => array(),
				'sort'      => array(),
				'start'     => '',
				'limit'     => '',
				'columns'   => $displayed_columns,
				'actions'   => $displayed_actions
			);

			/*
			 * Paging
			 */
			if (isset($this->request->post['start'] ) && $this->request->post['length'] != '-1') {
				$filter_data['start'] = (int)$this->request->post['start'];
				$filter_data['limit'] = (int)$this->request->post['length'];
			}

			/*
			 * Ordering
			 */
			if (isset($this->request->post['order']) && count($this->request->post['order'])) {
				for ($i = 0, $len = count($this->request->post['order']); $i < $len; $i++) {
					// Convert the column index into the column data property
					$column_idx = intval($this->request->post['order'][$i]['column']);
					$request_column = $this->request->post['columns'][$column_idx];
					$column_name = $request_column['data'];

					// if ($request_column['orderable'] == 'true' && $columns[$displayed_columns[$column_idx]]['sort']) {
					if ($request_column['orderable'] == 'true' && $columns[$column_name]['sort']) {
						$filter_data['sort'][] = array(
							'column' => $columns[$column_name]['sort'],
							'order' => ($this->request->post['order'][$i]['dir'] === 'asc' ? 'ASC' : 'DESC')
						);
					}
				}
			}

			/*
			 * Filtering
			 * NOTE this does not match the built-in DataTables filtering which does it
			 * word by word on any field. It would be possible to do it here, but performance
			 * on large databases would be very poor
			 */
			if (isset($this->request->post['search']) && isset($this->request->post['search']['value']) && $this->request->post['search']['value'] != '') {
				$filter_data['search'] = $this->request->post['search']['value'];
			}

			// Individual column filtering
			for ($i = 0, $len = count($this->request->post['columns']); $i < $len; $i++) {
				$request_column = $this->request->post['columns'][$i];
				$column_name = $request_column['data'];
				if ($request_column['searchable'] == 'true' && $request_column['search']['value'] != '') {
					// $filter_data['filter'][$displayed_columns[$i]] = $this->request->post['sSearch_' . $i];
					$filter_data['filter'][$column_name] = $request_column['search']['value'];
				}
			}

			if (isset($this->request->post['filter_special_price'])) {
				$filter_data['filter']['special_price'] = $this->request->post['filter_special_price'];
			}

			if (in_array('image', $displayed_columns)) {
				$this->load->model('tool/image');
			}

			$results = $this->model_catalog_product_ext->getProducts($filter_data);

			$iFilteredTotal = $this->model_catalog_product_ext->getFilteredTotalProducts();
			$iTotal = $this->model_catalog_product_ext->getTotalProducts();

			/*
			 * Output
			 */
			$output = array(
				'draw'              => (int)$this->request->post['draw'],
				'recordsTotal'      => $iTotal,
				'recordsFiltered'   => $iFilteredTotal,
				'data'              => array(),
				// 'error'             => ''
			);

			if (in_array('view_in_store', $displayed_columns)) {
				$this->load->model('setting/store');
				$_stores = $this->model_setting_store->getStores(array());

				$stores = array(
					'0' => array(
						'store_id'  => 0,
						'name'      => $this->config->get('config_name'),
						'url'       => HTTP_CATALOG
					)
				);

				foreach ($_stores as $store) {
					$stores[$store['store_id']] = array(
						'store_id'  => $store['store_id'],
						'name'      => $store['name'],
						'url'       => $store['url']
					);
				}
			} else {
				$stores = array();
			}

			foreach ($results as $result) {
				$product = array();

				for ($i = 0, $len = count($displayed_columns); $i < $len; $i++) {
					switch ($displayed_columns[$i]) {
						case 'selector':
							$value = '';
							break;
						case 'download':
						case 'filter':
						case 'store':
						case 'category':
							$_ids = explode('_', $result[$displayed_columns[$i]]);
							$_texts = explode('<br/>', $result[$displayed_columns[$i] . '_text']);
							$_data = array();
							foreach ($_ids as $idx => $value) {
								$_data[] = array('id' => $value, 'text' => $_texts[$idx]);
							}
							$product[$displayed_columns[$i] . '_data'] = $_data;
							$value = $_ids;
							break;
						case 'image':
							$w = (int)$this->config->get('aqe_list_view_image_width');
							$h = (int)$this->config->get('aqe_list_view_image_height');

							if (is_file(DIR_IMAGE . $result['image'])) {
								$image = $this->model_tool_image->resize($result['image'], $w, $h);
							} else {
								$image = $this->model_tool_image->resize('no_image.png', $w, $h);
							}

							$value = $result['image'];
							$product['image_thumb'] = $image;
							$product['image_alt'] = html_entity_decode(isset($result['image_alt']) ? $result['image_alt'] : $result['name'], ENT_QUOTES, 'UTF-8');
							$product['image_title'] = html_entity_decode(isset($result['image_title']) ? $result['image_title'] : $result['name'], ENT_QUOTES, 'UTF-8');
							break;
						case 'id':
							$value = $result['product_id'];
							break;
						case 'subtract':
						case 'shipping':
							$value = (int)$result[$displayed_columns[$i]];
							$product[$displayed_columns[$i] . '_text'] = $result[$displayed_columns[$i] . '_text'];
							break;
						case 'date_available':
							$date = new DateTime($result[$displayed_columns[$i]]);
							$value = $date->format('Y-m-d');
							// $product['date_available_text'] = $date->format($this->language->get('date_format_short'));
							$product['date_available_text'] = $date->format('Y-m-d');
							break;
						case 'date_added':
						case 'date_modified':
							$date = new DateTime($result[$displayed_columns[$i]]);
							$value = $date->format('Y-m-d H:i:s');
							// $product[$displayed_columns[$i] . '_text'] = $date->format($this->language->get('date_format_short') . ' ' . $this->language->get('time_format'));
							$product[$displayed_columns[$i] . '_text'] = $date->format('Y-m-d H:i:s');
							break;
						case 'stock_status':
						case 'tax_class':
						case 'length_class':
						case 'weight_class':
						case 'manufacturer':
							$value = (int)$result[$displayed_columns[$i] . '_id'];
							$product[$displayed_columns[$i] . '_text'] = (is_null($result[$displayed_columns[$i] . '_text'])) ? '' : $result[$displayed_columns[$i] . '_text'];
							break;
						case 'quantity':
							$value = (int)$result['quantity'];
							break;
						case 'status':
							$value = (int)$result['status'];
							$product['status_text'] = $result['status_text'];

							if ($this->config->get('aqe_highlight_status')) {
								$product['status_class'] = (int)$result['status'] ? 'success' : 'danger';
							}
							break;
						case 'price':
							$product['special'] = $result['special_price'];
							$value = $result['price'];
							break;
						case 'view_in_store':
							$product_stores = explode('_', $result['store_ids']);
							$_stores = array();

							foreach ($product_stores as $store) {
								if (!in_array($store, array_keys($stores)))
									continue;

								$_stores[] = array(
									'url' => $stores[$store]['url'] . 'index.php?route=product/product&product_id=' . $result['product_id'],
									'name' => $stores[$store]['name'],
								);
							}

							$value = $_stores;
							break;
						case 'action':

							$_buttons = array();

							foreach ($actions as $action => $attr) {
								switch ($action) {
									case 'edit':
										$_buttons[] = array(
											'type'  => $attr['type'],
											'action'=> $action,
											'title' => $this->language->get('action_' . $action),
											'url'   => html_entity_decode($this->url->link('catalog/product/edit', '&product_id=' . $result['product_id'] . '&token=' . $this->session->data['token'], 'SSL'), ENT_QUOTES, 'UTF-8'),
											'icon'  => 'pencil',
											'name'  => null,
											'rel'   => json_encode(array()),
											// 'data'  => 0,
											'class' => $attr['class'],
										);
										break;
									case 'view':
										$_buttons[] = array(
											'type'  => $attr['type'],
											'action'=> $action,
											'title' => $this->language->get('action_' . $action),
											'url'   => html_entity_decode(HTTP_CATALOG . 'index.php?route=product/product&product_id=' . $result['product_id'], ENT_QUOTES, 'UTF-8'),
											'icon'  => 'eye',
											'name'  => null,
											'rel'   => json_encode(array()),
											// 'data'  => 0,
											'class' => $attr['class'],
										);
										break;
									default:
										$_buttons[] = array(
											'type'  => $attr['type'],
											'action'=> $action,
											'title' => $this->language->get('action_' . $action),
											'url'   => null,
											'icon'  => null,
											'name'  => $this->language->get('action_' . $attr['short']),
											'rel'   => json_encode($attr['rel']),
											// 'data'  => isset($result[$action . '_exist']) ? (int)$result[$action . '_exist'] : 0,
											'class' => $attr['class'],
										);
										$product[$action . '_exist'] = isset($result[$action . '_exist']) ? (int)$result[$action . '_exist'] : 0;
										break;
								}
							}

							$value = $_buttons;
							break;
						default:
							$value = isset($result[$displayed_columns[$i]]) ? $result[$displayed_columns[$i]] : '';
							break;
					}

					$product[$displayed_columns[$i]] = $value;
				}

				$product['id'] = $result['product_id'];
				$product['DT_RowId'] = 'p_' . $result['product_id'];
				// $product['DT_RowClass'] = '';
				// $product['DT_RowData'] = '';

				$output['data'][] = $product;
			}

			$output['query_count'] = DB::$query_count;
			$output['page_time'] = microtime(true) - $this->start_time;

			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_enc($output));
		}
	}

	public function filter() {
		if ($this->request->server['REQUEST_METHOD'] == 'GET' && isset($this->request->get['type'])) {
			$response = array();
			switch ($this->request->get['type']) {
				case 'product':
					$this->load->model('catalog/product_ext');

					$results = array();

					if (isset($this->request->get['query'])) {
						if (is_array($this->request->get['query']) && isset($this->request->get['multiple'])) {
							$results = array();

							foreach ((array)$this->request->get['query'] as $value) {
								$result =  $this->model_catalog_product_ext->getProduct($value);
								$results[] = $result;
							}
						} else {
							$filter_data = array(
								'search'    => $this->request->get['query'],
								'filter'    => array(),
								'sort'      => array(),
								'start'     => '',
								'limit'     => '',
								'columns'   => array('name')
							);

							$results = $this->model_catalog_product_ext->getProducts($filter_data);
						}
					}

					foreach ($results as $result) {
						$result['name'] = html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8');
						$response[] = array(
							'value'     => $result['name'],
							'tokens'    => explode(' ', $result['name']),
							'id'        => $result['product_id'],
							'model'     => $result['model']
						);
					}
					break;
				case 'category':
					$this->load->model('catalog/category');

					if (isset($this->request->get['query'])) {
						if (is_array($this->request->get['query']) && isset($this->request->get['multiple'])) {
							$results = array();

							foreach ((array)$this->request->get['query'] as $value) {
								$result =  $this->model_catalog_category->getCategory($value);
								$result['name'] = $result['path'] ? $result['path'] . '&nbsp;&nbsp;&gt;&nbsp;&nbsp;' . $result['name'] : $result['name'];
								$results[] = $result;
							}
						} else {
							$filter_data = array(
								'filter_name' => $this->request->get['query']
							);

							$results = $this->model_catalog_category->getCategories($filter_data);

							if (stripos($this->language->get('text_none'), $this->request->get['query']) !== false) {
								$response[] = array(
										'value'     => $this->language->get('text_none'),
										'tokens'    => explode(' ', trim(str_replace('---', '', $this->language->get('text_none')))),
										'id'        => '*',
										'path'      => '',
										'full_name' => $this->language->get('text_none')
									);
							}
						}
					} else {
						$results = $this->cache->get('category.all');

						if ($results === false) {
							$results = $this->model_catalog_category->getCategories(array());
							$this->cache->set('category.all', $results);
						}

						$response[] = array(
								'value'     => $this->language->get('text_none'),
								'tokens'    => array_merge(explode(' ', trim(str_replace('---', '', $this->language->get('text_none')))), (array)trim($this->language->get('text_none'))),
								'id'        => '*',
								'path'      => '',
								'full_name' => $this->language->get('text_none')
							);
					}

					foreach ($results as $result) {
						$result['name'] = html_entity_decode(str_replace('&nbsp;', '', $result['name']), ENT_QUOTES, 'UTF-8');
						$parts = explode('>', $result['name']);
						$last_part = array_pop($parts);

						$response[] = array(
							'value'     => $last_part,
							'tokens'    => explode('>', $result['name']),
							'id'        => $result['category_id'],
							'path'      => $parts ? implode(' > ', $parts) . ' > ' : '',
							'full_name' => $result['name']
						);
					}
					break;
				case 'manufacturer':
					$this->load->model('catalog/manufacturer');

					if (isset($this->request->get['query'])) {
						$filter_data = array(
							'filter_name' => $this->request->get['query']
						);

						$results = $this->model_catalog_manufacturer->getManufacturers($filter_data);

						if (stripos($this->language->get('text_none'), $this->request->get['query']) !== false) {
							$response[] = array(
									'value'     => $this->language->get('text_none'),
									'tokens'    => explode(' ', trim(str_replace('---', '', $this->language->get('text_none')))),
									'id'        => '*',
								);
						}
					} else {
						$results = $this->cache->get('manufacturers.all');

						if ($results === false) {
							$results = $this->model_catalog_manufacturer->getManufacturers(array());
							$this->cache->set('manufacturers.all', $results);
						}

						$response[] = array(
								'value'     => $this->language->get('text_none'),
								'tokens'    => array_merge(explode(' ', trim(str_replace('---', '', $this->language->get('text_none')))), (array)trim($this->language->get('text_none'))),
								'id'        => '*',
							);
					}

					foreach ($results as $result) {
						$result['name'] = html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8');
						$response[] = array(
							'value'     => $result['name'],
							'tokens'    => explode(' ', $result['name']),
							'id'        => $result['manufacturer_id'],
						);
					}
					break;
				case 'download':
					$this->load->model('catalog/download');

					if (isset($this->request->get['query'])) {
						if (is_array($this->request->get['query']) && isset($this->request->get['multiple'])) {
							$results = array();

							foreach ((array)$this->request->get['query'] as $value) {
								$result =  $this->model_catalog_download->getDownload($value);
								$results[] = $result;
							}
						} else {
							$filter_data = array(
								'filter_name' => $this->request->get['query']
							);

							$results = $this->model_catalog_download->getDownloads($filter_data);

							if (stripos($this->language->get('text_none'), $this->request->get['query']) !== false) {
								$response[] = array(
										'value'     => $this->language->get('text_none'),
										'tokens'    => explode(' ', trim(str_replace('---', '', $this->language->get('text_none')))),
										'id'        => '*',
									);
							}
						}
					} else {
						$results = $this->cache->get('downloads.all');

						if ($results === false) {
							$results = $this->model_catalog_download->getDownloads(array());
							$this->cache->set('downloads.all', $results);
						}

						$response[] = array(
								'value'     => $this->language->get('text_none'),
								'tokens'    => array_merge(explode(' ', trim(str_replace('---', '', $this->language->get('text_none')))), (array)trim($this->language->get('text_none'))),
								'id'        => '*',
							);
					}

					foreach ($results as $result) {
						$result['name'] = html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8');
						$response[] = array(
							'value'     => $result['name'],
							'tokens'    => explode(' ', $result['name']),
							'id'        => $result['download_id'],
						);
					}
					break;
				case 'filter':
					$this->load->model('catalog/filter');

					if (isset($this->request->get['query'])) {
						if (is_array($this->request->get['query']) && isset($this->request->get['multiple'])) {
							$results = array();

							foreach ((array)$this->request->get['query'] as $value) {
								$result =  $this->model_catalog_filter->getFilter($value);
								$idx = null;
								foreach ($results as $key => $value) {
									if ($value['filter_group_id'] == $result['filter_group_id']) {
										$idx = $key;
										break;
									}
								}

								if (is_null($idx)) {
									$idx = count($results);
									$results[$idx] = array(
										'filter_group_id'   => $result['filter_group_id'],
										'name'              => $result['group'],
										'filters'           => array()
									);
								}

								$results[$idx]['filters'][] = array(
									'filter_id' => $result['filter_id'],
									'name'      => $result['name'],
								);
							}
						} else {
							$filter_data = array(
								'filter_name' => $this->request->get['query']
							);

							$results = $this->model_catalog_filter->getFiltersByGroup($filter_data);

							if (stripos($this->language->get('text_none'), $this->request->get['query']) !== false) {
								$response[] = array(
										'value'     => $this->language->get('text_none'),
										'tokens'    => explode(' ', trim(str_replace('---', '', $this->language->get('text_none')))),
										'group'     => '',
										'id'        => '*',
										'full_name' => $this->language->get('text_none')
									);
							}
						}
					} else {
						$results = $this->cache->get('filters.all');

						if ($results === false) {
							$results = $this->model_catalog_filter->getFiltersByGroup();
							$this->cache->set('filters.all', $results);
						}

						$response[] = array(
								'value'     => $this->language->get('text_none'),
								'tokens'    => array_merge(explode(' ', trim(str_replace('---', '', $this->language->get('text_none')))), (array)trim($this->language->get('text_none'))),
								'group'     => '',
								'id'        => '*',
								'full_name' => $this->language->get('text_none')
							);
					}

					foreach ($results as $fg) {
						foreach ($fg['filters'] as $f) {
							$name = strip_tags(html_entity_decode($fg['name'] . ' > ' . $f['name'], ENT_QUOTES, 'UTF-8'));
							$response[] = array(
								'value'     => $f['name'],
								'tokens'    => explode(' ', strip_tags(html_entity_decode($fg['name'] . ' ' . $f['name'], ENT_QUOTES, 'UTF-8'))),
								'group'     => $fg['name'] . ' > ',
								'group_name'=> $fg['name'],
								'id'        => $f['filter_id'],
								'full_name' => $name
							);
						}
					}
					break;
				case 'attributes':
					$this->load->model('catalog/attribute');

					$results = array();
					if (isset($this->request->get['query'])) {
						if (is_array($this->request->get['query']) && isset($this->request->get['multiple'])) {
							// TODO: if needed
						} else {
							$filter_data = array(
								'filter_name' => $this->request->get['query']
							);

							$results = $this->model_catalog_attribute->getAttributesByGroup($filter_data);
						}
					} else {
						// TODO: if needed
					}

					foreach ($results as $ag) {
						foreach ($ag['attributes'] as $a) {
							$name = strip_tags(html_entity_decode($ag['name'] . ' > ' . $a['name'], ENT_QUOTES, 'UTF-8'));
							$response[] = array(
								'value'     => $a['name'],
								'tokens'    => explode(' ', strip_tags(html_entity_decode($ag['name'] . ' ' . $a['name'], ENT_QUOTES, 'UTF-8'))),
								'group'     => $ag['name'],
								'group_name'=> $ag['name'],
								'id'        => $a['attribute_id'],
								'full_name' => $name
							);
						}
					}
					break;
				case 'name':
				case 'model':
				case 'sku':
				case 'upc':
				case 'ean':
				case 'jan':
				case 'isbn':
				case 'mpn':
				case 'location':
				case 'seo':
					$results = array();

					$this->load->model('catalog/product_ext');

					if (isset($this->request->get['query'])) {
						$results = $this->model_catalog_product_ext->filterKeywords($this->request->get['type'], $this->request->get['query']);
					}

					foreach ($results as $result) {
						$result = html_entity_decode($result, ENT_QUOTES, 'UTF-8');
						$response[] = array(
							'value'     => $result,
							'tokens'    => explode(' ', $result),
						);
					}
					break;
				case 'options':
					$this->load->language('catalog/option');

					$this->load->model('catalog/option');

					$this->load->model('tool/image');

					$results = array();

					if (isset($this->request->get['query'])) {
						if (is_array($this->request->get['query']) && isset($this->request->get['multiple'])) {
							// TODO: if needed
						} else {
							$filter_data = array(
								'filter_name' => $this->request->get['query']
							);

							$results = $this->model_catalog_option->getOptions($filter_data);
						}
					} else {
						// TODO: if needed
					}

					foreach ($results as $option) {
						$option_value_data = array();

						if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') {
							$option_values = $this->model_catalog_option->getOptionValues($option['option_id']);

							foreach ($option_values as $option_value) {
								if (is_file(DIR_IMAGE . $option_value['image'])) {
									$image = $this->model_tool_image->resize($option_value['image'], 50, 50);
								} else {
									$image = $this->model_tool_image->resize('no_image.png', 50, 50);
								}

								$option_value_data[] = array(
									'option_value_id' => $option_value['option_value_id'],
									'name'            => strip_tags(html_entity_decode($option_value['name'], ENT_QUOTES, 'UTF-8')),
									'image'           => $image
								);
							}

							$sort_order = array();

							foreach ($option_value_data as $key => $value) {
								$sort_order[$key] = $value['name'];
							}

							array_multisort($sort_order, SORT_ASC, $option_value_data);
						}

						$type = '';

						if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') {
							$type = $this->language->get('text_choose');
						}

						if ($option['type'] == 'text' || $option['type'] == 'textarea') {
							$type = $this->language->get('text_input');
						}

						if ($option['type'] == 'file') {
							$type = $this->language->get('text_file');
						}

						if ($option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
							$type = $this->language->get('text_date');
						}

						$name = strip_tags(html_entity_decode($option['name'], ENT_QUOTES, 'UTF-8'));
						$response[] = array(
							'value'         => $name,
							'tokens'        => explode(' ', strip_tags($name)),
							'category'      => $type,
							'type'          => $option['type'],
							'id'            => $option['option_id'],
							'option_value'  => $option_value_data
						);
					}
					break;
				default:
					break;
			}
		}

		// $response['query_count'] = DB::$query_count;
		// $response['page_time'] = microtime(true) - $this->start_time;

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_enc($response, JSON_UNESCAPED_SLASHES));
	}

	public function load() {
		$this->load->language('catalog/product_ext');

		$response = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateLoadData($this->request->post)) {
			$languages = array();

			$this->load->model('localisation/language');
			$langs = $this->model_localisation_language->getLanguages();
			foreach($langs as $lang) {
				$languages[$lang['language_id']] = $lang;
			}

			$id = $this->request->post['id'];
			$column = $this->request->post['column'];

			$data['aqe_alternate_row_colour'] = $this->config->get('aqe_alternate_row_colour');
			$data['aqe_row_hover_highlighting'] = $this->config->get('aqe_row_hover_highlighting');

			$response['data'] = array();

			switch ($column) {
				case 'tag':
				case 'name':
					$this->load->model('catalog/product');
					$result = $this->model_catalog_product->getProductDescriptions($id);

					foreach($languages as $language_id => $language) {
						$response['data'][] = array(
							'lang'  => $language_id,
							'value' => isset($result[$language_id][$column]) ? html_entity_decode($result[$language_id][$column], ENT_QUOTES, 'UTF-8') : '',
							'title' => $language['name'],
							'image' => 'view/image/flags/' . $language['image']
						);
					}
					$response['success'] = true;
					break;
				case 'seo':
					$this->load->model('catalog/product_ext');
					$result = $this->model_catalog_product_ext->getProductSeoKeywords($id);

					foreach($languages as $language_id => $language) {
						$response['data'][] = array(
							'lang'  => $language_id,
							'value' => isset($result[$language_id]) ? html_entity_decode($result[$language_id], ENT_QUOTES, 'UTF-8') : '',
							'title' => $language['name'],
							'image' => 'view/image/flags/' . $language['image']
						);
					}
					$response['success'] = true;
					break;
				case 'attributes':
					$data['languages'] = $languages;

					$data['text_autocomplete'] = $this->language->get('text_autocomplete');
					$data['entry_attribute'] = $this->language->get('entry_attribute');
					$data['entry_text'] = $this->language->get('entry_text');
					$data['button_add_attribute'] = $this->language->get('button_add_attribute');
					$data['button_remove'] = $this->language->get('button_remove');
					$data['button_remove_all'] = $this->language->get('button_remove_all');
					$data['text_no_records_found'] = $this->language->get('text_no_records_found');

					$data['product_id'] = $id;
					$data['column'] = $column;
					$data['product_attributes'] = array();

					$this->load->model('catalog/product');
					$this->load->model('catalog/attribute');

					$product_attributes = $this->model_catalog_product->getProductAttributes($id);

					foreach ($product_attributes as $product_attr) {
						$attribute_info = $this->model_catalog_attribute->getAttribute($product_attr['attribute_id']);

						if ($attribute_info) {
							$product_attribute = array(
								'attribute_id'  => $product_attr['attribute_id'],
								'name'          => html_entity_decode($attribute_info['name'], ENT_QUOTES, 'UTF-8'),
								'values'        => array()
							);

							foreach ($product_attr['product_attribute_description'] as $language_id => $value) {
								$product_attribute['values'][] = array(
									'value'         => html_entity_decode($value['text'], ENT_QUOTES, 'UTF-8'),
									'language_id'   => $language_id
								);
							}

							$data['product_attributes'][] = $product_attribute;
						}
					}

					$data['typeahead'] = html_entity_decode($this->url->link('catalog/product_ext/filter', 'type=attributes&query=%QUERY' . $this->urlParams(), 'SSL'), ENT_QUOTES, 'UTF-8');

					$response['data'] = $this->load->view('catalog/product_ext_qe_form.tpl', $data);
					$response['title'] = $this->language->get('action_attributes');
					$response['success'] = true;
					break;
				case 'discounts':
					$data['entry_customer_group'] = $this->language->get('entry_customer_group');
					$data['entry_quantity'] = $this->language->get('entry_quantity');
					$data['entry_priority'] = $this->language->get('entry_priority');
					$data['entry_price'] = $this->language->get('entry_price');
					$data['entry_date_start'] = $this->language->get('entry_date_start');
					$data['entry_date_end'] = $this->language->get('entry_date_end');
					$data['button_add_discount'] = $this->language->get('button_add_discount');
					$data['button_remove'] = $this->language->get('button_remove');
					$data['button_remove_all'] = $this->language->get('button_remove_all');
					$data['error_date'] = $this->language->get('error_date');

					$data['product_id'] = $id;
					$data['column'] = $column;

					$this->load->model('sale/customer_group');
					$this->load->model('catalog/product');

					$data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
					$data['product_discounts'] = $this->model_catalog_product->getProductDiscounts($id);

					$response['data'] = $this->load->view('catalog/product_ext_qe_form.tpl', $data);
					$response['title'] = $this->language->get('action_discounts');
					$response['success'] = true;
					break;
				case 'specials':
					$data['entry_customer_group'] = $this->language->get('entry_customer_group');
					$data['entry_priority'] = $this->language->get('entry_priority');
					$data['entry_price'] = $this->language->get('entry_price');
					$data['entry_date_start'] = $this->language->get('entry_date_start');
					$data['entry_date_end'] = $this->language->get('entry_date_end');
					$data['button_add_special'] = $this->language->get('button_add_special');
					$data['button_remove'] = $this->language->get('button_remove');
					$data['button_remove_all'] = $this->language->get('button_remove_all');
					$data['error_date'] = $this->language->get('error_date');

					$data['product_id'] = $id;
					$data['column'] = $column;

					$this->load->model('sale/customer_group');
					$this->load->model('catalog/product');

					$data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
					$data['product_specials'] = $this->model_catalog_product->getProductSpecials($id);

					$response['data'] = $this->load->view('catalog/product_ext_qe_form.tpl', $data);
					$response['title'] = $this->language->get('action_specials');
					$response['success'] = true;
					break;
				case 'filters':
					$data['text_select_filter'] = $this->language->get('text_select_filter');

					$data['product_id'] = $id;
					$data['column'] = $column;

					$this->load->model('catalog/filter');
					$this->load->model('catalog/product');

					$results = $this->cache->get('filters.all');

					if ($results === false) {
						$results = $this->model_catalog_filter->getFiltersByGroup();
						$this->cache->set('filters.all', $results);
					}

					$data['filters'] = $results;
					$data['product_filters'] = $this->model_catalog_product->getProductFilters($id);

					$response['data'] = $this->load->view('catalog/product_ext_qe_form.tpl', $data);
					$response['title'] = $this->language->get('action_filters');
					$response['success'] = true;
					break;
				case 'recurrings':
					$data['entry_recurring'] = $this->language->get('entry_recurring');
					$data['entry_customer_group'] = $this->language->get('entry_customer_group');
					$data['button_add_recurring'] = $this->language->get('button_add_recurring');
					$data['button_remove'] = $this->language->get('button_remove');
					$data['button_remove_all'] = $this->language->get('button_remove_all');

					$data['product_id'] = $id;
					$data['column'] = $column;

					$this->load->model('catalog/recurring');
					$this->load->model('catalog/product');
					$this->load->model('sale/customer_group');

					$data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
					$data['recurrings'] = $this->model_catalog_recurring->getRecurrings();
					$data['product_recurrings'] = $this->model_catalog_product->getRecurrings($id);

					$response['data'] = $this->load->view('catalog/product_ext_qe_form.tpl', $data);
					$response['title'] = $this->language->get('action_recurrings');
					$response['success'] = true;
					break;
				case 'related':
					$data['text_autocomplete'] = $this->language->get('text_autocomplete');

					$data['product_id'] = $id;
					$data['column'] = $column;

					$data['token'] = $this->session->data['token'];
					$data['filter'] = html_entity_decode($this->url->link('catalog/product_ext/filter', '', 'SSL'), ENT_QUOTES, 'UTF-8');

					$this->load->model('catalog/product');

					$results = $this->model_catalog_product->getProductRelated($id);
					$data['product_related'] = array();

					foreach ($results as $product_id) {
						$related_info = $this->model_catalog_product->getProduct($product_id);

						if ($related_info) {
							$data['product_related'][$product_id] = array(
								'product_id' => $related_info['product_id'],
								'name'       => html_entity_decode($related_info['name'], ENT_QUOTES, 'UTF-8'),
								'model'      => $related_info['model']
							);
						}
					}

					$response['data'] = $this->load->view('catalog/product_ext_qe_form.tpl', $data);
					$response['title'] = $this->language->get('action_related');
					$response['success'] = true;
					break;
				case 'descriptions':
					$data['entry_description'] = $this->language->get('entry_description');
					$data['entry_meta_title'] = $this->language->get('entry_meta_title');
					$data['entry_meta_description'] = $this->language->get('entry_meta_description');
					$data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
					$data['error_meta_title'] = $this->language->get('error_meta_title');

					$data['default_language'] = $this->config->get('config_language_id');

					$data['product_id'] = $id;
					$data['column'] = $column;

					$this->load->model('localisation/language');
					$this->load->model('catalog/product');

					$data['languages'] = $this->model_localisation_language->getLanguages();
					$data['product_description'] = array();
					$description = $this->model_catalog_product->getProductDescriptions($id);
					foreach ($description as $key => $value) {
						$value['description'] = html_entity_decode($value['description']);
						$data['product_description'][$key] = $value;
					}

					$response['data'] = $this->load->view('catalog/product_ext_qe_form.tpl', $data);
					$response['title'] = $this->language->get('action_descriptions');
					$response['success'] = true;
					break;
				case 'images':
					$additional_image_width = 100;
					$additional_image_height = 100;

					$data['button_add_image'] = $this->language->get('button_add_image');
					$data['button_remove'] = $this->language->get('button_remove');
					$data['button_remove_all'] = $this->language->get('button_remove_all');
					$data['entry_image'] = $this->language->get('entry_image');
					$data['entry_sort_order'] = $this->language->get('entry_sort_order');

					$data['product_id'] = $id;
					$data['column'] = $column;

					$this->load->model('catalog/product');
					$this->load->model('tool/image');

					$data['token'] = $this->session->data['token'];
					$data['additional_image_width'] = $additional_image_width;
					$data['additional_image_height'] = $additional_image_height;

					$product_images = $this->model_catalog_product->getProductImages($id);
					$data['no_image'] = $this->model_tool_image->resize('no_image.png', $additional_image_width, $additional_image_height);
					$data['product_images'] = array();

					foreach ($product_images as $product_image) {
						if (is_file(DIR_IMAGE . $product_image['image'])) {
							$image = $product_image['image'];
						} else {
							$image = 'no_image.png';
						}

						$data['product_images'][] = array(
							'image'      => $image,
							'thumb'      => $this->model_tool_image->resize($image, $additional_image_width, $additional_image_height),
							'sort_order' => $product_image['sort_order']
						);
					}

					$response['data'] = $this->load->view('catalog/product_ext_qe_form.tpl', $data);
					$response['title'] = $this->language->get('action_images');
					$response['success'] = true;
					break;
				case 'options':
					$data['text_yes'] = $this->language->get('text_yes');
					$data['text_no'] = $this->language->get('text_no');
					$data['text_plus'] = $this->language->get('text_plus');
					$data['text_minus'] = $this->language->get('text_minus');
					$data['text_autocomplete'] = $this->language->get('text_autocomplete');
					$data['entry_required'] = $this->language->get('entry_required');
					$data['entry_option_value'] = $this->language->get('entry_option_value');
					$data['entry_quantity'] = $this->language->get('entry_quantity');
					$data['entry_subtract'] = $this->language->get('entry_subtract');
					$data['entry_price'] = $this->language->get('entry_price');
					$data['entry_option_points'] = $this->language->get('entry_option_points');
					$data['entry_weight'] = $this->language->get('entry_weight');
					$data['button_add_option'] = $this->language->get('button_add_option');
					$data['button_remove'] = $this->language->get('button_remove');
					$data['button_remove_all'] = $this->language->get('button_remove_all');
					$data['text_no_records_found'] = $this->language->get('text_no_records_found');

					$data['product_id'] = $id;
					$data['column'] = $column;

					$this->load->model('catalog/product');
					$this->load->model('catalog/option');

					$product_options = $this->model_catalog_product->getProductOptions($id);
					$data['product_options'] = array();

					foreach ($product_options as $product_option) {
						$product_option_value_data = array();

						if (isset($product_option['product_option_value'])) {
							foreach ($product_option['product_option_value'] as $product_option_value) {
								$product_option_value_data[] = array(
									'product_option_value_id' => $product_option_value['product_option_value_id'],
									'option_value_id'         => $product_option_value['option_value_id'],
									'quantity'                => $product_option_value['quantity'],
									'subtract'                => $product_option_value['subtract'],
									'price'                   => $product_option_value['price'],
									'price_prefix'            => $product_option_value['price_prefix'],
									'points'                  => $product_option_value['points'],
									'points_prefix'           => $product_option_value['points_prefix'],
									'weight'                  => $product_option_value['weight'],
									'weight_prefix'           => $product_option_value['weight_prefix']
								);
							}
						}

						$data['product_options'][] = array(
							'product_option_id'    => $product_option['product_option_id'],
							'product_option_value' => $product_option_value_data,
							'option_id'            => $product_option['option_id'],
							'name'                 => html_entity_decode($product_option['name'], ENT_QUOTES, 'UTF-8'),
							'type'                 => $product_option['type'],
							'value'                => isset($product_option['value']) ? html_entity_decode($product_option['value'], ENT_QUOTES, 'UTF-8') : '',
							'required'             => $product_option['required']
						);
					}

					$data['option_values'] = array();

					foreach ($data['product_options'] as $product_option) {
						if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
							if (!isset($data['option_values'][$product_option['option_id']])) {
								$data['option_values'][$product_option['option_id']] = $this->model_catalog_option->getOptionValues($product_option['option_id']);
							}
						}
					}

					$data['typeahead'] = html_entity_decode($this->url->link('catalog/product_ext/filter', 'type=options&query=%QUERY' . $this->urlParams(), 'SSL'), ENT_QUOTES, 'UTF-8');

					$response['data'] = $this->load->view('catalog/product_ext_qe_form.tpl', $data);
					$response['title'] = $this->language->get('action_options');
					$response['success'] = true;
					break;
				default:
					$this->alert['error']['load'] = $this->language->get('error_load_data');
					break;
			}
		}
		$response = array_merge($response, array("errors" => $this->error), array("alerts" => $this->alert));

		$response['query_count'] = DB::$query_count;
		$response['page_time'] = microtime(true) - $this->start_time;

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_enc($response, JSON_UNESCAPED_SLASHES));
	}

	public function reload() {
		$this->load->language('catalog/product_ext');

		$response = array('success' => false);

		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateReloadData($this->request->post)) {
			$response['values'] = array();

			foreach ($this->request->post['data'] as $column => $products) {
				foreach ($products as $id) {
					switch ($column) {
						case 'price':
							$this->load->model('catalog/product');

							$product = $this->model_catalog_product->getProduct($id);

							$response['values'][$id][$column] = $product['price']; // sprintf('%.4f',round((float)$product['price'], 4)); // yym_custom

							$special = false;
							$product_specials = $this->model_catalog_product->getProductSpecials($id);

							foreach ($product_specials  as $product_special) {
								if (($product_special['date_start'] == '0000-00-00' || strtotime($product_special['date_start']) < time()) && ($product_special['date_end'] == '0000-00-00' || strtotime($product_special['date_end']) > time())) {
									$special = $product_special['price'];
									break;
								}
							}

							if ($special) {
								$response['values'][$id]['special'] = $special; // sprintf('%.4f',round((float)$special, 4)); // yym_custom
							} else {
								$response['values'][$id]['special'] = null;
							}
							$response['success'] = true;
							break;
						case 'filter':
							$this->load->model('catalog/product');

							$_filters = $this->cache->get('filters.all');

							if ($_filters === false) {
								$this->load->model('catalog/filter');
								$_filters = $this->model_catalog_filter->getFiltersByGroup();
								$this->cache->set('filters.all', $_filters);
							}

							$product_filters = $this->model_catalog_product->getProductFilters($id);

							$filters = array();

							foreach ($_filters as $fg) {
								foreach ($fg['filters'] as $filter) {
									if (in_array($filter['filter_id'], (array)$product_filters))
										$filters[] = array('id' => (int)$filter['filter_id'], 'text' => strip_tags(html_entity_decode($fg['name'] . ' &gt; ' . $filter['name'], ENT_QUOTES, 'UTF-8')));
								}
							}
							$response['values'][$id][$column] = $product_filters;
							$response['values'][$id]['filter_data'] = $filters;
							$response['success'] = true;
							break;
						case 'filters':
							$this->load->model('catalog/product');

							$product_filters = $this->model_catalog_product->getProductFilters($id);

							$response['values'][$id]['filters_exist'] = (int)$this->config->get('aqe_highlight_actions') * count($product_filters);
							$response['success'] = true;
							break;
						default:
							$this->alert['error']['load'] = $this->language->get('error_load_data');
							break;
					}
				}
			}
		}

		$response = array_merge($response, array("errors" => $this->error), array("alerts" => $this->alert));

		$response['query_count'] = DB::$query_count;
		$response['page_time'] = microtime(true) - $this->start_time;

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_enc($response, JSON_UNESCAPED_SLASHES));
	}

	public function update() {
		$this->load->language('catalog/product_ext');

		$this->load->model('catalog/product_ext');

		$response = array('success' => false);

		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateUpdateData($this->request->post)) {
			$id = (array)$this->request->post['id'];
			$column = $this->request->post['column'];
			$value = $this->request->post['value'];
			$lang_id = $this->config->get('config_language_id');
//			$expression = strpos(trim($value), "#") === 0 && preg_match('/^#\s*(?P<operator>[+-\/\*])\s*(?P<operand>-?\d+\.?\d*)(?P<percent>%)?$/', trim($value)) === 1;
            // yym_custom: quickfix for product quick edit variable type
			$expression = (gettype($value) != 'array') ? strpos(trim($value), "#") === 0 && preg_match('/^#\s*(?P<operator>[+-\/\*])\s*(?P<operand>-?\d+\.?\d*)(?P<percent>%)?$/', trim($value)) === 1 : true;

			if (isset($this->request->post['ids'])) {
				$id = array_unique(array_merge($id, (array)$this->request->post['ids']));
			}

			$results = array('done' => array(), 'failed' => array());
			$_results = array();

			foreach ($id as $_id) {
				$result = $this->model_catalog_product_ext->quickEditProduct($_id, $column, $value, $this->request->post);
				if ($result !== false) {
					$_results[$_id] = $result;
					$results['done'][] = $_id;
				} else {
					$results['failed'][] = $_id;
				}
			}

			if ($results['done']) {
				$response['success'] = true;

				if (count($results['done']) > 1) {
					$this->alert['success']['updated'] = sprintf($this->language->get('text_success_update_count'), $this->language->get('column_' . $this->request->post['column']), count($results['done']));
				} else {
					$this->alert['success']['updated'] = sprintf($this->language->get('text_success_update'), $this->language->get('column_' . $this->request->post['column']));
				}
				if ($column == 'discounts') {
					$this->load->model('catalog/product');
					$product_discounts = $this->model_catalog_product->getProductDiscounts($results['done'][0]);
					$response['value'] = (int)$this->config->get('aqe_highlight_actions') * count($product_discounts);
				} else if ($column == 'specials') {
					$this->load->model('catalog/product');
					$product_specials = $this->model_catalog_product->getProductSpecials($results['done'][0]);
					$response['value'] = (int)$this->config->get('aqe_highlight_actions') * count($product_specials);
				} else if ($column == 'descriptions') {
					$this->load->model('catalog/product');
					$product_descriptions = $this->model_catalog_product->getProductDescriptions($results['done'][0]);
					$response['value'] = (int)$this->config->get('aqe_highlight_actions') * count($product_descriptions);
				} else if ($column == 'related') {
					$this->load->model('catalog/product');
					$product_related = $this->model_catalog_product->getProductRelated($results['done'][0]);
					$response['value'] = (int)$this->config->get('aqe_highlight_actions') * count($product_related);
				} else if ($column == 'filters') {
					$this->load->model('catalog/product');
					$product_filters = $this->model_catalog_product->getProductFilters($results['done'][0]);
					$response['value'] = (int)$this->config->get('aqe_highlight_actions') * count($product_filters);
				} else if ($column == 'recurrings') {
					$this->load->model('catalog/product');
					$product_recurrings = $this->model_catalog_product->getRecurrings($results['done'][0]);
					$response['value'] = (int)$this->config->get('aqe_highlight_actions') * count($product_recurrings);
				} else if ($column == 'attributes') {
					$this->load->model('catalog/product');
					$product_attributes = $this->model_catalog_product->getProductAttributes($results['done'][0]);
					$response['value'] = (int)$this->config->get('aqe_highlight_actions') * count($product_attributes);
				} else if ($column == 'images') {
					$this->load->model('catalog/product');
					$product_images = $this->model_catalog_product->getProductImages($results['done'][0]);
					$response['value'] = (int)$this->config->get('aqe_highlight_actions') * count($product_images);
				} else if ($column == 'options') {
					$this->load->model('catalog/product');
					$product_options = $this->model_catalog_product->getProductOptions($results['done'][0]);
					$response['value'] = (int)$this->config->get('aqe_highlight_actions') * count($product_options);
				} else if (in_array($column, array('sort_order', 'points', 'minimum', 'viewed', 'quantity'))) {
					if ($expression) {
						$response['value'] = (int)$_results[$id[0]];
						if (count($id) > 1) {
							foreach ($id as $_id) {
								$response['values'][$_id][$column] = (int)$_results[$_id];
							}
						}
					} else {
						$response['value'] = (int)$value;
					}
				} else if (in_array($column, array('subtract', 'shipping'))) {
					$response['value'] = (int)$value;
					$response['values']['*'][$column . '_text'] = ((int)$value) ? $this->language->get('text_yes') : $this->language->get('text_no');
				} else if ($column == 'status') {
					$response['value'] = (int)$value;
					$response['values']['*']['status_text'] = ((int)$value) ? $this->language->get('text_enabled') : $this->language->get('text_disabled');
					if ($this->config->get('aqe_highlight_status')) {
						$response['values']['*']['status_class'] = (int)$value ? 'success' : 'danger';
					}
				} else if (in_array($column, array('weight', 'length', 'width', 'height'))) {
					if ($expression) {
						$response['value'] = sprintf('%.4f',round((float)$_results[$id[0]], 4));
						if (count($id) > 1) {
							foreach ($id as $_id) {
								$response['values'][$_id][$column] = sprintf('%.4f',round((float)$_results[$_id], 4));
							}
						}
					} else {
						$response['value'] = sprintf('%.4f',round((float)$value, 4));
					}
				} else if ($column == 'image') {
					$this->load->model('tool/image');

					$w = (int)$this->config->get('aqe_list_view_image_width');
					$h = (int)$this->config->get('aqe_list_view_image_height');

					if (is_file(DIR_IMAGE . $value)) {
						$image = $this->model_tool_image->resize($value, $w, $h);
					} else {
						$image = $this->model_tool_image->resize('no_image.png', $w, $h);
					}

					$response['value'] = $value;
					$response['values']['*']['image_thumb'] = $image;
				} else if ($column == 'date_available') {
					$date = new DateTime($value);
					$response['value'] = $value;
					// $response[$column . '_text'] = $date->format($this->language->get('date_format_short'));
					$response['values']['*'][$column . '_text'] = $date->format('Y-m-d');
				} else if (in_array($column, array('date_added', 'date_modified'))) {
					$date = new DateTime($value);
					$response['value'] = $value;
					// $response[$column . '_text'] = $date->format($this->language->get('date_format_short') . ' ' . $this->language->get('time_format'));
					$response['values']['*'][$column . '_text'] = $date->format('Y-m-d H:i:s');
				} else if ($column == 'tax_class') {
					$this->load->model('localisation/tax_class');
					$tax_class = $this->model_localisation_tax_class->getTaxClass((int)$value);
					if ($tax_class) {
						$response['value'] = (int)$tax_class['tax_class_id'];
						$response['values']['*']['tax_class_text'] = $tax_class['title'];
					} else {
						$response['value'] = '';
						$response['values']['*']['tax_class_text'] = '';
					}
				} else if ($column == 'stock_status') {
					$this->load->model('localisation/stock_status');
					$stock_status = $this->model_localisation_stock_status->getStockStatus((int)$value);
					if ($stock_status) {
						$response['value'] = (int)$stock_status['stock_status_id'];
						$response['values']['*']['stock_status_text'] = $stock_status['name'];
					} else {
						$response['value'] = '';
						$response['values']['*']['stock_status_text'] = '';
					}
				} else if ($column == 'length_class') {
					$this->load->model('localisation/length_class');
					$length_class = $this->model_localisation_length_class->getLengthClass((int)$value);
					if ($length_class) {
						$response['value'] = (int)$length_class['length_class_id'];
						$response['values']['*']['length_class_text'] = $length_class['title'];
					} else {
						$response['value'] = '';
						$response['values']['*']['length_class_text'] = '';
					}
				} else if ($column == 'weight_class') {
					$this->load->model('localisation/weight_class');
					$weight_class = $this->model_localisation_weight_class->getWeightClass((int)$value);
					if ($weight_class) {
						$response['value'] = (int)$weight_class['weight_class_id'];
						$response['values']['*']['weight_class_text'] = $weight_class['title'];
					} else {
						$response['value'] = '';
						$response['values']['*']['weight_class_text'] = '';
					}
				} else if ($column == 'manufacturer') {
					$this->load->model('catalog/manufacturer');
					$manufacturer = $this->model_catalog_manufacturer->getManufacturer((int)$value);
					if ($manufacturer) {
						$response['value'] = (int)$manufacturer['manufacturer_id'];
						$response['values']['*']['manufacturer_text'] = $manufacturer['name'];
					} else {
						$response['value'] = 0;
						$response['values']['*']['manufacturer_text'] = '';
					}
				} else if (in_array($column, array('name', 'tag', 'seo'))) {
					if (is_array($value)) {
						$response['value'] = '';
						foreach ((array)$value as $v) {
							if ($v['lang'] == $lang_id) {
								$response['value'] = $v['value'];
							}
						}
					} else {
						$response['value'] = $value;
					}
				} else if ($column == 'category') {
					$_categories = $this->cache->get('category.all');

					if ($_categories === false) {
						$this->load->model('catalog/category');
						$_categories = $this->model_catalog_category->getCategories(array());
						$this->cache->set('category.all', $_categories);
					}

					$categories = array();

					foreach ($_categories as $category) {
						if (in_array($category['category_id'], (array)$value))
							$categories[] = array('id' => (int)$category['category_id'], 'text' => $category['name']);
					}
					$response['value'] = $value;
					$response['values']['*']['category_data'] = $categories;
				} else if ($column == 'store') {
					$this->load->model('setting/store');
					$__stores = $this->model_setting_store->getStores(array());

					$_stores = array(
						'0' => array(
							'store_id'  => 0,
							'name'      => $this->config->get('config_name'),
							'url'       => HTTP_CATALOG
						)
					);

					foreach ($__stores as $store) {
						$_stores[$store['store_id']] = array(
							'store_id'  => $store['store_id'],
							'name'      => $store['name'],
							'url'       => $store['url']
						);
					}

					$stores = array();

					foreach ($_stores as $store) {
						if ($value && in_array($store['store_id'], (array)$value)) {
							$stores[] = array('id' => (int)$store['store_id'], 'text' => $store['name']);
						}
					}
					$response['value'] = $value;
					$response['values']['*']['store_data'] = $stores;
				} else if ($column == 'filter') {
					$_filters = $this->cache->get('filters.all');

					if ($_filters === false) {
						$this->load->model('catalog/filter');
						$_filters = $this->model_catalog_filter->getFiltersByGroup();
						$this->cache->set('filters.all', $_filters);
					}

					$filters = array();

					foreach ($_filters as $fg) {
						foreach ($fg['filters'] as $filter) {
							if (in_array($filter['filter_id'], (array)$value))
								$filters[] = array('id' => (int)$filter['filter_id'], 'text' => strip_tags(html_entity_decode($fg['name'] . ' &gt; ' . $filter['name'], ENT_QUOTES, 'UTF-8')));
						}
					}
					$response['value'] = $value;
					$response['values']['*']['filter_data'] = $filters;
				} else if ($column == 'download') {
					$_downloads = $this->cache->get('downloads.all');

					if ($_downloads === false) {
						$this->load->model('catalog/download');
						$_downloads = $this->model_catalog_download->getDownloads(array());
						$this->cache->set('downloads.all', $_downloads);
					}

					$downloads = array();

					foreach ($_downloads as $download) {
						if (in_array($download['download_id'], (array)$value))
							$downloads[] = array('id' => (int)$download['download_id'], 'text' => $download['name']);
					}
					$response['value'] = $value;
					$response['values']['*']['download_data'] = $downloads;
				} else if ($column == 'price') {
					$this->load->model('catalog/product');

					if ($expression) {
						$response['value'] = $_results[$id[0]]; // sprintf('%.4f',round((float)$_results[$id[0]], 4)); // yym_custom
					} else {
						$response['value'] = $value; // sprintf('%.4f',round((float)$value, 4)); // yym_custom
					}

					foreach ($id as $_id) {
						$special = false;
						$product_specials = $this->model_catalog_product->getProductSpecials($_id);
						foreach ($product_specials  as $product_special) {
							if (($product_special['date_start'] == '0000-00-00' || strtotime($product_special['date_start']) < time()) && ($product_special['date_end'] == '0000-00-00' || strtotime($product_special['date_end']) > time())) {
								$special = $product_special['price'];
								break;
							}
						}
						if ($special) {
							$response['values'][$_id]['special'] = $special; // sprintf('%.4f',round((float)$special, 4)); // yym_custom
						}
						$response['values'][$_id][$column] = $_results[$_id]; // sprintf('%.4f',round((float)$_results[$_id], 4)); // yym_custom
					}
				} else
					$response['value'] = $value;
			} else {
				$this->alert['error']['update'] = $this->language->get('error_update');
				// $response['msg'] = $this->language->get('error_update');
			}

			$response['results'] = $results;

			if ($results['failed']) {
				$this->load->model('catalog/product');

				$failed_products = array();

				foreach ($results['failed'] as $_id) {
					$product = $this->model_catalog_product->getProduct($_id);
					if ($product) {
						$failed_products[] = $product['name'];
					}
				}

				$this->alert['warning']['update'] = sprintf($this->language->get('text_error_update'), implode(', ', $failed_products));
				// $response['alerts']['warning']['error_update'] = sprintf($this->language->get('text_error_update'), implode(', ', $failed_products));
			}
		}

		$response = array_merge($response, array("errors" => $this->error), array("alerts" => $this->alert));

		$response['query_count'] = DB::$query_count;
		$response['page_time'] = microtime(true) - $this->start_time;

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_enc($response, JSON_UNESCAPED_SLASHES));
	}

	/* Event hooks */
	public function clear_products_cache() {
		$this->cache->delete('pqe.products');
	}

	public function clear_manufacturers_cache() {
		$this->cache->delete('manufacturers');
	}

	public function clear_downloads_cache() {
		$this->cache->delete('downloads');
	}

	public function clear_filters_cache() {
		$this->cache->delete('filters');
	}

	public function clear_cache() {
		$ajax_request = isset($this->request->server['HTTP_X_REQUESTED_WITH']) && !empty($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

		$this->load->language('catalog/product_ext');

		$response = array();

		if ($this->validateAction("clear_cache")) {
			$this->cache->delete('pqe.products');
			$this->cache->delete('manufacturers');
			$this->cache->delete('downloads');
			$this->cache->delete('filters');

			if ($ajax_request) {
				$this->alert['success']['cache_cleared'] = $this->language->get('text_success_clear_cache');
			} else {
				$this->session->data['success'] = $this->language->get('text_success_clear_cache');
			}
		} else {
			if (!$ajax_request) {
				$this->session->data['errors'] = $this->error;
				$this->session->data['alerts'] = $this->alert;
			}
		}

		if ($ajax_request) {
			$response = array_merge($response, array("errors" => $this->error), array("alerts" => $this->alert));

			$response['query_count'] = DB::$query_count;
			$response['page_time'] = microtime(true) - $this->start_time;

			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_enc($response, JSON_UNESCAPED_SLASHES));
		} else {
			$url = $this->urlParams();
			$this->response->redirect($this->url->link('catalog/product_ext', 'dTc=1' . $url, 'SSL'));
		}
	}

	protected function validatePermission() {
		$errors = false;

		if (!$this->user->hasPermission('modify', 'catalog/product_ext')) {
			$errors = true;
			$this->alert['error']['permission'] = $this->language->get('error_permission');
		}

		return !$errors;
	}

	protected function validateAction($action) {
		return $this->validatePermission();
	}

	protected function validateLoadData($data) {
		$errors = !$this->validatePermission();

		if (!isset($data['id']) || !isset($data['column'])) {
			$errors = true;
			$this->alert['error']['update'] = $this->language->get('error_update');
		}

		return !$errors;
	}

	protected function validateReloadData($data) {
		$errors = !$this->validatePermission();

		if (!isset($data['data'])) {
			$errors = true;
			$this->alert['error']['update'] = $this->language->get('error_update');
		}

		return !$errors;
	}

	protected function validateUpdateData($data) {
		$errors = !$this->validatePermission();

		if (!isset($data['id']) || !isset($data['column']) || !isset($data['value']) || !isset($data['old'])) {
			$errors = true;
			$this->alert['error']['update'] = $this->language->get('error_update');
		}

		$id = $data['id'];
		$column = $data['column'];
		$value = $data['value'];

		if (in_array($column, array('quantity', 'sort_order', 'minimum', 'points', 'viewed', 'price', 'length', 'width', 'height', 'weight')) && strpos(trim($value), "#") === 0 && preg_match('/^#\s*(?P<operation>[+-\/\*])\s*(?P<operand>-?\d+\.?\d*)(?P<percent>%)?$/', trim($value)) !== 1) {
			$errors = true;
			$this->error['error'] = $this->language->get('error_expression');
		}

		if ($column == 'model' && (utf8_strlen($value) < 1 || utf8_strlen($value) > 64)) {
			$errors = true;
			$this->error['error'] = $this->language->get('error_model');
		}

		if (in_array($column, array('name', 'tag'))) {
			foreach ((array)$value as $v) {
				if (!isset($v['value']) || !isset($v['lang'])) {
					$errors = true;
					$this->error['error'] = $this->language->get('error_update');
				} else {
					if ($column == 'name' && (utf8_strlen($v['value']) < 1 || utf8_strlen($v['value']) > 255)) {
						$errors = true;
						$this->error['value'][] = array('lang' => $v['lang'], 'text' => $this->language->get('error_name'));
					}
				}
			}
		}

		if ($column == 'seo') {
			if (isset($data['ids'])) {
				$errors = true;
				$this->error['error'] = $this->language->get('error_batch_edit_seo');
			} else {
				$multilingual_seo = $this->config->get('aqe_multilingual_seo');

				if ($multilingual_seo) {
					foreach ((array)$value as $v) {
						if (!isset($v['value']) || !isset($v['lang'])) {
							$errors = true;
							$this->error['error'] = $this->language->get('error_update');
						} else {
							if ($this->model_catalog_product_ext->urlAliasExists($id, utf8_decode($v['value']), $v['lang'])) {
								$errors = true;
								$this->error['value'][] = array('lang' => $v['lang'], 'text' => $this->language->get('error_duplicate_seo_keyword'));
							}
						}
					}
				} else {
					if ($this->model_catalog_product_ext->urlAliasExists($id, utf8_decode($value))) {
						$errors = true;
						$this->error['error'] = $this->language->get('error_duplicate_seo_keyword');
					}
				}
			}
		}

		if ($column == 'date_available') {
			if (!validate_date($value, 'Y-m-d')) {
				$errors = true;
				$this->error['error'] = $this->language->get('error_date');
			}
		}

		if ($column == 'date_added') {
			if (!validate_date($value, 'Y-m-d H:i:s')) {
				$errors = true;
				$this->error['error'] = $this->language->get('error_datetime');
			}
		}

		if ($column == 'discounts' && isset($value['product_discount'])) {
			foreach ((array)$value['product_discount'] as $idx => $v) {
				if ($v['date_start'] != "" && !validate_date($v['date_start'], 'Y-m-d')) {
					$errors = true;
					$this->error['discounts'][$idx]['date_start'] = $this->language->get('error_date');
				}
				if ($v['date_end'] != "" && !validate_date($v['date_end'], 'Y-m-d')) {
					$errors = true;
					$this->error['discounts'][$idx]['date_end'] = $this->language->get('error_date');
				}
			}
		}

		if ($column == 'specials' && isset($value['product_special'])) {
			foreach ((array)$value['product_special'] as $idx => $v) {
				if ($v['date_start'] != "" && !validate_date($v['date_start'], 'Y-m-d')) {
					$errors = true;
					$this->error['specials'][$idx]['date_start'] = $this->language->get('error_date');
				}
				if ($v['date_end'] != "" && !validate_date($v['date_end'], 'Y-m-d')) {
					$errors = true;
					$this->error['specials'][$idx]['date_end'] = $this->language->get('error_date');
				}
			}
		}

		if ($column == 'recurrings' && isset($value['product_recurring'])) {
			foreach ((array)$value['product_recurring'] as $idx => $v) {
				if (!isset($v['recurring_id']) || $v['recurring_id'] == "") {
					$errors = true;
					$this->error['recurrings'][$idx]['recurring_id'] = $this->language->get('error_recurring');
				}
			}
		}

		if ($column == 'attributes' && isset($value['product_attribute'])) {
			foreach ((array)$value['product_attribute'] as $idx => $v) {
				if (!isset($v['attribute_id']) || $v['attribute_id'] == "") {
					$errors = true;
					$this->error['attributes'][$idx]['id'] = $this->language->get('error_attribute');
				}
			}
		}

		if ($column == 'descriptions') {
			foreach ((array)$value['product_description'] as $language_id => $v) {
				if (utf8_strlen($v['meta_title']) < 2 || utf8_strlen($v['meta_title']) > 255) {
					$errors = true;
					$this->error['descriptions'][$language_id]['meta_title'] = $this->language->get('error_meta_title');
				}
			}
		}

		if ($errors && empty($this->alert['warning']['warning'])) {
			$this->alert['warning']['warning'] = $this->language->get('error_warning');
		}

		return !$errors;
	}

	protected function validateSettings(&$data) {
		$errors = !$this->validatePermission();

		if (isset($data['aqe_items_per_page'])) {
			if (!is_numeric($data['aqe_items_per_page'])) {
				$errors = true;
				$this->error['aqe_items_per_page'] = $this->language->get('error_numeric');
			} else if ((int)$data['aqe_items_per_page'] < -1 || (int)$data['aqe_items_per_page'] == 0) {
				$errors = true;
				$this->error['aqe_items_per_page'] = $this->language->get('error_items_per_page');
			}
		}

		if (isset($data['aqe_list_view_image_width'])) {
			if (!is_numeric($data['aqe_list_view_image_width']) || (int)$data['aqe_list_view_image_width'] < 1) {
				$errors = true;
				$this->error['aqe_list_view_image_width'] = $this->language->get('error_image_width');
			}
		}

		if (isset($data['aqe_list_view_image_height'])) {
			if (!is_numeric($data['aqe_list_view_image_height']) || (int)$data['aqe_list_view_image_height'] < 1) {
				$errors = true;
				$this->error['aqe_list_view_image_height'] = $this->language->get('error_image_height');
			}
		}

		if (isset($data['aqe_cache_size'])) {
			if (!is_numeric($data['aqe_cache_size']) || (int)$data['aqe_cache_size'] < 1 || (int)$data['aqe_cache_size'] < (int)$this->config->get('aqe_items_per_page')) {
				$errors = true;
				$this->error['aqe_cache_size'] = $this->language->get('error_cache_size');
			}
		}

		if ($errors && empty($this->alert['warning']['warning'])) {
			$this->alert['warning']['warning'] = $this->language->get('error_warning');
		}

		return !$errors;
	}

	protected function urlParams() {
		$url = '&token=' . $this->session->data['token'];

		return $url;
	}
}
