<div class="tab-pane" id="tab-license">
  <div class="text-center">
  <h2>Order ID: <?php echo $order_id; ?></h2>
  <h2>Email: <?php echo $email; ?></h2>
  <h2>Licensed Domain: <?php echo $domain; ?></h2>
  <h2>Activated Date: <?php echo $activated_date; ?></h2>
  To revoke your license for this domain, click <a href="#" id="button-revoke">here</a>.<br /><br />
  <a href="http://license.marketinsg.com/?order_id=<?php echo $order_id; ?>&email=<?php echo $email; ?>" class="btn btn-info btn-lg" target="_blank">License Portal</a>
  </div>
</div>
<?php if (!$order_id) { ?>
<div style="position:fixed;height:100%;width:100%;z-index:1;background:rgba(255,255,255,0.9);top:0;left:0;">
  <div class="container">
	<div class="row" style="padding:80px;">
	  <div class="col-sm-4 col-sm-offset-4 text-center">
		<h2>Register Extension for Support &amp; Updates</h2>
		<div class="form-group text-left">
		  <label class="control-label">Order ID</label>
		  <input type="text" name="license_order_id" value="" class="form-control" />
		</div>
		<div class="form-group text-left">
		  <label class="control-label">Email</label>
		  <input type="text" name="license_email" value="" class="form-control" />
		</div>
		<button type="button" id="button-license" class="btn btn-success btn-lg">License this Domain</button>
	  </div>
	</div>
  </div>
</div>
<?php } ?>
<div class="tab-pane form-horizontal" id="tab-about">
  <div class="col-sm-9">
	<h2>Support</h2>
	Need support? Fill up the form below to open a support ticket.<br /><br />
	<div class="form-group required">
	  <label class="col-sm-2 control-label" for="input-mail-name">Your Name</label>
	  <div class="col-sm-10">
		<input type="text" name="mail_name" value="" placeholder="Your Name" id="input-mail-name" class="form-control" />
	  </div>
	</div>
	<div class="form-group required">
	  <label class="col-sm-2 control-label" for="input-mail-email">Email Address</label>
	  <div class="col-sm-10">
		<input type="text" name="mail_email" value="" placeholder="Email Address" id="input-mail-email" class="form-control" />
	  </div>
	</div>
	<div class="form-group required">
	  <label class="col-sm-2 control-label" for="input-mail-order-id">Order ID</label>
	  <div class="col-sm-10">
		<input type="text" name="mail_order_id" value="" placeholder="Order ID" id="input-mail-order-id" class="form-control" />
	  </div>
	</div>
	<div class="form-group required">
	  <label class="col-sm-2 control-label" for="input-mail-message">Message</label>
	  <div class="col-sm-10">
		<textarea name="mail_message" rows="5" placeholder="Describe your issues. Provide your store admin and FTP login credentials if you require technical support." id="input-mail-message" class="form-control"></textarea>
	  </div>
	</div>
	<a class="btn btn-primary" id="button-mail">Contact Support</a>
	<a class="btn btn-success" href="http://www.opencart.com/index.php?route=extension/extension/info&amp;extension_id=<?php echo $extension_id; ?>" target="_blank" rel="nofollow">Rate <?php echo $extension; ?></a>
	<a class="btn btn-success" href="http://www.marketinsg.com/<?php echo $purchase_url; ?>" target="_blank">Purchase <?php echo $extension; ?></a>
  </div>
  <div class="col-sm-3">
	<h2>Follow Us</h2>
	<iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2FEquotix&amp;width=262&amp;height=558&amp;show_faces=true&amp;colorscheme=light&amp;stream=true&amp;show_border=false&amp;header=false&amp;appId=391573267589280" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:262px; height:558px;" allowTransparency="true"></iframe>
  </div>
</div>
<script type="text/javascript">
$('#button-license').on('click', function() {
	$.ajax({
		url: 'index.php?route=module/<?php echo $code; ?>/license&token=<?php echo $token; ?>',
		type: 'post',
		data: $('input[name=\'license_order_id\'], input[name=\'license_email\']'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-license').after('<i class="fa fa-spinner fa-spin"></i>');
		},
		success: function(json) {
			$('.fa-spinner').remove();
		
			if (json['success']) {
				location.reload();
			} else if (json['error']) {
				alert(json['error']);
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#button-revoke').on('click', function(e) {
	e.preventDefault();
	
	$.ajax({
		url: 'index.php?route=module/<?php echo $code; ?>/revoke&token=<?php echo $token; ?>',
		type: 'get',
		dataType: 'json',
		beforeSend: function() {
			$('#button-revoke').after('<i class="fa fa-spinner fa-spin"></i>');
		},
		success: function(json) {
			$('.fa-spinner').remove();
		
			if (json['success']) {
				location.reload();
			} else if (json['error']) {
				alert(json['error']);
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
</script>
<script type="text/javascript"><!--//
$('#button-mail').on('click', function() {
	$.ajax({
		url: 'index.php?route=module/<?php echo $code; ?>/mail&token=<?php echo $token; ?>',
		type: 'post',
		data: $('input[name=\'mail_name\'], input[name=\'mail_email\'], input[name=\'mail_order_id\'], textarea[name=\'mail_message\']'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-mail').after('<i class="fa fa-spinner"></i>');
		},
		success: function(json) {
			$('.fa-spinner, .text-danger').remove();
			
			if (json['error']) {
				if (json['error']['warning']) {
					alert(json['error']['warning']);
				}
				
				if (json['error']['name']) {
					$('input[name=\'mail_name\']').after('<div class="text-danger">' + json['error']['name'] + '</span>');
				}
				
				if (json['error']['email']) {
					$('input[name=\'mail_email\']').after('<div class="text-danger">' + json['error']['email'] + '</span>');
				}
				
				if (json['error']['order_id']) {
					$('input[name=\'mail_order_id\']').after('<div class="text-danger">' + json['error']['order_id'] + '</span>');
				}
				
				if (json['error']['message']) {
					$('textarea[name=\'mail_message\']').after('<div class="text-danger">' + json['error']['message'] + '</span>');
				}
			} else {
				alert(json['success']);
				
				$('input[name=\'mail_name\']').val('');
				$('input[name=\'mail_email\']').val('');
				$('input[name=\'mail_order_id\']').val('');
				$('textarea[name=\'mail_message\']').val('');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
//--></script>