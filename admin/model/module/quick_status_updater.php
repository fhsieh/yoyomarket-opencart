<?php
class ModelModuleQuickStatusUpdater extends Model {
  // yym_custom: method for getting tracking numbers from oc_tracking_number
  public function getTrackingNumbers($order_id) {
    $query = $this->db->query("SELECT tn.tracking_number_id, tn.number, tn.note, tmd.description, tmd.href FROM " . DB_PREFIX . "tracking_number tn LEFT JOIN " . DB_PREFIX . "tracking_method_description tmd ON (tn.tracking_method_id = tmd.tracking_method_id) WHERE tn.order_id = " . (int)$order_id . " AND tmd.language_id = " . (int)$this->config->get('config_language_id'));

    return $query->rows;
  }
}
?>