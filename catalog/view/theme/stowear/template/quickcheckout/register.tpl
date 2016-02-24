<div class="form-horizontal">
  <div class="form-group required">
	<label class="control-label col-sm-3"><?php echo $entry_password; ?></label>
	<div class="col-sm-4">
      <input type="password" name="password" value="" class="form-control" />
	</div>
  </div>
  <div class="form-group required">
	<label class="control-label col-sm-3"><?php echo $entry_confirm; ?></label>
	<div class="col-sm-4">
      <input type="password" name="confirm" value="" class="form-control" />
	</div>
  </div>
  <?php if ($text_agree) { ?>
  <div class="form-group">
    <div class="col-sm-9 col-sm-push-3">
      <input type="checkbox" name="agree" value="1" id="agree-reg" />
      <label for="agree-reg"><?php echo $text_agree; ?></label>
    </div>
  </div>
  <?php } ?>
  <?php if (!empty($field_newsletter['required'])) { ?>
  <input type="checkbox" name="newsletter" value="1" id="newsletter" class="hide" checked="checked" />
  <?php } elseif (!empty($field_newsletter['display'])) { ?>
  <div class="form-group">
    <div class="col-sm-9 col-sm-push-3">
	  <?php if(!empty($field_newsletter['default'])) { ?>
	  <input type="checkbox" name="newsletter" value="1" id="newsletter" checked="checked" />
	  <?php } else { ?>
	  <input type="checkbox" name="newsletter" value="1" id="newsletter" />
	  <?php } ?>
	  <label for="newsletter"><?php echo $entry_newsletter; ?></label><br />
    </div>
  </div>
  <?php } else { ?>
  <input type="checkbox" name="newsletter" value="1" id="newsletter" class="hide" />
  <?php } ?>
</div>