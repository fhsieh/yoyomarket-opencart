<div id="login" class="form-inline">
  <div class="col-sm-5 text-left">
	<label class="col-sm-3 text-right" for="input-login-email"><?php echo $entry_email; ?></label>
	<div class="col-sm-9">
	  <input type="text" name="email" value="" class="form-control" id="input-login-email" />
	</div>
  </div>
  <div class="col-sm-5 text-left">
	<label class="col-sm-3 text-right" for="input-login-password"><?php echo $entry_password; ?></label>
	<div class="col-sm-9">
	  <div class="input-group">
		<input type="password" name="password" value="" class="form-control" />
		<div class="input-group-btn">
          <a class="btn btn-primary" href="<?php echo $forgotten; ?>" title="<?php echo $text_forgotten; ?>" data-toggle="tooltip"><i class="fa fa-question-circle"></i></a>
		</div>
	  </div>
	</div>
  </div>
  <div class="col-sm-2 text-right">
    <div class="input-group">
      <span class="input-group-btn">
        <button type="button" id="button-login" data-loading-text="<?php echo $text_loading; ?>" class="btn"><?php echo $button_login; ?></button>
      </span>
    </div>
  </div>
</div>

<script type="text/javascript"><!--
$('#login input').keydown(function(e) {
	if (e.keyCode == 13) {
		$('#button-login').click();
	}
});
//--></script>