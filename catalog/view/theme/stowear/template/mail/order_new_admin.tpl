<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">
<table width="100%">
  <tbody>
    <tr>
      <td align="center">
        <div style="width: 600px; text-align: left;">
          <p style="margin-top: 0px; margin-bottom: 20px;">
            <?php echo $text_date_added; ?>: <?php echo $date_added; ?><br />
            <?php echo $text_total; ?>: <?php echo $total; ?>
            <?php if ($shipping_method) { ?><br /><?php echo $text_delivery_preference; ?>: <?php echo $delivery_preference; ?><?php } ?>
          </p>
          <table style="border-collapse: collapse; width: 100%; margin-bottom: 20px;">
            <thead>
              <tr>
                <td style="border-bottom: 1px solid #dddddd; background-color: #eeeeee; padding: 7px; text-align: left; font-size: 11px; font-weight: bold; color: #262626;" colspan="2"><?php echo $text_order_detail; ?></td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td style="border-left: 1px solid #dddddd; border-right: 1px solid #dddddd; border-bottom: 1px solid #dddddd; text-align: left; padding: 5px; font-size: 12px;">
                  <b><?php echo $text_email; ?></b>: <?php echo $email; ?><br />
                  <b><?php echo $text_telephone; ?></b>: <?php echo $telephone; ?><br />
                  <b><?php echo $text_ip; ?></b>: <?php echo $ip; ?>
                </td>
                <td style="border-right: 1px solid #dddddd; border-bottom: 1px solid #dddddd; text-align: left; padding: 5px; font-size: 12px;">
                  <b><?php echo $text_payment_method; ?></b>: <?php echo $payment_method; ?><br />
                  <?php if ($shipping_method) { ?><b><?php echo $text_shipping_method; ?></b>: <?php echo $shipping_method; ?><br />
                  <b><?php echo $text_delivery_telephone; ?></b>: <?php echo $delivery_telephone; ?><?php } ?>
                </td>
              </tr>
            </tbody>
          </table>

          <table style="border-collapse: collapse; width: 100%; margin-bottom: 20px;">
            <thead>
              <tr>
                <td style="border-bottom: 1px solid #dddddd; background-color: #eeeeee; padding: 7px; text-align: left; font-size: 11px; font-weight: bold; color: #262626;"><?php echo $text_payment_address; ?></td>
                <?php if ($shipping_address) { ?><td style="border-bottom: 1px solid #dddddd; background-color: #eeeeee; padding: 7px; text-align: left; font-size: 11px; font-weight: bold; color: #262626;"><?php echo $text_shipping_address; ?></td><?php } ?>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td style="border-left: 1px solid #dddddd; border-right: 1px solid #dddddd; border-bottom: 1px solid #dddddd; text-align: left; padding: 5px; font-size: 12px;">
                  <?php echo $payment_address; ?>
                </td>
                <?php if ($shipping_address) { ?><td style="border-right: 1px solid #dddddd; border-bottom: 1px solid #dddddd; text-align: left; padding: 5px; font-size: 12px;">
                  <?php echo $shipping_address; ?>
                </td><?php } ?>
              </tr>
            </tbody>
          </table>
          <?php if ($comment) { ?>
          <table style="border-collapse: collapse; width: 100%; margin-bottom: 20px;">
            <thead>
              <tr>
                <td style="border-bottom: 1px solid #dddddd; background-color: #eeeeee; padding: 7px; text-align: left; font-size: 11px; font-weight: bold; color: #262626;"><?php echo $text_instruction; ?></td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td style="border-left: 1px solid #dddddd; border-right: 1px solid #dddddd; border-bottom: 1px solid #dddddd; text-align: left; padding: 5px; font-size: 12px;"><?php echo $comment; ?></td>
              </tr>
            </tbody>
          </table>
          <?php } ?>
          <table style="border-collapse: collapse; width: 100%; margin-bottom: 20px;">
            <thead>
              <tr>
                <td style="border-bottom: 1px solid #dddddd; background-color: #eeeeee; padding: 7px; text-align: left; font-size: 11px; font-weight: bold; color: #262626;"><?php echo $text_model; ?></td>
                <td style="border-bottom: 1px solid #dddddd; background-color: #eeeeee; padding: 7px; text-align: left; font-size: 11px; font-weight: bold; color: #262626;"><?php echo $text_product; ?></td>
                <td style="border-bottom: 1px solid #dddddd; background-color: #eeeeee; padding: 7px; text-align: center; font-size: 11px; font-weight: bold; color: #262626;"><?php echo $text_quantity; ?></td>
                <td style="border-bottom: 1px solid #dddddd; background-color: #eeeeee; padding: 7px; text-align: center; font-size: 11px; font-weight: bold; color: #262626;"><?php echo $text_total; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($products as $product) { ?>
              <tr>
                <td style="border-left: 1px solid #dddddd; border-right: 1px solid #dddddd; border-bottom: 1px solid #dddddd; text-align: left; padding: 5px; font-size: 12px;"><?php echo $product['model']; ?></td>
                <td style="border-right: 1px solid #dddddd; border-bottom: 1px solid #dddddd; text-align: left; padding: 5px; font-size: 12px;"><?php echo $product['name']; ?>
                  <?php foreach ($product['option'] as $option) { ?>
                  <br />
                  <small>- <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                  <?php } ?></td>
                <td style="border-right: 1px solid #dddddd; border-bottom: 1px solid #dddddd; text-align: center; padding: 5px; font-size: 12px;"><?php echo $product['quantity']; ?></td>
                <td style="border-right: 1px solid #dddddd; border-bottom: 1px solid #dddddd; text-align: center; padding: 5px; font-size: 12px;"><?php echo $product['total']; ?></td>
              </tr>
              <?php } ?>
              <?php foreach ($vouchers as $voucher) { ?>
              <tr>
                <td style="border-left: 1px solid #dddddd; border-right: 1px solid #dddddd; border-bottom: 1px solid #dddddd; text-align: left; padding: 5px; font-size: 12px;"></td>
                <td style="border-right: 1px solid #dddddd; border-bottom: 1px solid #dddddd; text-align: left; padding: 5px; font-size: 12px;"><?php echo $voucher['description']; ?></td>
                <td style="border-right: 1px solid #dddddd; border-bottom: 1px solid #dddddd; text-align: center; padding: 5px; font-size: 12px;">1</td>
                <td style="border-right: 1px solid #dddddd; border-bottom: 1px solid #dddddd; text-align: center; padding: 5px; font-size: 12px;"><?php echo $voucher['amount']; ?></td>
              </tr>
              <?php } ?>
            </tbody>
            <tfoot>
              <?php foreach ($totals as $total) { ?>
              <tr>
                <td style="border-left: 1px solid #dddddd; border-right: 1px solid #dddddd; border-bottom: 1px solid #dddddd; text-align: right; padding: 5px; font-size: 12px;" colspan="3"><b><?php echo $total['title']; ?>:</b></td>
                <td style="border-right: 1px solid #dddddd; border-bottom: 1px solid #dddddd; text-align: center; padding: 5px; font-size: 12px;"><?php echo $total['text']; ?></td>
              </tr>
              <?php } ?>
            </tfoot>
          </table>
          <p style="margin-top: 0px; margin-bottom: 20px;"><?php echo $text_footer; ?></p>
        </div>
      </td>
    </tr>
  </tbody>
</table>
</body>
</html>
