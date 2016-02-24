<?php
class ControllerPaymentCOD extends Controller {
  private $error = array();

  public function index() {
    $this->language->load('payment/cod');

    $this->load->model('setting/setting');

    $this->document->setTitle($this->language->get('heading_title'));

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      $this->model_setting_setting->editSetting('cod', $this->request->post);
      $this->session->data['success'] = $this->language->get('text_success');
      $this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
    }

    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_home'),
      'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
    );

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_payment'),
      'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL')
    );

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('heading_title'),
      'href' => $this->url->link('payment/cod', 'token=' . $this->session->data['token'], 'SSL')
    );

    $data['heading_title'] = $this->language->get('heading_title');

    $data['tab_general'] = $this->language->get('tab_general');
    $data['tab_extension'] = $this->language->get('tab_extension');

    $data['entry_extension_status'] = $this->language->get('entry_extension_status');
    $data['entry_extension_status_info'] = $this->language->get('entry_extension_status_info');
    $data['entry_status'] = $this->language->get('entry_status');
    $data['entry_status_info'] = $this->language->get('entry_status_info');
    $data['entry_shipping_geo_zone'] = $this->language->get('entry_shipping_geo_zone');
    $data['entry_shipping_geo_zone_info'] = $this->language->get('entry_shipping_geo_zone_info');
    $data['entry_customer_group'] = $this->language->get('entry_customer_group');
    $data['entry_customer_group_info'] = $this->language->get('entry_customer_group_info');
    $data['entry_tax_class'] = $this->language->get('entry_tax_class');
    $data['entry_tax_class_info'] = $this->language->get('entry_tax_class_info');
    $data['entry_method'] = $this->language->get('entry_method');
    $data['entry_method_info'] = $this->language->get('entry_method_info');
    $data['entry_flat_rate'] = $this->language->get('entry_flat_rate');
    $data['entry_flat_rate_info'] = $this->language->get('entry_flat_rate_info');
    $data['entry_percentage'] = $this->language->get('entry_percentage');
    $data['entry_percentage_info'] = $this->language->get('entry_percentage_info');
    $data['entry_custom'] = $this->language->get('entry_custom');
    $data['entry_custom_info'] = $this->language->get('entry_custom_info');
    $data['entry_enable_rule'] = $this->language->get('entry_enable_rule');
    $data['entry_enable_rule_info'] = $this->language->get('entry_enable_rule_info');
    $data['entry_order_total'] = $this->language->get('entry_order_total');
    $data['entry_order_total_info'] = $this->language->get('entry_order_total_info');
    $data['entry_order_total_sort_order'] = $this->language->get('entry_order_total_sort_order');
    $data['entry_order_total_sort_order_info'] = $this->language->get('entry_order_total_sort_order_info');
    $data['entry_order_status'] = $this->language->get('entry_order_status');
    $data['entry_order_status_info'] = $this->language->get('entry_order_status_info');
    $data['entry_sort_order'] = $this->language->get('entry_sort_order');
    $data['entry_sort_order_info'] = $this->language->get('entry_sort_order_info');

    $data['text_none'] = $this->language->get('text_none');
    $data['text_default'] = $this->language->get('text_default');
    $data['text_enabled'] = $this->language->get('text_enabled');
    $data['text_disabled'] = $this->language->get('text_disabled');
    $data['text_all_zones'] = $this->language->get('text_all_zones');

    $data['button_save'] = $this->language->get('button_save');
    $data['button_cancel'] = $this->language->get('button_cancel');

    $this->load->model('localisation/geo_zone');

    $geo_zones = Array ( 0 => Array ( "geo_zone_id" => 0, "name" => $data['text_all_zones'], "description" => $data['text_all_zones'], "date_modified" => '0000-00-00 00:00:00', "date_added" => '0000-00-00 00:00:00' ));
    $geo_zones = array_merge($geo_zones,$this->model_localisation_geo_zone->getGeoZones());

    $data['geo_zones'] = $geo_zones;

    $this->load->model('sale/customer_group');

    $customer_groups = $this->model_sale_customer_group->getCustomerGroups();

    $data['customer_groups'] = $customer_groups;
    $data['action'] = $this->url->link('payment/cod', 'token=' . $this->session->data['token'], 'SSL');
    $data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

    $this->load->model('extension/extension');

    $extensions = $this->model_extension_extension->getInstalled('shipping');

    foreach ($extensions as $key => $value) {
      if (!file_exists(DIR_APPLICATION . 'controller/shipping/' . $value . '.php')) {
        $this->model_setting_extension->uninstall('shipping', $value);
        unset($extensions[$key]);
      }
    }

    $data['extensions'] = array();
    $data['extensions'][] = array(
      'name'  => 'noshipping',
      'title' => $this->language->get('tab_noshipping'),
    );

    $files = glob(DIR_APPLICATION . 'controller/shipping/*.php');

    if ($files) {
      foreach ($files as $file) {
        $extension = basename($file, '.php');
        $this->language->load('shipping/' . $extension);
        if (in_array($extension, $extensions)) {
          $data['extensions'][] = array(
            'name'  => $extension,
            'title' => $this->language->get('heading_title')
          );
        }
      }
    }

    $geo_zones = Array ( 0 => Array ( "geo_zone_id" => 0, "name" => $data['text_all_zones'], "description" => $data['text_all_zones'], "date_modified" => '0000-00-00 00:00:00', "date_added" => '0000-00-00 00:00:00' ));
    $geo_zones = array_merge($geo_zones,$this->model_localisation_geo_zone->getGeoZones());

    $this->language->load('payment/cod');

    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency");

    $currencies=array();
    foreach ($query->rows as $result)
    {
      $currencies[$result['code']] = array(
        'currency_id'   => $result['currency_id'],
        'title'         => $result['title'],
        'symbol_left'   => $result['symbol_left'],
        'symbol_right'  => $result['symbol_right'],
        'decimal_place' => $result['decimal_place'],
        'value'         => $result['value']
      );
    }

    $data['currencies'] = $currencies;

    $this->load->model('localisation/tax_class');

    $data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

    $this->load->model('localisation/order_status');

    $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();


    //----------------------Data-----------------------

    //Default
    if (isset($this->request->post['cod_status'])) {
      $data['cod_status'] = $this->request->post['cod_status'];
    } else {
      $data['cod_status'] = $this->config->get('cod_status');
    }

    if (isset($this->request->post['cod_default_status'])) {
      $data['cod_default_status'] = $this->request->post['cod_default_status'];
    } else {
      $data['cod_default_status'] = $this->config->get('cod_default_status');
    }

    if (isset($this->request->post['cod_default_shipping_geo_zone'])) {
      $data['cod_default_shipping_geo_zone'] = $this->request->post['cod_default_shipping_geo_zone'];
    } else {
      $data['cod_default_shipping_geo_zone'] = $this->config->get('cod_default_shipping_geo_zone');
    }

    if (isset($this->request->post['cod_default_sort_order'])) {
      $data['cod_default_sort_order'] = $this->request->post['cod_default_sort_order'];
    } else {
      $data['cod_default_sort_order'] = $this->config->get('cod_default_sort_order');
    }

    foreach ($geo_zones as $geo_zone) {
      foreach ($customer_groups as $customer_group) {
        if (isset($this->request->post['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_tax_class_id'])) {
          $data['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_tax_class_id'] = $this->request->post['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_tax_class_id'];
        } else {
          $data['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_tax_class_id'] = $this->config->get('cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_tax_class_id');
        }

        if (isset($this->request->post['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_method'])) {
          $data['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_method'] = $this->request->post['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_method'];
        } else {
          $data['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_method'] = $this->config->get('cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_method');
        }

        if (isset($this->request->post['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat'])) {
          $data['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat'] = $this->request->post['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat'];
        } else {
          $data['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat'] = $this->config->get('cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat');
        }

        if (isset($this->request->post['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat_currency'])) {
          $data['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat_currency'] = $this->request->post['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat_currency'];
        } else {
          $data['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat_currency'] = $this->config->get('cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat_currency');
        }

        if (isset($this->request->post['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_percent'])) {
          $data['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_percent'] = $this->request->post['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_percent'];
        } else {
          $data['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_percent'] = $this->config->get('cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_percent');
        }

        if (isset($this->request->post['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_custom'])) {
          $data['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_custom'] = $this->request->post['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_custom'];
        } else {
          $data['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_custom'] = $this->config->get('cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_custom');
        }

        if (isset($this->request->post['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_enable_rule'])) {
          $data['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_enable_rule'] = $this->request->post['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_enable_rule'];
        } else {
          $data['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_enable_rule'] = $this->config->get('cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_enable_rule');
        }

        if (isset($this->request->post['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_status_id'])) {
          $data['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_status_id'] = $this->request->post['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_status_id'];
        } else {
          $data['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_status_id'] = $this->config->get('cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_status_id');
        }

        if (isset($this->request->post['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total'])) {
          $data['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total'] = $this->request->post['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total'];
        } else {
          $data['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total'] = $this->config->get('cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total');
        }

        if (isset($this->request->post['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total_sort_order'])) {
          $data['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total_sort_order'] = $this->request->post['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total_sort_order'];
        } else {
          $data['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total_sort_order'] = $this->config->get('cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total_sort_order');
        }

        if (isset($this->request->post['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_status'])) {
          $data['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_status'] = $this->request->post['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_status'];
        } else {
          $data['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_status'] = $this->config->get('cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_status');
        }
      }
    }

    //Extensions
    foreach($data['extensions'] as $extension) {
      if (isset($this->request->post['cod_'.$extension['name'].'_status'])) {
        $data['cod_'.$extension['name'].'_status'] = $this->request->post['cod_'.$extension['name'].'_status'];
      } else {
        $data['cod_'.$extension['name'].'_status'] = $this->config->get('cod_'.$extension['name'].'_status');
      }

      if (isset($this->request->post['cod_'.$extension['name'].'_shipping_geo_zone'])) {
        $data['cod_'.$extension['name'].'_shipping_geo_zone'] = $this->request->post['cod_'.$extension['name'].'_shipping_geo_zone'];
      } else {
        $data['cod_'.$extension['name'].'_shipping_geo_zone'] = $this->config->get('cod_'.$extension['name'].'_shipping_geo_zone');
      }

      if (isset($this->request->post['cod_'.$extension['name'].'_sort_order'])) {
        $data['cod_'.$extension['name'].'_sort_order'] = $this->request->post['cod_'.$extension['name'].'_sort_order'];
      } else {
        $data['cod_'.$extension['name'].'_sort_order'] = $this->config->get('cod_'.$extension['name'].'_sort_order');
      }

      foreach ($geo_zones as $geo_zone) {
        foreach ($customer_groups as $customer_group) {
          if (isset($this->request->post['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_tax_class_id'])) {
            $data['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_tax_class_id'] = $this->request->post['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_tax_class_id'];
          } else {
            $data['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_tax_class_id'] = $this->config->get('cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_tax_class_id');
          }

          if (isset($this->request->post['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_method'])) {
            $data['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_method'] = $this->request->post['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_method'];
          } else {
            $data['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_method'] = $this->config->get('cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_method');
          }

          if (isset($this->request->post['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat'])) {
            $data['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat'] = $this->request->post['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat'];
          } else {
            $data['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat'] = $this->config->get('cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat');
          }

          if (isset($this->request->post['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat_currency'])) {
            $data['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat_currency'] = $this->request->post['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat_currency'];
          } else {
            $data['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat_currency'] = $this->config->get('cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat_currency');
          }

          if (isset($this->request->post['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_percent'])) {
            $data['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_percent'] = $this->request->post['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_percent'];
          } else {
            $data['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_percent'] = $this->config->get('cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_percent');
          }

          if (isset($this->request->post['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_custom'])) {
            $data['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_custom'] = $this->request->post['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_custom'];
          } else {
            $data['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_custom'] = $this->config->get('cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_custom');
          }

          if (isset($this->request->post['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_enable_rule'])) {
            $data['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_enable_rule'] = $this->request->post['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_enable_rule'];
          } else {
            $data['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_enable_rule'] = $this->config->get('cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_enable_rule');
          }

          if (isset($this->request->post['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_status_id'])) {
            $data['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_status_id'] = $this->request->post['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_status_id'];
          } else {
            $data['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_status_id'] = $this->config->get('cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_status_id');
          }

          if (isset($this->request->post['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total'])) {
            $data['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total'] = $this->request->post['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total'];
          } else {
            $data['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total'] = $this->config->get('cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total');
          }

          if (isset($this->request->post['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total_sort_order'])) {
            $data['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total_sort_order'] = $this->request->post['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total_sort_order'];
          } else {
            $data['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total_sort_order'] = $this->config->get('cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total_sort_order');
          }

          if (isset($this->request->post['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_status'])) {
            $data['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_status'] = $this->request->post['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_status'];
          } else {
            $data['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_status'] = $this->config->get('cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_status');
          }
        }
      }
    }

    //----------------------Errors-----------------------
    if (isset($this->error['warning'])) {
      $data['error_warning'] = $this->error['warning'];
    } else {
      $data['error_warning'] = '';
    }

    //Default
    if (isset($this->error['cod_default_sort_order'])) {
      $data['cod_default_sort_order_error'] = $this->error['cod_default_sort_order'];
    } else {
      $data['cod_default_sort_order_error'] = '';
    }

    $geo_zones = Array ( 0 => Array ( "geo_zone_id" => 0, "name" => $data['text_all_zones'], "description" => $data['text_all_zones'], "date_modified" => '0000-00-00 00:00:00', "date_added" => '0000-00-00 00:00:00' ));
    $geo_zones = array_merge($geo_zones,$this->model_localisation_geo_zone->getGeoZones());

    foreach ($geo_zones as $geo_zone) {
      foreach ($customer_groups as $customer_group) {
        if (isset($this->error['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat'])) {
          $data['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat_error'] = $this->error['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat'];
        } else {
          $data['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat_error'] ='';
        }

        if (isset($this->error['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_percent'])) {
          $data['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_percent_error'] = $this->error['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_percent'];
        } else {
          $data['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_percent_error'] ='';
        }

        if (isset($this->error['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total_sort_order'])) {
          $data['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total_sort_order_error'] = $this->error['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total_sort_order'];
        } else {
          $data['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total_sort_order_error'] ='';
        }
      }
    }

    //Extensions
    foreach($data['extensions'] as $extension) {
      if (isset($this->error['cod_'.$extension['name'].'_sort_order'])) {
        $data['cod_'.$extension['name'].'_sort_order_error'] = $this->error['cod_'.$extension['name'].'_sort_order'] ;
      } else {
        $data['cod_'.$extension['name'].'_sort_order_error'] ='';
      }

      foreach ($geo_zones as $geo_zone) {
        foreach ($customer_groups as $customer_group) {
          if (isset($this->error['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat'])) {
            $data['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat_error'] =$this->error['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat'];
          } else {
            $data['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat_error'] = '';
          }

          if (isset($this->error['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_percent'])) {
            $data['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_percent_error'] =$this->error['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_percent'];
          } else {
            $data['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_percent_error'] = '';
          }

          if (isset($this->error['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total_sort_order'])) {
            $data['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total_sort_order_error'] =$this->error['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total_sort_order'];
          } else {
            $data['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total_sort_order_error'] = '';
          }
        }
      }
    }

    //-----------------------Render--------------------------
    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('payment/cod.tpl', $data));
  }

  protected function validate() {

    $this->load->model('extension/extension');

    $extensions = $this->model_extension_extension->getInstalled('shipping');

    foreach ($extensions as $key => $value) {
      if (!file_exists(DIR_APPLICATION.'controller/shipping/'.$value.'.php')) {
        $this->model_setting_extension->uninstall('shipping', $value);
        unset($extensions[$key]);
      }
    }

    $data['extensions'] = array();
    $data['extensions'][] = array(
      'name'  => 'noshipping',
      'title' => $this->language->get('tab_noshipping'),
    );

    $files = glob(DIR_APPLICATION . 'controller/shipping/*.php');

    if ($files) {
      foreach ($files as $file) {
        $extension = basename($file, '.php');

        $this->language->load('shipping/'.$extension);
        if (in_array($extension, $extensions)) {
          $data['extensions'][] = array(
            'name'  => $extension,
            'title' => $this->language->get('heading_title'),
          );
        }
      }
    }

    $this->language->load('payment/cod');

    $data['text_all_zones'] = $this->language->get('text_all_zones');

    $this->load->model('sale/customer_group');

    $customer_groups = $this->model_sale_customer_group->getCustomerGroups();

    $this->load->model('localisation/geo_zone');

    $geo_zones = Array ( 0 => Array ( "geo_zone_id" => 0, "name" => $data['text_all_zones'], "description" => $data['text_all_zones'], "date_modified" => '0000-00-00 00:00:00', "date_added" => '0000-00-00 00:00:00' ));
    $geo_zones = array_merge($geo_zones,$this->model_localisation_geo_zone->getGeoZones());

    if (!$this->user->hasPermission('modify', 'payment/cod')) {
      $this->error['warning'] = $this->language->get('error_permission');
    }

    //Default

    if (isset($this->request->post['cod_default_sort_order']) && !is_numeric($this->request->post['cod_default_sort_order']) && $this->request->post['cod_default_sort_order']!='') {
      $this->error['cod_default_sort_order'] = $this->language->get('error_number');
    }

    foreach ($geo_zones as $geo_zone) {
      foreach ($customer_groups as $customer_group) {
        if (isset($this->request->post['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat']) && !is_numeric($this->request->post['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat']) && $this->request->post['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat']!='') {
          $this->error['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat'] = $this->language->get('error_number');
        }

        if (isset($this->request->post['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_percent']) && !is_numeric($this->request->post['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_percent']) && $this->request->post['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_percent']!='') {
          $this->error['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_percent'] = $this->language->get('error_number');
        }

        if (isset($this->request->post['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total_sort_order']) && !is_numeric($this->request->post['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total_sort_order']) && $this->request->post['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total_sort_order']!='') {
          $this->error['cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total_sort_order'] = $this->language->get('error_number');
        }
      }
    }

    //Extensions
    foreach($data['extensions'] as $extension) {
      if (isset($this->request->post['cod_'.$extension['name'].'_sort_order']) && !is_numeric($this->request->post['cod_'.$extension['name'].'_sort_order']) && $this->request->post['cod_'.$extension['name'].'_sort_order']!='') {
        $this->error['cod_'.$extension['name'].'_sort_order'] = $this->language->get('error_number');
      }

      foreach ($geo_zones as $geo_zone) {
        foreach ($customer_groups as $customer_group) {
          if (isset($this->request->post['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat']) && !is_numeric($this->request->post['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat']) && $this->request->post['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat']!='' && $this->request->post['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_method']==1) {
            $this->error['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat'] = $this->language->get('error_number');
          }

          if (isset($this->request->post['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_percent']) && !is_numeric($this->request->post['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_percent']) && $this->request->post['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_percent']!='' && $this->request->post['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_method']==2) {
            $this->error['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_percent'] = $this->language->get('error_number');
          }

          if (isset($this->request->post['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total_sort_order']) && !is_numeric($this->request->post['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total_sort_order']) && $this->request->post['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total_sort_order']!='') {
            $this->error['cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total_sort_order'] = $this->language->get('error_number');
          }
        }
      }
    }

    if (!$this->error) {
      return true;
    } else {
      return false;
    }
  }

  public function confirm() {
    $this->load->model('checkout/order');

    $this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('cod_order_status_id'));
  }
}
?>