<?php
class ControllerShippingOversize extends Controller {
  private $error = array();

  public function install(){
    $this->load->model('shipping/oversize');
    $this->model_shipping_oversize->createTable();
  }

  public function index() {
    $this->load->language('shipping/oversize');
    $this->document->setTitle($this->language->get('heading_title'));
    $this->load->model('setting/setting');
    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      if(!isset($this->request->post['oversize_geo_zone_id'])) {
        $this->request->post['oversize_geo_zone_id'] = array("0");
      }
      $this->model_setting_setting->editSetting('oversize', $this->request->post);
      $this->session->data['success'] = $this->language->get('text_success');
      $this->response->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
    }
    $data['heading_title']                      = $this->language->get('heading_title');
    $data['text_edit']                          = $this->language->get('text_edit');
    $data['text_enabled']                       = $this->language->get('text_enabled');
    $data['text_disabled']                      = $this->language->get('text_disabled');
    $data['text_all_zones']                     = $this->language->get('text_all_zones');
    $data['text_none']                          = $this->language->get('text_none');
    $data['entry_total']                        = $this->language->get('entry_total');
    $data['entry_geo_zone']                     = $this->language->get('entry_geo_zone');
    $data['entry_status']                       = $this->language->get('entry_status');
    $data['entry_text_label']                   = $this->language->get('entry_text_label');
    $data['entry_hide_other_shipping_methods']  = $this->language->get('entry_hide_other_shipping_methods');
    $data['help_text_label']                    = $this->language->get('help_text_label');
    $data['default_text_label']                 = $this->language->get('default_text_label');
    $data['entry_sort_order']                   = $this->language->get('entry_sort_order');
    $data['button_save']                        = $this->language->get('button_save');
    $data['button_cancel']                      = $this->language->get('button_cancel');
//  $data['entry_notes']                        = $this->language->get('entry_notes');
//  $data['text_notes']                         = $this->language->get('text_notes');
    $data['entry_oversize_shipping_products']   = $this->language->get('entry_oversize_shipping_products');
    $data['text_select_all']                    = $this->language->get('text_select_all');
    $data['text_unselect_all']                  = $this->language->get('text_unselect_all');

    if (isset($this->error['warning'])) {
      $data['error_warning'] = $this->error['warning'];
    } else {
      $data['error_warning'] = '';
    }
    $data['breadcrumbs'] = array();
    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_home'),
      'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      'separator' => false
    );
    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_shipping'),
      'href'      => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'),
      'separator' => ' :: '
    );
    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('heading_title'),
      'href'      => $this->url->link('shipping/oversize', 'token=' . $this->session->data['token'], 'SSL'),
      'separator' => ' :: '
    );
    $data['action'] = $this->url->link('shipping/oversize', 'token=' . $this->session->data['token'], 'SSL');
    $data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');

    if (isset($this->request->post['oversize_geo_zone_id'])) {
      $data['oversize_geo_zone_id'] = $this->request->post['oversize_geo_zone_id'];
    } elseif($this->config->has('oversize_geo_zone_id')) {
      $data['oversize_geo_zone_id'] = $this->config->get('oversize_geo_zone_id');
    }
    else {
      $data['oversize_geo_zone_id'] = array(0);
    }

    $this->load->model('localisation/language');
    $data['languages'] = $this->model_localisation_language->getLanguages();

    $this->load->model('localisation/geo_zone');
    $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
    if (isset($this->request->post['oversize_status'])) {
      $data['oversize_status'] = $this->request->post['oversize_status'];
    } else {
      $data['oversize_status'] = $this->config->get('oversize_status');
    }
    if (isset($this->request->post['oversize_text_label'])) {
      $data['oversize_text_label'] = $this->request->post['oversize_text_label'];
    } else {
      $data['oversize_text_label'] = $this->config->get('oversize_text_label');
    }
    if (isset($this->request->post['oversize_sort_order'])) {
      $data['oversize_sort_order'] = $this->request->post['oversize_sort_order'];
    } else {
      $data['oversize_sort_order'] = $this->config->get('oversize_sort_order');
    }

    $data['oversize_shipping_products'] = array();
    $this->load->model('shipping/oversize');
    $data['oversize_shipping_products'] = $this->model_shipping_oversize->getOversizeShippingProducts();
    $temp_index = 0;
    foreach($data['oversize_shipping_products'] as $oversize_shipping_product){
      $data['oversize_shipping_products'][$temp_index++]['href'] = $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'].'&product_id='.$oversize_shipping_product['product_id'], 'SSL');
    }

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');
    $this->response->setOutput($this->load->view('shipping/oversize.tpl', $data));
  }
  protected function validate() {
    if (!$this->user->hasPermission('modify', 'shipping/oversize')) {
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