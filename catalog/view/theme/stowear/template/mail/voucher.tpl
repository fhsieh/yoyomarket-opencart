<?php echo $header; ?>
          <?php if ($image) { ?><img src="<?php echo $image; ?>"><?php } ?>
          <p style="margin-top: 0px; margin-bottom: 20px;"><?php echo $text_greeting; ?></p>
          <p style="margin-top: 0px; margin-bottom: 20px;"><?php echo $text_from; ?></p>
          <?php if ($message) { ?><p style="margin-top: 0px; margin-bottom: 20px;"><?php echo $text_message; ?><br /><?php echo $message; ?></p><?php } ?>
          <p style="margin-top: 0px; margin-bottom: 20px;"><?php echo $text_code; ?>: <strong><?php echo $code; ?></strong></p>
          <p style="margin-top: 0px; margin-bottom: 20px;"><?php echo $text_redeem; ?></p>
          <p style="margin-top: 0px; margin-bottom: 20px;"><?php echo $text_footer; ?></p>
<?php echo $footer; ?>