<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <title>Stock Check List</title>
  <base href="<?php echo $base; ?>" />
  <link type="text/css" href="view/stylesheet/print-stock.css" rel="stylesheet" media="all" />
</head>
<body>
  <div class="container">
    <span class="date pull-right"><?php echo $date; ?></span>
    <table class="table">
    <?php $location = ''; ?>
    <?php foreach ($products as $product) { ?>
      <?php if ($location != $product['location']) { ?>
      <?php $location = $product['location']; ?>
      <?php $divider = 1; ?>
      <tr>
        <td class="location" colspan="4">
          <strong><?php echo $location; ?></strong>
        </td>
      </tr>
      <?php } ?>
      <tr class="<?php if ($product['status'] && $product['quantity'] == 0) { echo 'muted'; } else if ($product['status'] == 0) { echo 'disabled'; } ?>">
        <td class="model text-center"><?php echo $product['model']; ?></td>
        <td class="name text-left">
          <?php echo $product['name']; ?>
          <?php if ($product['notes'] != '') { ?><span class="pull-right"><strong><?php echo $product['notes']; ?></strong></span><?php } ?>
        </td>
        <td class="cost text-center<?php if ($product['status'] == 1 && $product['quantity'] > 0 && $product['cost'] == '¥0') echo ' missing'; ?>">
          <?php
            if ($product['status'] == 1 && $product['quantity'] > 0 && $product['cost'] != '¥0') {
              echo $product['cost'];
            } else if ($product['status'] == 1 && $product['quantity'] == 0) {
              echo 'OUT';
            } else if ($product['status'] == 0) {
              echo 'DISABLED';
            }
          ?>
        </td>
        <td class="note"></td>
      </tr>
    <?php } ?>
    </table>
  </div>
</body>
</html>