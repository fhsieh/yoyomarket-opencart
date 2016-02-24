<?php
class ControllerDashboardStats extends Controller {
  public function index() {
    $this->load->language('dashboard/stats');

    $data['token'] = $this->session->data['token'];

    $data['heading_title'] = $this->language->get('heading_title');

    $data['text_month_total'] = $this->language->get('text_month_total');
    $data['text_month_count'] = $this->language->get('text_month_count');
    $data['text_today'] = $this->language->get('text_today');
    $data['text_pending'] = $this->language->get('text_pending');
    $data['text_paypal'] = $this->language->get('text_paypal');
    $data['text_cod'] = $this->language->get('text_cod');
    $data['text_month_average_day'] = $this->language->get('text_month_average_day');
    $data['text_month_average_order'] = $this->language->get('text_month_average_order');
    $data['text_ikea'] = $this->language->get('text_ikea');
    $data['text_costco'] = $this->language->get('text_costco');
    $data['text_box_count'] = $this->language->get('text_box_count');
    $data['text_ikea_ratio'] = $this->language->get('text_ikea_ratio');
    $data['text_costco_ratio'] = $this->language->get('text_costco_ratio');

    $none = $this->language->get('text_none');

    $include_history = $this->config->get('config_history_status');
    $include_pending = $this->config->get('config_pending_status');

    $range_month = date('Y-n');
    $range_today = date('Y-n-d');

    // Statistics for this month
    $month = $this->db->query("SELECT SUM(total) AS total, COUNT(*) AS `count` FROM " . DB_PREFIX . "order WHERE DATE_FORMAT(date_added, '%Y-%c') = '" . $this->db->escape($range_month) . "' AND order_status_id IN (" . $this->db->escape(implode(',', $include_history)) . ")")->row;

    $data['month_count'] = $month['count'];
    if ($month['count'] > 0) {
      $data['month_total'] = $this->currency->format($month['total'], $this->config->get('config_currency'));
      $data['month_average_day'] = $this->currency->format(round($month['total'] / (int)date('j')), $this->config->get('config_currency'));
      $data['month_average_order'] = $this->currency->format(round($month['total'] / $month['count']), $this->config->get('config_currency'));
    } else {
      $data['month_total'] = $this->currency->format(0, $this->config->get('config_currency'));
      $data['month_average_day'] = $this->currency->format(0, $this->config->get('config_currency'));
      $data['month_average_order'] = $this->currency->format(0, $this->config->get('config_currency'));
    }

    // Statistics for today
    $today = $this->db->query("SELECT SUM(total) AS total, COUNT(*) AS `count` FROM " . DB_PREFIX . "order WHERE DATE_FORMAT(date_added, '%Y-%c-%d') = '" . $this->db->escape($range_today) . "' AND order_status_id IN (" . $this->db->escape(implode(',', $include_history)) . ")")->row;

    $data['today_count'] = $today['count'];
    if ($today['count'] > 0) {
      $data['today_total'] = $this->currency->format($today['total'], $this->config->get('config_currency'));
    } else {
      $data['today_total'] = $this->currency->format(0, $this->config->get('config_currency'));
    }

    // Pending sales
    $pending = $this->db->query("SELECT SUM(total) AS total, COUNT(*) AS `count` FROM " . DB_PREFIX . "order WHERE order_status_id IN (" . $this->db->escape(implode(',', $include_pending)) . ")")->row;

    $data['pending_count'] = $pending['count'];
    if ($pending['count'] > 0) {
      $data['pending_total'] = $this->currency->format($pending['total'], $this->config->get('config_currency'));
    } else {
      $data['pending_total'] = $this->currency->format(0, $this->config->get('config_currency'));
    }

    // IKEA sales
    $ikea = $this->db->query("SELECT SUM(op.total) AS total, COUNT(*) AS `count` FROM " . DB_PREFIX . "order_product op LEFT JOIN " . DB_PREFIX . "order o ON (op.order_id = o.order_id) WHERE op.model LIKE 'IKEA-%' AND DATE_FORMAT(o.date_added, '%Y-%c') = '" . $this->db->escape($range_month) . "' AND o.order_status_id IN (" . $this->db->escape(implode(',', $include_history)) . ")")->row;

    $data['ikea_count'] = $ikea['count'];
    if ($ikea['count'] > 0) {
      $data['ikea_total'] = $this->currency->format($ikea['total'], $this->config->get('config_currency'));
    } else {
      $data['ikea_total'] = $this->currency->format(0, $this->config->get('config_currency'));
    }

    // Costco:IKEA ratios
    if ($month['count'] > 0) {
      $data['ikea_ratio'] = round(100 * ($ikea['count'] > 0 ? $ikea['total'] : 0) / $month['total']);
      $data['costco_ratio'] = 100 - $data['ikea_ratio'];
    }

    // PayPal sales
    $paypal = $this->db->query("SELECT SUM(total) AS total, COUNT(*) AS `count` FROM " . DB_PREFIX . "order WHERE payment_code = 'pp_standard' AND DATE_FORMAT(date_added, '%Y-%c') = '" . $this->db->escape($range_month) . "' AND order_status_id IN (" . $this->db->escape(implode(',', array_merge($include_history, $include_pending))) . ")")->row;

    $data['paypal_count'] = $paypal['count'];
    if ($paypal['count'] > 0) {
      $data['paypal_total'] = $this->currency->format($paypal['total'], $this->config->get('config_currency'));
    } else {
      $data['paypal_total'] = $this->currency->format(0, $this->config->get('config_currency'));
    }

    // COD sales
    $cod = $this->db->query("SELECT SUM(total) AS total, COUNT(*) AS `count` FROM " . DB_PREFIX . "order WHERE payment_code = 'cod' AND DATE_FORMAT(date_added, '%Y-%c') = '" . $this->db->escape($range_month) . "' AND order_status_id IN (" . $this->db->escape(implode(',', array_merge($include_history, $include_pending))) . ")")->row;

    $data['cod_count'] = $cod['count'];
    if ($cod['count'] > 0) {
      $data['cod_total'] = $this->currency->format($cod['total'], $this->config->get('config_currency'));
    } else {
      $data['cod_total'] = $this->currency->format(0, $this->config->get('config_currency'));
    }

    // Boxes shipped
    $boxes = $this->db->query("SELECT COUNT(*) AS `count` FROM " . DB_PREFIX . "tracking_number WHERE DATE_FORMAT(date_added, '%Y-%c') = '" . $this->db->escape($range_month) . "' AND tracking_method_id = '1'")->row;

    $data['box_count'] = $boxes['count'];
    if ($boxes['count'] > 0 && $month['count'] > 0) {
      $data['box_average'] = sprintf('%0.2f', $boxes['count'] / $month['count']);
    }

    $data['entry_count'] = sprintf($this->language->get('entry_count'), $month['count']);
    $data['entry_today'] = $today['count'] > 0 ? sprintf($this->language->get('entry_today'), $data['today_total'], $data['today_count']) : $none;
    $data['entry_pending'] = $pending['count'] > 0 ? sprintf($this->language->get('entry_pending'), $data['pending_total'], $data['pending_count']) : $none;
    $data['entry_paypal'] = $paypal['count'] > 0 ? sprintf($this->language->get('entry_paypal'), $data['paypal_total'], $data['paypal_count']) : $none;
    $data['entry_cod'] = $cod['count'] > 0 ? sprintf($this->language->get('entry_cod'), $data['cod_total'], $data['cod_count']) : $none;
    $data['entry_ikea'] = $ikea['count'] > 0 ? sprintf($this->language->get('entry_ikea'), $data['ikea_total'], $data['ikea_count'], $data['ikea_ratio'] . '%') : $none;
    $data['entry_box_count'] = sprintf($this->language->get('entry_box_count'), $data['box_count'], $data['box_average']);

    return $this->load->view('dashboard/stats.tpl', $data);
  }
}