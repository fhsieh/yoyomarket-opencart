<?php
class ControllerDashboardHistory extends Controller {
  public function index() {

//    $this->load->language('dashboard/history');

    $data['heading_title'] = 'Performance'; // $this->language->get('heading_title');

    $data['months'] = array(
      'January',
      'February',
      'March',
      'April',
      'May',
      'June',
      'July',
      'August',
      'September',
      'October',
      'November',
      'December'
    );

    $data['token'] = $this->session->data['token'];

    return $this->load->view('dashboard/history.tpl', $data);
  }

  public function chart() {
    $this->load->language('dashboard/history');

    $month = (isset($this->request->get['month'])) ? (int)$this->request->get['month'] : (int)date('n');
    $year = (int)date('Y');
    $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    $json = array();

    $json['total'] = array_fill(0, $days, 0);
    $json['count'] = array_fill(0, $days, 0);
    $json['progress'] = array();
    $json['xaxis'] = array();

    // labels for xaxis
    for ($d = 0; $d < $days; $d++) {
      $json['xaxis'][] = strval($d + 1);
    }

    // initialize 5 years including current year for progress, initializing first data at 0
    for ($y = 0; $y < 5; $y++) {
      if ($y == 0 && $month > (int)date('n')) { // current year, future month
        $fill = 0;
      } else if ($y == 0 && $month == (int)date('n')) { // current year, current month
        $fill = (int)date('j');
      } else { // previous year or previous month
        $fill = $days;
      }
      $json['progress'][$y] = array_fill(0, $fill + 1, 0); // fill n+1 for chartist to start at (0,0)
    }

    $this->load->model('report/history');

    $histories = $this->model_report_history->getHistoryByMonth($month);

    // loop through all history records
    foreach ($histories as $history) {
      $date = strtotime($history['date']);
      $y = (int)date('Y', $date);
      $m = (int)date('n', $date);
      $d = (int)date('j', $date);

      // only calculate if history type is cod or pp_standard
      if (in_array($history['type'], array('cod', 'pp_standard'))) {

        // if the record is for current year, add to daily total
        if ($y == $year) {
          $json['total'][$d - 1] += $history['total'];
          $json['count'][$d - 1] += $history['count'];
        }

        // if the record is within the past 5 years, add to line chart array
        $json['progress'][$year - $y][$d] += $history['total'];
      }
    }

    // loop through progress to aggregate values
    foreach ($json['progress'] as $y => $ds) {
      foreach ($ds as $d => $total) {
        if ($d != 0) { // skip first key and leave it as 0
          $json['progress'][$y][$d] += $json['progress'][$y][$d - 1]; // add the previous day's total to today's total
        }
      }
    }

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }

  public function refresh() {
    $month = (isset($this->request->get['month'])) ? (int)$this->request->get['month'] : (int)date('n');

    $this->load->model('report/history');

    $orders = $this->model_report_history->getOrdersByMonth($month);

    $history = array();

    foreach ($orders as $order) {
      // 17: approved
      //  2: processing
      // 20: packing
      // 21: on hold
      //  3: shipped (from config_complete_status)

      if (in_array($order['order_status_id'], $this->config->get('config_history_status'))) {
        $date = date('Y-m-d', strtotime($order['date_added']));

        if (!isset($history[$date])) {
          $history[$date] = array();
        }

        if (!isset($history[$date][$order['payment_code']])) {
          $history[$date][$order['payment_code']] = array(
            'total' => $order['total'],
            'count' => 1
          );
        } else {
          $history[$date][$order['payment_code']]['total'] += $order['total'];
          $history[$date][$order['payment_code']]['count'] += 1;
        }
      }
    }

    $json = array();

    $json['success'] = $this->model_report_history->updateHistory($history);

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }

}