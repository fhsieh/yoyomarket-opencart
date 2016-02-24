<?php
class ModelTotalCODFee extends Model {
  public function getTotal(&$total_data, &$total, &$taxes) {
    if (isset($this->session->data['payment_method']['code'])) {
      if($this->session->data['payment_method']['code']=='cod' && $this->session->data['payment_method']['order_total']==true) {

        $title=$this->session->data['payment_method']['order_total_title'];
        if($title==null) {
          $this->language->load('total/cod_fee');
          $title=$this->language->get('text_cod_fee');
        }

        $sort_order=$this->session->data['payment_method']['order_total_sort_order'];
        if($sort_order==null) {
          $sort_order = $this->config->get('cod_fee_sort_order');
        }

        $total_data[] = array(
          'code'       => 'cod_fee',
          'title'      => $title,
          'text'       => $this->currency->format($this->session->data['payment_method']['cost']),
          'value'      => $this->session->data['payment_method']['cost'],
          'sort_order' => $sort_order
        );

        if ($this->session->data['payment_method']['tax_class_id']) {
          $tax_rates = $this->tax->getRates($this->session->data['payment_method']['cost'], $this->session->data['payment_method']['tax_class_id']);

          foreach ($tax_rates as $tax_rate) {
            if (!isset($taxes[$tax_rate['tax_rate_id']])) {
              $taxes[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
            } else {
              $taxes[$tax_rate['tax_rate_id']] += $tax_rate['amount'];
            }
          }
        }
        $total += $this->session->data['payment_method']['cost'];
      }
    }
  }
}
?>