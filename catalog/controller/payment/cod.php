<?php
class ControllerPaymentCOD extends Controller {
  public function index() {
    $data['action']='index.php?route=checkout/success';
    $data['continue'] = $this->url->link('checkout/success');
    $data['button_confirm']=$this->language->get('button_confirm');


    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/cod.tpl')) {
      return $this->load->view($this->config->get('config_template') . '/template/payment/cod.tpl', $data);
    } else {
      return $this->load->view('default/template/payment/cod.tpl', $data);
    }
  }

  public function confirm() {
    if ($this->session->data['payment_method']['code'] == 'cod') {
      $this->load->model('checkout/order');
      $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->session->data['payment_method']['order_status_id']);
    }
  }
}
?>