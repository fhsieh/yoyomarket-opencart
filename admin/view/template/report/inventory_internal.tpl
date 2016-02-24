<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <title>Inventory Report</title>
  <base href="<?php echo $base; ?>" />
  <link type="text/css" href="view/stylesheet/print-stock.css" rel="stylesheet" media="all" />
</head>
<body>
  <div class="container">
    <span class="date pull-right"><?php echo $date; ?></span>
    <h3>Total Holdings: <strong><?php echo $total_internal; ?></strong></h3>
    <table class="table table-condensed table-bordered">
    <?php $location = ''; ?>
    <?php foreach ($products as $product) { ?>
      <?php if ($location != $product['location']) { ?>
      <?php $location = $product['location']; ?>
      <tr>
        <td class="location" colspan="5">
          <strong><?php echo $location; ?></strong>
        </td>
      </tr>
      <?php } ?>
      <tr<?php if ($product['quantity'] == 0) echo ' class="muted"'; ?>>
        <td class="model text-center"><?php echo $product['model']; ?></td>
        <td class="name text-left">
          <?php echo $product['name']; ?>
          <?php if ($product['notes'] != '') { ?><span class="pull-right"><strong><?php echo $product['notes']; ?></strong></span><?php } ?>
        </td>
        <td class="cost text-center"><?php echo $product['cost']; ?></td>
        <td class="quantity text-center"><?php echo $product['quantity']; ?></td>
        <td class="total text-center"><?php echo $product['total']; ?></td>
      </tr>
    <?php } ?>
    </table>
  </div>
</body>
</html>