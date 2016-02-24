<?php
class ModelPosPos extends Model {
	
	// This function is how POS module creates it's tables
	public function createModuleTables() {
        $res = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX. "order` LIKE 'user_id'");
        if($res->num_rows == 0){
            $this->db->query("ALTER TABLE `". DB_PREFIX. "order` ADD `user_id` INT( 11 )");
        }
		
		$res = $this->db->query("SHOW TABLES LIKE '". DB_PREFIX. "order_payment'");
		if ($res->num_rows == 0) {
			// table does not exists, simply recreate the table
			$query = $this->db->query("CREATE TABLE " . DB_PREFIX . "order_payment (order_payment_id INT(11) NOT NULL AUTO_INCREMENT, order_id INT(11) NOT NULL DEFAULT '0', pos_return_id INT(11) NOT NULL DEFAULT '0', user_id INT(11) NOT NULL, payment_type VARCHAR(100), tendered_amount FLOAT NOT NULL, payment_note VARCHAR(256), payment_time DATETIME, PRIMARY KEY (order_payment_id), KEY order_id(order_id))");
		} else {
			$res = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX. "order_payment` LIKE 'user_id'");
			if($res->num_rows == 0) {
				// old table, migrate data
				$migrate_data = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_payment");
				$this->db->query("DROP TABLE " . DB_PREFIX . "order_payment");
				$query = $this->db->query("CREATE TABLE " . DB_PREFIX . "order_payment (order_payment_id INT(11) NOT NULL AUTO_INCREMENT, order_id INT(11) NOT NULL, user_id INT(11) NOT NULL, payment_type VARCHAR(100), tendered_amount FLOAT NOT NULL, payment_note VARCHAR(256), payment_time DATETIME, PRIMARY KEY (order_payment_id), KEY order_id(order_id))");
				foreach ($migrate_data->rows as $key => $row) {
					$order_query = $this->db->query("SELECT user_id FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$row['order_id'] . "'");
					$order_info = $order_query->row;
					$user_id = 0;
					if ($order_info) {
						// add for admin payment details begin
						$user_id = $order_info['user_id'];
					}
					// add data back to table
					$this->db->query("INSERT INTO " . DB_PREFIX . "order_payment SET order_id = '" . $row['order_id'] . "', user_id = '" . $user_id . "', payment_type = '" . $row['payment_type'] . "', tendered_amount = '" . $row['tendered_amount'] . "', payment_note = '" . $this->db->escape($row['payment_note']) . "', payment_time = '" . $row['payment_time'] . "'");
				}
			}
			$res = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX. "order_payment` LIKE 'pos_return_id'");
			if($res->num_rows == 0) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_payment` ADD pos_return_id INT(11) NOT NULL DEFAULT '0'");
			}
		}
	}

	public function deleteModuleTables() {
		// $query = $this->db->query("DROP TABLE " . DB_PREFIX . "order_payment");
		// add for Sale Person Affiliate begin
		// $query = $this->db->query("DROP TABLE " . DB_PREFIX . "user_affiliate");
		// add for Sale Person Affiliate end
	}
	public function deleteOrder($order_id) {
		$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0' AND order_id = '" . (int)$order_id . "'");

		if ($order_query->num_rows) {
			$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

			foreach($product_query->rows as $product) {
				$this->db->query("UPDATE `" . DB_PREFIX . "product` SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_id = '" . (int)$product['product_id'] . "' AND subtract = '1'");

				$option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");

				foreach ($option_query->rows as $option) {
					$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
				}
			}
		}

		$this->db->query("DELETE FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
      	$this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'");
      	$this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_history WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_fraud WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_transaction WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_reward WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "affiliate_transaction WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_payment WHERE order_id = '" . (int)$order_id . "'");
	}

	public function addOrderPayment($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "order_payment SET order_id = '" . (int)$data['order_id'] . "', user_id = '" . (int)$data['user_id'] . "', payment_type = '" . $this->db->escape($data['payment_type']) . "', tendered_amount = '" . (float)$data['tendered_amount'] . "', payment_note = '" . $this->db->escape($data['payment_note']) . "', payment_time = NOW()");
		$order_payment_id = $this->db->getLastId();
		
		if (isset($data['change'])) {
			$change_row = $this->db->query("SELECT order_payment_id FROM " . DB_PREFIX . "order_payment WHERE order_id = '" . (int)$data['order_id'] . "' AND payment_type = 'pos_change'");
			if ($change_row->row) {
				$this->db->query("UPDATE " . DB_PREFIX . "order_payment SET tendered_amount = '" . (float)$data['change'] . "', payment_note = '" . $order_payment_id . "', payment_time = NOW() WHERE order_payment_id = '" . $change_row->row['order_payment_id'] . "'");
			} else {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_payment SET order_id = '" . (int)$data['order_id'] . "', user_id = '" . (int)$data['user_id'] . "', payment_type = 'pos_change', tendered_amount = '" . (float)$data['change'] . "', payment_note = '" . $order_payment_id . "', payment_time = NOW()");
			}
		}
		
		return $order_payment_id;
	}

	public function deleteOrderPayment($order_payment_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_payment WHERE payment_note = '" . $order_payment_id . "' AND payment_type = 'pos_change'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_payment WHERE order_payment_id = '" . $order_payment_id . "'");
	}

	public function retrieveOrderPayments($order_id) {
		// use order id to retrieve all the payment data
		$sqlQuery = "SELECT * FROM " . DB_PREFIX . "order_payment WHERE order_id = '" . $order_id . "'";
		$query = $this->db->query($sqlQuery);
		$payments = array();
		$pos_change = null;
		if (!empty($query->rows)) {
			foreach ($query->rows as $row) {
				if ($row['payment_type'] == 'pos_change') {
					$pos_change = $row;
				} else {
					$payments[] = $row;
				}
			}
		}
		if ($pos_change) {
			foreach ($payments as $key => $payment) {
				if ($payment['order_payment_id'] == $pos_change['payment_note']) {
					$payments[$key]['tendered_amount'] += $pos_change['tendered_amount'];
				}
			}
		}
		return $payments;
	}

	public function getOrderPayment($order_payment_id) {
		$sql_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_payment WHERE order_payment_id = '" . $order_payment_id . "'") ;
		return $sql_query->row;
	}
		
	public function saveOrderStatus($order_id, $order_status_id) {
		$sql = "UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$order_status_id . "'";
		$sql .= ", date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'";

		$this->db->query($sql);
	}
	
	public function addOrder($data) {
		$this->event->trigger('pre.order.add', $data);

		$invoice_prefix = $this->config->get('config_invoice_prefix');
		$store_id = isset($data['store_id']) ? $data['store_id'] : $this->config->get('config_store_id');
		$store_name = isset($data['store_name']) ? $data['store_name'] : $this->config->get('config_name');
		$store_url = isset($data['store_url']) ? $data['store_url'] : $this->config->get('config_url');
		
		$this->load->model('localisation/country');
		
		$this->load->model('localisation/zone');
		
		$country_info = $this->model_localisation_country->getCountry($data['shipping_country_id']);
		
		if ($country_info) {
			$shipping_country = $country_info['name'];
			$shipping_address_format = $country_info['address_format'];
		} else {
			$shipping_country = '';	
			$shipping_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}	
		
		$zone_info = $this->model_localisation_zone->getZone($data['shipping_zone_id']);
		
		if ($zone_info) {
			$shipping_zone = $zone_info['name'];
		} else {
			$shipping_zone = '';			
		}	

		$country_info = $this->model_localisation_country->getCountry($data['payment_country_id']);
		
		if ($country_info) {
			$payment_country = $country_info['name'];
			$payment_address_format = $country_info['address_format'];			
		} else {
			$payment_country = '';	
			$payment_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';					
		}
	
		$zone_info = $this->model_localisation_zone->getZone($data['payment_zone_id']);
		
		if ($zone_info) {
			$payment_zone = $zone_info['name'];
		} else {
			$payment_zone = '';			
		}	

		$this->load->model('localisation/currency');

		$currency_info = $this->model_localisation_currency->getCurrencyByCode(isset($data['currency_code']) ? $data['currency_code'] : $this->config->get('config_currency'));
		
		if ($currency_info) {
			$currency_id = $currency_info['currency_id'];
			$currency_code = $currency_info['code'];
			$currency_value = $currency_info['value'];
		} else {
			$currency_id = 0;
			$currency_code = $this->config->get('config_currency');
			$currency_value = 1.00000;			
		}

		$order_status_id = (isset($data['order_status_id'])) ? $data['order_status_id'] : '1';
      	
		$this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET invoice_prefix = '" . $this->db->escape($invoice_prefix) . "', store_id = '" . (int)$data['store_id'] . "', store_name = '" . $this->db->escape($store_name) . "', store_url = '" . $this->db->escape($store_url) . "', customer_id = '" . (int)$data['customer_id'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($payment_country) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_zone = '" . $this->db->escape($payment_zone) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($payment_address_format) . "', payment_custom_field = '" . $this->db->escape(isset($data['payment_custom_field']) ? serialize($data['payment_custom_field']) : '') . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($shipping_country) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($shipping_zone) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($shipping_address_format) . "', shipping_custom_field = '" . $this->db->escape(isset($data['shipping_custom_field']) ? serialize($data['shipping_custom_field']) : '') . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', comment = '" . $this->db->escape($data['comment']) . (isset($data['order_id']) ? "\n".$data['order_id'] : "") . "', total = '0', affiliate_id = '0', commission = '0', marketing_id = '" . (isset($data['marketing_id']) ? (int)$data['marketing_id'] : '0') . "', tracking = '" . $this->db->escape(isset($data['tracking']) ? $data['tracking'] : '') . "', language_id = '" . (int)$this->config->get('config_language_id') . "', currency_id = '" . (int)$currency_id . "', currency_code = '" . $this->db->escape($currency_code) . "', currency_value = '" . (float)$currency_value . "', ip = '" . $this->db->escape(isset($data['ip']) ? $data['ip'] : '') . "', forwarded_ip = '" .  $this->db->escape(isset($data['forwarded_ip']) ? $data['forwarded_ip'] : '') . "', user_agent = '" . $this->db->escape(isset($data['user_agent']) ? $data['user_agent'] : '') . "', accept_language = '', date_added = NOW(), date_modified = NOW(), order_status_id = '" . $order_status_id . "', user_id = '" . $data['user_id'] . "'");

		$order_id = $this->db->getLastId();

		// Get the total
		$total = 0;
		
		if (isset($data['totals'])) {		
      		foreach ($data['totals'] as $order_total) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($order_total['code']) . "', title = '" . $this->db->escape($order_total['title']) . "', `value` = '" . (float)$order_total['value'] . "', sort_order = '" . (int)$order_total['sort_order'] . "'");
			}
			
			$total += $order_total['value'];
		}
		// add order payment
		if (!empty($data['order_payments'])) {
			foreach ($data['order_payments'] as $order_payment) {
				$order_payment['order_id'] = $order_id;
				$this->addOrderPayment($order_payment);
			}
		}
		
		// Update order total			 
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET total = '" . (float)$total . "' WHERE order_id = '" . (int)$order_id . "'"); 	
		

		$this->event->trigger('post.order.add', $order_id);

		return $order_id;
	}
	
	public function getProductsForBrowse($data) {
		$sql = "SELECT p.product_id, p.price, p.model, p.shipping, p.tax_class_id, p.quantity, p.subtract, p.image, p.points, p.upc, p.sku, p.ean, p.mpn, pd.name, pd.description, pm.name AS m_name, GROUP_CONCAT(po.option_id) as options FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN `" . DB_PREFIX . "product_option` po ON p.product_id = po.product_id LEFT JOIN `" . DB_PREFIX . "manufacturer` pm ON p.manufacturer_id = pm.manufacturer_id WHERE p.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name']) && !empty($data['filter_scopes'])) {
			$sql .= " AND (pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
			foreach ($data['filter_scopes'] as $filter_scope) {
				if ($filter_scope == 'model') {
					$sql .= " OR LOWER(p.model) LIKE LOWER('%" . $this->db->escape($data['filter_name']) . "%')";
				} elseif ($filter_scope == 'manufacturer') {
					$sql .= " OR LOWER(pm.name) LIKE LOWER('%" . $this->db->escape($data['filter_name']) . "%')";
				} elseif ($filter_scope == 'upc') {
					$sql .= " OR LOWER(p.upc) LIKE LOWER('%" . $this->db->escape($data['filter_name']) . "%')";
				} elseif ($filter_scope == 'sku') {
					$sql .= " OR LOWER(p.sku) LIKE LOWER('%" . $this->db->escape($data['filter_name']) . "%')";
				} elseif ($filter_scope == 'ean') {
					$sql .= " OR LOWER(p.ean) LIKE LOWER('%" . $this->db->escape($data['filter_name']) . "%')";
				} elseif ($filter_scope == 'mpn') {
					$sql .= " OR LOWER(p.mpn) LIKE LOWER('%" . $this->db->escape($data['filter_name']) . "%')";
				}
			}
			$sql .=")";
		}

		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.sort_order'
		);
		$sql .= " ASC";


		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	// add for Browse begin
	public function getCategories() {
		// get all categories
		$query = $this->db->query("SELECT c.category_id, c.parent_id, cd.name FROM `" . DB_PREFIX . "category` c LEFT JOIN `" . DB_PREFIX . "category_description` cd ON c.category_id = cd.category_id WHERE cd.language_id = '". (int)$this->config->get('config_language_id') . "'");
		return $query->rows;
	}
	public function getSubCategories($category_id) {
		// get all sub categories under the given category
		$query = $this->db->query("SELECT c.category_id, c.image, cd.name FROM `" . DB_PREFIX . "category` c LEFT JOIN `" . DB_PREFIX . "category_description` cd ON c.category_id = cd.category_id WHERE c.status = '1' AND cd.language_id = '". (int)$this->config->get('config_language_id') . "' AND c.parent_id = '" . $category_id . "'");
		return $query->rows;
	}
	public function getProducts($category_id) {
		// get all products in the given category
		$query = $this->db->query("SELECT p.product_id, p.model, p.price, p.subtract, p.shipping, p.tax_class_id, p.quantity, p.image, p.points, pd.name, pd.description, p.upc, p.sku, p.ean, p.mpn, pm.name AS m_name, GROUP_CONCAT(po.option_id) as options FROM `" . DB_PREFIX . "product_to_category` pc LEFT JOIN `" . DB_PREFIX . "product` p ON pc.product_id = p.product_id LEFT JOIN `" . DB_PREFIX . "product_description` pd ON p.product_id = pd.product_id LEFT JOIN `" . DB_PREFIX . "product_option` po ON p.product_id = po.product_id LEFT JOIN `" . DB_PREFIX . "manufacturer` pm ON p.manufacturer_id = pm.manufacturer_id WHERE p.status = '1' AND pd.language_id = '". (int)$this->config->get('config_language_id') . "' AND pc.category_id = '" . $category_id . "' GROUP BY p.product_id");
		return $query->rows;
	}
	public function getCategoryFullPath($category_id) {
		// get the category paths in category names
		$query = $this->db->query("SELECT name FROM (SELECT cp.category_id, GROUP_CONCAT(CONCAT(cd.category_id, '|||', cd.name, '|||', c.image) ORDER BY cp.level SEPARATOR '!|||!') AS name FROM " . DB_PREFIX . "category_path cp LEFT JOIN `" . DB_PREFIX . "category` c ON (cp.path_id = c.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.category_id ORDER BY name) tmp WHERE category_id = '" . (int)$category_id . "'");
		return $query->row;
	}
	public function getCategoryFullPathOld($category_id) {
		// get the category paths in category names
		if ((int)$category_id == 0) {
			return null;
		}
		
		$query = $this->db->query("SELECT name, c.category_id, parent_id, c.image FROM `" . DB_PREFIX . "category` c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, cd.name ASC");
		
		if ($query->row['parent_id']) {
			return $this->getCategoryFullPathOld($query->row['parent_id']) . '!|||!' . $query->row['category_id'] . '|||' . $query->row['name'] . '|||' . $query->row['image'];
		} else {
			return $query->row['category_id'] . '|||' . $query->row['name'] . '|||' . $query->row['image'];
		}
	}
	public function getTotalSubItems($category_id) {
		$total_items = 0;
		
		$sub_category_total_query = $this->db->query("SELECT count(category_id) AS total FROM `" . DB_PREFIX . "category` WHERE parent_id = '" . $category_id . "'");
		if ($sub_category_total_query->row) {
			$total_items += $sub_category_total_query->row['total'];
		}
		
		$product_total_query = $this->db->query("SELECT count(p.product_id) AS total FROM `" . DB_PREFIX . "product_to_category` pc LEFT JOIN `" . DB_PREFIX . "product` p ON p.product_id = pc.product_id WHERE pc.category_id = '" . $category_id . "' AND p.status = '1'");
		if ($product_total_query->row) {
			$total_items += $product_total_query->row['total'];
		}
		
		return $total_items;
	}
	// add for Browse end
	
	public function getOrder($order_id) {
		$order_query = $this->db->query("SELECT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = o.customer_id) AS customer FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int)$order_id . "'");

		if ($order_query->num_rows) {
			$reward = 0;
			
			$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
		
			foreach ($order_product_query->rows as $product) {
				$reward += $product['reward'];
			}			
			
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");

			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}
			
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");

			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}
		
			if ($order_query->row['affiliate_id']) {
				$affiliate_id = $order_query->row['affiliate_id'];
			} else {
				$affiliate_id = 0;
			}				
				
			$this->load->model('marketing/affiliate');
				
			$affiliate_info = $this->model_marketing_affiliate->getAffiliate($affiliate_id);
				
			if ($affiliate_info) {
				$affiliate_firstname = $affiliate_info['firstname'];
				$affiliate_lastname = $affiliate_info['lastname'];
			} else {
				$affiliate_firstname = '';
				$affiliate_lastname = '';				
			}

			$this->load->model('localisation/language');
			
			$language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);
			
			if ($language_info) {
				$language_code = $language_info['code'];
				$language_directory = $language_info['directory'];
			} else {
				$language_code = '';
				$language_directory = '';
			}
			
			return array(
				'order_id'                => $order_query->row['order_id'],
				'invoice_no'              => $order_query->row['invoice_no'],
				'invoice_prefix'          => $order_query->row['invoice_prefix'],
				'store_id'                => $order_query->row['store_id'],
				'store_name'              => $order_query->row['store_name'],
				'store_url'               => $order_query->row['store_url'],
				'customer_id'             => $order_query->row['customer_id'],
				'customer'                => $order_query->row['customer'],
				'customer_group_id'       => $order_query->row['customer_group_id'],
				'firstname'               => $order_query->row['firstname'],
				'lastname'                => $order_query->row['lastname'],
				'telephone'               => $order_query->row['telephone'],
				'fax'                     => $order_query->row['fax'],
				'email'                   => $order_query->row['email'],
				'payment_firstname'       => $order_query->row['payment_firstname'],
				'payment_lastname'        => $order_query->row['payment_lastname'],
				'payment_company'         => $order_query->row['payment_company'],
				'payment_address_1'       => $order_query->row['payment_address_1'],
				'payment_address_2'       => $order_query->row['payment_address_2'],
				'payment_postcode'        => $order_query->row['payment_postcode'],
				'payment_city'            => $order_query->row['payment_city'],
				'payment_zone_id'         => $order_query->row['payment_zone_id'],
				'payment_zone'            => $order_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $order_query->row['payment_country_id'],
				'payment_country'         => $order_query->row['payment_country'],
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $order_query->row['payment_address_format'],
				'payment_method'          => $order_query->row['payment_method'],
				'payment_code'            => $order_query->row['payment_code'],				
				'shipping_firstname'      => $order_query->row['shipping_firstname'],
				'shipping_lastname'       => $order_query->row['shipping_lastname'],
				'shipping_company'        => $order_query->row['shipping_company'],
				'shipping_address_1'      => $order_query->row['shipping_address_1'],
				'shipping_address_2'      => $order_query->row['shipping_address_2'],
				'shipping_postcode'       => $order_query->row['shipping_postcode'],
				'shipping_city'           => $order_query->row['shipping_city'],
				'shipping_zone_id'        => $order_query->row['shipping_zone_id'],
				'shipping_zone'           => $order_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $order_query->row['shipping_country_id'],
				'shipping_country'        => $order_query->row['shipping_country'],
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_address_format' => $order_query->row['shipping_address_format'],
				'shipping_method'         => $order_query->row['shipping_method'],
				'shipping_code'           => $order_query->row['shipping_code'],
				'comment'                 => $order_query->row['comment'],
				'total'                   => $order_query->row['total'],
				'reward'                  => $reward,
				'order_status_id'         => $order_query->row['order_status_id'],
				'affiliate_id'            => $order_query->row['affiliate_id'],
				'affiliate_firstname'     => $affiliate_firstname,
				'affiliate_lastname'      => $affiliate_lastname,
				'commission'              => $order_query->row['commission'],
				'language_id'             => $order_query->row['language_id'],
				'language_code'           => $language_code,
				'language_directory'      => $language_directory,				
				'currency_id'             => $order_query->row['currency_id'],
				'currency_code'           => $order_query->row['currency_code'],
				'currency_value'          => $order_query->row['currency_value'],
				'ip'                      => $order_query->row['ip'],
				'forwarded_ip'            => $order_query->row['forwarded_ip'], 
				'user_agent'              => $order_query->row['user_agent'],	
				'accept_language'         => $order_query->row['accept_language'],					
				'date_added'              => $order_query->row['date_added'],
				'date_modified'           => $order_query->row['date_modified'],
				'user_id'				  => $order_query->row['user_id']
			);
		} else {
			return false;
		}
	}
	
	public function get_full_username($user_id, $short=false) {
		$user_query = $this->db->query("SELECT firstname, lastname FROM `" . DB_PREFIX . "user` WHERE user_id = '" . (int)$user_id . "'");
		if ($user_query->row) {
			if ($short) {
				return $user_query->row['firstname'] . '.' . strtoupper(substr($user_query->row['lastname'], 0, 1));
			} else {
				return $user_query->row['firstname'] . ' ' . $user_query->row['lastname'];
			}
		}
		return '';
	}
	
	public function getCustomers($data = array()) {
		$sql = "SELECT *, CONCAT(firstname, ' ', lastname) AS name FROM " . DB_PREFIX . "customer";

		$implode = array();

		if (!empty($data['filter_customer_id'])) {
			$implode[] = "customer_id = '" . (int)($data['filter_customer_id']) . "'";
		}

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "email LIKE '%" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (!empty($data['filter_telephone'])) {
			$implode[] = "telephone LIKE '%" . $this->db->escape($data['filter_telephone']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'name',
			'email',
			'date_added'
		);

		$sql .= " ORDER BY customer_id";

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getTotalCustomers($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer";

		$implode = array();

		if (!empty($data['filter_customer_id'])) {
			$implode[] = "customer_id = '" . (int)($data['filter_customer_id']) . "'";
		}

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "email LIKE '%" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (!empty($data['filter_telephone'])) {
			$implode[] = "telephone LIKE '%" . $this->db->escape($data['filter_telephone']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	
	public function editCustomer($customer_id, $data) {
		if (!isset($data['custom_field'])) {
			$data['custom_field'] = array();
		}

		// update for customer loyalty card
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . (isset($data['telephone']) ? $this->db->escape($data['telephone']) : '') . "', fax = '" . (isset($data['fax']) ? $this->db->escape($data['fax']) : '') . "', custom_field = '" . (isset($data['custom_field']) ? $this->db->escape(serialize($data['custom_field'])) : '') . "', newsletter = '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 0) . "', status = '" . (isset($data['status']) ? (int)$data['status'] : 0) . "', safe = '" . (isset($data['safe']) ? (int)$data['safe'] : 0) . "' WHERE customer_id = '" . (int)$customer_id . "'");

		if (!empty($data['password'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' WHERE customer_id = '" . (int)$customer_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");

		if (isset($data['addresses'])) {
			foreach ($data['addresses'] as $address) {
				if (!isset($address['custom_field'])) {
					$address['custom_field'] = array();
				}

				if (!empty($address['address_id']) && (int)$address['address_id'] > 0) {
					// reserve the previous address id
					$address_id = (int)$address['address_id'];
					$this->db->query("INSERT INTO " . DB_PREFIX . "address SET address_id = '" . (int)$address['address_id'] . "', customer_id = '" . (int)$customer_id . "', firstname = '" . $this->db->escape($address['firstname']) . "', lastname = '" . $this->db->escape($address['lastname']) . "', company = '" . $this->db->escape($address['company']) . "', address_1 = '" . $this->db->escape($address['address_1']) . "', address_2 = '" . $this->db->escape($address['address_2']) . "', city = '" . $this->db->escape($address['city']) . "', postcode = '" . $this->db->escape($address['postcode']) . "', country_id = '" . (int)$address['country_id'] . "', zone_id = '" . (int)$address['zone_id'] . "', custom_field = '" . $this->db->escape(serialize($address['custom_field'])) . "'");
				} else {
					// new address
					$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$customer_id . "', firstname = '" . $this->db->escape($address['firstname']) . "', lastname = '" . $this->db->escape($address['lastname']) . "', company = '" . $this->db->escape($address['company']) . "', address_1 = '" . $this->db->escape($address['address_1']) . "', address_2 = '" . $this->db->escape($address['address_2']) . "', city = '" . $this->db->escape($address['city']) . "', postcode = '" . $this->db->escape($address['postcode']) . "', country_id = '" . (int)$address['country_id'] . "', zone_id = '" . (int)$address['zone_id'] . "', custom_field = '" . $this->db->escape(serialize($address['custom_field'])) . "'");
					$address_id = $this->db->getLastId();
				}

				if (isset($address['default'])) {
					$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
				}
			}
		}
	}
	
	public function addCustomer($data, $customer_table=false, $address_table=false) {
		$is_sync = ($customer_table) ? true : false;
		
		if (!$customer_table) {
			$customer_table = DB_PREFIX . "customer";
		}
		if (!$address_table) {
			$address_table = DB_PREFIX . "address";
		}
		
		// check if the customer has already been added
		if ($is_sync) {
			$check_query = $this->db->query("SELECT customer_id FROM `" . $customer_table . "` WHERE customer_id = '" . $data['customer_id'] . "'");
			if ($check_query->row) {
				// already exists, return
				return 0;
			}
		}
		
		$this->db->query("INSERT INTO `" . $customer_table . "` SET customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', newsletter = '" . (int)$data['newsletter'] . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', status = '" . (int)$data['status'] . "', safe = '" . (isset($data['safe']) ? (int)$data['safe'] : 0) . "', date_added = NOW()");

		$customer_id = $this->db->getLastId();

		if (isset($data['addresses'])) {
			$address_customer_id = $customer_id;
			if ($is_sync) {
				// for syncing tables, use the original customer id for now
				$address_customer_id = $data['customer_id'];
			}
			
			foreach ($data['addresses'] as $address) {
				$this->db->query("INSERT INTO `" . $address_table . "` SET customer_id = '" . (int)$address_customer_id . "', firstname = '" . $this->db->escape($address['firstname']) . "', lastname = '" . $this->db->escape($address['lastname']) . "', company = '" . $this->db->escape($address['company']) . "', address_1 = '" . $this->db->escape($address['address_1']) . "', address_2 = '" . $this->db->escape($address['address_2']) . "', city = '" . $this->db->escape($address['city']) . "', postcode = '" . $this->db->escape($address['postcode']) . "', country_id = '" . (int)$address['country_id'] . "', zone_id = '" . (int)$address['zone_id'] . "', custom_field = ''");
				$address_id = $this->db->getLastId();

				if (isset($address['default'])) {
					$this->db->query("UPDATE `" . $customer_table . "` SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
				}
				
				if ($is_sync) {
					// for syncing tables, use the original address id for now
					$org_address_id = $address['address_id'];
					$this->db->query("UPDATE `" . $address_table . "` SET address_id = '" . $org_address_id . "' WHERE address_id = '" . $address_id . "'");
					if (isset($address['default'])) {
						$this->db->query("UPDATE `" . $customer_table . "` SET address_id = '" . $org_address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
					}
				}
			}
		}
		
		return $customer_id;
	}
	
	public function saveOrderComment($order_id, $comment) {
		$sql = "UPDATE `" . DB_PREFIX . "order` SET comment = '" . $this->db->escape($comment) . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'";
		$this->db->query($sql);
	}
	public function getOrderProducts($order_id) {
		$order_products = $this->db->query("SELECT op.*, p.subtract, p.tax_class_id, p.shipping, p.image, p.ean FROM `" . DB_PREFIX . "order_product` op LEFT JOIN `" . DB_PREFIX . "product` p ON op.product_id = p.product_id WHERE order_id = '" . (int)$order_id . "' ORDER BY order_product_id")->rows;
		if (!empty($order_products)) {
			foreach ($order_products as $key => $order_product) {
				if (!isset($order_product['shipping'])) {
					$order_products[$key]['shipping'] = 0;
				}
			}
		}
		return $order_products;
	}
	public function getOrders($data = array()) {
		$sql = "SELECT o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, o.user_id, o.order_status_id, o.email, ";
		$sql .= "(SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, ";
		$sql .= "o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . "order` o";

		if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}
		
		if (isset($data['filter_total'])) {
			$sql .= " AND ROUND(o.total, 2) = '" . round((float)$data['filter_total'], 2) . "'";
		}

		$sort_data = array(
			'o.order_id',
			'customer',
			'status',
			'o.date_added',
			'o.date_modified',
			'o.total'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY o.order_id";
		}

		if (isset($data['order']) && ($data['order'] == 'ASC')) {
			$sql .= " ASC";
		} else {
			$sql .= " DESC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}
	public function getTotalOrders($data = array()) {
      	$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order`";

		if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
			$sql .= " WHERE order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE order_status_id > '0'";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}
		
		if (isset($data['filter_total'])) {
			$sql .= " AND ROUND(total,2) = '" . round((float)$data['filter_total'], 2) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}
?>