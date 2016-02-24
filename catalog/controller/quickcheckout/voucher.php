<?php
class ControllerQuickCheckoutVoucher extends Controller {
	public function index() {
		$this->language->load('quickcheckout/checkout');

		$points_total = 0;

		foreach ($this->cart->getProducts() as $product) {
			if ($product['points']) {
				$points_total += $product['points'];
			}
		}

      	$data['text_use_coupon'] = $this->language->get('text_use_coupon');
		$data['text_use_voucher'] = $this->language->get('text_use_voucher');
		$data['text_use_reward'] = $this->language->get('text_use_reward');
		$data['entry_coupon'] = $this->language->get('entry_coupon');
		$data['entry_voucher'] = $this->language->get('entry_voucher');
		$data['entry_reward'] = sprintf($this->language->get('entry_reward'), $this->customer->getRewardPoints()); // yym_custom

		if ($points_total && $this->customer->isLogged()) {
			$data['reward'] = true;
		} else {
			$data['reward'] = false;
		}

		// All variables
		$data['voucher_module'] = $this->config->get('quickcheckout_voucher');
		$data['coupon_module'] = $this->config->get('quickcheckout_coupon');
		$data['reward_module'] = $this->config->get('quickcheckout_reward');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/quickcheckout/voucher.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/quickcheckout/voucher.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/quickcheckout/voucher.tpl', $data));
		}
	}

	public function validateCoupon() {
		$this->language->load('quickcheckout/checkout');
		$this->load->model('checkout/coupon');

		$json = array();

		if (!isset($this->request->post['coupon']) || empty($this->request->post['coupon'])) {
			$this->request->post['coupon'] = '';
			$this->session->data['coupon'] = '';
		}

		$coupon_info = $this->model_checkout_coupon->getCoupon($this->request->post['coupon']);

		if (!$coupon_info) {
			$json['error']['warning'] = $this->language->get('error_coupon');
		}

		if (!$json) {
			$this->session->data['coupon'] = $this->request->post['coupon'];

			$json['success'] = $this->language->get('text_coupon');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function validateVoucher() {
		$this->language->load('quickcheckout/checkout');
		$this->load->model('checkout/voucher');

		$json = array();

		if (!isset($this->request->post['voucher']) || empty($this->request->post['voucher'])) {
			$this->request->post['voucher'] = '';
			$this->session->data['voucher'] = '';
		}

		$voucher_info = $this->model_checkout_voucher->getVoucher($this->request->post['voucher']);

		if (!$voucher_info) {
			$json['error']['warning'] = $this->language->get('error_voucher');
		}

		if (!$json) {
			$this->session->data['voucher'] = $this->request->post['voucher'];

			$json['success'] = $this->language->get('text_coupon');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function validateReward() {
		$this->language->load('quickcheckout/checkout');

		$points = $this->customer->getRewardPoints();

		$points_total = 0;

		foreach ($this->cart->getProducts() as $product) {
			if ($product['points']) {
				$points_total += $product['points'];
			}
		}

		$json = array();

		if (empty($this->request->post['reward'])) {
			$json['error']['warning'] = $this->language->get('error_reward');
		}

		if ($this->request->post['reward'] > $points) {
			$json['error']['warning'] = sprintf($this->language->get('error_points'), $this->request->post['reward']);
		}

		if ($this->request->post['reward'] > $points_total) {
			$json['error']['warning'] = sprintf($this->language->get('error_maximum'), $points_total);
		}

		if (!$json) {
			$this->session->data['reward'] = abs($this->request->post['reward']);

			$json['success'] = $this->language->get('text_reward');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}