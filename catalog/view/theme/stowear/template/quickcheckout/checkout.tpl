<?php echo $header; // yym_custom
$theme_options = $this->registry->get('theme_options');
$config = $this->registry->get('config');
include('catalog/view/theme/' . $config->get('config_template') . '/template/new_elements/wrapper_top.tpl'); ?>
<div class="container">
  <?php /* yym ?>
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php */ ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php /* yym ?><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1><?php */ ?>
	  <!-- Start -->
	  <?php /* FontAwesome for themes that require it ?>
	  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	  <?php */ ?>
	  <div id="warning-messages"></div>
	  <div id="success-messages"></div>
	  <?php if ($mobile_stylesheet) { ?>
	  <link rel="stylesheet" media="screen and (min-width: 701px) and (max-width: 99999px)" href="<?php echo $stylesheet; ?>" />
	  <link rel="stylesheet" media="screen and (min-width: 1px) and (max-width: 700px)" href="<?php echo $mobile_stylesheet; ?>" />
	  <?php } else { ?>
	  <link rel="stylesheet" href="<?php echo $stylesheet; ?>" />
	  <?php } ?>

	  <?php if ($html_header) { ?>
	  <?php echo $html_header; ?>
	  <?php } ?>

	  <div id="quickcheckout-countdown"></div>

	  <div id="quickcheckoutconfirm">

	  <?php if (!$logged && $login_module) { ?>
	  <div class="quickcheckoutmid" id="login-box">
		<div id="checkout">
		  <div class="quickcheckout-heading"><i class="fa fa-sign-in"></i> <?php echo $text_checkout_option; ?></div>
		  <div class="quickcheckout-content"></div>
		</div>
		<div class="or"><?php echo $text_or; ?></div>
	  </div>
	  <?php } ?>
	  <div class="quickcheckoutleft">
		<?php if (!$logged) { ?>
		<div id="payment-address">
		  <div class="quickcheckout-heading"><i class="fa fa-user"></i> <?php echo $text_checkout_account; ?></div>
		  <div class="quickcheckout-content"></div>
		</div>
		<?php } else { ?>
		<div id="payment-address">
		  <div class="quickcheckout-heading"><i class="fa fa-user"></i> <?php echo $text_checkout_payment_address; ?></div>
		  <div class="quickcheckout-content"></div>
		</div>
		<?php } ?>
		<?php if ($shipping_required) { ?>
		<div id="shipping-address">
		  <div class="quickcheckout-heading"><i class="fa fa-user"></i> <?php echo $text_checkout_shipping_address; ?></div>
		  <div class="quickcheckout-content"></div>
		</div>
		<?php } ?>
		<?php if ($layout == '2') { ?>
		  <?php if ($cart_module) { ?>
		  <div id="cart1">
		    <div class="quickcheckout-content" style="border:none; padding: 0px;"></div>
		  </div>
		  <?php } ?>
		<?php } ?>
	  </div>
	  <div class="quickcheckoutright">
		<?php if ($shipping_required) { ?>
		<?php if ($shipping_module) { ?>
		<div id="shipping-method">
		<?php } else { ?>
		<div id="shipping-method" style="display:none;">
		<?php } ?>
		  <div class="quickcheckout-heading"><i class="fa fa-truck"></i> <?php echo $text_checkout_shipping_method; ?></div>
		  <div class="quickcheckout-content"></div>
		</div>
		<?php } ?>
		<?php if ($payment_module) { ?>
		<div id="payment-method">
		<?php } else { ?>
		<div id="payment-method" style="display:none;">
		<?php } ?>
		  <div class="quickcheckout-heading"><i class="fa fa-credit-card"></i> <?php echo $text_checkout_payment_method; ?></div>
		  <div class="quickcheckout-content"></div>
		</div>
		<?php if ($layout == '2') { ?>
		  <?php if ($voucher_module || $coupon_module || $reward_module) { ?>
		  <div id="voucher">
		    <div class="quickcheckout-content" style="border:none; padding: 0px;overflow: hidden;"></div>
		  </div>
		  <?php } ?>
		<?php } ?>
	  </div>
	  <?php if ($layout == '1' || $layout == '3') { ?>
	    <?php if ($cart_module || $voucher_module || $coupon_module || $reward_module) { ?>
	    <div class="quickcheckoutleft extra-width">
		  <?php if ($cart_module) { ?>
		  <div id="cart1">
		    <div class="quickcheckout-content" style="border:none; padding: 0px;"></div>
		  </div>
		  <?php } ?>
		  <?php if ($voucher_module || $coupon_module || $reward_module) { ?>
		  <div id="voucher">
		    <div class="quickcheckout-content" style="border:none; padding: 0px;overflow: hidden;"></div>
		  </div>
		  <?php } ?>
		</div>
		<?php } ?>
	  <?php } ?>

	  <div style="clear: both;"></div>

	  <div class="quickcheckoutmid">
		<div id="terms">
		  <div class="buttons"></div><?php /* yym_custom */ ?>
		</div>
	  </div>

	  </div><!-- Close QuickCheckoutConfirm div -->

	  <?php if ($html_footer) { ?>
	  <?php echo $html_footer; ?>
	  <?php } ?>
	  <!-- End -->
	<?php echo $content_bottom; ?></div>
  <?php /* yym ?><?php echo $column_right; ?><?php */ ?></div>
</div>
<script type="text/javascript"><!--
<?php if ($countdown && $countdown_end) { ?>
$('#quickcheckout-countdown').countdown({
	timezone: <?php echo $timezone; ?>,
	until: new Date('<?php echo $countdown_end; ?>'),
    layout: '<?php echo addslashes($countdown_before); ?><b><?php echo $countdown_timer; ?></b> {desc}',
    description: '<?php echo addslashes($countdown_after); ?>'
});
<?php } ?>
<?php if ($load_screen) { ?>
$(window).load(function() {
    $.blockUI({
		message: '<h1><?php echo $text_please_wait; ?></h1>',
		css: {
			border: 'none',
			padding: '15px',
			backgroundColor: '#000000',
			'-webkit-border-radius': '10px',
			'-moz-border-radius': '10px',
			'-khtml-border-radius': '10px',
			'border-radius': '10px',
			opacity: .8,
			color: '#fff'
		}
	});

    setTimeout($.unblockUI, 2000);
});
<?php } ?>

<?php if (!$logged) { ?>
	<?php if ($login_module) { ?>
	// Login box
	$(document).ready(function() {
		$.ajax({
			url: 'index.php?route=quickcheckout/login',
			dataType: 'html',
			cache: false,
			beforeSend: function() {
				$('#checkout .quickcheckout-content').html('<div class="text-center"><i class="fa fa-spinner fa-5x fa-spin"></i></div>');
			},
			success: function(html) {
				$('#checkout .quickcheckout-content').html(html);
			},
			<?php if ($debug) { ?>
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
			<?php } ?>
		});
	});

	// Login Form Clicked
	$(document).on('click', '#button-login', function() {
		$.ajax({
			url: 'index.php?route=quickcheckout/login/validate',
			type: 'post',
			data: $('#checkout #login :input'),
			dataType: 'json',
			cache: false,
			beforeSend: function() {
				$('#button-login').prop('disabled', true);
				$('#button-login').button('loading');
			},
			complete: function() {
				$('#button-login').prop('disabled', false);
				$('#button-login').button('reset');
			},
			success: function(json) {
				$('.alert').remove();

				if (json['redirect']) {
					location = json['redirect'];
				} else if (json['error']) {
					$('#warning-messages').prepend('<div class="alert alert-danger" style="display: none;"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '</div>');

					$('html, body').animate({ scrollTop: 0 }, 'slow');

					$('.alert-danger').fadeIn('slow');
				}
			},
			<?php if ($debug) { ?>
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
			<?php } ?>
		});
	});
	<?php } ?>

// Validate Register
function validateRegister() {
	$.ajax({
		url: 'index.php?route=quickcheckout/register/validate',
		type: 'post',
		data: $('#payment-address input[type=\'text\'], #payment-address input[type=\'password\'], #payment-address input[type=\'checkbox\']:checked, #payment-address input[type=\'radio\']:checked, #payment-address input[type=\'hidden\'], #payment-address select'),
		dataType: 'json',
		cache: false,
		success: function(json) {
			$('.alert, .text-danger').remove();

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				$('#button-payment-method').prop('disabled', false);
				$('#button-payment-method').button('reset');

				$('.fa-spinner').remove();

				$('html, body').animate({ scrollTop: 0 }, 'slow');

				if (json['error']['warning']) {
					$('#warning-messages').prepend('<div class="alert alert-danger" style="display: none;"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '</div>');

					$('.alert-danger').fadeIn('slow');
				}

				<?php if ($text_error) { ?>
					if (json['error']['password']) {
						$('#payment-address input[name=\'password\']').after('<div class="text-danger">' + json['error']['password'] + '</div>');
					}

					if (json['error']['confirm']) {
						$('#payment-address input[name=\'confirm\']').after('<div class="text-danger">' + json['error']['confirm'] + '</div>');
					}
				<?php } ?>
				<?php if ($highlight_error) { ?>
					if (json['error']['password']) {
						$('#payment-address input[name=\'password\']').css('border', '1px solid #f00').css('background', '#F8ACAC');
					}

					if (json['error']['confirm']) {
						$('#payment-address input[name=\'confirm\']').css('border', '1px solid #f00').css('background', '#F8ACAC');
					}
				<?php } ?>
			} else {
				<?php if ($shipping_required) { ?>
				var shipping_address = $('#payment-address input[name=\'shipping_address\']:checked').val();

				if (shipping_address) {
					validateShippingMethod();
				} else {
					validateGuestShippingAddress();
				}
				<?php } else {?>
				validatePaymentMethod();
				<?php } ?>
			}
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});
}

// Load Guest Payment Form
$(document).ready(function() {
	$.ajax({
		url: 'index.php?route=quickcheckout/guest',
		dataType: 'html',
		cache: false,
		beforeSend: function() {
			$('#payment-address .quickcheckout-content').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-5x"></i></div>');
		},
		success: function(html) {
			$('#payment-address .quickcheckout-content').html(html);
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});
});

// Validate Guest Payment Address
function validateGuestAddress() {
	$.ajax({
		url: 'index.php?route=quickcheckout/guest/validate',
		type: 'post',
		data: $('#payment-address input[type=\'text\'], #payment-address input[type=\'checkbox\']:checked, #payment-address input[type=\'radio\']:checked, #payment-address select'),
		dataType: 'json',
		cache: false,
		success: function(json) {
			$('.alert, .text-danger').remove();

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				$('#button-payment-method').prop('disabled', false);
				$('#button-payment-method').button('reset');

				$('.fa-spinner').remove();

				$('html, body').animate({ scrollTop: 0 }, 'slow');

				if (json['error']['warning']) {
					$('#warning-messages').prepend('<div class="alert alert-danger" style="display: none;"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '</div>');

					$('.alert-danger').fadeIn('slow');
				}

				<?php if ($text_error) { ?>
					for (i in json['error']) {
						var element = $('#input-payment-' + i.replace('_', '-'));

						if ($(element).parent().hasClass('input-group')) {
							$(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
						} else {
							$(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
						}
					}
				<?php } ?>
				<?php if ($highlight_error) { ?>
					for (i in json['error']) {
						var element = $('#input-payment-' + i.replace('_', '-'));

						$(element).css('border', '1px solid #f00').css('background', '#F8ACAC');
					}
				<?php } ?>
			} else {
				var create_account = $('#payment-address input[name=\'create_account\']:checked').val();

				<?php if ($shipping_required) { ?>
				var shipping_address = $('#payment-address input[name=\'shipping_address\']:checked').val();

				if (create_account) {
					validateRegister();
				} else {
					if (shipping_address) {
						validateShippingMethod();
					} else {
						validateGuestShippingAddress();
					}
				}
				<?php } else { ?>
				if (create_account) {
					validateRegister();
				} else {
					validatePaymentMethod();
				}
				<?php } ?>
			}
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});
}

// Validate Guest Shipping Address
function validateGuestShippingAddress() {
	$.ajax({
		url: 'index.php?route=quickcheckout/guest_shipping/validate',
		type: 'post',
		data: $('#shipping-address input[type=\'text\'], #shipping-address input[type=\'checkbox\']:checked, #shipping-address input[type=\'radio\']:checked, #shipping-address select'),
		dataType: 'json',
		cache: false,
		success: function(json) {
			$('.alert, .text-danger').remove();

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				$('#button-payment-method').prop('disabled', false);
				$('#button-payment-method').button('reset');

				$('.fa-spinner').remove();

				$('html, body').animate({ scrollTop: 0 }, 'slow');

				if (json['error']['warning']) {
					$('#warning-messages').prepend('<div class="alert alert-danger" style="display: none;"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '</div>');

					$('.alert-danger').fadeIn('slow');
				}

				<?php if ($text_error) { ?>
					for (i in json['error']) {
						var element = $('#input-shipping-' + i.replace('_', '-'));

						if ($(element).parent().hasClass('input-group')) {
							$(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
						} else {
							$(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
						}
					}
				<?php } ?>
				<?php if ($highlight_error) { ?>
					for (i in json['error']) {
						var element = $('#input-shipping-' + i.replace('_', '-'));

						$(element).css('border', '1px solid #f00').css('background', '#F8ACAC');
					}
				<?php } ?>
			} else {
				validateShippingMethod();
			}
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});
}

// Confirm Payment
$(document).on('click', '#button-payment-method', function() {
	$('#button-payment-method').prop('disabled', true);
	$('#button-payment-method').button('loading');

	$('#button-payment-method').after('<i class="fa fa-spinner fa-spin"></i>');

	validateGuestAddress();
});
<?php } else { ?>
// Load payment addresses
$(document).ready(function() {
	$.ajax({
		url: 'index.php?route=quickcheckout/payment_address',
		dataType: 'html',
		cache: false,
		beforeSend: function() {
			$('#payment-address .quickcheckout-content').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-5x"></i></div>');
		},
		success: function(html) {
			$('#payment-address .quickcheckout-content').html(html);
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});
});

<?php if ($shipping_required) { ?>
// Load shipping addresses
$(document).ready(function() {
	$.ajax({
		url: 'index.php?route=quickcheckout/shipping_address',
		dataType: 'html',
		cache: false,
		beforeSend: function() {
			$('#shipping-address .quickcheckout-content').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-5x"></i></div>');
		},
		success: function(html) {
			$('#shipping-address .quickcheckout-content').html(html);
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});
});
<?php } ?>

// Validate Payment Address
function validatePaymentAddress() {
	$.ajax({
		url: 'index.php?route=quickcheckout/payment_address/validate',
		type: 'post',
		data: $('#payment-address input[type=\'text\'], #payment-address input[type=\'password\'], #payment-address input[type=\'checkbox\']:checked, #payment-address input[type=\'radio\']:checked, #payment-address input[type=\'hidden\'], #payment-address select'),
		dataType: 'json',
		cache: false,
		success: function(json) {
			$('.alert, .text-danger').remove();

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				$('#button-payment-method').prop('disabled', false);
				$('#button-payment-method').button('reset');

				$('.fa-spinner').remove();

				$('html, body').animate({ scrollTop: 0 }, 'slow');

				if (json['error']['warning']) {
					$('#warning-messages').prepend('<div class="alert alert-danger" style="display: none;"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '</div>');

					$('.alert-danger').fadeIn('slow');
				}

				<?php if ($text_error) { ?>
					for (i in json['error']) {
						var element = $('#input-payment-' + i.replace('_', '-'));

						if ($(element).parent().hasClass('input-group')) {
							$(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
						} else {
							$(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
						}
					}
				<?php } ?>
				<?php if ($highlight_error) { ?>
					for (i in json['error']) {
						var element = $('#input-payment-' + i.replace('_', '-'));

						$(element).css('border', '1px solid #f00').css('background', '#F8ACAC');
					}
				<?php } ?>
			} else {
				<?php if ($shipping_required) { ?>
					validateShippingAddress();
				<?php } else { ?>
					validatePaymentMethod();
				<?php } ?>
			}
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});
}

<?php if ($shipping_required) { ?>
// Validate Shipping Address
function validateShippingAddress() {
	$.ajax({
		url: 'index.php?route=quickcheckout/shipping_address/validate',
		type: 'post',
		data: $('#shipping-address input[type=\'text\'], #shipping-address input[type=\'password\'], #shipping-address input[type=\'checkbox\']:checked, #shipping-address input[type=\'radio\']:checked, #shipping-address select'),
		dataType: 'json',
		cache: false,
		success: function(json) {
			$('.alert, .text-danger').remove();

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				$('#button-payment-method').prop('disabled', false);
				$('#button-payment-method').button('reset');

				$('.fa-spinner').remove();

				$('html, body').animate({ scrollTop: 0 }, 'slow');

				if (json['error']['warning']) {
					$('#warning-messages').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"> ' + json['error']['warning'] + '</div>');

					$('.alert-danger').fadeIn('slow');
				}

				<?php if ($text_error) { ?>
					for (i in json['error']) {
						var element = $('#input-shipping-' + i.replace('_', '-'));

						if ($(element).parent().hasClass('input-group')) {
							$(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
						} else {
							$(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
						}
					}
				<?php } ?>
				<?php if ($highlight_error) { ?>
					for (i in json['error']) {
						var element = $('#input-shipping-' + i.replace('_', '-'));

						$(element).css('border', '1px solid #f00').css('background', '#F8ACAC');
					}
				<?php } ?>
			} else {
				validateShippingMethod();
			}
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});
}
<?php } ?>

// Confirm payment
$(document).on('click', '#button-payment-method', function() {
	$('#button-payment-method').prop('disabled', true);
	$('#button-payment-method').button('loading');

	$('#button-payment-method').after('<i class="fa fa-spinner fa-spin"></i>');

	validatePaymentAddress();
});
<?php } ?> // Close if logged php

// Payment Method
function reloadPaymentMethod() {
	$.ajax({
		url: 'index.php?route=quickcheckout/payment_method',
		type: 'post',
		data: $('#payment-address input[type=\'text\'], #payment-address input[type=\'checkbox\']:checked, #payment-address input[type=\'radio\']:checked, #payment-address input[type=\'hidden\'], #payment-address select, #payment-method input[type=\'text\'], #payment-method input[type=\'checkbox\']:checked, #payment-method input[type=\'radio\']:checked, #payment-method input[type=\'hidden\'], #payment-method select'),
		dataType: 'html',
		cache: false,
		beforeSend: function() {
			$('#payment-method .quickcheckout-content').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-5x"></i></div>');
		},
		success: function(html) {
			$('#payment-method .quickcheckout-content').html(html);
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});
}

function reloadPaymentMethodById(address_id) {
	$.ajax({
		url: 'index.php?route=quickcheckout/payment_method&address_id=' + address_id,
		type: 'post',
		data: $('#payment-method input[type=\'checkbox\']:checked, #payment-method input[type=\'radio\']:checked, #payment-method input[type=\'hidden\'], #payment-method select'),
		dataType: 'html',
		cache: false,
		beforeSend: function() {
			$('#payment-method .quickcheckout-content').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-5x"></i></div>');
		},
		success: function(html) {
			$('#payment-method .quickcheckout-content').html(html);
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});
}

// Validate Payment Method
function validatePaymentMethod() {
	$.ajax({
		url: 'index.php?route=quickcheckout/payment_method/validate',
		type: 'post',
		data: $('#payment-method select, #payment-method input[type=\'radio\']:checked, #payment-method input[type=\'checkbox\']:checked, #payment-method textarea'),
		dataType: 'json',
		cache: false,
		success: function(json) {
			$('.alert, .text-danger').remove();

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				$('#button-payment-method').prop('disabled', false);
				$('#button-payment-method').button('reset');

				$('.fa-spinner').remove();

				$('html, body').animate({ scrollTop: 0 }, 'slow');

				if (json['error']['warning']) {
					$('#warning-messages').prepend('<div class="alert alert-danger" style="display: none;"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '</div>');

					$('.alert-danger').fadeIn('slow');
				}
			} else {
				validateTerms();
			}
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});
}

// Shipping Method
<?php if ($shipping_required) { ?>
	function reloadShippingMethod(type) {
		if (type == 'payment') {
			var post_data = $('#payment-address input[type=\'text\'], #payment-address input[type=\'checkbox\']:checked, #payment-address input[type=\'radio\']:checked, #payment-address input[type=\'hidden\'], #payment-address select, #shipping-method input[type=\'text\'], #shipping-method input[type=\'checkbox\']:checked, #shipping-method input[type=\'radio\']:checked, #shipping-method input[type=\'hidden\'], #shipping-method select');
		} else {
			var post_data = $('#shipping-address input[type=\'text\'], #shipping-address input[type=\'checkbox\']:checked, #shipping-address input[type=\'radio\']:checked, #shipping-address input[type=\'hidden\'], #shipping-address select, #shipping-method input[type=\'text\'], #shipping-method input[type=\'checkbox\']:checked, #shipping-method input[type=\'radio\']:checked, #shipping-method input[type=\'hidden\'], #shipping-method select');
		}

		$.ajax({
			url: 'index.php?route=quickcheckout/shipping_method',
			type: 'post',
			data: post_data,
			dataType: 'html',
			cache: false,
			beforeSend: function() {
				$('#shipping-method .quickcheckout-content').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-5x"></i></div>');
			},
			success: function(html) {
				$('#shipping-method .quickcheckout-content').html(html);
			},
			<?php if ($debug) { ?>
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
			<?php } ?>
		});
	}

	function reloadShippingMethodById(address_id) {
		$.ajax({
			url: 'index.php?route=quickcheckout/shipping_method&address_id=' + address_id,
			type: 'post',
			data: $('#shipping-method input[type=\'text\'], #shipping-method input[type=\'checkbox\']:checked, #shipping-method input[type=\'radio\']:checked, #shipping-method input[type=\'hidden\'], #shipping-method select'),
			dataType: 'html',
			cache: false,
			beforeSend: function() {
				$('#shipping-method .quickcheckout-content').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-5x"></i></div>');
			},
			success: function(html) {
				$('#shipping-method .quickcheckout-content').html(html);
			},
			<?php if ($debug) { ?>
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
			<?php } ?>
		});
	}

	// Validate Shipping Method
	function validateShippingMethod() {
		$.ajax({
			url: 'index.php?route=quickcheckout/shipping_method/validate',
			type: 'post',
			data: $('#shipping-method select, #shipping-method input[type=\'radio\']:checked, #shipping-method textarea, #shipping-method input[type=\'text\']'),
			dataType: 'json',
			cache: false,
			success: function(json) {
				$('.alert, .text-danger').remove();

				if (json['redirect']) {
					location = json['redirect'];
				} else if (json['error']) {
					$('#button-payment-method').prop('disabled', false);
					$('#button-payment-method').button('reset');

					$('.fa-spinner').remove();

					$('html, body').animate({ scrollTop: 0 }, 'slow');

					if (json['error']['warning']) {
						$('#warning-messages').prepend('<div class="alert alert-danger" style="display: none;"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '</div>');

						$('.alert-danger').fadeIn('slow');
					}
				} else {
					validatePaymentMethod();
				}
			},
			<?php if ($debug) { ?>
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
			<?php } ?>
		});
	}
<?php } ?>

// Load confirm button
$(document).ready(function() {
	$.ajax({
		url: 'index.php?route=quickcheckout/terms',
		dataType: 'html',
		cache: false,
		beforeSend: function() {
			$('#terms .buttons').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-5x"></i></div>'); // yym_custom
		},
		success: function(html) {
			$('#terms .buttons').html(html); // yym_custom
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});
});

// Validate confirm button
function validateTerms() {
	$.ajax({
		url: 'index.php?route=quickcheckout/terms/validate',
		type: 'post',
		data: $('#terms input[type=\'checkbox\']:checked'),
		dataType: 'json',
		cache: false,
		success: function(json) {
			if (json['redirect']) {
				location = json['redirect'];
			}

			if (json['error']) {
				$('#button-payment-method').prop('disabled', false);
				$('#button-payment-method').button('reset');

				$('.fa-spinner').remove();

				$('html, body').animate({ scrollTop: 0 }, 'slow');

				if (json['error']['warning']) {
					$('#warning-messages').prepend('<div class="alert alert-danger" style="display: none;"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '</div>');

					$('.alert-danger').fadeIn('slow');
				}
			} else {
				loadConfirm();
			}
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});
}

// Load confirm
function loadConfirm() {
	$.ajax({
		url: 'index.php?route=quickcheckout/confirm',
		dataType: 'html',
		cache: false,
		beforeSend: function() {
			$('html, body').animate({ scrollTop: 0 }, 'slow');

			<?php if ($slide_effect) { ?>
			$('#quickcheckoutconfirm').slideUp('slow');
			<?php } else { ?>
			$('#quickcheckoutconfirm').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-5x"></i></div>');
			<?php } ?>
		},
		success: function(html) {
			<?php if ($load_screen) { ?>
			$.blockUI({
				message: '<h1><?php echo $text_please_wait; ?></h1>',
				css: {
					border: 'none',
					padding: '15px',
					backgroundColor: '#000000',
					'-webkit-border-radius': '10px',
					'-moz-border-radius': '10px',
					'-khtml-border-radius': '10px',
					'border-radius': '10px',
					opacity: .8,
					color: '#fff'
				}
			});

			setTimeout($.unblockUI, 2000);
			<?php } ?>

			$('#quickcheckoutconfirm').css('display', 'none');

			$('#quickcheckoutconfirm').html(html);

			<?php if (!$auto_submit) { ?>
				<?php if ($slide_effect) { ?>
				$('#quickcheckoutconfirm').slideDown('slow');
				<?php } else { ?>
				$('#quickcheckoutconfirm').css('display', 'block');
				<?php } ?>
			<?php } ?>
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});
}

// Load cart
<?php if ($cart_module) { ?>
function loadCart() {
	$.ajax({
		url: 'index.php?route=quickcheckout/cart',
		dataType: 'html',
		cache: false,
		beforeSend: function() {
			$('.tooltip').remove();
			$('#cart1 .quickcheckout-content').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-5x"></i></div>');
		},
		success: function(html) {
			$('#cart1 .quickcheckout-content').html(html);
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});
}

$(document).ready(function(){
	loadCart();
});
<?php } ?>

// Load Voucher / Coupon / Reward
<?php if ($voucher_module || $coupon_module || $reward_module) { ?>
$(document).ready(function() {
	$.ajax({
		url: 'index.php?route=quickcheckout/voucher',
		dataType: 'html',
		cache: false,
		beforeSend: function() {
			$('#voucher .quickcheckout-content').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-5x"></i></div>');
		},
		success: function(html) {
			$('#voucher .quickcheckout-content').html(html);

			$('#voucher .quickcheckout-content').slideDown('slow');
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});
});

$(document).on('click', '#button-coupon', function() {
	$.ajax({
		url: 'index.php?route=quickcheckout/voucher/validateCoupon',
		type: 'post',
		data: $('#coupon-content :input'),
		dataType: 'json',
		cache: false,
		beforeSend: function() {
			$('#button-coupon').prop('disabled', true);
			$('#button-coupon').after('<i class="fa fa-spinner fa-spin"></i>');
		},
		complete: function() {
			$('#button-coupon').prop('disabled', false);
			$('#coupon-content .fa-spinner').remove();
		},
		success: function(json) {
			$('.alert').remove();

			$('html, body').animate({ scrollTop: 0 }, 'slow');

			if (json['success']) {
				$('#success-messages').prepend('<div class="alert alert-success" style="display:none;"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

				$('.alert-success').fadeIn('slow');
			} else if (json['error']) {
				$('#warning-messages').prepend('<div class="alert alert-danger" style="display: none;"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '</div>');

				$('.alert-danger').fadeIn('slow');
			}

			<?php if (!$logged) { ?>
				if ($('#payment-address input[name=\'shipping_address\']:checked').val()) {
					reloadPaymentMethod();

					<?php if ($shipping_required) { ?>
					reloadShippingMethod('payment');
					<?php } ?>
				} else {
					reloadPaymentMethod();

					<?php if ($shipping_required) { ?>
					reloadShippingMethod('shipping');
					<?php } ?>
				}
			<?php } else { ?>
				if ($('#payment-address input[name=\'payment_address\']:checked').val() == 'new') {
					reloadPaymentMethod();
				} else {
					reloadPaymentMethodById($('#payment-address select[name=\'address_id\']').val());
				}

				<?php if ($shipping_required) { ?>
				if ($('#shipping-address input[name=\'shipping_address\']:checked').val() == 'new') {
					reloadShippingMethod('shipping');
				} else {
					reloadShippingMethodById($('#shipping-address select[name=\'address_id\']').val());
				}
				<?php } ?>
			<?php } ?>
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});
});

$(document).on('click', '#button-voucher', function() {
	$.ajax({
		url: 'index.php?route=quickcheckout/voucher/validateVoucher',
		type: 'post',
		data: $('#voucher-content :input'),
		dataType: 'json',
		cache: false,
		beforeSend: function() {
			$('#button-voucher').prop('disabled', true);
			$('#button-voucher').after('<i class="fa fa-spinner fa-spin"></i>');
		},
		complete: function() {
			$('#button-voucher').prop('disabled', false);
			$('#voucher-content .fa-spinner').remove();
		},
		success: function(json) {
			$('.alert').remove();

			$('html, body').animate({ scrollTop: 0 }, 'slow');

			if (json['success']) {
				$('#success-messages').prepend('<div class="alert alert-success" style="display:none;"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

				$('.alert-success').fadeIn('slow');
			} else if (json['error']) {
				$('#warning-messages').prepend('<div class="alert alert-danger" style="display: none;"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '</div>');

				$('.alert-danger').fadeIn('slow');
			}

			<?php if (!$logged) { ?>
				if ($('#payment-address input[name=\'shipping_address\']:checked').val()) {
					reloadPaymentMethod();

					<?php if ($shipping_required) { ?>
					reloadShippingMethod('payment');
					<?php } ?>
				} else {
					reloadPaymentMethod();

					<?php if ($shipping_required) { ?>
					reloadShippingMethod('shipping');
					<?php } ?>
				}
			<?php } else { ?>
				if ($('#payment-address input[name=\'payment_address\']:checked').val() == 'new') {
					reloadPaymentMethod();
				} else {
					reloadPaymentMethodById($('#payment-address select[name=\'address_id\']').val());
				}

				<?php if ($shipping_required) { ?>
				if ($('#shipping-address input[name=\'shipping_address\']:checked').val() == 'new') {
					reloadShippingMethod('shipping');
				} else {
					reloadShippingMethodById($('#shipping-address select[name=\'address_id\']').val());
				}
				<?php } ?>
			<?php } ?>
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});
});

$(document).on('click', '#button-reward', function() {
	$.ajax({
		url: 'index.php?route=quickcheckout/voucher/validateReward',
		type: 'post',
		data: $('#reward-content :input'),
		dataType: 'json',
		cache: false,
		beforeSend: function() {
			$('#button-reward').prop('disabled', true);
			$('#button-reward').after('<i class="fa fa-spinner fa-spin"></i>');
		},
		complete: function() {
			$('#button-reward').prop('disabled', false);
			$('#reward-content .fa-spinner').remove();
		},
		success: function(json) {
			$('.alert').remove();

			$('html, body').animate({ scrollTop: 0 }, 'slow');

			if (json['success']) {
				$('#success-messages').prepend('<div class="alert alert-success" style="display:none;"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

				$('.alert-success').fadeIn('slow');
			} else if (json['error']) {
				$('#warning-messages').prepend('<div class="alert alert-danger" style="display: none;"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '</div>');

				$('.alert-danger').fadeIn('slow');
			}

			<?php if (!$logged) { ?>
				if ($('#payment-address input[name=\'shipping_address\']:checked').val()) {
					reloadPaymentMethod();

					<?php if ($shipping_required) { ?>
					reloadShippingMethod('payment');
					<?php } ?>
				} else {
					reloadPaymentMethod();

					<?php if ($shipping_required) { ?>
					reloadShippingMethod('shipping');
					<?php } ?>
				}
			<?php } else { ?>
				if ($('#payment-address input[name=\'payment_address\']:checked').val() == 'new') {
					reloadPaymentMethod();
				} else {
					reloadPaymentMethodById($('#payment-address select[name=\'address_id\']').val());
				}

				<?php if ($shipping_required) { ?>
				if ($('#shipping-address input[name=\'shipping_address\']:checked').val() == 'new') {
					reloadShippingMethod('shipping');
				} else {
					reloadShippingMethodById($('#shipping-address select[name=\'address_id\']').val());
				}
				<?php } ?>
			<?php } ?>
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});
});
<?php } ?>

<?php if ($shipping_required) { ?>
$(document).on('focusout', 'input[name=\'postcode\']', function(){
	if ($('#payment-address input[name=\'shipping_address\']:checked').val()) {
		reloadShippingMethod('payment');
	} else {
		reloadShippingMethod('shipping');
	}
});
<?php } ?>

<?php if ($highlight_error) { ?>
	$(document).on('keydown', 'input', function() {
		$(this).css('background', '').css('border', '');

		$(this).siblings('.text-danger').remove();
	});
	$(document).on('change', 'select', function() {
		$(this).css('background', '').css('border', '');

		$(this).siblings('.text-danger').remove();
	});
<?php } ?>

<?php if ($edit_cart) { ?>
$(document).on('click', '.button-update', function() {
	$.ajax({
		url: 'index.php?route=quickcheckout/cart/update',
		type: 'post',
		data: $('#cart1 :input'),
		dataType: 'json',
		cache: false,
		beforeSend: function() {
			$('#cart1 .button-update').prop('disabled', true);
		},
		success: function(json) {
			if (json['redirect']) {
				location = json['redirect'];
			} else {
				<?php if (!$logged) { ?>
					if ($('#payment-address input[name=\'shipping_address\']:checked').val()) {
						reloadPaymentMethod();

						<?php if ($shipping_required) { ?>
						reloadShippingMethod('payment');
						<?php } ?>
					} else {
						reloadPaymentMethod();

						<?php if ($shipping_required) { ?>
						reloadShippingMethod('shipping');
						<?php } ?>
					}
				<?php } else { ?>
					if ($('#payment-address input[name=\'payment_address\']:checked').val() == 'new') {
						reloadPaymentMethod();
					} else {
						reloadPaymentMethodById($('#payment-address select[name=\'address_id\']').val());
					}

					<?php if ($shipping_required) { ?>
					if ($('#shipping-address input[name=\'shipping_address\']:checked').val() == 'new') {
						reloadShippingMethod('shipping');
					} else {
						reloadShippingMethodById($('#shipping-address select[name=\'address_id\']').val());
					}
					<?php } ?>
				<?php } ?>
			}
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});
});

$(document).on('click', '.button-remove', function() {
	var remove_id = $(this).attr('data-remove');

	$.ajax({
		url: 'index.php?route=quickcheckout/cart/update&remove=' + remove_id,
		type: 'get',
		dataType: 'json',
		cache: false,
		beforeSend: function() {
			$('#cart1 .button-remove').prop('disabled', true);
		},
		success: function(json) {
			if (json['redirect']) {
				location = json['redirect'];
			} else {
				<?php if (!$logged) { ?>
					if ($('#payment-address input[name=\'shipping_address\']:checked').val()) {
						reloadPaymentMethod();

						<?php if ($shipping_required) { ?>
						reloadShippingMethod('payment');
						<?php } ?>
					} else {
						reloadPaymentMethod();

						<?php if ($shipping_required) { ?>
						reloadShippingMethod('shipping');
						<?php } ?>
					}
				<?php } else { ?>
					if ($('#payment-address input[name=\'payment_address\']:checked').val() == 'new') {
						reloadPaymentMethod();
					} else {
						reloadPaymentMethodById($('#payment-address select[name=\'address_id\']').val());
					}

					<?php if ($shipping_required) { ?>
					if ($('#shipping-address input[name=\'shipping_address\']:checked').val() == 'new') {
						reloadShippingMethod('shipping');
					} else {
						reloadShippingMethodById($('#shipping-address select[name=\'address_id\']').val());
					}
					<?php } ?>
				<?php } ?>
			}
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});

	return false;
});
<?php } ?>
//--></script>
<?php /* yym_custom */ ?>
<?php include('catalog/view/theme/' . $config->get('config_template') . '/template/new_elements/wrapper_bottom.tpl'); ?>
<?php echo $footer; ?>