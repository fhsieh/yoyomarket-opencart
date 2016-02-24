<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
  <meta charset="UTF-8" />
  <title>Customer Receipt</title>
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
      <div class="print-header">
        <img src="/image/catalog/assets/logo-letterhead.png" class="print-logo">
      </div>
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
      <?php if ($gift) { ?>
      <table class="table-products">
        <thead>
          <tr>
            <td style="width: 2cm;" class="title"><b><?php echo $column_model; ?></b></td>
            <td style="width: auto;" class="title"><b><?php echo $column_product; ?></b></td>
            <td style="width: 2cm;" class="title text-center"><b><?php echo $column_quantity; ?></b></td>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($order['product'] as $product) { ?>
          <tr class="product">
            <td><?php echo $product['model']; ?></td>
            <td><?php echo $product['name']; ?>
              <?php foreach ($product['option'] as $option) { ?>
              <br /><small>- <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
              <?php } ?>
            </td>
            <td class="text-center"><?php echo $product['quantity']; ?></td>
          </tr>
          <?php } ?>
          <?php foreach ($order['voucher'] as $voucher) { ?>
          <tr class="voucher">
            <td></td>
            <td><?php echo $voucher['description']; ?></td>
            <td class="text-center">1</td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <?php } else { ?>
      <table class="table-products">
        <thead>
          <tr>
            <td style="width: 2cm;" class="title"><b><?php echo $column_model; ?></b></td>
            <td style="width: auto;" class="title"><b><?php echo $column_product; ?></b></td>
            <td style="width: 2cm;" class="title"><b><?php echo $column_price; ?></b></td>
            <td style="width: 2cm;" class="title text-center"><b><?php echo $column_quantity; ?></b></td>
            <td style="width: 2cm;" class="title text-right"><b><?php echo $column_total; ?></b></td>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($order['product'] as $product) { ?>
          <tr class="product">
            <td><?php echo $product['model']; ?></td>
            <td><?php echo $product['name']; ?>
              <?php foreach ($product['option'] as $option) { ?>
              <br /><small>- <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
              <?php } ?>
            </td>
            <td><?php echo $product['price']; ?></td>
            <td class="text-center"><?php echo $product['quantity']; ?></td>
            <td class="text-right"><?php echo $product['total']; ?></td>
          </tr>
          <?php } ?>
          <?php foreach ($order['voucher'] as $voucher) { ?>
          <tr class="voucher">
            <td></td>
            <td><?php echo $voucher['description']; ?></td>
            <td><?php echo $voucher['amount']; ?></td>
            <td class="text-center">1</td>
            <td class="text-right"><?php echo $voucher['amount']; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <?php } ?>
      <div class="print-footer">
        <table class="table-totals">
          <tbody>
            <?php if ($gift) { ?>
            <tr class="total">
              <td></td>
            </tr>
            <?php } else { ?>
            <?php foreach ($order['total'] as $total) { ?>
            <tr class="total">
              <td style="width: auto;"></td>
              <td style="width: 6cm;"><span class="title"><?php echo $total['title']; ?></span> <span class="pull-right"><?php echo $total['text']; ?></span></td>
            </tr>
            <?php } ?>
            <?php } ?>
          </tbody>
        </table>
        <p><?php echo $entry_footer; ?> <span class="pull-right"><?php echo $order['store_email']; ?>&emsp;|&emsp;<?php echo $order['store_telephone']; ?></span></p>
      </div>
    </div>
    <?php } ?>
  </div>
</body>
</html>