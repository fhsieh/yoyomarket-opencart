<?php
// yym_custom: stats for admin menu stats panel
class ControllerCommonStats extends Controller {
  public function index() {
    $this->load->language('common/stats');

    $data['text_today_count']       = $this->language->get('text_today_count');
    $data['text_today_sales']       = $this->language->get('text_today_sales');
    $data['text_total_count']       = $this->language->get('text_total_count');
    $data['text_total_sales']       = $this->language->get('text_total_sales');
    $data['text_daily_sales']       = $this->language->get('text_daily_sales');
    $data['text_complete_status']   = $this->language->get('text_complete_status');
    $data['text_processing_status'] = $this->language->get('text_processing_status');
    $data['text_packing_status']    = $this->language->get('text_packing_status'); // yym_custom
    $data['text_other_status']      = $this->language->get('text_other_status');

    $config_status['processing'] = array(17,21,1,2);
    $config_status['packing']    = array(20);
    $config_status['complete']   = $this->config->get('config_complete_status');
    $config_status['other']      = array(7,11,16);

    $this->load->model('localisation/order_status');
    $results = $this->model_localisation_order_status->getOrderStatuses();
    foreach ($results as $result) {
      if (!in_array($result['order_status_id'], array_merge($config_status['complete'], $config_status['processing']))) {
        $config_status['other'][] = $result['order_status_id'];
      }
    }

//    $data['order_today_count'] = $this->getTotalOrders(array('filter_order_date' => 'today'));
//    $data['order_today_sales'] = $this->currency->format($this->getTotalSales(array('filter_order_date' => 'today')));
//    $data['order_total_sales'] = $this->currency->format($this->getTotalSales(array('filter_order_date' => 'month')));
//    $data['order_daily_sales'] = $this->currency->format(round($this->getTotalSales(array('filter_order_date' => 'month')) / (int)date('d')));

    $data['order_total_count'] = $this->getTotalOrders(array('filter_order_status' => implode(',', array_merge($config_status['processing'], $config_status['packing'], $config_status['complete'], $config_status['other'])), 'filter_order_date' => 'month'));

    $data['order_processing_count'] = $this->getTotalOrders(array('filter_order_status' => implode(',', $config_status['processing']), 'filter_order_date' => 'month'));
    if ($data['order_processing_count'] && $data['order_total_count']) {
      $data['order_processing_percent'] = round(($data['order_processing_count'] / $data['order_total_count']) * 100);
    } else {
      $data['order_processing_percent'] = 0;
    }

    $data['order_packing_count'] = $this->getTotalOrders(array('filter_order_status' => implode(',', $config_status['packing']), 'filter_order_date' => 'month'));
    if ($data['order_packing_count'] && $data['order_total_count']) {
      $data['order_packing_percent'] = round(($data['order_packing_count'] / $data['order_total_count']) * 100);
    } else {
      $data['order_packing_percent'] = 0;
    }

    $data['order_complete_count'] = $this->getTotalOrders(array('filter_order_status' => implode(',', $config_status['complete']), 'filter_order_date' => 'month'));
    if ($data['order_complete_count'] && $data['order_total_count']) {
      $data['order_complete_percent'] = round(($data['order_complete_count'] / $data['order_total_count']) * 100);
    } else {
      $data['order_complete_percent'] = 0;
    }

    $data['order_other_count'] = $this->getTotalOrders(array('filter_order_status' => implode(',', $config_status['other']), 'filter_order_date' => 'month'));
    if ($data['order_other_count'] && $data['order_total_count']) {
      $data['order_other_percent'] = round(($data['order_other_count'] / $data['order_total_count']) * 100);
    } else {
      $data['order_other_percent'] = 0;
    }

    return $this->load->view('common/stats.tpl', $data);
  }

  public function getTotalOrders($data = array()) {
    $sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order`";

    if (!empty($data['filter_order_status'])) {
      $implode = array();
      $order_statuses = explode(',', $data['filter_order_status']);
      foreach ($order_statuses as $order_status_id) {
        $implode[] = "order_status_id = '" . (int)$order_status_id . "'";
      }
      if ($implode) {
        $sql .= " WHERE (" . implode(" OR ", $implode) . ")";
      }
    } else {
      $sql .= " WHERE order_status_id > '0'";
    }

    if (!empty($data['filter_order_date'])) {
      if ($data['filter_order_date'] == 'month') {
        $sql .= " AND DATE_FORMAT(date_added, '%Y-%c') >= DATE_FORMAT(NOW(), '%Y-%c')";
      } elseif ($data['filter_order_date'] == 'today') {
        $sql .= " AND DATE_FORMAT(date_added, '%Y-%c-%d') >= DATE_FORMAT(NOW(), '%Y-%c-%d')";
      }
    }

    $query = $this->db->query($sql);

    return $query->row['total'];
  }

  public function getTotalSales($data = array()) {
    $sql = "SELECT SUM(total) AS total FROM `" . DB_PREFIX . "order`";

    if (!empty($data['filter_order_date'])) {
      if ($data['filter_order_date'] == 'month') {
        $sql .= " WHERE DATE_FORMAT(date_added, '%Y-%m') >= DATE_FORMAT(NOW(), '%Y-%m')";
      } elseif ($data['filter_order_date'] == 'today') {
        $sql .= " WHERE DATE_FORMAT(date_added, '%Y-%m-%d') >= DATE_FORMAT(NOW(), '%Y-%m-%d')";
      }
    }

    $query = $this->db->query($sql);

    return $query->row['total'];
  }

}