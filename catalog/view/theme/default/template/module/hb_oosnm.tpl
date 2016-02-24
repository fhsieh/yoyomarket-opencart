<div id="notify-form" class="modal fade">
  <div class="modal-dialog" style="max-width: 400px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">x</span></button>
        <h4 class="modal-title"><?php echo $text_title; ?></h4>
      </div>
      <div class="modal-body">
        <div id="notify-message" class="text-left"></div>
        <div id="oosn_info_text"><?php echo $oosn_info_text; ?></div><div id="opt_info"></div><br />
        <input type="hidden" id="product_id" name="product_id" value="">

        <?php if ($hb_oosn_name_enable == 'y') {?>
        <div class="form-group">
          <label class="col-sm-3 control-label"><?php echo $oosn_text_name; ?></label>
          <div class="col-sm-9">
            <input type="text" name="notify_name" placeholder="<?php echo $oosn_text_name_plh; ?>" id="notify_name" class="form-control" value="<?php echo $fname;?>" />
          </div>
        </div>
        <?php } ?>

        <div class="form-group">
          <label class="col-sm-3 control-label"><?php echo $oosn_text_email; ?></label>
          <div class="col-sm-9">
            <input type="text" name="notify_email" placeholder="<?php echo $oosn_text_email_plh; ?>" id="notify_email" class="form-control" value="<?php echo $email;?>" />
          </div>
        </div>

        <?php if ($hb_oosn_mobile_enable == 'y') {?>
        <div class="form-group">
          <label class="col-sm-3 control-label"><?php echo $oosn_text_phone; ?></label>
          <div class="col-sm-9">
            <input type="text" name="notify_phone" placeholder="<?php echo $oosn_text_phone_plh; ?>" id="notify_phone" class="form-control" value="<?php echo $phone;?>" />
          </div>
        </div>
        <?php } ?>
      </div>
      <div class="modal-footer">
        <button type="button" id="notify-button" class="btn btn-primary btn-lg btn-block"><?php echo $notify_submit; ?></button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
function notify_form(i) {
  $('#product_id').val(i);
  $('#notify-message').html('');
  $('body').append($('#notify-form'));
  $('#notify-form').modal('show');
  $('#notify-button').prop('disabled', false);
}

$("#notify-button").click(function() {
  $('#notify-button').prop('disabled', true);

  $.ajax({
    type: 'post',
    url: 'index.php?route=product/product_oosn',
    data: {
      product_id:             $('#product_id').val(),
      data:                   $('#notify_email').val(),
      name:                   $('#notify_name').length ? $('#notify_name').val() : '',
      phone:                  $('#notify_phone').length ? $('#notify_phone').val() : '',
      selected_option_value:  $('#option_values').length ? $('#option_values').val() : 0,
      selected_option:        $('#option_values').length ? $('#selected_option').val() : 0,
      all_selected_option:    $('#option_values').length ? $('#all_selected_option').val() : 0
    },
    dataType: 'json',
    success: function(e) {
      if (e.success) {
        $('#notify-message').html(e.success);
        $('#notify-button').prop('disabled', false);
      }
    },
    error: function(e, o, t) {
      $('#notify-message').html('<span class="alert alert-danger">' + t + '\r\n' + e.statusText + '\r\n' + e.responseText + '</span>');
      $('#notify-button').prop('disabled', false);
    }
  });
});
</script>
