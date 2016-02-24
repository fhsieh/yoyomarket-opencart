<?php
class ControllerOlarkOlark extends Controller {
  public function index() {
    $this->load->language('olark/olark');

    $data = array();

    $data['welcome_title'] = $this->language->get('welcome_title');
    $data['chatting_title'] = $this->language->get('chatting_title');
    $data['unavailable_title'] = $this->language->get('unavailable_title');
    $data['away_message'] = $this->language->get('away_message');
    $data['welcome_message'] = $this->language->get('welcome_message');
    $data['chat_input_text'] = $this->language->get('chat_input_text');
    $data['name_input_text'] = $this->language->get('name_input_text');
    $data['email_input_text'] = $this->language->get('email_input_text');
    $data['phone_input_text'] = $this->language->get('phone_input_text');
    $data['offline_note_message'] = $this->language->get('offline_note_message');
    $data['send_button_text'] = $this->language->get('send_button_text');
    $data['offline_note_thankyou_text'] = $this->language->get('offline_note_thankyou_text');
    $data['offline_note_error_text'] = $this->language->get('offline_note_error_text');
    $data['offline_note_sending_text'] = $this->language->get('offline_note_sending_text');
    $data['operator_is_typing_text'] = $this->language->get('operator_is_typing_text');
    $data['operator_has_stopped_typing_text'] = $this->language->get('operator_has_stopped_typing_text');
    $data['introduction_error_text'] = $this->language->get('introduction_error_text');
    $data['introduction_messages'] = $this->language->get('introduction_messages');
    $data['introduction_submit_button_text'] = $this->language->get('introduction_submit_button_text');
    $data['disabled_input_text_when_convo_has_ended'] = $this->language->get('disabled_input_text_when_convo_has_ended');
    $data['disabled_panel_text_when_convo_has_ended'] = $this->language->get('disabled_panel_text_when_convo_has_ended');
    $data['ended_chat_message'] = $this->language->get('ended_chat_message');

    $data['logged'] = $this->customer->isLogged();

    if ($data['logged']) {
      $data['name'] = $this->customer->getFirstName() . ' ' . $this->customer->getLastName();
      $data['email'] = $this->customer->getEmail();
      $data['telephone'] = $this->customer->getTelephone();
      $data['status'] = '"Customer is logged in, Customer ID: ' . $this->customer->getId() . '"';
    } else {
      $data['status'] = '"Customer is not logged in"';
    }

    if ($this->cart->countProducts()) {
      $data['cart'] = '"' . $this->cart->countProducts() . ' item(s) in cart", "Cart total: ' . $this->currency->format($this->cart->getTotal(), $this->config->get('config_currency')) . '"';
    } else {
      $data['cart'] = '"No items in cart"';
    }

    if (isset($this->request->get['route'])) {
      if ($this->request->get['route'] == 'quickcheckout/checkout') {
        $data['checkout'] = true;
      }

      if ($this->request->get['route'] == 'information/contact') {
        $data['expand'] = true;
      }
    }

    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/olark/olark.tpl')) {
      return $this->load->view($this->config->get('config_template') . '/template/olark/olark.tpl', $data);
    } else {
      return $this->load->view('default/template/olark/olark.tpl', $data);
    }
  }
}