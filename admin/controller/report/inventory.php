<?php
class ControllerReportInventory extends Controller {
  public function index() {
    $this->load->language('report/inventory');

    $this->document->setTitle($this->language->get('heading_title'));

    $data = array();

    if ($this->request->server['HTTPS']) {
      $data['base'] = HTTPS_SERVER;
    } else {
      $data['base'] = HTTP_SERVER;
    }

    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('text_home'),
        'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
    );

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('heading_title'),
        'href' => $this->url->link('report/inventory', 'token=' . $this->session->data['token'], 'SSL')
    );

    $data['heading_title'] = $this->language->get('heading_title');

    $data['text_internal'] = $this->language->get('text_internal');
    $data['text_markup'] = $this->language->get('text_markup');
    $data['text_outofstock'] = $this->language->get('text_outofstock');
    $data['text_onsale'] = $this->language->get('text_onsale');

    $data['column_model'] = $this->language->get('column_model');
    $data['column_name'] = $this->language->get('column_name');
    $data['column_cost'] = $this->language->get('column_cost');
    $data['column_quantity'] = $this->language->get('column_quantity');
    $data['column_total'] = $this->language->get('column_total');
    $data['column_profit'] = $this->language->get('column_profit');
    $data['column_supplier'] = $this->language->get('column_supplier');
    $data['column_type'] = $this->language->get('column_type');
    $data['column_price'] = $this->language->get('column_price');
    $data['column_sale'] = $this->language->get('column_sale');
    $data['column_date_end'] = $this->language->get('column_date_end');

    $data['internal_href'] = $this->url->link('report/inventory/internal', 'token=' . $this->session->data['token'], 'SSL');

    // internal
    $count_internal_items = 0;
    $total_internal = 0;

    // markup
    $total_product_cost = 0;
    $total_product_price = 0;
    $config_markup_low = $this->config->get('config_markup_low');
    $config_markup_high = $this->config->get('config_markup_high');

    // out of stock
    $count_enabled = 0;
    $config_stock_low = $this->config->get('config_stock_low');
    $config_stock_high = $this->config->get('config_stock_high');

    // sales
    $total_sale_price = 0;
    $total_sale_sale_price = 0;

    // product arrays
    $data['products_int'] = array();
    $data['products_low'] = array();
    $data['products_out'] = array();
    $data['products_sale'] = array();

    $sort = array(
      'int' => array(
        'model' => array(),
        'section' => array()
      ),
      'low' => array(
        'profit' => array()
      ),
      'out' => array(
        'supplier' => array(),
        'model' => array()
      ),
      'sale' => array(
        'date_end' => array()
      )
    );

    $this->load->model('catalog/product');

    $filter_data = array();

    $products = $this->model_catalog_product->getProducts($filter_data);

    foreach ($products as $product) {
      if ($product['status'] && strpos($product['model'], 'IKEA-') === false) {
        $count_enabled += 1;

        $product_data = $product;

        $product_data['color'] = (strpos($product['location'], '-') !== false) ? strtolower(explode('-', $product['location'])[0]) : strtolower($product['location']);
        $product_data['href'] = $this->url->link('catalog/product/edit', 'product_id=' . $product['product_id'] . '&token=' . $this->session->data['token'], 'SSL');
        $product_data['cost'] = $this->currency->format($product['cost'], $this->config->get('config_currency'));
        $product_data['price'] = $this->currency->format($product['price'], $this->config->get('config_currency'));

        // internal
        if (strpos($product['location'], 'Orange') !== false && $product['quantity'] > 0) {
          $total_internal += (int)$product['cost'] * (int)$product['quantity'];
          $count_internal_items += $product['quantity'];

          $product_data['total'] = $this->currency->format((int)$product['cost'] * (int)$product['quantity'], $this->config->get('config_currency'));
          $data['products_int'][] = $product_data;

          $section = 0;
          if (strpos($product['location'], '-') !== false) {
            $parse = explode('-', $product['location']);
            if (is_numeric($parse[1])) {
              $section = (int)$parse[1];
            }
          }
          $sort['int']['model'][] = $product['model'];
          $sort['int']['section'][] = $section;
        }

        // discounts
        $discounts = $this->model_catalog_product->getProductDiscounts($product['product_id']);

        foreach ($discounts as $discount) {
          if (($discount['date_start'] == '0000-00-00' || $discount['date_start'] < date('Y-m-d')) && ($discount['date_end'] == '0000-00-00' || $discount['date_end'] > date('Y-m-d'))) {
            $total_sale_price += $product['price'];
            $total_sale_sale_price += $discount['price'];

            $product['sale_price'] = $discount['price'];
            $product_data['sale_price'] = $this->currency->format($discount['price'], $this->config->get('config_currency'));
            $product_data['sale_end'] = $discount['date_end'];
            $product_data['sale_type'] = 'Quantity ' . $discount['quantity'] . '+';
            $data['products_sale'][] = $product_data;

            $sort['sale']['date_end'][] = $discount['date_end'];
          }
        }

        // specials
        $specials = $this->model_catalog_product->getProductSpecials($product['product_id']);

        foreach ($specials as $special) {
          if (($special['date_start'] == '0000-00-00' || $special['date_start'] < date('Y-m-d')) && ($special['date_end'] == '0000-00-00' || $special['date_end'] > date('Y-m-d'))) {
            $total_sale_price += $product['price'];
            $total_sale_sale_price += $special['price'];

            $product['sale_price'] = $special['price'];
            $product_data['sale_price'] = $this->currency->format($special['price'], $this->config->get('config_currency'));
            $product_data['sale_end'] = $special['date_end'];
            $product_data['sale_type'] = 'Sale';
            $data['products_sale'][] = $product_data;

            $sort['sale']['date_end'][] = $special['date_end'];
            break;
          }
        }

        // markup
        if ($product['cost'] > 0) {
          $total_product_cost += $product['cost'];
          $total_product_price += $product['price'];
        }

        $price = (isset($product['sale_price']) ? $product['sale_price'] : $product['price']);
        $profit = ((int)$product['cost'] > 0) ? round(100 * ($price - $product['cost']) / $product['cost']) : 0;

        if ($profit < $config_markup_high) {
          $product_data['class'] = ($profit >= $config_markup_low) ? 'warning' : 'danger';
          $product_data['profit'] = $profit . '%';
          $data['products_low'][] = $product_data;

          $sort['low']['profit'][] = $profit;
        }

        // out of stock
        if ($product['subtract'] && $product['minimum'] > $product['quantity']) {
          if ($product['quantity']) $product_data['name'] .= '&ensp;<span class="text-danger"><i class="fa fa-cubes"></i></span>';
          $data['products_out'][] = $product_data;

          $sort['out']['supplier'][] = $product['supplier'];
          $sort['out']['model'][] = $product['model'];
        }
      }
    }

    // sorting here
    array_multisort($sort['int']['section'], SORT_ASC, $sort['int']['model'], SORT_ASC, $data['products_int']);
    array_multisort($sort['low']['profit'], SORT_ASC, $data['products_low']);
    array_multisort($sort['out']['supplier'], SORT_ASC, $sort['out']['model'], SORT_ASC, $data['products_out']);
    array_multisort($sort['sale']['date_end'], SORT_ASC, $data['products_sale']);

    // internal totals
    $data['internal_count'] = sprintf($this->language->get('entry_internal_count'), count($data['products_int']), $count_internal_items);
    $data['internal_total'] = sprintf($this->language->get('entry_internal_total'), $this->currency->format($total_internal, $this->config->get('config_currency')));

    // out of stock totals
    $total_instock = round(100 * ($count_enabled - count($data['products_out'])) / $count_enabled);

    if ($total_instock < $config_stock_low) {
      $class = 'danger';
      $icon = 'exclamation-triangle';
    } else if ($total_instock < $config_stock_high) {
      $class = 'warning';
      $icon = 'exclamation-triangle';
    } else {
      $class = 'success';
      $icon = 'check-circle';
    }

    $data['outofstock_count'] = sprintf($this->language->get('entry_outofstock_count'), count($data['products_out']), $count_enabled);
    $data['outofstock_total'] = sprintf($this->language->get('entry_outofstock_total'), $total_instock . '%', $class, $icon);

    // markup totals
    $total_markup = round(100 * ($total_product_price - $total_product_cost) / $total_product_cost);

    if ($total_markup < $config_markup_low) {
      $class = 'danger';
      $icon = 'exclamation-triangle';
    } else if ($total_markup < $config_markup_high) {
      $class = 'warning';
      $icon = 'exclamation-triangle';
    } else {
      $class = 'success';
      $icon = 'check-circle';
    }

    $data['markup_count'] = sprintf($this->language->get('entry_markup_count'), count($data['products_low']), $config_markup_high . '%');
    $data['markup_total'] = sprintf($this->language->get('entry_markup_total'), $total_markup . '%', $class, $icon);

    // sales totals
    $total_special = round(100 * ($total_sale_price - $total_sale_sale_price) / $total_sale_price);
    $data['special_count'] = sprintf($this->language->get('entry_special_count'), count($data['products_sale']));
    $data['special_total'] = sprintf($this->language->get('entry_special_total'), $total_special . '%');



    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('report/inventory.tpl', $data));
  }

  // yym_admin_product
  public function internal() {
    $data = array();

    if ($this->request->server['HTTPS']) {
      $data['base'] = HTTPS_SERVER;
    } else {
      $data['base'] = HTTP_SERVER;
    }

    $this->load->model('catalog/product');

    $filter_data = array(
      'filter_location' => 'Orange'
    );

    $product_data = $this->model_catalog_product->getProducts($filter_data);

    $total_internal = 0;

    $sort = array(
      'section' => array(),
      'model'   => array()
    );

    foreach ($product_data as $key => $row) {
      if ($row['status'] && $row['quantity'] > 0 && strpos($row['model'], 'IKEA-') === false) {
        $section = 0;

        if (strpos($row['location'], '-') !== false) {
          $parse = explode('-', $row['location']);
          if (is_numeric($parse[1])) {
            $section = (int)$parse[1];
          }
        }

        $sort['section'][$key] = $section;
        $sort['model'][$key] = $row['model'];

        $total_internal += (int)$row['cost'] * (int)$row['quantity'];

        $product_data[$key]['cost'] = $this->currency->format($row['cost'], $this->config->get('config_currency'));
        $product_data[$key]['total'] = $this->currency->format((int)$row['cost'] * (int)$row['quantity'], $this->config->get('config_currency'));
      } else {
        unset($product_data[$key]);
      }
    }

    array_multisort($sort['section'], SORT_ASC, $sort['model'], SORT_ASC, $product_data);

    $data['products'] = $product_data;
    $data['total_internal'] = $this->currency->format($total_internal, $this->config->get('config_currency'));

    $data['date'] = date($this->language->get('date_format_short'));

    $this->response->setOutput($this->load->view('report/inventory_internal.tpl', $data));
  }

}