<div class="pull-left"><?php /* yym_custom */ ?>
  <a href="<?php echo $continue; ?>" class="btn btn-default"><?php echo $button_shopping; ?></a>
</div>
<div class="pull-left"><?php /* yym_custom */ ?>
  <label><?php if ($text_agree) { ?>
    <?php echo $text_agree; ?>
    <?php if ($agree) { ?>
    <input type="checkbox" name="agree" value="1" checked="checked" />
    <?php } else { ?>
    <input type="checkbox" name="agree" value="1" />
    <?php } ?>
  <?php } ?></label>
</div>
<div class="pull-right"><?php /* yym_custom */ ?>
  <button type="button" id="button-payment-method" class="btn btn-primary" data-loading-text="<?php echo $text_loading; ?>"><?php echo $button_continue; ?></button>
</div>