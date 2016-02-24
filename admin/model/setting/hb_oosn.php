<?php
class ModelSettingHbOosn extends Model {
	public function getReports($data = array(),$filteroption) {
		$sql = "SELECT a.product_id, b.name, a.selected_option, a.all_selected_option, a.email, a.fname, a.phone, a.language_code, a.enquiry_date, a.notified_date FROM `" . DB_PREFIX . "out_of_stock_notify` a, `" . DB_PREFIX . "product_description` b where a.product_id = b.product_id and b.language_id = (SELECT language_id FROM `" . DB_PREFIX . "language` WHERE code = (SELECT `value` FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_admin_language')) ";
        
        if ($filteroption == 'archive'){
            $sql.= " and a.notified_date IS NOT NULL ";
        }elseif ($filteroption == 'awaiting'){
            $sql.= " and a.notified_date IS NULL ";
        }
        
        $sql.= "ORDER BY a.enquiry_date DESC ";
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
	
	public function getTotalReports($filteroption) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "out_of_stock_notify";
        
        if ($filteroption == 'archive'){
            $sql.= " WHERE notified_date IS NOT NULL ";
        }elseif ($filteroption == 'awaiting'){
            $sql.= " WHERE notified_date IS NULL ";
        }
        
        $query = $this->db->query($sql);

		return $query->row['total'];
	}
    
    public function getTotalAlert() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "out_of_stock_notify");

        return $query->row['total'];
	}
    
    public function getTotalResponded() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "out_of_stock_notify WHERE notified_date IS NOT NULL");

        return $query->row['total'];
	}
    
    public function getCustomerNotified() {
        $query = $this->db->query("SELECT COUNT(distinct(email)) AS total FROM " . DB_PREFIX . "out_of_stock_notify WHERE notified_date IS NOT NULL");

    	return $query->row['total'];
	}
    
    public function getTotalRequested() {
    	$query = $this->db->query("SELECT COUNT(distinct(product_id)) AS total FROM " . DB_PREFIX . "out_of_stock_notify WHERE notified_date IS NULL");

		return $query->row['total'];
	}
    public function getAwaitingNotification() {
        $query = $this->db->query("SELECT COUNT(distinct(email)) AS total FROM " . DB_PREFIX . "out_of_stock_notify WHERE notified_date IS NULL");

		return $query->row['total'];
	}
    
    public function getDemandedList() {
        $query = $this->db->query("SELECT distinct(a.product_id) as pid, b.name, 
        (SELECT COUNT(distinct(email)) from " . DB_PREFIX . "out_of_stock_notify 
        WHERE product_id = pid) AS count FROM " . DB_PREFIX . "out_of_stock_notify a, " . DB_PREFIX . "product_description b 
        where a.product_id = b.product_id and 
        b.language_id = (SELECT language_id FROM `" . DB_PREFIX . "language` WHERE code = (SELECT `value` FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_admin_language')) and a.notified_date IS NULL ORDER BY count DESC LIMIT 100");

		return $query->rows;
	}
	
	public function deleteRecords($delete) {
        if ($delete == 'all'){
		    $this->db->query("DELETE FROM " . DB_PREFIX . "out_of_stock_notify");
        }elseif ($delete == 'archive'){
            $this->db->query("DELETE FROM " . DB_PREFIX . "out_of_stock_notify WHERE notified_date IS NOT NULL");
        }elseif ($delete == 'awaiting'){
            $this->db->query("DELETE FROM " . DB_PREFIX . "out_of_stock_notify WHERE notified_date IS NULL");
        }
	}	

	
	public function getLanguagess() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language`");
		return $query->rows;
	}

	/////////////////////////////////////////
	
	public function install(){
		if ((VERSION == '2.0.0.0') or (VERSION == '2.0.1.0')){
			$code_column = 'group';
		}else {
			$code_column = 'code';
		}
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "out_of_stock_notify` (
			  `oosn_id` int(11) NOT NULL AUTO_INCREMENT,
			  `product_id` int(11) NOT NULL,
			  `selected_option_value` TEXT NULL,
			  `selected_option` TEXT NULL,
			  `all_selected_option` TEXT NULL,
			  `email` varchar(50) NOT NULL,
			  `fname` varchar(50) NULL,
			  `phone` varchar(20) NULL,
			  `language_code` VARCHAR(10) NOT NULL,
			  `enquiry_date` datetime NOT NULL,
			  `notified_date` datetime DEFAULT NULL,
			  PRIMARY KEY (`oosn_id`)
			)DEFAULT CHARSET=utf8");
		
		 $this->db->query("INSERT INTO `" . DB_PREFIX . "setting` (`setting_id`, `store_id`, `".$code_column."`, `key`, `value`, `serialized`) VALUES (NULL, '0', 'hb_oosn', 'hb_oosn_name_enable', 'y', '0')"); 
		 $this->db->query("INSERT INTO `" . DB_PREFIX . "setting` (`setting_id`, `store_id`, `".$code_column."`, `key`, `value`, `serialized`) VALUES (NULL, '0', 'hb_oosn', 'hb_oosn_mobile_enable', 'n', '0')"); 
		 $this->db->query("INSERT INTO `" . DB_PREFIX . "setting` (`setting_id`, `store_id`, `".$code_column."`, `key`, `value`, `serialized`) VALUES (NULL, '0', 'hb_oosn', 'hb_oosn_sms_enable', 'n', '0')");
		 $this->db->query("INSERT INTO `" . DB_PREFIX . "setting` (`setting_id`, `store_id`, `".$code_column."`, `key`, `value`, `serialized`) VALUES (NULL, '0', 'hb_oosn', 'hb_oosn_animation', 'mfp-newspaper', '0')");
		 $this->db->query("INSERT INTO `" . DB_PREFIX . "setting` (`setting_id`, `store_id`, `".$code_column."`, `key`, `value`, `serialized`) VALUES (NULL, '0', 'hb_oosn', 'hb_oosn_product_qty', '1', '0')");  
		 $this->db->query("INSERT INTO `" . DB_PREFIX . "setting` (`setting_id`, `store_id`, `".$code_column."`, `key`, `value`, `serialized`) VALUES (NULL, '0', 'hb_oosn', 'hb_oosn_stock_status', '0', '0')"); 
		 $this->db->query("INSERT INTO `" . DB_PREFIX . "setting` (`setting_id`, `store_id`, `".$code_column."`, `key`, `value`, `serialized`) VALUES (NULL, '0', 'hb_oosn', 'hb_oosn_css', '#oosn_info_text, #opt_info{\r\npadding-top:0px;\r\ncolor:#F00;\r\n}\r\n\r\n#msgoosn{\r\ncolor:green;\r\n}', '0')");
		 $this->db->query("INSERT INTO `" . DB_PREFIX . "setting` (`setting_id`, `store_id`, `".$code_column."`, `key`, `value`, `serialized`) VALUES (NULL, '0', 'hb_oosn', 'hb_oosn_mobile_validation', '10', '0')");

		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();
		
		foreach ($languages as $language){
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` (`setting_id`, `store_id`, `".$code_column."`, `key`, `value`, `serialized`) VALUES (NULL, '0', 'hb_oosn', 'hb_oosn_admin_email_subject_".md5($language['code'])."', 'Customer is looking for a product', '0'); ");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` (`setting_id`, `store_id`, `".$code_column."`, `key`, `value`, `serialized`) VALUES (NULL, '0', 'hb_oosn', 'hb_oosn_admin_email_body_".md5($language['code'])."', 'Dear Store Admin,\r\n\r\nOne of your customers is looking for a product. The details of the product are as follows\r\n\r\nProduct ID : {product_id}\r\nProduct Name : {product_name}\r\nProduct SKU : {sku}\r\nCustomer Selected Options: {all_option}\r\nOptions Out of Stock : {option}\r\n\r\nCUSTOMER DETAILS:\r\nCustomer Name : {fname}\r\nCustomer Email : {customer_email}\r\nCustomer Phone : {phone} ', '0'); ");
		
		$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` (`setting_id`, `store_id`, `".$code_column."`, `key`, `value`, `serialized`) VALUES (NULL, '0', 'hb_oosn', 'hb_oosn_customer_email_subject_".$language['language_id']."', 'Your Requested Product is now Available to purchase', '0'); ");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` (`setting_id`, `store_id`, `".$code_column."`, `key`, `value`, `serialized`) VALUES (NULL, '0', 'hb_oosn', 'hb_oosn_customer_email_body_".$language['language_id']."', '&lt;table align=&quot;center&quot; bgcolor=&quot;#f9f9f9&quot; cellpadding=&quot;10px&quot; width=&quot;90%&quot;&gt;\r\n	&lt;tbody&gt;\r\n		&lt;tr&gt;\r\n			&lt;td&gt;\r\n			&lt;p&gt;Hi {customer_name},&lt;/p&gt;\r\n\r\n			&lt;p&gt;The product {product_name} {option} Model: {model}, you requested for is now available for ordering.&lt;/p&gt;\r\n\r\n			&lt;table cellpadding=&quot;10&quot;&gt;\r\n				&lt;tbody&gt;\r\n					&lt;tr&gt;\r\n						&lt;td&gt;{show_image}&lt;/td&gt;\r\n						&lt;td&gt;&lt;b&gt;{product_name}&lt;/b&gt;&lt;br /&gt;\r\n						Model: {model}&lt;br /&gt;\r\n						&lt;br /&gt;\r\n						&lt;a class=&quot;button&quot; href=&quot;{link}&quot;&gt;BUY NOW ! Limited Stock !&lt;/a&gt;&lt;/td&gt;\r\n					&lt;/tr&gt;\r\n				&lt;/tbody&gt;\r\n			&lt;/table&gt;\r\n\r\n			&lt;p&gt;Regards,&lt;/p&gt;\r\n\r\n			&lt;p&gt;Your Store Name&lt;/p&gt;\r\n			&lt;/td&gt;\r\n		&lt;/tr&gt;\r\n	&lt;/tbody&gt;\r\n&lt;/table&gt;\r\n\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n', '0'); ");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` (`setting_id`, `store_id`, `".$code_column."`, `key`, `value`, `serialized`) VALUES (NULL, '0', 'hb_oosn', 'hb_oosn_product_image_h_".$language['language_id']."', '80', '0'); ");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` (`setting_id`, `store_id`, `".$code_column."`, `key`, `value`, `serialized`) VALUES (NULL, '0', 'hb_oosn', 'hb_oosn_product_image_w_".$language['language_id']."', '80', '0'); ");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` (`setting_id`, `store_id`, `".$code_column."`, `key`, `value`, `serialized`) VALUES (NULL, '0', 'hb_oosn', 'hb_oosn_customer_sms_body_".$language['language_id']."', '{product_name} {model} {option} is back in stock. Visit huntbee.com to buy it now.', '0'); ");
		}

	    $this->db->query("INSERT INTO `" . DB_PREFIX . "setting` (`setting_id`, `store_id`, `".$code_column."`, `key`, `value`, `serialized`) VALUES (NULL, '0', 'hb_oosn_installer', 'hb_oosn_installed', '1', '0')"); 
	}
	
	public function uninstall() {
		if ((VERSION == '2.0.0.0') or (VERSION == '2.0.1.0')){
			$code_column = 'group';
		}else {
			$code_column = 'code';
		}
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "out_of_stock_notify`");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `".$code_column."` = 'hb_oosn'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `".$code_column."` = 'hb_oosn_installer'");
	}
	
}
?>