<?php
class ModelShippingOversize extends Model {
  function getQuote($address) {
    $this->language->load('shipping/oversize');

    $status = false;

    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

    if ($query->num_rows) {
      $shipping_data = $this->getShippingData($this->cart->getProducts());

      if ($shipping_data['has_oversize_items']) {
        $status = true;
      }
    }

    $method_data = array();

    if($status) {
      $quote_data = array();
      $quote_data['oversize'] = array(
        'code'         => 'oversize.oversize',
        'title'        => $this->language->get('text_description') . ' (' . $shipping_data['oversize_items'] . ')',
        'cost'         => 0.00,
        'tax_class_id' => 0,
        'text'         => $this->language->get('text_included') // $this->currency->format(0.00)
      );
      $method_data = array(
        'code'       => 'oversize',
        'title'      => $this->language->get('text_title'),
        'quote'      => $quote_data,
        'sort_order' => $this->config->get('oversize_sort_order'),
        'error'      => false
      );
    }

    return $method_data;
  }

  function getShippingData($product_list) {
    $this->load->model('shipping/oversize');

    $shipping_data['subtotal'] = 0;
    $shipping_data['oversize_items'] = '';
    $shipping_data['has_standard_items'] = false;
    $shipping_data['has_oversize_items'] = false;

    if (count($product_list) > 0) {
      foreach ($product_list as $product) {
        if ($this->isOversize($product['product_id'])) {
          $shipping_data['has_oversize_items'] = true;
          $shipping_data['oversize_items'] .= $product['model'] . ', ';
        } else {
          $shipping_data['has_standard_items'] = true;
          $shipping_data['subtotal'] += (int)$product['price'] * (int)$product['quantity'];
        }
      }
      $shipping_data['oversize_items'] = substr($shipping_data['oversize_items'], 0, -2);
    }
    return $shipping_data;
  }

  function isOversize($product_id) {
    $result = false;

    if($this->getOversizeShippingStatus() && $this->getProductOversizeShippingStatus((int)$product_id)) { // oversize shipping enabled, product oversize shipping = true
      $oversize_geo_zones = array();
      $oversize_geo_zones = $this->config->get('oversize_geo_zone_id');
      if(count($oversize_geo_zones)) {
        if(in_array(0, $oversize_geo_zones)) { // all zones
          $result = true;
        } elseif (isset($this->session->data['shipping_address']['zone_id'])) { // check if customer geo zone
          if(in_array($this->session->data['shipping_address']['zone_id'], $oversize_geo_zones)) {
            $result = true;
          }
        }
      }
    }

    return $result;
  }

  function getOversizeShippingStatus() {
    $result = false;
    if($this->config->get('oversize_status')) {
      $result = true;
    }
    return $result;
  }

  function getProductOversizeShippingStatus($product_id) {
    $result = false;
    $query = $this->db->query("SELECT oversize_shipping FROM " . DB_PREFIX . "oversize_shipping WHERE product_id = '" . (int)$product_id . "' AND oversize_shipping = 1");
    if(($query->num_rows)) {
      $result = true;
    }
    return $result;
  }

  function getOversizeShippingTotalWeight() {
    $weight = 0;
    foreach ($this->cart->getProducts() as $product) {
      if ($product['shipping'] && $this->getOversizeShippingStatus() && $this->isOversize($product['product_id'])) {
        $weight += $this->weight->convert($product['weight'], $product['weight_class_id'], $this->config->get('config_weight_class_id'));
      }
    }
    return $weight;
  }

}
?>