<?php
class ControllerModuleMailchimp extends Controller {

	public function index($setting) {
		static $module_index = 0;

		$data['module_index'] = $module_index++;

		if (isset($setting['list_id']) && strlen(trim($setting['list_id'])) > 0) {
			$this->language->load('module/mailchimp');
			$data['heading_title']   = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['title'], ENT_QUOTES, 'UTF-8');
			$data['html']            = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['description'], ENT_QUOTES, 'UTF-8');
			$data['text_submit']     = $this->language->get('text_submit');
			$data['language_id']     = $this->config->get('config_language_id');
			$data['list_id']         = $setting['list_id'];
			$data['popup']           = $setting['popup'];
			$data['success_message'] = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['success_message'], ENT_QUOTES, 'UTF-8');
			$data['button_text']     = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['button_text'], ENT_QUOTES, 'UTF-8');

			if (intval($setting['popup']) == 1) {
				$data['md5code'] = md5(implode('-', array(
					$setting['list_id'],
					$data['module_index'],
				)));

				if (isset($_COOKIE['md5code']) && $_COOKIE['md5code'] == $data['md5code']) {
					return '';
				}
			}

			$data['setting'] = $setting;
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/mailchimp.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/module/mailchimp.tpl', $data);
			} else {
				return $this->load->view('default/template/module/mailchimp.tpl', $data);
			}
		}
	}

	public function add2list() {
		$mailchimp_api_key = trim($this->config->get('mailchimp_api'));
		$mailchimp_list_id = trim($this->request->post['list_id']);
		unset($this->request->post['list_id']);

		if (strlen($mailchimp_list_id) > 1 && strlen($mailchimp_api_key) > 1) {

			require_once DIR_SYSTEM . 'library/Drewm/MailChimp.php';
			$MailChimp = new \Drewm\MailChimp($mailchimp_api_key);

			$result = $MailChimp->call('lists/subscribe', array(
				'id'                => $mailchimp_list_id,
				'email'             => array('email' => trim($this->request->post['EMAIL'])),
				'merge_vars'        => $this->request->post,
				'double_optin'      => false,
				'update_existing'   => true,
				'replace_interests' => false,
				'send_welcome'      => false,
			));

			if (isset($result['email']) && !empty($result['email']) && isset($this->request->post['md5code'])) {
				$this->storeCookie('md5code', $this->request->post['md5code']);
			}

			echo json_encode($result);

		}
	}

	function storeCookie($name, $value) {
		$expire_cookie = strtotime('+30 Days');
		$server        = str_replace('www.', '', $this->request->server['HTTP_HOST']);
		setcookie($name, $value, $expire_cookie, '/', '.' . $server);
		setcookie($name, $value, $expire_cookie, '/', $server);
		setcookie($name, $value, $expire_cookie, '/');
	}

	public function subscribeMailChimp($email = null, $fname = null, $lname = null, $customer_group_id = 0) {

		$mailchimp_api_key = trim($this->config->get('mailchimp_api'));
		$mailchimp_list_id = trim($this->config->get('mailchimp_list_' . $customer_group_id));

		if (strlen($mailchimp_list_id) > 1 && strlen($mailchimp_api_key) > 1) {
			if (!empty($email)) {
				require_once DIR_SYSTEM . 'library/Drewm/MailChimp.php';
				$MailChimp = new \Drewm\MailChimp($mailchimp_api_key);

				$result = $MailChimp->call('lists/subscribe', array(
					'id'                => $mailchimp_list_id,
					'email'             => array('email' => trim($email)),
					'merge_vars'        => array('FNAME' => trim($fname), 'LNAME' => trim($lname)),
					'double_optin'      => false,
					'update_existing'   => true,
					'replace_interests' => false,
					'send_welcome'      => false,
				));
			}
		}
	}

	public function after_order_add($order_id) {
		$mailchimp_api_key = trim($this->config->get('mailchimp_api'));

		$mailChimpOrder           = array();
		$mailChimpOrder           = $this->getOrder($order_id);
		$mailChimpOrder['apikey'] = $mailchimp_api_key;

		if (count($mailChimpOrder['order']) > 0) {
			require_once DIR_SYSTEM . 'library/Drewm/MailChimp.php';
			$MailChimp = new \Drewm\MailChimp($mailchimp_api_key);
			$result    = $MailChimp->call('ecomm/order-add', $mailChimpOrder);
		}

	}

	public function after_order_edit($order_id) {
		$mailchimp_api_key = trim($this->config->get('mailchimp_api'));

		$mailChimpOrder           = array();
		$mailChimpOrder           = $this->getOrder($order_id);
		$mailChimpOrder['apikey'] = $mailchimp_api_key;

		if (count($mailChimpOrder['order']) > 0) {
			require_once DIR_SYSTEM . 'library/Drewm/MailChimp.php';
			$MailChimp = new \Drewm\MailChimp($mailchimp_api_key);

			$result = $MailChimp->call('ecomm/order-del', array(
				'apikey'   => $mailchimp_api_key,
				'store_id' => $mailChimpOrder['order']['store_id'],
				'order_id' => $order_id,
			));
			$result = $MailChimp->call('ecomm/order-add', $mailChimpOrder);
		}
	}

	public function getOrderInfo($order_id) {
		$order_query = $this->db->query("SELECT order_status_id,order_id,store_id,store_name,email,date_modified,total,firstname,lastname,customer_group_id  FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int) $order_id . "' LIMIT 1");

		if ($order_query->num_rows) {
			$this->subscribeMailChimp($order_query->row['email'], $order_query->row['firstname'], $order_query->row['lastname'], $order_query->row['customer_group_id']);
			return array(
				'order_id'          => $order_query->row['order_id'],
				'store_id'          => $order_query->row['store_id'] + 1,
				'store_name'        => $order_query->row['store_name'],
				'email'             => $order_query->row['email'],
				'total'             => $order_query->row['total'],
				'date_added'        => $order_query->row['date_modified'],
				'firstname'         => $order_query->row['firstname'],
				'lastname'          => $order_query->row['lastname'],
				'customer_group_id' => $order_query->row['customer_group_id'],
				'order_status_id'   => $order_query->row['order_status_id'],
			);
		} else {
			return false;
		}
	}

	private function getOrder($order_id) {
		$this->load->language('account/order');
		$this->load->model('account/order');

		$order_info = $this->getOrderInfo($order_id);

		$data['order'] = array();
		if ($order_info && isset($order_info['order_status_id']) && intval($order_info['order_status_id']) > 0) {
			$data['order']['id']                = $order_id;
			$data['order']['order_date']        = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));
			$data['order']['email']             = $order_info['email'];
			$data['order']['store_id']          = $order_info['store_id'];
			$data['order']['store_name']        = $order_info['store_name'];
			$data['order']['shipping']          = $this->getShipping($order_id);
			$data['order']['tax']               = $this->getTax($order_id);
			$data['order']['total']             = $order_info['total'];
			$data['order']['firstname']         = $order_info['firstname'];
			$data['order']['lastname']          = $order_info['lastname'];
			$data['order']['customer_group_id'] = $order_info['customer_group_id'];

			if (isset($this->request->cookie['mc_ecomm_eid'])) {
				$data['order']['email_id'] = $this->request->cookie['mc_ecomm_eid'];
			}

			if (isset($this->request->cookie['mc_ecomm_cid'])) {
				$data['order']['campaign_id'] = $this->request->cookie['mc_ecomm_cid'];
			}

			// Products
			$data['order']['items'] = array();

			$products = $this->model_account_order->getOrderProducts($order_id);
			$line_num = 1;
			foreach ($products as $product) {
				$cat_info                 = $this->getProductCategories($product['product_id']);
				$data['order']['items'][] = array(
					'line_num'      => $line_num,
					'product_id'    => $product['product_id'],
					'sku'           => $this->getProductSKU($product['product_id']),
					'product_name'  => $this->getProductName($product['product_id']),
					'category_id'   => $cat_info['category_ids'],
					'category_name' => $cat_info['category_names'],
					'qty'           => $product['quantity'],
					'cost'          => $product['total'] + ($this->config->get('config_tax') ? ($product['tax']) : 0),
				);
				$line_num++;
			}
		}

		return $data;
	}

	private function getTax($order_id) {
		$tax_result = $this->db->query('SELECT SUM(value) as total_tax FROM ' . DB_PREFIX . 'order_total WHERE code =\'tax\' AND order_id=' . $order_id);
		if ($tax_result->num_rows) {
			return $tax_result->row['total_tax'];
		}

		return 0;
	}

	private function getShipping($order_id) {
		$shipping_result = $this->db->query('SELECT SUM(value) as total_shipping FROM ' . DB_PREFIX . 'order_total WHERE code =\'shipping\' AND order_id=' . $order_id);
		if ($shipping_result->num_rows) {
			return $shipping_result->row['total_shipping'];
		}

		return 0;
	}

	private function getProductSKU($product_id) {
		$product = $this->db->query('SELECT sku FROM ' . DB_PREFIX . 'product WHERE product_id=' . $product_id . ' LIMIT 1');
		if ($product->num_rows) {
			return strlen(trim($product->row['sku'])) == 0 ? 'sku_' . $product_id : $product->row['sku'];
		}

		return 'sku_0';
	}

	private function getProductName($product_id) {
		$product = $this->db->query('SELECT name FROM ' . DB_PREFIX . 'product_description WHERE product_id=' . $product_id . ' AND language_id = ' . (int) $this->config->get('config_language_id') . ' LIMIT 1');
		if ($product->num_rows) {
			return $product->row['name'];
		}

		return '-';
	}

	private function getProductCategories($product_id) {
		$categories = $this->db->query('SELECT category_id FROM ' . DB_PREFIX . 'product_to_category WHERE product_id=' . $product_id);

		$data['category_ids']   = '1';
		$data['category_names'] = 'empty';

		if ($categories->num_rows) {
			$data['category_ids']   = array();
			$data['category_names'] = array();
			foreach ($categories->rows as $row) {
				$category_info            = $this->getCategory($row['category_id']);
				$data['category_ids'][]   = $row['category_id'];
				$data['category_names'][] = $category_info['name'];
			}
			$data['category_ids']   = implode(',', $data['category_ids']);
			$data['category_names'] = implode(',', $data['category_names']);
		}

		return $data;
	}

	private function getCategory($category_id) {
		$query = $this->db->query("SELECT cd.name FROM " . DB_PREFIX . "category_description cd WHERE cd.category_id = '" . (int) $category_id . "' AND cd.language_id = '" . (int) $this->config->get('config_language_id') . "' LIMIT 1");

		return $query->row;
	}
}