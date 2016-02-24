<?php
class ControllerSaleOrderExt extends Controller {
  protected $alert = array(
    'danger'    => array(),
    'warning'   => array(),
    'success'   => array(),
    'info'      => array()
  );

  public function add() {
    $this->action('add');
  }

  public function delete() {
    $this->action('delete');
  }

  public function update() {
    $this->action('update');
  }

  private function action($action) {
    $response = array('success' => false);

    $response['debug'] = array(
      'action' => $action,
      'data' => $this->request->post,
      'request_method' => $this->request->server['REQUEST_METHOD'],
      'validateAction' => $this->validateAction($action, $this->request->post)
    );

    if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateAction($action, $this->request->post)) {
      $this->load->language('sale/order_ext');

      $this->load->model('sale/order');
      $this->load->model('sale/order_ext');

      $result = array();

      switch ($action) {
        case 'add':
          $language_id = $this->model_sale_order_ext->getOrderLanguage($this->request->post['order_id']);

          $product_info = $this->model_sale_order_ext->getLocalizedProduct($this->request->post['product_id'], $language_id);

          $option = false;

          if (isset($this->request->post['option'])) {
            $option = array();

            foreach ($this->request->post['option'] as $product_option_id => $product_option_value_ids) {

              if (!is_array($product_option_value_ids)) {
                $product_option_value_ids = [$product_option_value_ids];
              }

              foreach ($product_option_value_ids as $product_option_value_id) {
                $option_info = $this->model_sale_order_ext->getLocalizedOption($product_option_id, $product_option_value_id, $language_id);
                $option[] = array(
                  'product_option_id'       => $product_option_id,
                  'product_option_value_id' => $product_option_value_id,
                  'name'                    => $option_info['name'],
                  'value'                   => $option_info['value'],
                  'type'                    => $option_info['type']
                );
              }
            }
          }

          $data = array(
            'order_id'    => (int)$this->request->post['order_id'],
            'product_id'  => (int)$this->request->post['product_id'],
            'name'        => $product_info['name'],
            'model'       => $product_info['model'],
            'option'      => $option,
            'quantity'    => (int)$this->request->post['quantity'],
            'price'       => (int)$product_info['price'],
            'total'       => (int)$product_info['price'] * (int)$this->request->post['quantity']
          );

          $result[] = $this->model_sale_order_ext->addProduct($data);
          // if product subtract_stock == true, update stock according to difference
          break;

        case 'delete':
          $data = array('id' => $this->request->post['id']);
          $result[] = $this->model_sale_order_ext->deleteProduct($data);
          // if product subtract_stock == true, update stock according to difference
          break;

        case 'update':
          if ($this->request->post['table'] == 'product') {
            $data = array(
              'order_product_id'  => (int)$this->request->post['id'],
              'price'             => (int)$this->request->post['price'],
              'quantity'          => (int)$this->request->post['quantity'],
              'total'             => (int)$this->request->post['price'] * (int)$this->request->post['quantity']
            );
            $result[] = $this->model_sale_order_ext->editProduct($data);
            // if product subtract_stock == true, update stock according to difference
          } else if ($this->request->post['table'] == 'total') {
            $data = array(
              'order_id'        => (int)$this->request->post['order_id'],
              'order_total_id'  => (int)$this->request->post['id'],
              'title'           => $this->request->post['title'],
              'value'           => (int)$this->request->post['value']
            );
            $result[] = $this->model_sale_order_ext->editTotal($data);
          } else if ($this->request->post['table'] == 'order') {
            $data = array(
              'order_id'  => (int)$this->request->post['order_id'],
              'id'        => $this->request->post['id'],
              'value'     => $this->request->post['value']
            );
            $result[] = $this->model_sale_order_ext->editOrder($data);
          }
          break;
      }

      if (!empty($result)) {
        $response['success'] = true;

        $totals = $this->updateOrderTotals($this->request->post['order_id']);

        if ($totals != false) {
          $this->load->model('sale/order_ext');

          foreach ($totals as $key => $value) {
            $result[] = $this->model_sale_order_ext->editTotal($value);

            // table total shipping was updated, update table order shipping
            if ($key == 'shipping') {
              $result[] = array(
                'action'  => 'update',
                'table'   => 'order',
                'id'      => $key,
                'columns' => array(
                  'value' => $value['title']
                )
              );
            }
          }
        }

        $response['result'] = $result;

        $this->alert['success'] = $this->language->get('error_success');
      } else {
        $this->alert['info'] = $this->language->get('error_nothing');
      }
    }
    $response = array_merge($response, array("alerts" => $this->alert));

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($response));

    return;
  }

  protected function updateOrderTotals($order_id) {
    $result = false;

    $this->load->model('sale/order');

    $totals = $this->model_sale_order->getOrderTotals($order_id);

    if (!empty($totals)) {
      $result = array();
      $sub_total = 0;
      $other_total = 0;
      $weight = 0;

      $products = $this->model_sale_order->getOrderProducts($order_id);

      $this->load->model('catalog/product');

      foreach ($products as $product) {
        $product_info = $this->model_catalog_product->getProduct($product['product_id']);

        $sub_total += $product['total'];
        $weight += $this->weight->convert($product_info['weight'] * $product['quantity'], $product_info['weight_class_id'], $this->config->get('config_weight_class_id'));
      }

      foreach ($totals as $total) {
        if ($total['code'] == 'sub_total') {
          if ($sub_total != (int)$total['value']) {
            $result['sub_total'] = array(
              'order_total_id' => $total['order_total_id'],
              'value'          => $sub_total
            );
          }
        } else if ($total['code'] == 'total') {
          $result['total'] = array(
            'order_id'        => $order_id,
            'order_total_id'  => $total['order_total_id'],
            'code'            => $total['code'],
            'value'           => $total['value']
          );
        } else if ($total['code'] == 'shipping') {
          $this->load->model('sale/order_ext');

          $exp = '/(.+)\(([\d.]+kg)\)$/';
          $match = array();
          preg_match($exp, $total['title'], $match);
          $weight = $this->weight->format($weight, $this->config->get('config_weight_class_id'));

          if (!empty($match) && $weight != $match[2]) {
            $result['shipping'] = array(
              'order_id'        => $order_id,
              'order_total_id'  => $total['order_total_id'],
              'code'            => $total['code'],
              'title'           => $match[1] . '(' . $weight . ')',
              'value'           => $total['value']
            );
          }

          $other_total += $total['value'];
        } else {
          $other_total += $total['value'];
        }
      }

      if (isset($result['total']) && $result['total']['value'] != $sub_total + $other_total) {
        $result['total']['value'] = $sub_total + $other_total;
      } else {
        unset($result['total']);
      }
    }

    return $result;
  }

  protected function validatePermission() {
    $errors = false;

    if (!$this->user->hasPermission('modify', 'sale/order')) {
      $errors = true;
      $this->alert['danger'] = $this->language->get('error_permission');
    }

    return !$errors;
  }

  protected function validateAction($action, $data) {
    $errors = !$this->validatePermission();

    $this->load->language('sale/order_ext');

    $this->load->model('sale/order_ext');

    switch ($action) {
      case 'add':
        if (!isset($data['order_id']) || !isset($data['product_id']) || !isset($data['quantity'])) {
          $errors = true;
          $this->alert['danger'] = $this->language->get('error_data');
        } else {
          $stock_info = $this->model_sale_order_ext->getStock($data['product_id']);

          if ($stock_info['subtract'] == 1 && (int)$data['quantity'] > $stock_info['quantity']) {
            $errors = true;
            $this->alert['warning'] = $this->language->get('error_quantity');
          }
        }

        break;

      case 'delete':
        if (!isset($data['id'])) {
          $errors = true;
          $this->alert['danger'] = $this->language->get('error_data');
        }
        break;

      case 'update':
        if (!isset($data['table'])) {
          $errors = true;
          $this->alert['danger'] = $this->language->get('error_data');
        } else if ($data['table'] == 'product') {
          if (!isset($data['id']) || !isset($data['price']) || !isset($data['quantity'])) {
            $errors = true;
            $this->alert['danger'] = $this->language->get('error_data');
          } else {
            $product_info = $this->model_sale_order_ext->getProduct($data['id']);
            $stock_info = $this->model_sale_order_ext->getStock($product_info['product_id']);

            if ($stock_info['subtract'] == 1 && $stock_info['quantity'] < (int)$data['quantity'] - $product_info['quantity']) {
              $errors = true;
              $this->alert['warning'] = $this->language->get('error_quantity');
            }
          }
        } else if ($data['table'] == 'total') {
          if (!isset($data['id']) || !isset($data['title']) || !isset($data['value'])) {
            $errors = true;
            $this->alert['danger'] = $this->language->get('error_data');
          }
        } else if ($data['table'] == 'order') {
          if (!isset($data['value'])) {
            $errors = true;
            $this->alert['danger'] = $this->language->get('error_data');
          }
        }

        break;
    }

    return !$errors;
  }

}