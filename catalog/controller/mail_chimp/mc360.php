<?php
class ControllerMailChimpMC360 extends Controller {
	public function index() {
		if (isset($this->request->get['mc_cid'])) {
			$this->storeCookie('mc_ecomm_cid', trim($this->request->get['mc_cid']));
		}

		if (isset($this->request->get['mc_eid'])) {
			$this->storeCookie('mc_ecomm_eid', trim($this->request->get['mc_eid']));
		}
	}

	function storeCookie($name, $value) {
		$expire_cookie = strtotime('+30 Days');
		$server        = str_replace('www.', '', $this->request->server['HTTP_HOST']);
		setcookie($name, $value, $expire_cookie, '/', '.' . $server);
		setcookie($name, $value, $expire_cookie, '/', $server);
		setcookie($name, $value, $expire_cookie, '/');
	}
}
?>