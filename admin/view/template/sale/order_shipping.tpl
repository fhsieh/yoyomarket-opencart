<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
  <meta charset="UTF-8" />
  <title>Packing List</title>
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
    <?php
    $mapping = array(
      'Frozen'          => 'frozen',
      'Chilled'         => 'chilled',
      'Frozen/Chilled'  => 'chilled_frozen',
      'Chilled/Frozen'  => 'chilled_frozen',
      '冷凍'             => 'frozen',
      '冷蔵'             => 'chilled',
      '冷凍/冷蔵'         => 'chilled_frozen',
      '冷蔵/冷凍'         => 'chilled_frozen'
    );

    $limits = array(
      'frozen'         => 15.000,
      'chilled'        => 15.000,
      'normal'         => 25.000
    );
    ?>
    <?php foreach ($orders as $order) { ?>
    <?php
    $weights = array();

    foreach ($order['product'] as $product) {
      $weight = (float)substr($product['weight'], 0, -2); // trim kg
      if ($product['attributes']) {
        $weights[$mapping[$product['attributes']]][] = $weight;
      } else {
        $weights['normal'][] = $weight;
      }
    }

    //var_dump($weights);
    //echo '<br><br>Pre-adjusted weights:<br>';
    //foreach ($weights as $key => $row) {
    //  echo $key . ': ' . array_sum($row) . '<br>';
    //}

    if (isset($weights['chilled_frozen'])) {
      if (isset($weights['chilled']) && !isset($weights['frozen'])) {
        $weights['chilled'] = array_merge($weights['chilled'], $weights['chilled_frozen']);
      } elseif (isset($weights['frozen']) && !isset($weights['chilled'])) {
        $weights['frozen'] = array_merge($weights['frozen'], $weights['chilled_frozen']);
      } elseif (isset($weights['frozen']) && isset($weights['chilled'])) {
        foreach ($weights['chilled_frozen'] as $item_weight) {
          //echo 'mod: ' . fmod(array_sum($weights['frozen']), (float)$limits['frozen']);
          if ((fmod(array_sum($weights['frozen']), $limits['frozen']) + $item_weight) <= $limits['frozen']) {
            $weights['frozen'][] = $item_weight;
          } else {
            $weights['chilled'][] = $item_weight;
          }
        }
      } else {
        $weights['chilled'] = $weights['chilled_frozen'];
      }

      unset($weights['chilled_frozen']);
    }

    //var_dump($weights);
    //echo '<br><br>Post-adjusted weights:<br>';
    //foreach ($weights as $key => $row) {
    //  echo $key . ': ' . array_sum($row) . '<br>';
    //}

    $boxes = array();

    foreach ($limits as $key => $row) {
      if (isset($weights[$key])) {
        $boxes[$key] = (int)ceil(array_sum($weights[$key]) / $limits[$key]);
      } else {
        $boxes[$key] = 0;
      }
    }

    //var_dump($boxes);
    //echo '<br><br>';

    ?>
    <div style="page-break-after: always;">
      <div class="print-header">
        <table class="table-packing">
          <tbody>
            <tr>
              <td colspan="1" rowspan="2" class="tn bn ln vt" style="width: auto;">
                <h3><b><?php echo $order['order_id']; ?></b></h3>
              </td>
              <td colspan="1" rowspan="2" class="tc sm" style="width: 4.5cm;">NEW&emsp;/&emsp;REFERRAL&emsp;/&emsp;OTHER</td>
              <td colspan="1" rowspan="1" class="rd tc sm" style="width: 3cm">CORRESP&emsp;/&emsp;REFUND</td>
              <td colspan="1" rowspan="1" class="rt ld" style="width: 0.85cm;"></td>
              <td colspan="1" rowspan="2" class="tt rd lt tc" style="width: 0.5cm;">F</td>
              <td colspan="1" rowspan="2" class="tt rd ld tc" style="width: 1cm;"><?php if ($boxes['frozen'] > 0) { ?><?php echo $boxes['frozen']; ?><?php } ?></td>
              <td colspan="1" rowspan="2" class="tt ld" style="width: 1cm;"></td>
              <td colspan="2" rowspan="5" class="tt rt vt">
                <?php if (in_array($order['payment_method'], array('Collect On Delivery', 'コレクト'))) { ?>
                <b>COD:</b>
                <?php } else { ?>
                <b><?php echo $order['payment_method']; ?></b>
                <?php } ?>
                <?php echo $order['total']; ?>
              </td>
            </tr>
            <tr>
              <td colspan="2" rowspan="1" class="rt">¥</td>
            </tr>
            <tr>
              <td colspan="4" rowspan="6" class="rt bn ln vt">
              </td>
              <td colspan="1" rowspan="2" class="rd lt tc">C</td>
              <td colspan="1" rowspan="2" class="rd ld tc"><?php if ($boxes['chilled'] > 0) { ?><?php echo $boxes['chilled']; ?><?php } ?></td>
              <td colspan="1" rowspan="2" class="ld"></td>
            </tr>
            <tr></tr>
            <tr>
              <td colspan="1" rowspan="2" class="rd bt lt tc">N</td>
              <td colspan="1" rowspan="2" class="rd bt ld tc"><?php if ($boxes['normal'] > 0) { ?><?php echo $boxes['normal']; ?><?php } ?></td>
              <td colspan="1" rowspan="2" class="bt ld"></td>
            </tr>
            <tr>
              <td colspan="2" rowspan="1" class="rt bt sm">MANIFEST</td>
            </tr>
            <tr>
              <td colspan="2" rowspan="1" class="tt rd lt tc sm">SEINO</td>
              <td colspan="1" rowspan="1" class="tt ld"></td>
              <td colspan="1" rowspan="1" class="tt rt" style="width: 2cm;">¥</td>
              <td colspan="1" rowspan="2" class="tt rt bt lt tc sm" style="width: 1cm;">IKEA</td>
            </tr>
            <tr>
              <td colspan="4" rowspan="1" class="rt bt lt tc">&ndash;<span style="display: inline-block; width: 1.5cm; margin: 0 -0.5em;"></span>&ndash;</td>
            </tr>
          </tbody>
        </table>
      </div>
      <table class="table-details">
        <tbody>
          <tr>
            <td style="width: 2cm;"><span class="title"><?php echo $text_order_id; ?></span></td>
            <td style="width: auto;" class="bn"><?php echo $order['order_id']; ?>
              <span class="pull-right"><span class="title"><?php echo $text_date_added; ?></span> <?php echo $order['date_added']; ?></span>
            </td>
          </tr>
          <tr>
            <td colspan="2">
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
            <td style="width: 6cm; border-top: 0.75pt solid #e0e0e0;">
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
      <table class="table-products table-products-bordered">
        <thead>
          <tr>
            <td style="width: 2cm;" class="title text-center"><b><?php echo $column_box; ?></b></td>
            <!--<td style="width: 2cm;" class="title text-center"><b><?php echo $column_model; ?></b></td>-->
            <td style="width: 1cm;" class="title text-center"><b><?php echo $column_quantity; ?></b></td>
            <td style="width: auto;" class="title"><b><?php echo $column_product; ?></b></td>
            <td style="width: 2cm;" class="title text-right"><b><?php echo $column_weight; ?></b></td>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($order['product'] as $product) { ?>
          <tr>
            <td></td>
            <!--<td class="text-center"><?php echo $product['model']; ?></td>-->
            <td class="text-center"><?php echo $product['quantity']; ?></td>
            <td><?php echo $product['name']; ?>
              <?php if ($product['attributes'] || $product['notes']) {?><span class="pull-right"><?php echo implode(', ', array_filter(array($product['notes'], $product['attributes']))); ?></span><?php } ?>
              <?php if ($product['attributes']) { ?><?php } ?>
              <?php foreach ($product['option'] as $option) { ?>
              <br /><small>- <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
              <?php } ?></td>
            <td class="text-right"><?php echo $product['weight']; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <td colspan="5" class="text-center"><em>End of order <?php echo $order['order_id']; ?></em></td>
          </tr>
        </tbody>
      </table>
    </div>
    <?php } ?>
  </div>
</body>
</html>