<?php
class ControllerTotalCODFee extends Controller {
  private $error = array();

  public function index() {
    $this->language->load('total/cod_fee');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('setting/setting');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      $this->model_setting_setting->editSetting('cod_fee', $this->request->post);
      $this->session->data['success'] = $this->language->get('text_success');
      $this->response->redirect($this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'));
    }

    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_home'),
      'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
    );

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_total'),
      'href' => $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL')
    );

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('heading_title'),
      'href' => $this->url->link('total/cod_fee', 'token=' . $this->session->data['token'], 'SSL')
    );

    $data['heading_title'] = $this->language->get('heading_title');

    $data['text_enabled'] = $this->language->get('text_enabled');
    $data['text_disabled'] = $this->language->get('text_disabled');

    $data['entry_status'] = $this->language->get('entry_status');
    $data['entry_sort_order'] = $this->language->get('entry_sort_order');

    $data['button_save'] = $this->language->get('button_save');
    $data['button_cancel'] = $this->language->get('button_cancel');

    if (isset($this->error['warning'])) {
      $data['error_warning'] = $this->error['warning'];
    } else {
      $data['error_warning'] = '';
    }

    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_home'),
      'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
    );

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_total'),
      'href' => $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL')
    );

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('heading_title'),
      'href' => $this->url->link('total/cod_fee', 'token=' . $this->session->data['token'], 'SSL')
    );

    $data['action'] = $this->url->link('total/cod_fee', 'token=' . $this->session->data['token'], 'SSL');

    $data['cancel'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');

    if (isset($this->request->post['cod_fee_status'])) {
      $data['cod_fee_status'] = $this->request->post['cod_fee_status'];
    } else {
      $data['cod_fee_status'] = $this->config->get('cod_fee_status');
    }

    if (isset($this->request->post['cod_fee_sort_order'])) {
      $data['cod_fee_sort_order'] = $this->request->post['cod_fee_sort_order'];
    } else {
      $data['cod_fee_sort_order'] = $this->config->get('cod_fee_sort_order');
    }


    //-----------------------Render--------------------------
    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('total/cod_fee.tpl', $data));
  }

  protected function validate() {
    if (!$this->user->hasPermission('modify', 'total/cod_fee')) {
      $this->error['warning'] = $this->language->get('error_permission');
    }

    if (!$this->error) {
      return true;
    } else {
      return false;
    }
  }
}
?>
