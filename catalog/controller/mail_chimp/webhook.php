<?php
class ControllerMailChimpWebhook extends Controller {
	private $error = array();

	public function index() {
		if ($this->validate()) {
			switch ($this->request->post['type']) {
				case 'subscribe':$this->subscribe($this->request->post['data']);
					break;
				case 'unsubscribe':$this->unsubscribe($this->request->post['data']);
					break;
				case 'cleaned':$this->cleaned($this->request->post['data']);
					break;
				case 'upemail':$this->upemail($this->request->post['data']);
					break;
				case 'profile':$this->profile($this->request->post['data']);
					break;
				default:
					$this->wh_log('Request type "' . $this->request->post['type'] . '" unknown, ignoring.');
			}
		}
	}

	function wh_log($msg) {
		/* $logfile = $_SERVER['DOCUMENT_ROOT'] . '/webhook.log';
	file_put_contents($logfile, date("Y-m-d H:i:s") . " | " . var_export($this->request->post, true) . "\n", FILE_APPEND); */
	}

	function subscribe($data) {

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = 1 WHERE email = '" . $this->db->escape($data['email']) . "' LIMIT 1");

/*
//$this->wh_log($data['list_id'] . ' just subscribed!');
$customer_group_id = 0;
$customer_group_approved = 0;
$cgs = $this->db->query("SELECT customer_group_id, approval FROM " . DB_PREFIX . "customer_group");
if($cgs->num_rows){
foreach($cgs->rows as $cg){
$mailchimp_list_id = trim($this->config->get('mailchimp_list_'.$cg['customer_group_id']));
if($mailchimp_list_id == $data['list_id']){
$customer_group_id = $cg['customer_group_id'];
$customer_group_approved = $cg['approval']; break;
}
}
}
$first_name = '';
if(isset($data['merges']['FNAME'])){
$first_name = $data['merges']['FNAME'];
}
$last_name = '';
if(isset($data['merges']['LNAME'])){
$last_name = $data['merges']['LNAME'];
}

if($first_name!='' || $last_name!=''){
$data['password'] = '123456';
$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$customer_group_id . "', store_id = '" . (int)$this->config->get('config_store_id') . "', firstname = '" . $this->db->escape($first_name) . "', lastname = '" . $this->db->escape($last_name) . "', email = '" . $this->db->escape($data['email']) , salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', newsletter = 1, ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved = '" . (int)!$customer_group_approved . "', date_added = NOW()");
}

 */
	}
	function unsubscribe($data) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = 0 WHERE email = '" . $this->db->escape($data['email']) . "' LIMIT 1");
	}
	function cleaned($data) {
		$this->wh_log($data['email'] . ' was cleaned from your list!');
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = 0 WHERE email = '" . $this->db->escape($data['email']) . "' LIMIT 1");
	}
	function upemail($data) {
		$this->wh_log($data['old_email'] . ' changed their email address to ' . $data['new_email'] . '!');
	}
	function profile($data) {
		$this->wh_log($data['email'] . ' updated their profile!');
	}

	protected function validate() {
		if (trim($this->request->get['key']) != 'WEBBYMAILCHIMP') {
			return false;
		}

		if (!isset($this->request->post['type'])) {
			return false;
		}

		return true;
	}
}