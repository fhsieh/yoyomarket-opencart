<?php
class ModelSaleOrderExt extends Model {
  public function addProduct($data) {
    $result = false;

    // remove added quantity from stock if subtract
    $stock_info = $this->getStock((int)$data['product_id']);
    if ($stock_info['subtract'] == 1) {
      $this->editStockQuantity((int)$data['product_id'], $stock_info['quantity'] - (int)$data['quantity']);
    }

    // add to oc_order_product
    $this->db->query("INSERT INTO " . DB_PREFIX . "order_product (order_id, product_id, name, model, quantity, price, total) VALUES ('" . (int)$data['order_id'] . "', '" . (int)$data['product_id'] . "', '" . $this->db->escape($data['name']) . "', '" . $this->db->escape($data['model']) . "', '" . (int)$data['quantity'] . "', '" . (int)$data['price'] . "', '" . (int)$data['total'] . "')");

    // return array for js
    $result = array(
      'action'      => 'add',
      'table'       => 'product',
      'id'          => $this->db->getLastId(), // order_product_id
      'product_id'  => $data['product_id'],
      'href'        => $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $data['product_id'], 'SSL'),
      'columns'     => array(
        'model'     => $data['model'],
        'name'      => $data['name'],
        'price'     => $data['price'],
        'quantity'  => $data['quantity'],
        'total'     => $data['total']
      )
    );

    // add to oc_order_option
    if ($data['option'] != false) {
      foreach ($data['option'] as $option) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "order_option (order_id, order_product_id, product_option_id, product_option_value_id, name, value, type) VALUES ('" . (int)$data['order_id'] . "', '" . (int)$result['id'] . "', '" . (int)$option['product_option_id'] . "', '" . (int)$option['product_option_value_id'] . "', '" . $this->db->escape($option['name']) . "', '" . $this->db->escape($option['value']) . "', '" . $this->db->escape($option['type']) . "')");
      }
      $result['option'] = $data['option'];
    }

    return $result;
  }

  public function deleteProduct($data) {
    $result = false;

    // add delete quantity to stock if subtract
    $product_info = $this->getProduct((int)$data['id']);
    $stock_info = $this->getStock($product_info['product_id']);

    if ($stock_info['subtract'] == 1) {
      $this->editStockQuantity($product_info['product_id'], $stock_info['quantity'] + $product_info['quantity']);
    }

    // delete from oc_order_product
    $this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_product_id = '" . (int)$data['id'] . "'");

    // delete from oc_order_option
    $this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_product_id = '" . (int)$data['id'] . "'");

    // return array for js
    $result = array(
      'action'  => 'delete',
      'table'   => 'product',
      'id'      => $data['id']
    );

    return $result;
  }

  public function editProduct($data) {
    $result = false;

    // update quantityy if subtract
    $product_info = $this->getProduct((int)$data['order_product_id']);

    if ((int)$data['quantity'] != $product_info['quantity']) {
      $stock_info = $this->getStock($product_info['product_id']);

      if ($stock_info['subtract'] == 1) {
        $this->editStockQuantity($product_info['product_id'], $stock_info['quantity'] - ((int)$data['quantity'] - $product_info['quantity']));
      }
    }

    // update oc_order_product.price, .quantity, .total
    $this->db->query("UPDATE " . DB_PREFIX . "order_product SET price = '" . (int)$data['price'] . "', quantity = '" . (int)$data['quantity'] . "', total = '" . (int)$data['total'] . "' WHERE order_product_id = '" . (int)$data['order_product_id'] . "'");

    // return array for js
    $result = array(
      'action'  => 'update',
      'table'   => 'product',
      'id'      => $data['order_product_id'],
      'columns' => array(
        'price'     => $data['price'],
        'quantity'  => $data['quantity'],
        'total'     => $data['total']
      )
    );

    return $result;
  }

  public function editTotal($data) {
    $result = false;

    // update oc_order_total.value, and oc_order_total.title if necessary
    $sql = "UPDATE " . DB_PREFIX . "order_total SET value = '" . (int)$data['value'] . "'";

    if (isset($data['title'])) {
      $sql .= ", title = '" . $this->db->escape($data['title']) . "'";
    }

    $sql .= " WHERE order_total_id = '" . (int)$data['order_total_id'] . "'";

    $this->db->query($sql);

    // update oc_order.total
    if (isset($data['code']) && $data['code'] == 'total') {
      $this->db->query("UPDATE " . DB_PREFIX . "order SET total = '" . (int)$data['value'] . "' WHERE order_id = '" . (int)$data['order_id'] . "'");
    }

    // update oc_order.shipping_method
    if (isset($data['code']) && $data['code'] == 'shipping') {
      $this->db->query("UPDATE " . DB_PREFIX . "order SET shipping_method = '" . $this->db->escape($data['title']) . "' WHERE order_id = '" . (int)$data['order_id'] . "'");
    }

    // return array for js
    $result = array(
      'action'  => 'update',
      'table'   => 'total',
      'id'      => $data['order_total_id'],
      'columns' => array(
        'value' => $data['value']
      )
    );

    // include title if changed
    if (isset($data['title'])) {
      $result['columns']['title'] = $data['title'];
    }

    return $result;
  }

  public function editOrder($data) {
    $result = false;

    $sql = "UPDATE " . DB_PREFIX . "order SET " . $this->db->escape($data['id']) . " = ";

    if ($data['id'] == 'shipping_date' && $data['value'] == '') { // patch to enable deleting date from shipping_date
      $sql .= "NULL";
    } else {
      $sql .= "'" . $this->db->escape($data['value']) . "'";
    }

    $sql .= " WHERE order_id = '" . (int)$data['order_id'] . "'";

    $this->db->query($sql);

    $result = array(
      'action'  => 'update',
      'table'   => 'order',
      'id'      => $data['id'],
      'columns' => array(
        'value'   => $data['value']
      )
    );

    return $result;
  }

  public function getOrderLanguage($order_id) {
    $query = $this->db->query("SELECT language_id FROM " . DB_PREFIX . "order WHERE order_id = '" . (int)$order_id . "'");

    return $query->row['language_id'];
  }

  public function getLocalizedProduct($product_id, $language_id) {
    $query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "') AS keyword FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$language_id . "'");

    return $query->row;
  }

  public function getLocalizedOption($product_option_id, $product_option_value_id, $language_id) {
    $query = $this->db->query("SELECT o.type FROM " . DB_PREFIX . "product_option po LEFT JOIN " . DB_PREFIX . "option o ON (po.option_id = o.option_id) WHERE po.product_option_id = '" . (int)$product_option_id . "'");

    switch ($query->row['type']) {
      case 'text':
      case 'textarea':
        $query = $this->db->query("SELECT od.name, o.type FROM " . DB_PREFIX . "product_option po LEFT JOIN " . DB_PREFIX . "option o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (po.option_id = od.option_id) WHERE po.product_option_id = '" . (int)$product_option_id . "' AND od.language_id = '" . (int)$language_id . "'");
        break;
      default:
        $query = $this->db->query("SELECT od.name, ovd.name AS value, o.type FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option o ON (pov.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (pov.option_id = od.option_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (pov.option_value_id = ovd.option_value_id) WHERE pov.product_option_id = '" . (int)$product_option_id . "' AND pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND od.language_id = '" . (int)$language_id . "' AND ovd.language_id = '" . (int)$language_id . "'");
        break;
    }

    $result = array(
      'name'  => $query->row['name'],
      'value' => isset($query->row['value']) ? $query->row['value'] : $product_option_value_id,
      'type'  => $query->row['type']
    );

    return $result;
  }

  public function getShippingMethod($order_id) {
    $query = $this->db->query("SELECT shipping_method FROM " . DB_PREFIX . "order WHERE order_id = '" . (int)$order_id . "'");

    return $query->row['shipping_method'];
  }

  public function getProduct($order_product_id) {
    $query = $this->db->query("SELECT product_id, quantity FROM " . DB_PREFIX . "order_product WHERE order_product_id = '" . (int)$order_product_id . "'");

    return $query->row;
  }

  public function getStock($product_id) {
    $query = $this->db->query("SELECT quantity, status, subtract FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");

    return $query->row;
  }

  public function editStockQuantity($product_id, $quantity) {
    $this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = '" . (int)$quantity . "' WHERE product_id = '" . (int)$product_id . "'");

    return true;
  }
}