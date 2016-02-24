<?php
class ModelPaymentCOD extends Model {
  public function getMethod($address, $total) {
    if($this->config->get('cod_status')) {
      if(isset($this->session->data['shipping_method']['code'])) {
        $exploded=explode(".",($this->session->data['shipping_method']['code']));
        $shipping_method=$exploded[0];
      } else {
        $shipping_method='noshipping';
      }

      $shipping_method_status=$this->config->get('cod_'.$shipping_method.'_status');

      if($shipping_method_status==0) {
        $shipping_method_status=$this->config->get('cod_default_status');
      } else {
        $shipping_method_status=$shipping_method_status-1;
      }

      if($shipping_method_status) {

        $shipping_geo_zone=$this->config->get('cod_'.$shipping_method.'_shipping_geo_zone');

        if($shipping_geo_zone==0) {
          $shipping_geo_zone=$this->config->get('cod_default_shipping_geo_zone');
        } else {
          $shipping_geo_zone=$shipping_geo_zone-1;
        }

        if($shipping_geo_zone==1) {
          $shipping_method_geo_zone_num=0;

          if(isset($this->session->data['shipping_method']['code'])) {
            $shipping_method_geo_zone=explode("_", $this->session->data['shipping_method']['code']);
            $shipping_method_geo_zone_num=count($shipping_method_geo_zone);
          }

          $shipping_method_geo_zone_id=0;

          if($shipping_method_geo_zone_num>1) {
            $shipping_method_geo_zone_id=$shipping_method_geo_zone[$shipping_method_geo_zone_num-1];
            if(is_numeric($shipping_method_geo_zone_id)==false) {
              $shipping_method_geo_zone_id=0;
            }
          }
        } else {
          $shipping_method_geo_zone_id=0;
        }

        if ($this->customer->isLogged()) {
          $customer_group_id = $this->customer->getGroupId();
        } else {
          $customer_group_id = $this->config->get('config_customer_group_id');
        }

        if($shipping_method_geo_zone_id==0) {
          $quote_data = array();
          $query = $this->db->query("SELECT * FROM ".DB_PREFIX."zone_to_geo_zone WHERE country_id = '".(int)$address['country_id']."' AND (zone_id = '".(int)$address['zone_id']."' OR zone_id = '0')");

          $zone_to_geo_zone_results = array();
          if($query->num_rows>1) {
            $temparray=array();
            foreach ($query->rows as $val) {
              $temparray[$val['geo_zone_id']] = $val;
            }
            $zone_to_geo_zone_results=array_values($temparray);
          } else {
            $zone_to_geo_zone_results=$query->rows;
          }

          if(count($zone_to_geo_zone_results)>1) {
            $found=false;
            foreach($zone_to_geo_zone_results as $zone_to_geo_zone_result) {
              $shipping_method_geo_zone_status=$this->config->get('cod_'.$shipping_method.'_'.$zone_to_geo_zone_result['geo_zone_id'].'_'.$customer_group_id.'_status');
              if($shipping_method_geo_zone_status==0) {
                $shipping_method_geo_zone_status=$this->config->get('cod_default_'.$zone_to_geo_zone_result['geo_zone_id'].'_'.$customer_group_id.'_status');
              } else {
                $shipping_method_geo_zone_status=$shipping_method_geo_zone_status-1;
              }

              if($shipping_method_geo_zone_status && $found==false) {
                $shipping_method_geo_zone_id=$zone_to_geo_zone_result['geo_zone_id'];
                $found=true;
              } else if($shipping_method_geo_zone_status && $found==true) {
                $shipping_method_geo_zone_id=0;
                break;
              }
            }
            if($found==false) {
              $shipping_method_geo_zone_id=$zone_to_geo_zone_results[0]['geo_zone_id'];
            }
          } else if(count($zone_to_geo_zone_results)==1) {
            $shipping_method_geo_zone_id=$query->rows[0]['geo_zone_id'];
          } else {
            $shipping_method_geo_zone_id=0;
          }
        }

        $shipping_method_geo_zone_status=$this->config->get('cod_'.$shipping_method.'_'.$shipping_method_geo_zone_id.'_'.$customer_group_id.'_status');

        if($shipping_method_geo_zone_status==0) {
          $shipping_method_geo_zone_status=$this->config->get('cod_default_'.$shipping_method_geo_zone_id.'_'.$customer_group_id.'_status');
        } else {
          $shipping_method_geo_zone_status=$shipping_method_geo_zone_status-1;
        }

        if($shipping_method_geo_zone_status) {
          //Shipping Sort Order
          $shipping_method_sort_order=$this->config->get('cod_'.$shipping_method.'_sort_order');
          if($shipping_method_sort_order=="") {
            $shipping_method_sort_order=$this->config->get('cod_default_sort_order');
          }
          if($shipping_method_sort_order=="" || is_numeric($shipping_method_sort_order)==false) {
            $shipping_method_sort_order=0;
          }

          //Shipping Geo Zone Tax Class
          $shipping_method_tax_class_id=$this->config->get('cod_'.$shipping_method.'_'.$shipping_method_geo_zone_id.'_'.$customer_group_id.'_tax_class_id');
          if($shipping_method_tax_class_id==0) {
            $shipping_method_tax_class_id=$this->config->get('cod_default_'.$shipping_method_geo_zone_id.'_'.$customer_group_id.'_tax_class_id');
          }

          if($shipping_method_tax_class_id==-1) {
            $shipping_method_tax_class_id=0;
          }

          //Shipping Geo Zone Order Status
          $shipping_method_order_status_id=$this->config->get('cod_'.$shipping_method.'_'.$shipping_method_geo_zone_id.'_'.$customer_group_id.'_order_status_id');
          if($shipping_method_order_status_id==0) {
            $shipping_method_order_status_id=$this->config->get('cod_default_'.$shipping_method_geo_zone_id.'_'.$customer_group_id.'_order_status_id');
          }

          //Shipping Geo Zone Method
          $shipping_method_method=$this->config->get('cod_'.$shipping_method.'_'.$shipping_method_geo_zone_id.'_'.$customer_group_id.'_method');
          if($shipping_method_method==0) {
            $shipping_method_method=$this->config->get('cod_default_'.$shipping_method_geo_zone_id.'_'.$customer_group_id.'_method');
          } else {
            $shipping_method_method=$shipping_method_method-1;
          }

          $title=null;
          $cost=null;
          $cost_and_tax=null;
          $tax=null;
          $method_enabled=true;
          $order_total_title=null;
          $order_total=null;
          $order_total_sort_order=null;

          //Shipping Geo Zone Choose Method
          if($shipping_method_method==0) {
            $shipping_method_flat=$this->config->get('cod_'.$shipping_method.'_'.$shipping_method_geo_zone_id.'_'.$customer_group_id.'_flat');
            if($shipping_method_flat=="") {
              $shipping_method_flat=$this->config->get('cod_default_'.$shipping_method_geo_zone_id.'_'.$customer_group_id.'_flat');
            }

            if($shipping_method_flat=="" || is_numeric($shipping_method_flat)==false) {
              $shipping_method_flat=0;
            }

            $shipping_method_flat_currency=$this->config->get('cod_'.$shipping_method.'_'.$shipping_method_geo_zone_id.'_'.$customer_group_id.'_flat_currency');
            if($shipping_method_flat_currency==0) {
              $shipping_method_flat_currency=$this->config->get('cod_default_'.$shipping_method_geo_zone_id.'_'.$customer_group_id.'_flat_currency');
            }
            $query = $this->db->query("SELECT code FROM ".DB_PREFIX."currency WHERE currency_id=".$shipping_method_flat_currency);
            $cost=$this->convertCurrency($shipping_method_flat, $query->row['code']);
          } else if($shipping_method_method==1) {
            $shipping_method_percent=$this->config->get('cod_'.$shipping_method.'_'.$shipping_method_geo_zone_id.'_'.$customer_group_id.'_percent');
            if($shipping_method_percent=="") {
              $shipping_method_percent=$this->config->get('cod_default_'.$shipping_method_geo_zone_id.'_'.$customer_group_id.'_percent');
            }
            if($shipping_method_percent=="" || is_numeric($shipping_method_percent)==false) {
              $shipping_method_percent=0;
            }

            $cost=($this->tax->calculate($this->getTotal(), $this->config->get('cod_'.$shipping_method.'_'.$shipping_method_geo_zone_id.'_'.$customer_group_id.'_tax_class_id'), $this->config->get('config_tax')))*($shipping_method_percent/100);
          } else {
            $shipping_method_custom=$this->config->get('cod_'.$shipping_method.'_'.$shipping_method_geo_zone_id.'_'.$customer_group_id.'_custom');
            if($shipping_method_custom=="")
            {
              $shipping_method_custom=$this->config->get('cod_default_'.$shipping_method_geo_zone_id.'_'.$customer_group_id.'_custom');
            }
            eval(htmlspecialchars_decode($shipping_method_custom, ENT_NOQUOTES));
          }

          //Enable Rule
          $enable_rule=$this->config->get('cod_'.$shipping_method.'_'.$shipping_method_geo_zone_id.'_'.$customer_group_id.'_enable_rule');
          if($enable_rule=="") {
            $enable_rule=$this->config->get('cod_default_'.$shipping_method_geo_zone_id.'_'.$customer_group_id.'_enable_rule');
          }

          if($enable_rule!="") {
            $enabled=$method_enabled;
            eval(htmlspecialchars_decode($enable_rule, ENT_NOQUOTES));
            $method_enabled=$enabled;
          }

          //Order Total
          $shipping_method_order_total=$this->config->get('cod_'.$shipping_method.'_'.$shipping_method_geo_zone_id.'_'.$customer_group_id.'_order_total');
          if($shipping_method_order_total==0) {
            $shipping_method_order_total=$this->config->get('cod_default_'.$shipping_method_geo_zone_id.'_'.$customer_group_id.'_order_total');
          } else {
            $shipping_method_order_total=$shipping_method_order_total-1;
          }

          //Order Total Sort Order
          $shipping_method_order_total_sort_order=$this->config->get('cod_'.$shipping_method.'_'.$shipping_method_geo_zone_id.'_'.$customer_group_id.'_order_total_sort_order');
          if($shipping_method_order_total_sort_order=="") {
            $shipping_method_order_total_sort_order=$this->config->get('cod_default_'.$shipping_method_geo_zone_id.'_'.$customer_group_id.'_order_total_sort_order');
          }
          if($shipping_method_order_total_sort_order=="" || is_numeric($shipping_method_order_total_sort_order)==false) {
            $shipping_method_order_total_sort_order=0;
          }

          if($method_enabled) {

            if($order_total==null) {
              $order_total=$shipping_method_order_total;
            }

            if($order_total_sort_order==null) {
              $order_total_sort_order=$shipping_method_order_total_sort_order;
            }

            if($cost_and_tax==null) {
              $cost_and_tax=$this->tax->calculate($cost, $shipping_method_tax_class_id, $this->config->get('config_tax'));
            }

            if($tax==null) {
              $tax=$cost_and_tax-$cost;
            }

            if($title==null) {
              $this->load->language('payment/cod');
              $title=$this->language->get('text_title');
              if($cost!=0) {
                $title=$title.'('.$this->formatCurrency($cost_and_tax).')';
                if($tax!=0) {
                  $title=$title.'('.$this->language->get('text_without_taxes').' '.$this->formatCurrency($cost).')';
                }
              } else {
                $title=$title.'('.$this->language->get('text_free').')';
              }
            }

            $method_data = array();

            $method_data = array(
              'code'                    => 'cod',
              'title'                   => $title,
              'terms'                   => '',
              'cost'                    => $cost,
              'tax'                     => $tax,
              'cost_and_tax'            => $cost_and_tax,
              'tax_class_id'            => $shipping_method_tax_class_id,
              'text'                    => $cost_and_tax,
              'order_status_id'         => $shipping_method_order_status_id,
              'sort_order'              => $shipping_method_sort_order,
              'order_total_title'       => $order_total_title,
              'order_total'             => $order_total,
              'order_total_sort_order'  => $order_total_sort_order
            );

            return $method_data;
          }
        }
      }
    }
  }


  //Easy to use Functions for Customization

  private function getWeight() {
    return $this->cart->getWeight();
  }

  private function getProducts() {
    return $this->cart->getProducts();
  }

  private function getCurrency() {
    return $this->session->data['currency'];
  }

  private function getDefaultCurrency() {
    $query = $this->db->query("SELECT * FROM ".DB_PREFIX."currency");

    foreach ($query->rows as $result) {
      if($result['value']==1) {
        return $result['code'];
      }
    }
    return null;
  }

  private function convertCurrency($amount,$currency_code) {
    return $this->currency->convert($amount,$currency_code,$this->getDefaultCurrency());
  }

  private function formatCurrency($amount) {
    return $this->currency->format($amount, $currency_code='', $value = '', $format = true);
  }

  private function getSubTotal() {
    return $this->cart->getSubTotal();
  }

  private function getSubTotalWithTax() {
    return $this->cart->getTotal();
  }

  private function getShippingCost() {
    if (isset($this->session->data['shipping_method']['cost'])) {
      $getShippingCost = $this->session->data['shipping_method']['cost'];
    } else {
      $getShippingCost = null;
    }
    return $getShippingCost;
  }

  private function getShippingCostWithTax() {
    if (isset($this->session->data['shipping_method']['cost'])) {
      if (isset($this->session->data['shipping_method']['cost'])) {
        $getShippingCostWithTax = $this->tax->calculate($this->session->data['shipping_method']['cost'], $this->session->data['shipping_method']['tax_class_id'], $this->config->get('config_tax'));
      } else {
        $getShippingCostWithTax = $this->session->data['shipping_method']['cost'];
      }
    } else {
      $getShippingCostWithTax = null;
    }
    return $getShippingCostWithTax;
  }

  private function getTotal() {
    return $this->getSubTotalWithTax()+$this->getShippingCostWithTax();
  }

  private function isCartOutOfStock() {
    $isCartOutOfStock=false;
    $products=$this->getProducts();
    foreach ($products as &$product) {
      if($product['stock']==0) {
        $isCartOutOfStock=true;
        break;
      }
    }
    return $isCartOutOfStock;
  }

  private function isGiftVoucherUsed() {
    $isGiftVoucherUsed=false;
    if (isset($this->session->data['voucher']) && ($this->session->data['voucher'] != '')) {
      $isGiftVoucherUsed=true;
    }
    return $isGiftVoucherUsed;
  }

  private function isCouponUsed() {
    $isCouponUsed=false;
    if (isset($this->session->data['coupon']) && ($this->session->data['coupon'] != '')) {
      $isCouponUsed=true;
    }
    return $isCouponUsed;
  }

  private function getCostWhenWeight($weight_rule) {
    $cost_when_weight=0;
    $weight = $this->cart->getWeight();

    $rates = explode(',', $weight_rule);

    foreach ($rates as $rate) {
      $data = explode(':', $rate);
      if ($data[0] >= $weight) {
        if (isset($data[1])) {
          $cost_when_weight = $data[1];
        }
        break;
      }
    }
    return $cost_when_weight;
  }
}
?>