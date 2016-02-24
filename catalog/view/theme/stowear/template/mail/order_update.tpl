<?php echo $header; ?>
          <p style="margin-top: 0px; margin-bottom: 20px;"><?php echo $text_order_status; ?> <strong><?php echo $status; ?></strong></p>
          <?php if (isset($comment) && $comment != '') { ?><p style="margin-top: 0px; margin-bottom: 20px;"><?php echo $comment; ?></p><?php } ?>
          <?php if (isset($rewards) && $rewards != '') { ?><p style="margin-top: 0px; margin-bottom: 20px;"><?php echo $rewards; ?></p><?php } ?>
          <?php if ($customer_id) { ?>
          <p style="margin-top: 0px; margin-bottom: 20px;"><?php echo $text_link; ?><br/><a href="<?php echo $link; ?>"><?php echo $link; ?></a></p>
          <?php } ?>
          <p style="margin-top: 0px; margin-bottom: 20px;"><?php echo $text_footer; ?></p>
<?php echo $footer; ?>