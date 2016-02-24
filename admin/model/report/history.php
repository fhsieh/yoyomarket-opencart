<?php
class ModelReportHistory extends Model {
  public function getHistoryByMonth($month) {
    $result = false;

    $query = "SELECT * FROM " . DB_PREFIX . "history WHERE DATE_FORMAT(`date`, '%c') = '" . $this->db->escape($month) . "'";

    $result = $this->db->query($query);

    return $result->rows;
  }

  public function getOrdersByMonth($month) {
    $result = false;

    $query = "SELECT * FROM " . DB_PREFIX . "order WHERE DATE_FORMAT(date_added, '%c') = '" . $this->db->escape($month) . "'";

    $result = $this->db->query($query);

    return $result->rows;
  }

  public function updateHistory($history) {
    $result = false;

    foreach ($history as $date => $types) {
      foreach ($types as $type => $data) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "history WHERE DATE_FORMAT(`date`, '%Y-%m-%d') = '" . $this->db->escape($date) . "' AND `type` = '" . $this->db->escape($type) . "'");
        if ($query->num_rows) {
          $sql = "UPDATE " . DB_PREFIX . "history SET `count` = '" . (int)$data['count'] . "', `total` = '" . (int)$data['total'] . "' WHERE history_id = '" . (int)$query->row['history_id'] . "'";
        } else {
          $sql = "INSERT INTO " . DB_PREFIX . "history (date, type, count, total) VALUES ('" . $this->db->escape($date) . "', '" . $this->db->escape($type) . "', '" . (int)$data['count'] . "', '" . (int)$data['total'] . "')";
        }
        $this->db->query($sql);
      }
    }

    $result = true;

    return $result;
  }
}