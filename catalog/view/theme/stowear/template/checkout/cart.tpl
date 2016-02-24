<?php echo $header;
$theme_options = $this->registry->get('theme_options');
$config = $this->registry->get('config');
include('catalog/view/theme/' . $config->get('config_template') . '/template/new_elements/wrapper_top.tpl'); ?>

<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
  <div class="table-responsive cart-info">
    <table class="table table-bordered">
      <thead>
        <tr>
          <td class="text-center cart-image"><?php echo $column_image; ?></td><?php /* yym */ ?>
          <td class="text-left cart-name hidden-xs"><?php echo $column_name; ?></td><?php /* yym */ ?>
          <td class="text-center cart-price hidden-xs"><?php echo $column_price; ?></td><?php /* yym */ ?>
          <td class="text-center cart-quantity"><?php echo $column_quantity; ?></td><?php /* yym */ ?>
          <td class="text-center cart-total"><?php echo $column_total; ?></td><?php /* yym */ ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($products as $product) { ?>
        <tr>
          <td class="text-center"><?php if ($product['thumb']) { ?>
            <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail" /></a>
            <?php } ?>
            <div class="visible-xs"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?><div>
            </td>
          <td class="text-left hidden-xs"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
            <?php if (!$product['stock']) { ?>
            <span class="text-danger"><i class="fa fa-exclamation-circle"></i></span><!-- yym -->
            <?php } ?>
            <?php if ($product['option']) { ?>
            <?php foreach ($product['option'] as $option) { ?>
            <br />
            - <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
            <?php } ?>
            <?php } ?>
            <?php if ($product['reward']) { ?>
            <br />
            <small><?php echo $product['reward']; ?></small>
            <?php } ?>
            <?php if ($product['recurring']) { ?>
            <br />
            <span class="label label-info"><?php echo $text_recurring_item; ?></span> <small><?php echo $product['recurring']; ?></small>
            <?php } ?></td>
          <td class="text-center hidden-xs"><?php echo $product['price']; ?></td><?php /* yym */ ?>
          <td class="text-center">
            <div class="input-group btn-block"><?php /* yym_custom */ ?>
              <input type="text" name="quantity[<?php echo $product['key']; ?>]" value="<?php echo $product['quantity']; ?>" size="1" class="form-control" />
              <span class="input-group-btn">
                <button data-toggle="tooltip" title="<?php echo $button_update; ?>" class="btn btn-danger button-update"><i class="fa fa-refresh"></i></button>
                <button data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-primary button-remove" onclick="cart.remove('<?php echo $product['key']; ?>');"><i class="fa fa-times"></i></button>
              </span>
            </div>
          </td>
          <td class="text-center"><?php echo $product['total']; ?></td><?php /* yym */ ?>
        </tr>
        <?php } ?>
        <?php foreach ($vouchers as $vouchers) { ?>
        <tr>
          <td></td>
          <td class="text-left hidden-xs"><?php echo $vouchers['description']; ?></td><?php /* yym */ ?>
          <td class="text-center hidden-xs"><?php echo $vouchers['amount']; ?></td><?php /* yym */ ?>
          <td class="text-center">
            <div class="input-group btn-block"><?php /* yym_custom */ ?>
              <input type="text" name="" value="1" size="1" disabled="disabled" class="form-control" />
              <span class="input-group-btn">
                <button data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-primary button-remove" onclick="voucher.remove('<?php echo $vouchers['key']; ?>');"><i class="fa fa-times"></i></button>
              </span>
            </div>
          </td>
          <td class="text-center"><?php echo $vouchers['amount']; ?></td><?php /* yym */ ?>
        </tr>
        <?php } ?>
        <?php /* yym_catalog_cart_checkout */ ?>
        <?php foreach ($totals as $total) { ?>
        <tr>
          <td class="text-right" colspan="4"><strong><?php echo $total['title']; ?></strong></td>
          <td class="text-center"><?php echo $total['text']; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</form>

<?php /* yym ?>
<?php if ($coupon || $voucher || $reward || $shipping) { ?>
<h2><?php echo $text_next; ?></h2>
<p style="padding-bottom: 10px"><?php echo $text_next_choice; ?></p>
<div class="panel-group" id="accordion">
<?php echo $coupon; ?><?php echo $voucher; ?><?php echo $reward; ?><?php echo $shipping; ?>
</div>
<?php } ?>

<div class="cart-total">
    <table>
      <?php foreach ($totals as $total) { ?>
      <tr>
        <td class="text-right"><strong><?php echo $total['title']; ?>:</strong></td>
        <td class="text-right"><?php echo $total['text']; ?></td>
      </tr>
      <?php } ?>
    </table>
</div>
<?php */ ?>
<div class="buttons">
  <div class="pull-left"><a href="<?php echo $continue; ?>" class="btn btn-default"><?php echo $button_shopping; ?></a></div>
  <div class="pull-right"><a href="<?php echo $checkout; ?>" class="btn btn-primary"><?php echo $button_checkout; ?></a></div>
</div>

<?php include('catalog/view/theme/' . $config->get('config_template') . '/template/new_elements/wrapper_bottom.tpl'); ?>
<?php echo $footer; ?>