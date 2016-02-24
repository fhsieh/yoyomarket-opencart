<?php
//==============================================================================
// Stripe Payment Gateway v210.2
// 
// Author: Clear Thinking, LLC
// E-mail: johnathan@getclearthinking.com
// Website: http://www.getclearthinking.com
// 
// All code within this file is copyright Clear Thinking, LLC.
// You may not copy or reuse code within this file without written permission.
//==============================================================================

class ModelPaymentStripe extends Model {		
	private $type = 'payment';
	private $name = 'stripe';
	
	public function getMethod($address, $total = 0) {
		$settings = $this->getSettings();
		
		$current_geozones = array();
		$geozones = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '0' OR zone_id = '" . (int)$address['zone_id'] . "')");
		foreach ($geozones->rows as $geozone) {
			$current_geozones[] = $geozone['geo_zone_id'];
		}
		if (empty($current_geozones)) {
			$current_geozones = array(0);
		}
		
		$customer_group_id = (version_compare(VERSION, '2.0') < 0) ? (int)$this->customer->getCustomerGroupId() : (int)$this->customer->getGroupId();
		
		if (!$settings['status'] ||
			($settings['min_total'] && (float)$settings['min_total'] > $total) ||
			($settings['max_total'] && (float)$settings['max_total'] < $total) ||
			!array_intersect(array($this->config->get('config_store_id')), explode(';', $settings['stores'])) ||
			!array_intersect($current_geozones, explode(';', $settings['geo_zones'])) ||
			!array_intersect(array($customer_group_id), explode(';', $settings['customer_groups'])) ||
			empty($settings['currencies_' . $this->session->data['currency']])
		) {
			return array();
		} else {
			return array(
				'code'			=> $this->name,
				'sort_order'	=> $settings['sort_order'],
				'terms'			=> '',
				'title'			=> html_entity_decode($settings['title_' . $this->session->data['language']], ENT_QUOTES, 'UTF-8'),
			);
		}
	}
	
	public function createOrder($order_data) {
		$settings = $this->getSettings();
		
		$forwarded_ip = '';
		if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
			$forwarded_ip = $this->request->server['HTTP_X_FORWARDED_FOR'];
		} elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
			$forwarded_ip = $this->request->server['HTTP_CLIENT_IP'];
		}
		
		$default_order_data = array(
			// Order Data
			'invoice_prefix'			=> $this->config->get('config_invoice_prefix'),
			'store_id'					=> $this->config->get('config_store_id'),
			'store_name'				=> $this->config->get('config_name'),
			'store_url'					=> ($this->config->get('config_store_id') ? $this->config->get('config_url') : HTTP_SERVER),
		
			// Customer Data
			'customer_id'				=> $this->customer->getId(),
			'customer_group_id'			=> $this->config->get('config_customer_group_id'),
			'firstname'					=> '',
			'lastname'					=> '',
			'email'						=> '',
			'telephone'					=> '',
			'fax'						=> '',
			
			// Payment Data
			'payment_firstname'			=> '',
			'payment_lastname'			=> '',
			'payment_company'			=> '',
			'payment_company_id'		=> '',
			'payment_tax_id'			=> '',
			'payment_address_1'			=> '',
			'payment_address_2'			=> '',
			'payment_city'				=> '',
			'payment_postcode'			=> '',
			'payment_zone'				=> '',
			'payment_zone_id'			=> '',
			'payment_country'			=> '',
			'payment_country_id'		=> '',
			'payment_address_format'	=> '',
			'payment_method'			=> html_entity_decode($settings['title_' . $this->session->data['language']], ENT_QUOTES, 'UTF-8'),
			'payment_code'				=> $this->name,
			
			// Shipping Data
			'shipping_firstname'		=> '',
			'shipping_lastname'			=> '',
			'shipping_company'			=> '',
			'shipping_company_id'		=> '',
			'shipping_tax_id'			=> '',
			'shipping_address_1'		=> '',
			'shipping_address_2'		=> '',
			'shipping_city'				=> '',
			'shipping_postcode'			=> '',
			'shipping_zone'				=> '',
			'shipping_zone_id'			=> '',
			'shipping_country'			=> '',
			'shipping_country_id'		=> '',
			'shipping_address_format'	=> '',
			'shipping_method'			=> (isset($this->session->data['shipping_method']['title']) ? $this->session->data['shipping_method']['title'] : ''),
			'shipping_code'				=> (isset($this->session->data['shipping_method']['code']) ? $this->session->data['shipping_method']['code'] : ''),
			
			// Currency Data
			'currency_id'				=> $this->currency->getId(),
			'currency'					=> $this->currency->getCode(),
			'currency_code'				=> $this->currency->getCode(),
			'value'						=> $this->currency->getValue($this->currency->getCode()),
			'currency_value'			=> $this->currency->getValue($this->currency->getCode()),
			
			// Browser Data
			'ip'						=> $this->request->server['REMOTE_ADDR'],
			'forwarded_ip'				=> $forwarded_ip,
			'user_agent'				=> (isset($this->request->server['HTTP_USER_AGENT']) ? $this->request->server['HTTP_USER_AGENT'] : ''),
			'accept_language'			=> (isset($this->request->server['HTTP_ACCEPT_LANGUAGE']) ? $this->request->server['HTTP_ACCEPT_LANGUAGE'] : ''),
			
			// Other Data
			'affiliate_id'				=> 0,
			'commission'				=> 0,
			'comment'					=> (isset($this->session->data['comment']) ? $this->session->data['comment'] : ''),
			'language_id'				=> $this->config->get('config_language_id'),
			'marketing_id'				=> 0,
			'products'					=> array(),
			'totals'					=> array(),
			'total'						=> 0,
			'tracking'					=> '',
			'vouchers'					=> array(),
		);
		
		foreach ($default_order_data as $field => $default) {
			$data[$field] = (isset($order_data[$field])) ? $order_data[$field] : $default;
		}
		
		// Customer
		if (empty($data['firstname'])) {
			$data['firstname'] = $data['email'];
		}
		if (empty($data['payment_firstname'])) {
			$data['payment_firstname'] = $data['firstname'];
		}
		
		$this->load->model('account/customer');
		$customer = $this->model_account_customer->getCustomer($data['customer_id']);
		if (!empty($customer)) {
			$data['customer_group_id'] = $customer['customer_group_id'];
			$data['firstname'] = $customer['firstname'];
			$data['lastname'] = $customer['lastname'];
			$data['email'] = $customer['email'];
			$data['telephone'] = $customer['telephone'];
			$data['fax'] = $customer['fax'];
			
			//$this->session->data['guest']['firstname'] = $customer['firstname'];
			//$this->session->data['guest']['lastname'] = $customer['lastname'];
		} else {
			//$this->session->data['guest']['firstname'] = $data['firstname'];
			//$this->session->data['guest']['lastname'] = $data['lastname'];
		}
		
		// Products
		if (empty($data['products'])) {
			$products = $this->cart->getProducts();
			foreach ($products as &$product) {
				foreach ($product['option'] as &$option) {
					if (version_compare(VERSION, '2.0') < 0) {
						$option['value'] = ($option['type'] == 'file') ? $this->encryption->decrypt($option['option_value']) : $option['option_value'];
					} else {
						$option['value'] = ($option['type'] == 'file') ? $this->encryption->decrypt($option['value']) : $option['value'];
					}
				}
				$product['tax'] = $this->tax->getTax($product['price'], $product['tax_class_id']);
			}
			$data['products'] = $products;
		}
		
		// Vouchers
		if (!empty($this->session->data['vouchers'])) {
			$vouchers = $this->session->data['vouchers'];
			foreach ($vouchers as &$voucher) {
				$voucher['code'] = substr(md5(mt_rand()), 0, 10);
			}
			$data['vouchers'] = $vouchers;
		}
		
		// Order Totals
		if (empty($data['totals'])) {
			$order_totals_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = 'total' ORDER BY `code` ASC");
			$order_totals = $order_totals_query->rows;
			
			$sort_order = array();
			foreach ($order_totals as $key => $value) {
				$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
			}
			array_multisort($sort_order, SORT_ASC, $order_totals);
			
			$total_data = array();
			$order_total = 0;
			$taxes = $this->cart->getTaxes();
			
			foreach ($order_totals as $ot) {
				if (!$this->config->get($ot['code'] . '_status')) continue;
				$this->load->model('total/' . $ot['code']);
				$this->{'model_total_' . $ot['code']}->getTotal($total_data, $order_total, $taxes);
			}
			
			$data['totals'] = $total_data;
			$data['total'] = $order_total;
		}
		
		$this->load->model('checkout/order');
		$order_id = $this->model_checkout_order->addOrder($data);
		
		return $order_id;
	}
	
	//==============================================================================
	// Private functions
	//==============================================================================
	private function getSettings() {
		$settings = array();
		$settings_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `" . (version_compare(VERSION, '2.0.1') < 0 ? 'group' : 'code') . "` = '" . $this->db->escape($this->name) . "' ORDER BY `key` ASC");
		
		foreach ($settings_query->rows as $setting) {
			$value = $setting['value'];
			if ($setting['serialized']) {
				$value = (version_compare(VERSION, '2.1', '<')) ? unserialize($setting['value']) : json_decode($setting['value'], true);
			}
			$split_key = preg_split('/_(\d+)_?/', str_replace($this->name . '_', '', $setting['key']), -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
			
				if (count($split_key) == 1)	$settings[$split_key[0]] = $value;
			elseif (count($split_key) == 2)	$settings[$split_key[0]][$split_key[1]] = $value;
			elseif (count($split_key) == 3)	$settings[$split_key[0]][$split_key[1]][$split_key[2]] = $value;
			elseif (count($split_key) == 4)	$settings[$split_key[0]][$split_key[1]][$split_key[2]][$split_key[3]] = $value;
			else 							$settings[$split_key[0]][$split_key[1]][$split_key[2]][$split_key[3]][$split_key[4]] = $value;
		}
		
		return $settings;
	}
}
?>