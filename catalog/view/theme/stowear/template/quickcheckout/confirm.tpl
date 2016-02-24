<?php if (!isset($redirect)) { ?>
<div class="row">
  <div class="col-sm-4 quickcheckout-confirm-left">
    <div class="payment-address">
      <div class="quickcheckout-heading"><i class="fa fa-user"></i> <?php echo $text_checkout_payment_address; ?></div>
      <div class="quickcheckout-content"><?php echo $payment_address; ?></div>
    </div>
  </div>
  <div class="col-sm-4 quickcheckout-confirm-mid">
    <div class="shipping-address">
      <div class="quickcheckout-heading"><i class="fa fa-user"></i> <?php echo $text_checkout_shipping_address; ?></div>
      <div class="quickcheckout-content"><?php echo $shipping_address; ?></div>
    </div>
  </div>
  <div class="col-sm-4 quickcheckout-confirm-right">
    <div class="payment-delivery">
      <div class="quickcheckout-heading"><?php echo $text_checkout_payment_delivery; ?></div>
      <div class="quickcheckout-content">
        <b><?php echo $text_checkout_payment_method; ?></b> <?php echo $payment_method; ?><br />
        <b><?php echo $text_checkout_shipping_method; ?></b> <?php echo $shipping_method; ?><br />
        <?php if ($shipping_method != 'No Shipping') { ?>
        <b><?php echo $text_checkout_delivery_preference; ?></b> <?php echo $delivery_preference; ?><br />
        <b><?php echo $text_checkout_delivery_telephone; ?></b> <?php echo $delivery_telephone; ?>
        <?php } ?>
      </div>
    </div>
  </div>
</div><?php /* yym_custom */ ?>

<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <td class="text-center cart-image"><?php echo $column_image; ?></td><?php /* yym */ ?>
        <td class="text-left cart-name"><?php echo $column_name; ?></td><?php /* yym */ ?>
        <td class="text-center cart-price"><?php echo $column_price; ?></td><?php /* yym */ ?>
        <td class="text-center cart-quantity"><?php echo $column_quantity; ?></td><?php /* yym */ ?>
        <td class="text-center cart-total"><?php echo $column_total; ?></td><?php /* yym */ ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($products as $product) { ?>
      <tr>
        <td class="text-left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
          <?php foreach ($product['option'] as $option) { ?>
          <br />
          - <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
          <?php } ?>
          <?php if($product['recurring']) { ?>
          <br />
          <span class="label label-info"><?php echo $text_recurring; ?></span> <small><?php echo $product['recurring']; ?></small>
          <?php } ?></td>
        <td class="text-center"><?php echo $product['price']; ?></td><?php /* yym */ ?>
        <td class="text-center">x&nbsp;<?php echo $product['quantity']; ?></td><?php /* yym */ ?><?php /* yym */ ?>
        <td class="text-center"><?php echo $product['total']; ?></td><?php /* yym */ ?>
      </tr>
      <?php } ?>
      <?php foreach ($vouchers as $voucher) { ?>
      <tr>
      <td class="text-center"></td>
      <td class="text-left"><?php echo $voucher['description']; ?></td><?php /* yym */ ?><?php /* yym */ ?>
      <td class="text-center"><?php echo $voucher['amount']; ?></td><?php /* yym */ ?><?php /* yym */ ?>
      <td class="text-center">x&nbsp;1</td><?php /* yym */ ?>
      <td class="text-center"><?php echo $voucher['amount']; ?></td><?php /* yym */ ?>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <?php foreach ($totals as $total) { ?>
      <tr>
        <td colspan="4" class="text-right"><strong><?php echo $total['title']; ?></strong></td><?php /* yym */ ?>
        <td class="text-center"><?php echo $total['text']; ?></td><?php /* yym */ ?>
      </tr>
      <?php } ?>
    </tfoot>
  </table>
</div>
<div class="payment"><?php /* yym */ ?>
  <div class="row">
    <div class="col-xs-6">
      <div class="buttons">
        <a class="btn btn-default" href="<?php echo $back; ?>"><?php echo $button_back; ?></a>
      </div>
    </div>
    <div class="col-xs-6"><?php echo $payment; ?></div>
  </div>
</div>
<?php } else { ?>
<script type="text/javascript"><!--
location = '<?php echo $redirect; ?>';
//--></script>
<?php } ?>
<?php if ($auto_submit) { ?>
<script type="text/javascript"><!--
$('.payment form').submit();
$('.payment input[type=\'button\']').trigger('click');
$('.payment #button-confirm').trigger('click');
//--></script>
<?php } ?>