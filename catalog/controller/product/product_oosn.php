<?php
class ControllerProductProductOosn extends Controller {

	public function index() {
		$email = $_POST['data'];
		$pid = $_POST['product_id'];
		$selected_option_value = html_entity_decode($_POST['selected_option_value']);
		$selected_option = strip_tags($_POST['selected_option']);
		$selected_option = preg_replace('/(\s)+/', ' ', $selected_option);
		$all_selected_option = strip_tags($_POST['all_selected_option']);
		$all_selected_option = preg_replace('/(\s)+/', ' ', $all_selected_option);


		if (isset($_POST['name'])){
			$fname = $_POST['name'];
		}else {
			$fname = '';
		}

		if (isset($_POST['phone'])){
			$phone = $_POST['phone'];
			$phone = preg_replace('/\D/', '', $phone);
		}else {
			$phone = '';
		}

		$this->load->model('catalog/product');
		$this->language->load('module/hb_oosnm');
		$product_info = $this->model_catalog_product->getProduct($pid);
		$product_name = $product_info['name'];
		$sku = $product_info['sku'];
		//date_default_timezone_set('Asia/Calcutta');  //time zone for India
		$datetime = date("Y-m-d H:i:s");
		$language_code = $this->language->get('code');

		$formset = 1;
		$text = '';

		if ((strlen(trim($_POST['data'])) < 5) || (utf8_strlen($_POST['data']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $_POST['data'])) {
			$text = $this->language->get('oosn_valid_email');
			$formset = 0;
		}

		if ($this->config->get('hb_oosn_name_enable') == 'y') {
			if ((utf8_strlen($fname) < 3) || (utf8_strlen($fname) > 96)) {
				$text = $this->language->get('oosn_valid_name');
				$formset = 0;
			}
		}

		if ($this->config->get('hb_oosn_mobile_enable') == 'y') {
			$hb_oosn_mobile_validation = $this->config->get('hb_oosn_mobile_validation');
			if (utf8_strlen($phone) <> $hb_oosn_mobile_validation) {
				$text = $this->language->get('oosn_valid_phone');
				$formset = 0;
			}
		}

		//check duplicate entry
		$counts = $this->db->query("SELECT count(*) as count FROM `" . DB_PREFIX . "out_of_stock_notify` WHERE product_id = '".$pid."' and selected_option_value = '".$this->db->escape($selected_option_value)."' and selected_option = '".$this->db->escape($selected_option)."' and all_selected_option = '".$this->db->escape($all_selected_option)."' and email = '".$email."' and fname = '".$fname."' and phone = '".$phone."' and language_code = '".$language_code."'");
		if ($counts->row['count'] > 0){
			$text = $this->language->get('oosn_duplicate_entry');
			$formset = 0;
		}


		if  ($formset == 1){
			$this->db->query("INSERT INTO `" . DB_PREFIX . "out_of_stock_notify` (product_id, selected_option_value, selected_option, all_selected_option, email, fname, phone, language_code, enquiry_date) VALUES ('".$pid."','".$this->db->escape($selected_option_value)."','".$this->db->escape($selected_option)."','".$this->db->escape($all_selected_option)."','".$email."','".$fname."','".$phone."','".$language_code."','".$datetime."')");

			$admin_language = md5($this->config->get('config_admin_language'));

			$mail_body = $this->config->get('hb_oosn_admin_email_body_'.$admin_language);
			$mail_body = str_replace("{product_name}",$product_name,$mail_body);
			$mail_body = str_replace("{product_id}",$pid,$mail_body);
			$mail_body = str_replace("{sku}",$sku,$mail_body);
			$mail_body = str_replace("{customer_email}",$email,$mail_body);
			$mail_body = str_replace("{option}",$selected_option,$mail_body);
			$mail_body = str_replace("{all_option}",$all_selected_option,$mail_body);
			$mail_body = str_replace("{fname}",$fname,$mail_body);
			$mail_body = str_replace("{phone}",$phone,$mail_body);

			$this->hbemail('TEXT', $this->config->get('config_email'), $this->config->get('config_email'), $this->config->get('hb_oosn_admin_email_subject_'.$admin_language), $mail_body);

			$text  = $this->language->get('oosn_thanks_text');
		}

			$json['success'] = $text;
			$this->response->setOutput(json_encode($json));
}


	public function resetdate(){
		$this->db->query("UPDATE `" . DB_PREFIX . "out_of_stock_notify` SET `notified_date` = NULL WHERE `notified_date` = '0000-00-00 00:00:00';");
		echo 'NOTIFIED DATE SET TO NULL WHERE 0000-00-00 00:00:00';
	}

	public function autonotify(){
			$this->load->model('wgi/hb_oosn');
			$products = $this->model_wgi_hb_oosn->getUniqueId(); //gets all null notified date records
			foreach ($products as $product){
				$oosn_id = $product['oosn_id'];
				$product_id = $product['product_id'];
				$selected_option = $product['selected_option'];  // selected option text format
				$selected_option_value = $product['selected_option_value'];// selected option values json

				$hb_oosn_stock_status = $this->config->get('hb_oosn_stock_status');
				$hb_oosn_product_qty = $this->config->get('hb_oosn_product_qty');

				$stockstatus = $this->model_wgi_hb_oosn->getStockStatus($product_id);

				if ($stockstatus){
					$qty = $stockstatus['quantity'];
					$stock_status_id = $stockstatus['stock_status_id'];
				}else {
					$qty = $hb_oosn_product_qty - 1;
					$stock_status_id = 0;
				}

				if ($hb_oosn_stock_status ==  '0'){
					$stock_status_id = 0;
				}

			if (($selected_option == '0') or (empty($selected_option)) ) { //option check
				if (($qty >= $hb_oosn_product_qty) and ($stock_status_id == $hb_oosn_stock_status)){
					$this->sendNotificationtoCustomers($oosn_id);
				}
			}//option check
			else { //option exsists
				//$optionstockstatus = $this->model_wgi_hb_oosn->getOptionStockStatus($product_id, $product_option_value_id, $product_option_id);
				$set_mail_active = 1;

				$options = explode('|',$selected_option_value);
				foreach ($options as $option){
					$json = $option;
					$obj = json_decode($json);
					$product_id = $obj->pi;
					$product_option_id = $obj->poi;
					$product_option_value_id = $obj->povi;

					$no_selection_option = 0;
					$optionstockstatus = $this->model_wgi_hb_oosn->getOptionStockStatus($product_id, $product_option_value_id, $product_option_id);

					if ($optionstockstatus){
						$optionstockstatus_qty = $optionstockstatus['quantity'];
					}else {
						$optionstockstatus_qty = $hb_oosn_product_qty - 1;
						$no_selection_option = 1;
					}

					if (($optionstockstatus_qty >= $hb_oosn_product_qty) or ($no_selection_option == 1)) {
						$set_mail_active = $set_mail_active*1;
					}else{
						$set_mail_active = 0;
					}

				}

				if ($set_mail_active == 1) {
					$this->sendNotificationtoCustomers($oosn_id);
				}
			}// option exists, end else

			}// end of looping of all unique products

			echo 'done'; // yym_custom
	}

public function sendNotificationtoCustomers($oosn_id){
	$this->load->model('wgi/hb_oosn');
	$emaillists = $this->model_wgi_hb_oosn->getemail($oosn_id);
	foreach ($emaillists as $emaillist){
		$product_id = $emaillist['product_id'];
		$customer_email = $emaillist['email'];
		$customer_name = $emaillist['fname'];
		$customer_phone = $emaillist['phone'];
		if (empty($emaillist['fname'])){
			$customer_name = '';
		}

		if (strlen($emaillist['selected_option']) > 3){
			$selected_option = $emaillist['selected_option'];
			$all_selected_option = $emaillist['all_selected_option'];
		}else {
			$selected_option = $all_selected_option = '';
		}
		$oosn_id = $emaillist['oosn_id'];
		$customer_language_id = $emaillist['language_id'];

		$product_details = $this->model_wgi_hb_oosn->getProductDetails($product_id,$customer_language_id);
		$pname = $product_details['name'];
		$store_id = $this->model_wgi_hb_oosn->getProductStore($product_id);
		$store_url = $this->model_wgi_hb_oosn->getStoreUrl($store_id);

		if(empty($store_url)){
			$store_url = $this->config->get('config_url');
		}
		$link = $store_url.'index.php?route=product/product&product_id='.$product_id;
		$pmodel = $product_details['model'];
		$pimage = $product_details['image'];

		if (!empty($pimage)){
			$pimagelink = $store_url.'/image/'.$pimage;
			$pimagelink = str_replace("//image","/image",$pimagelink);
			$showimage = '<img height="'.$this->config->get('hb_oosn_product_image_h_'.$customer_language_id).'px" src="'.$pimagelink.'" width="'.$this->config->get('hb_oosn_product_image_w_'.$customer_language_id).'px" />';
		} else{
			$pimagelink = $store_url.'/image/no_image.jpeg';
			$showimage = '<img height="'.$this->config->get('hb_oosn_product_image_h_'.$customer_language_id).'px" src="'.$pimagelink.'" width="'.$this->config->get('hb_oosn_product_image_w_'.$customer_language_id).'px" />';
		}

			$mail_body = $this->config->get('hb_oosn_customer_email_body_'.$customer_language_id);
			$mail_body = str_replace("{product_name}",$pname,$mail_body);
			$mail_body = str_replace("{customer_name}",$customer_name,$mail_body);
			$mail_body = str_replace("{model}",$pmodel,$mail_body);
			$mail_body = str_replace("{option}",$selected_option,$mail_body);
			$mail_body = str_replace("{all_option}",$all_selected_option,$mail_body);
			$mail_body = str_replace("{image_url}",$pimagelink,$mail_body);
			$mail_body = str_replace("{show_image}",$showimage,$mail_body);
			$mail_body = str_replace("{link}",$link,$mail_body);

			$mail_subject =  $this->config->get('hb_oosn_customer_email_subject_'.$customer_language_id);
			$mail_subject = str_replace("{product_name}",$pname,$mail_subject);

			$message  = '<html dir="ltr" lang="en">' . "\n";
			$message .= '  <head>' . "\n";
			$message .= '    <title>' . $mail_subject . '</title>' . "\n";
			$message .= '    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
			$message .= '  </head>' . "\n";
			$message .= '  <body>' . html_entity_decode($mail_body, ENT_QUOTES, 'UTF-8') . '</body>' . "\n";
			$message .= '</html>' . "\n";

            // yym_custom
            $data['logo'] = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo');
            $data['logo_width'] = floor((int)$this->config->get('config_image_location_width') / 2);
            $data['logo_height'] = floor((int)$this->config->get('config_image_location_height') / 2);
            $data['store_name'] = $this->config->get('store_name');
            $data['store_url'] = $this->config->get('config_url');
            $data['text_message'] = html_entity_decode($mail_body, ENT_QUOTES, 'UTF-8');

            $data['header'] = $this->load->view($this->config->get('config_template') . '/template/mail/header.tpl', $data);
            $data['footer'] = $this->load->view($this->config->get('config_template') . '/template/mail/footer.tpl', $data);

            $html = $this->load->view($this->config->get('config_template') . '/template/mail/product_oosn.tpl', $data);

			if (preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $customer_email)) {
				$this->hbemail('HTML',$customer_email, $this->config->get('config_email'), $mail_subject, $html); // yym_custom
			}

			if (($this->config->get('hb_oosn_sms_enable') == 'y') and (strlen($customer_phone) == $this->config->get('hb_oosn_mobile_validation'))){
				$sms =  $this->config->get('hb_oosn_customer_sms_body_'.$customer_language_id);
				$pname = (strlen($pname) > 20) ? substr($pname,0,20).'...' : $pname;
				$pmodel = (strlen($pmodel) > 10) ? substr($pmodel,0,10).'...' : $pmodel;
				$selected_option = (strlen($selected_option) > 15) ? substr($selected_option,0,14).'...' : $selected_option;
				$all_selected_option = (strlen($all_selected_option) > 15) ? substr($all_selected_option,0,14).'...' : $all_selected_option;

				$sms = str_replace("{product_name}",$pname,$sms);
				$sms = str_replace("{link}",$link,$sms);
				$sms = str_replace("{model}",$pmodel,$sms);
		    	$sms = str_replace("{option}",$selected_option,$sms);
		    	$sms = str_replace("{all_option}",$all_selected_option,$sms);
			    $sms = preg_replace('/\s+/', ' ',$sms);
				$sms = urlencode($sms);

				$url = html_entity_decode($this->config->get('config_hb_sms_http_api'));
				$url = str_replace("{to}",$customer_phone,$url);
				$url = str_replace("{msg}",$sms,$url);
				$returnedoutput = file_get_contents($url);
			}

		$this->model_wgi_hb_oosn->updatenotifieddate($oosn_id);
	}
}

	public function checkstock() {
		$option_id = $_POST['option_id'];
		$option_value_id = $_POST['option_value_id'];
		$option_value = strip_tags($_POST['option_value']);
		$option_selected = strip_tags($_POST['option_selected']);
		$pid = $_POST['product_id'];

		$query = $this->db->query("SELECT a.quantity, b.stock_status_id from " . DB_PREFIX . "product_option_value a, " . DB_PREFIX . "product b WHERE a.product_id = b.product_id and a.product_id = $pid and a.product_option_id = '".$option_id."' and a.product_option_value_id = '".$option_value_id."' LIMIT 1");
		$qty = $query->row['quantity'];
		$stock_status_id = $query->row['stock_status_id'];

		$hb_oosn_stock_status = $this->config->get('hb_oosn_stock_status');
		$hb_oosn_product_qty = $this->config->get('hb_oosn_product_qty');
		if ($hb_oosn_stock_status ==  '0'){
			$stock_status_id = 0;
		}

		if (($qty < $hb_oosn_product_qty) and ($stock_status_id == $hb_oosn_stock_status)){
			$json['success'] = 1;
		}else{
			$json['nochange'] = 1;
		}

		$this->response->setOutput(json_encode($json));
	}

	public function hbemail($type, $to, $from, $subject, $body){
		if ((VERSION == '2.0.0.0') or (VERSION == '2.0.1.0') or (VERSION == '2.0.1.1')){
			$mail = new Mail($this->config->get('config_mail'));
			$mail->setTo($to);
			$mail->setFrom($from);
			$mail->setSender($this->config->get('config_name'));
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			if ($type == 'TEXT'){
				$mail->setText(strip_tags($body));
			} else {
				$mail->setHtml($body);
			}
			$mail->send();
		}elseif (VERSION == '2.0.2.0') {
			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_host');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($to);
			$mail->setFrom($from);
			$mail->setSender($this->config->get('config_name'));
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			if ($type == 'TEXT'){
				$mail->setText($body);
			} else {
				$mail->setHtml($body);
			}
			$mail->send();
		}
		else {
			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($to);
			$mail->setFrom($from);
			$mail->setSender($this->config->get('config_name'));
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			if ($type == 'TEXT'){
				$mail->setText($body);
			} else {
				$mail->setHtml($body);
			}
			$mail->send();
		}
	}

}
?>