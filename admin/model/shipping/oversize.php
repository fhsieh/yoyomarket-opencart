<?php
class ModelShippingOversize extends Model
{
  public function createTable() {
    $sql = "CREATE TABLE IF NOT EXISTS " . DB_PREFIX ."oversize_shipping (product_id int NOT NULL, oversize_shipping int default 0, primary key(product_id))";
    $this->db->query($sql);
  }

  public function getOversizeShippingProducts() {
    $sql = "SELECT fs.product_id,pd.name, fs.oversize_shipping FROM ". DB_PREFIX ."oversize_shipping fs LEFT JOIN ".DB_PREFIX ."product_description pd ON (fs.product_id = pd.product_id) WHERE fs.oversize_shipping = 1 GROUP BY fs.product_id";
    $query = $this->db->query($sql);
    return $query->rows;
  }
}