<?php
class ControllerReportSaleOrder extends Controller {
  public function index() {
    $this->load->language('report/sale_order');

    $this->document->setTitle($this->language->get('heading_title'));

    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('text_home'),
        'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
    );

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('heading_title'),
        'href' => $this->url->link('report/sale_order', 'token=' . $this->session->data['token'], 'SSL')
    );

    $data['heading_title'] = $this->language->get('heading_title');

    $data['column_month'] = $this->language->get('column_month');
    $data['column_orders'] = $this->language->get('column_orders');
    $data['column_sub_total'] = $this->language->get('column_sub_total');
    $data['column_reward'] = $this->language->get('column_reward');
    $data['column_coupon'] = $this->language->get('column_coupon');
    $data['column_voucher'] = $this->language->get('column_voucher');
    $data['column_shipping'] = $this->language->get('column_shipping');
    $data['column_total'] = $this->language->get('column_total');
    $data['column_average'] = $this->language->get('column_average');

    $data['token'] = $this->session->data['token'];

    $data['months'] = array(
      $this->language->get('month_1'),
      $this->language->get('month_2'),
      $this->language->get('month_3'),
      $this->language->get('month_4'),
      $this->language->get('month_5'),
      $this->language->get('month_6'),
      $this->language->get('month_7'),
      $this->language->get('month_8'),
      $this->language->get('month_9'),
      $this->language->get('month_10'),
      $this->language->get('month_11'),
      $this->language->get('month_12')
    );

    $data['year'] = isset($this->request->get['year']) ? $this->request->get['year'] : (int)date('Y');

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('report/sale_order.tpl', $data));
  }

  public function year() {
    $this->load->language('report/sale_order');

    $year = (isset($this->request->get['year'])) ? (int)$this->request->get['year'] : (int)date('Y');

    $json = array();

    $json['chart'] = array(
      'xaxis' => array(),
      'total' => array()
    );

    $include = array_merge($this->config->get('config_history_status'), $this->config->get('config_pending_status'));

    for ($month = 1; $month <= 12; $month++) {
      $orders = $this->db->query("SELECT COUNT(*) as `count` FROM " . DB_PREFIX . "order WHERE order_status_id IN (" . $this->db->escape(implode(',', $include)) . ") AND DATE_FORMAT(date_added, '%Y-%c') = '" . $this->db->escape($year . '-' . $month) . "'")->row['count'];

      $totals = $this->db->query("SELECT ot.code AS code, SUM(ot.value) AS value FROM " . DB_PREFIX . "order_total ot LEFT JOIN " . DB_PREFIX . "order o ON (ot.order_id = o.order_id) WHERE o.order_status_id IN (" . $this->db->escape(implode(',', $include)) . ") AND DATE_FORMAT(o.date_added, '%Y-%c') = '" . $this->db->escape($year . '-' . $month) . "' GROUP BY ot.code")->rows;

      $total_data = array(
        'sub_total' => 0,
        'reward'    => 0,
        'coupon'    => 0,
        'voucher'   => 0,
        'shipping'  => 0,
        'total'     => 0
      );

      foreach ($totals as $total) {
        switch($total['code']) {
          case 'cod_fee':
            $total_data['shipping'] += $total['value'];
            break;
          default:
            $total_data[$total['code']] += $total['value'];
        }
      }

      $json['table'][$month] = array(
        'id'        => '#month_' . $month,
        'month'     => $this->language->get('month_' . $month) . ' ' . $year,
        'href'      => $this->url->link('report/sale_order/month', 'token=' . $this->session->data['token'] . '&year=' . $year . '&month=' . $month, 'SSL'),
        'orders'    => $orders,
        'sub_total' => $this->currency->format($total_data['sub_total'], $this->config->get('config_currency')),
        'reward'    => $this->currency->format($total_data['reward'], $this->config->get('config_currency')),
        'coupon'    => $this->currency->format($total_data['coupon'], $this->config->get('config_currency')),
        'voucher'   => $this->currency->format($total_data['voucher'], $this->config->get('config_currency')),
        'shipping'  => $this->currency->format($total_data['shipping'], $this->config->get('config_currency')),
        'total'     => $this->currency->format($total_data['total'], $this->config->get('config_currency')),
        'average'   => ($orders > 0) ? $this->currency->format(round($total_data['total'] / $orders), $this->config->get('config_currency')) : $this->currency->format(0, $this->config->get('config_currency'))
      );

      $json['chart']['xaxis'][] = $this->language->get('month_' . $month);
      $json['chart']['total'][] = isset($total_data['total']) ? $total_data['total'] : 0;
    }

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }

  public function month() {
    $this->load->language('report/sale_order');

    $data = array();

    if ($this->request->server['HTTPS']) {
      $data['base'] = HTTPS_SERVER;
    } else {
      $data['base'] = HTTP_SERVER;
    }

    $year = isset($this->request->get['year']) ? $this->request->get['year'] : date('Y');
    $month = isset($this->request->get['month']) ? $this->request->get['month'] : date('n');

    $data['heading_title'] = $this->language->get('heading_title') . ': ' . $this->language->get('month_' . $month) . ' ' . $year;

    $include = array_merge($this->config->get('config_history_status'), $this->config->get('config_pending_status'));

    $orders = $this->db->query("SELECT o.order_id AS order_id, o.firstname AS firstname, o.lastname AS lastname, o.payment_method AS payment_method, o.date_added AS date_added, o.date_modified AS date_modified, o.order_status_id AS order_status_id, os.name AS status FROM " . DB_PREFIX . "order o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE DATE_FORMAT(o.date_added, '%Y-%c') = '" . $this->db->escape($year . '-' . $month) . "' AND o.order_status_id IN (" . $this->db->escape(implode(',', $include)) . ") AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.date_added ASC")->rows;

    $data['orders'] = array();

    foreach ($orders as $order) {
      $totals = $this->db->query("SELECT code, value FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int) $order['order_id'] . "'")->rows;

      $total_data = array(
        'sub_total' => 0,
        'reward'    => 0,
        'coupon'    => 0,
        'voucher'   => 0,
        'shipping'  => 0,
        'total'     => 0
      );

      foreach ($totals as $total) {
        switch($total['code']) {
          case 'cod_fee':
            $total_data['shipping'] += $total['value'];
            break;
          default:
            $total_data[$total['code']] += $total['value'];
        }
      }

      $data['orders'][] = array(
        'order_id'        => $order['order_id'],
        'name'            => $order['firstname'] . ' ' . $order['lastname'],
        'date_added'      => date($this->language->get('date_format_short'), strtotime($order['date_added'])),
        'date_modified'   => in_array($order['order_status_id'], $this->config->get('config_complete_status')) ? date($this->language->get('date_format_short'), strtotime($order['date_modified'])) : '',
        'sub_total'       => $this->currency->format($total_data['sub_total'], $this->config->get('config_currency')),
        'reward'          => $this->currency->format($total_data['reward'], $this->config->get('config_currency')),
        'coupon'          => $this->currency->format($total_data['coupon'], $this->config->get('config_currency')),
        'voucher'         => $this->currency->format($total_data['voucher'], $this->config->get('config_currency')),
        'shipping'        => $this->currency->format($total_data['shipping'], $this->config->get('config_currency')),
        'total'           => $this->currency->format($total_data['total'], $this->config->get('config_currency')),
        'payment_method'  => str_replace('Collect On Delivery', 'COD', $order['payment_method']),
        'status'          => $order['status']
      );
    }

    $data['column_order'] = $this->language->get('column_order');
    $data['column_name'] = $this->language->get('column_name');
    $data['column_date_added'] = $this->language->get('column_date_added');
    $data['column_sub_total'] = $this->language->get('column_sub_total');
    $data['column_reward'] = $this->language->get('column_reward');
    $data['column_coupon'] = $this->language->get('column_coupon');
    $data['column_voucher'] = $this->language->get('column_voucher');
    $data['column_shipping'] = $this->language->get('column_shipping');
    $data['column_total'] = $this->language->get('column_total');
    $data['column_payment_method'] = $this->language->get('column_payment_method');
    $data['column_status'] = $this->language->get('column_status');

    $data['date'] = date($this->language->get('date_format_short'));

    $this->response->setOutput($this->load->view('report/sale_order_month.tpl', $data));

  }
}