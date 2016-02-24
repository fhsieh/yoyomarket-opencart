<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <title><?php echo $heading_title; ?></title>
  <base href="<?php echo $base; ?>" />
  <link type="text/css" href="view/stylesheet/print-sale.css" rel="stylesheet" media="all" />
</head>
<body>
  <div class="container">
    <span class="date pull-right"><?php echo $date; ?></span>
    <h3><?php echo $heading_title; ?></h3>
    <table class="table">
      <thead>
        <tr>
          <td class="text-left"><?php echo $column_order; ?></td>
          <td class="text-left"><?php echo $column_name; ?></td>
          <td class="text-center"><?php echo $column_date_added; ?></td>
          <td class="text-right"><?php echo $column_sub_total; ?></td>
          <td class="text-right"><?php echo $column_reward; ?></td>
          <td class="text-right"><?php echo $column_coupon; ?></td>
          <td class="text-right"><?php echo $column_voucher; ?></td>
          <td class="text-right"><?php echo $column_shipping; ?></td>
          <td class="text-right"><?php echo $column_total; ?></td>
          <td class="text-right"><?php echo $column_average; ?></td>
          <td class="text-left"><?php echo $column_payment_method; ?></td>
          <td class="text-left"><?php echo $column_status; ?></td>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $order) { ?>
        <tr>
          <td class="text-left"><?php echo $order['order_id']; ?></td>
          <td class="text-left"><?php echo $order['name']; ?></td>
          <td class="text-center"><?php echo $order['date_added']; ?></td>
          <td class="text-right"><?php echo $order['sub_total']; ?></td>
          <td class="text-right"><?php echo $order['reward']; ?></td>
          <td class="text-right"><?php echo $order['coupon']; ?></td>
          <td class="text-right"><?php echo $order['voucher']; ?></td>
          <td class="text-right"><?php echo $order['shipping']; ?></td>
          <td class="text-right"><?php echo $order['total']; ?></td>
          <td class="text-right"><?php echo $order['average']; ?></td>
          <td class="text-left"><?php echo $order['payment_method']; ?></td>
          <td class="text-left">
            <?php echo $order['status']; ?><?php if ($order['date_modified']) { ?>: <?php echo $order['date_modified']; ?><?php } ?>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</body>
</html>
