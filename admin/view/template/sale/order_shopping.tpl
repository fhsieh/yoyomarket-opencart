<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
  <meta charset="UTF-8" />
  <title>Shopping Sheet</title>
  <base href="<?php echo $base; ?>" />
  <link href="view/javascript/bootstrap/css/bootstrap.css" rel="stylesheet" media="all" />
  <link href="view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
  <link type="text/css" href="view/stylesheet/stylesheet.css" rel="stylesheet" media="all" />
  <link type="text/css" href="view/stylesheet/print.css" rel="stylesheet" media="all" />
  <script type="text/javascript" src="view/javascript/jquery/jquery-2.1.1.min.js"></script>
  <script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
  <div class="container">
    <?php foreach ($orders as $order) { ?>
    <div style="page-break-after: always;">
      <div class="print-header"></div>
      <table class="table-details">
        <tbody>
          <tr>
            <td style="width: 2cm;"><span class="title"><?php echo $text_order_id; ?></span></td>
            <td style="width: auto;"><?php echo $order['order_id']; ?></td>
            <td style="width: 6cm;"><span class="title"><?php echo $text_date_added; ?></span> <span class="pull-right"><?php echo $order['date_added']; ?></span></td>
          </tr>
          <tr>
            <td colspan="3">
              <table class="address">
                <tbody>
                  <tr>
                    <td style="width: 2cm;"><span class="title"><?php echo $text_bill_to; ?></span></td>
                    <td style="min-width: 4cm;">
                      <?php echo $order['payment_address']; ?><br />
                      <?php echo $order['email']; ?>
                    </td>
                    <td style="width: 2cm;"><span class="title"><?php echo $text_ship_to; ?></span></td>
                    <td>
                      <?php echo $order['shipping_address']; ?><br />
                      <?php echo $order['delivery_telephone']; ?>
                    </td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
          <tr>
            <td rowspan="3">
              <span class="title"><?php echo $column_comment; ?></span>
            </td>
            <td rowspan="3">
              <?php if ($order['comment']) { ?><?php echo $order['comment']; ?><?php } ?>
            </td>
            <td>
              <span class="title"><?php echo $text_payment; ?></span>
              <span class="pull-right"><?php echo $order['payment_method']; ?></span>
            </td>
          </tr>
          <tr>
            <td>
              <span class="title"><?php echo $text_shipping; ?></span>
              <span class="pull-right"><?php if ($order['shipping_method']) { ?><?php echo $order['shipping_method']; ?><?php } ?></span>
            </td>
          </tr>
          <tr>
            <td>
              <span class="title"><?php echo $text_delivery; ?></span>
              <span class="pull-right">
                <?php echo $order['delivery_preference']; ?>
              </span>
            </td>
          </tr>
        </tbody>
      </table>
<!--      <div style="margin-bottom: -20mm;"></div>-->
      <table class="table-products table-products-bordered">
        <thead>
          <tr>
            <td style="width: 2cm;" class="title text-center"><?php echo $column_location; ?></td>
            <td style="width: 2cm;" class="title text-center"><?php echo $column_model; ?></td>
            <td style="width: 1cm;" class="title text-center"><?php echo $column_quantity; ?></td>
            <td style="width: auto;" class="title"><?php echo $column_product; ?></td>
            <td style="width: 2cm;" class="title text-center"><?php echo $column_cost; ?></td>
            <td style="width: 2cm;" class="title text-center"><?php echo $column_purchased; ?></td>
            <td style="width: 2cm;" class="title text-right"><?php echo $column_price; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($order['product'] as $product) { ?>
          <tr>
            <td class="text-center"><?php echo $product['location']; ?></td>
            <td class="text-center"><?php echo $product['model']; ?></td>
            <td class="text-center"><?php echo $product['quantity']; ?></td>
            <td><?php echo $product['name']; ?><?php if ($product['notes'] != '') { ?><span class="pull-right"><?php echo $product['notes']; ?></span><?php } ?>
              <?php foreach ($product['option'] as $option) { ?>
              <br /><small>- <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
              <?php } ?></td>
            <td class="text-center"><?php echo $product['cost']; ?></td>
            <td></td>
            <td class="text-right"><?php echo $product['price']; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <td colspan="7" class="text-center"><em>End of order <?php echo $order['order_id']; ?>&emsp;|&emsp;<?php echo $order['name']; ?>: <?php echo $order['telephone']; ?></em></td>
          </tr>
        </tbody>
      </table>
    </div>
    <?php } ?>
  </div>
</body>
</html>