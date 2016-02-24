<?php
class ControllerCatalogAttributeImage extends Controller {
	private $error = array();

	public function index() {

		$query = $this->db->query("SHOW TABLES LIKE '".DB_PREFIX."attribute_image'");

		if($query->num_rows == 0) {
				$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "attribute_image` (
				  `attribute_image_id` int(11) NOT NULL AUTO_INCREMENT,
				  `attribute_image_group_id` int(11) NOT NULL,
				  `sort_order` int(3) NOT NULL,
				  `added_image` text,
				  PRIMARY KEY (`attribute_image_id`)
				);");

				$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "attribute_image_description` (
				  `attribute_image_id` int(11) NOT NULL,
				  `language_id` int(11) NOT NULL,
				  `name` varchar(64) NOT NULL,
				  PRIMARY KEY (`attribute_image_id`,`language_id`)
				);");

				$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "attribute_image_group` (
				  `attribute_image_group_id` int(11) NOT NULL AUTO_INCREMENT,
				  `sort_order` int(3) NOT NULL,
				  PRIMARY KEY (`attribute_image_group_id`)
				);");

				$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "attribute_image_group_description` (
				  `attribute_image_group_id` int(11) NOT NULL,
				  `language_id` int(11) NOT NULL,
				  `name` varchar(64) NOT NULL,
				  PRIMARY KEY (`attribute_image_group_id`,`language_id`)
				);");

				$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_attribute_image` (
				  `product_id` int(11) NOT NULL,
				  `attribute_image_id` int(11) NOT NULL,
				  PRIMARY KEY (`product_id`,`attribute_image_id`)
				);");

				$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` (`store_id`, `group`, `key`, `value`, `serialized`) VALUES
(0, 'config', 'config_attribute_image_css', '.image_attributes {padding-bottom:20px}\r\nul#image_attributes,\r\nul#image_attributes li {\r\n/* Setting a common base */\r\nmargin: 0;\r\npadding: 0;\r\n}\r\n\r\nul#image_attributes li {\r\ndisplay: inline-block;\r\nwidth: 60px;\r\nmin-height: 60px;\r\nvertical-align: top;\r\ntext-align:center;\r\n \r\n/* For IE 7 */\r\nzoom: 1;\r\n*display: inline;\r\n}', 0),
(0, 'config', 'config_attribute_image_height', '50', 0),
(0, 'config', 'config_attribute_image_width', '50', 0);");

		}

		$this->load->language('catalog/attribute_image');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/attribute_image');

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/attribute_image');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/attribute_image');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_attribute_image->addAttributeImage($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/attribute_image', 'token=' . $this->session->data['token'] . $url, 'SSL'));

		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/attribute_image');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/attribute_image');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_attribute_image->editAttributeImage($this->request->get['attribute_image_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/attribute_image', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/attribute_image');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/attribute_image');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $attribute_image_id) {
				$this->model_catalog_attribute_image->deleteAttributeImage($attribute_image_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/attribute_image', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'aid.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/attribute_image', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['insert'] = $this->url->link('catalog/attribute_image/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('catalog/attribute_image/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['attribute_images'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$attribute_image_total = $this->model_catalog_attribute_image->getTotalAttributeImages();

		$results = $this->model_catalog_attribute_image->getAttributeImages($filter_data);

		foreach ($results as $result) {
			$data['attribute_images'][] = array(
				'attribute_image_id'    => $result['attribute_image_id'],
				'name'            => $result['name'],
				'attribute_image_group' => $result['attribute_image_group'],
				'sort_order'      => $result['sort_order'],
				'edit'            => $this->url->link('catalog/attribute_image/edit', 'token=' . $this->session->data['token'] . '&attribute_image_id=' . $result['attribute_image_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_attribute_image_group'] = $this->language->get('column_attribute_image_group');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');

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

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('catalog/attribute_image', 'token=' . $this->session->data['token'] . '&sort=aid.name' . $url, 'SSL');
		$data['sort_attribute_image_group'] = $this->url->link('catalog/attribute_image', 'token=' . $this->session->data['token'] . '&sort=attribute_image_group' . $url, 'SSL');
		$data['sort_sort_order'] = $this->url->link('catalog/attribute_image', 'token=' . $this->session->data['token'] . '&sort=ai.sort_order' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $attribute_image_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/attribute_image', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($attribute_image_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($attribute_image_total - $this->config->get('config_limit_admin'))) ? $attribute_image_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $attribute_image_total, ceil($attribute_image_total / $this->config->get('config_limit_admin')));


		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/attribute_image_list.tpl', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['attribute_image_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_browse'] = $this->language->get('text_browse');
		$data['text_clear'] = $this->language->get('text_clear');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_attribute_image_group'] = $this->language->get('entry_attribute_image_group');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_remove'] = $this->language->get('button_remove');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/attribute_image', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['attribute_image_id'])) {
			$data['action'] = $this->url->link('catalog/attribute_image/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('catalog/attribute_image/edit', 'token=' . $this->session->data['token'] . '&attribute_image_id=' . $this->request->get['attribute_image_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('catalog/attribute_image', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['attribute_image_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$attribute_image_info = $this->model_catalog_attribute_image->getAttributeImage($this->request->get['attribute_image_id']);
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['attribute_image_description'])) {
			$data['attribute_image_description'] = $this->request->post['attribute_image_description'];
		} elseif (isset($this->request->get['attribute_image_id'])) {
			$data['attribute_image_description'] = $this->model_catalog_attribute_image->getAttributeImageDescriptions($this->request->get['attribute_image_id']);
		} else {
			$data['attribute_image_description'] = array();
		}

		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($attribute_image_info)) {
			$data['image'] = $attribute_image_info['added_image'];
		} else {
			$data['image'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($attribute_image_info) && $attribute_image_info['added_image'] && is_file(DIR_IMAGE . $attribute_image_info['added_image']) ) {
			$data['thumb'] = $this->model_tool_image->resize($attribute_image_info['added_image'] , 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);


		if (isset($this->request->post['attribute_image_group_id'])) {
			$data['attribute_image_group_id'] = $this->request->post['attribute_image_group_id'];
		} elseif (!empty($attribute_image_info)) {
			$data['attribute_image_group_id'] = $attribute_image_info['attribute_image_group_id'];
		} else {
			$data['attribute_image_group_id'] = '';
		}

		$this->load->model('catalog/attribute_image_group');

		$data['attribute_image_groups'] = $this->model_catalog_attribute_image_group->getAttributeImageGroups();

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($attribute_image_info)) {
			$data['sort_order'] = $attribute_image_info['sort_order'];
		} else {
			$data['sort_order'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/attribute_image_form.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/attribute_image')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['attribute_image_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 2) || (utf8_strlen($value['name']) > 64)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/attribute_image')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('catalog/product');

		return !$this->error;
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('catalog/attribute_image');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 5
			);

			$results = $this->model_catalog_attribute_image->getAttributeImages($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'attribute_image_id'    => $result['attribute_image_id'],
					'name'            => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'attribute_image_group' => $result['attribute_image_group'],
					'image' => $result['added_image']
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
?>