<?php
//==============================================================================
// Stripe Payment Gateway v210.2
// 
// Author: Clear Thinking, LLC
// E-mail: johnathan@getclearthinking.com
// Website: http://www.getclearthinking.com
// 
// All code within this file is copyright Clear Thinking, LLC.
// You may not copy or reuse code within this file without written permission.
//==============================================================================
?>

<?php if ($settings['use_checkout'] || $embed) { ?>
	
	<div id="payment"></div>
 	<script src="https://checkout.stripe.com/checkout.js"></script>
	<script type="text/javascript"><!--
		function confirmOrder() {
			<?php if (!empty($settings['checkout_shipping']) && $no_shipping_method) { ?>
				alert('<?php echo $error_shipping_required; ?>');
			<?php } else { ?>
				StripeCheckout.open({
					//
					key:				'<?php echo $settings[$settings['transaction_mode'].'_publishable_key']; ?>',
					token:				function(token, args) { displayWait(); chargeToken(token, args); },
					
					// Highly Recommended
				<?php if ($checkout_image) { ?>
					image:				'<?php echo $checkout_image; ?>',
				<?php } ?>
					name:				'<?php echo str_replace("'", "\'", $checkout_title); ?>',
				<?php if ($checkout_description) { ?>
					description:		'<?php echo str_replace("'", "\'", $checkout_description); ?>',
				<?php } ?>
					amount:				<?php echo $checkout_amount; ?>,
					
					// Optional
				<?php if ($checkout_button) { ?>
					panelLabel:			'<?php echo str_replace(array("'", '[amount]'), array("\'", '{{amount}}'), $checkout_button); ?>',
				<?php } ?>
					currency:			'<?php echo strtolower($currency); ?>',
					billingAddress:		<?php echo (!empty($settings['checkout_billing'])) ? 'true' : 'false'; ?>,
					shippingAddress:	<?php echo (!empty($settings['checkout_shipping'])) ? 'true' : 'false'; ?>,
					email:				'<?php echo (!empty($settings['checkout_bitcoin'])) ? '' : $order_info['email']; ?>',
					allowRememberMe:	<?php echo (!empty($settings['checkout_remember_me'])) ? 'true' : 'false'; ?>,
					bitcoin:			<?php echo (!empty($settings['checkout_bitcoin'])) ? 'true' : 'false'; ?>,
				});
			<?php } ?>
			
			return false;
		}
	//--></script>
	
<?php } else { ?>
	
	<style type="text/css">
		#stored-card, #new-card {
			padding: 5px;
		}
		#card-name, #card-number {
			width: 200px;
		}
		#card-month, #card-year {
			display: inline-block;
			width: 42px;
		}
		#card-security {
			width: 60px;
		}
		.smalltext {
			font-size: 11px;
		}
		#store-card, #stored-card {
			margin-top: 10px;
		}
	</style>
	<?php if (version_compare(VERSION, '2.0', '<')) { ?>
		<style type="text/css">
			fieldset {
				margin-bottom: 25px;
			}
			legend {
				font-size: 18px;
			}
			.col-sm-2 {
				display: inline-block;
				width: 170px;
				height: 30px;
			}
			.col-sm-10 {
				display: inline-block;
				height: 30px;
				vertical-align: middle;
			}
		</style>
	<?php } ?>
	
	<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
	<form id="payment" class="form-horizontal">
		<fieldset>
			<legend><?php echo $text_card_details; ?></legend>
		<?php if ($customer) { ?>
			<div class="form-group">
				<label class="col-sm-2 control-label">
					<select id="card-select" class="form-control" onchange="if ($('#new-card').css('display') == 'none') { $('#stored-card').fadeOut(400, function(){$('#new-card').fadeIn()}); } else { $('#new-card').fadeOut(400, function(){$('#stored-card').fadeIn()}); }">
						<option value="stored"><?php echo $text_use_your_stored_card; ?></option>
						<option value="new"><?php echo $text_use_a_new_card; ?></option>
					</select>
				</label>
				<div class="col-sm-10" id="stored-card">
					<?php echo $customer['default_source']['brand'] . ' ' . $text_ending_in . ' ' . $customer['default_source']['last4']; ?>
					(<?php echo str_pad($customer['default_source']['exp_month'], 2, '0', STR_PAD_LEFT) . '/' . substr($customer['default_source']['exp_year'], 2); ?>)
				</div>
			</div>
			<div id="new-card" style="display: none">
		<?php } else { ?>
			<div id="new-card">
		<?php } ?>
				<div class="form-group">
					<label class="col-sm-2 control-label"><?php echo $text_card_name; ?></label>
					<div class="col-sm-10">
						<input type="text" id="card-name" class="form-control" value="<?php echo $order_info['firstname'] . ' ' . $order_info['lastname']; ?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label"><?php echo $text_card_number; ?></label>
					<div class="col-sm-10">
						<input type="text" id="card-number" class="form-control" autocomplete="off" value="" /></td>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label"><?php echo $text_card_type; ?></label>
					<div class="col-sm-10" id="card-type">
						<img width="36" height="24" src="https://assets.braintreegateway.com/payment_method_logo/visa.png" alt="Visa" />
						<img width="36" height="24" src="https://assets.braintreegateway.com/payment_method_logo/mastercard.png" alt="MasterCard" />
						<img width="36" height="24" src="https://assets.braintreegateway.com/payment_method_logo/american_express.png" alt="American Express" />
						<img width="36" height="24" src="https://assets.braintreegateway.com/payment_method_logo/discover.png" alt="Discover" />
						<img width="36" height="24" src="https://assets.braintreegateway.com/payment_method_logo/jcb.png" alt="JCB" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label"><?php echo $text_card_expiry; ?></label>
					<div class="col-sm-10">
						<input type="text" id="card-month" class="form-control" maxlength="2" autocomplete="off" value="" />
						/ <input type="text" id="card-year" class="form-control" maxlength="2" autocomplete="off" value="" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label"><?php echo $text_card_security; ?></label>
					<div class="col-sm-10">
						<input type="text" id="card-security" class="form-control" maxlength="4" autocomplete="off" value="" />
					</div>
				</div>
				<?php if ($logged_in && $settings['allow_stored_cards'] && $settings['send_customer_data'] != 'never') { ?>					
					<div class="form-group">
						<?php if ($customer) { ?>
							<label class="col-sm-2 control-label">
								<?php echo $text_replace_stored_card; ?><br />
								<span class="smalltext"><?php echo $text_your_current_card_will; ?></span>
							</label>
						<?php } elseif ($settings['send_customer_data'] != 'never') { ?>
							<label class="col-sm-2 control-label"><?php echo $text_store_card_for_future; ?></label>
						<?php } ?>
						<div class="col-sm-10" style="vertical-align: top">
							<input type="checkbox" id="store-card" />
						</div>
					</div>
				<?php } ?>
			</div>
		</fieldset>
	</form>
	<script type="text/javascript"><!--
		function confirmOrder() {
			Stripe.setPublishableKey('<?php echo $settings[$settings['transaction_mode'].'_publishable_key']; ?>');
			displayWait();
			
			if ($('#new-card').css('display') == 'none') {
				chargeToken('', '');
			} else {
				Stripe.createToken({
					name: $('#card-name').val(),
					number: $('#card-number').val(),
					exp_month: $('#card-month').val(),
					exp_year: '20' + $('#card-year').val(),
					cvc: $('#card-security').val(),
					address_line1: '<?php echo trim(str_replace("'", "\'", html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8'))); ?>',
					address_line2: '<?php echo trim(str_replace("'", "\'", html_entity_decode($order_info['payment_address_2'], ENT_QUOTES, 'UTF-8'))); ?>',
					address_city: '<?php echo trim(str_replace("'", "\'", html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8'))); ?>',
					address_state: '<?php echo trim(str_replace("'", "\'", html_entity_decode($order_info['payment_zone'], ENT_QUOTES, 'UTF-8'))); ?>',
					address_zip: '<?php echo trim(str_replace("'", "\'", html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8'))); ?>',
					address_country: '<?php echo trim(str_replace("'", "\'", html_entity_decode($order_info['payment_country'], ENT_QUOTES, 'UTF-8'))); ?>'
				}, function(status, response){
					if (response.error) {
						displayError(response.error.message);
					} else {
						chargeToken(response, '');
					}
				});
			}
		}
	//--></script>
	
<?php } ?>

<div class="buttons">
	<div class="right pull-right">
		<a id="button-confirm" onclick="confirmOrder()" class="<?php echo $settings['button_class']; ?>" style="<?php echo $settings['button_styling']; ?>">
			<?php echo $settings['button_text_' . $language]; ?>
		</a>
	</div>
</div>

<script type="text/javascript"><!--
	$('#card-number').keyup(function(){
		$('#card-type img').css('opacity', 0.3);
		$('#card-type img[alt="' + Stripe.card.cardType($('#card-number').val()) + '"]').css('opacity', 1);
	});
	
	function displayWait() {
		$('#button-confirm').removeAttr('onclick').attr('disabled', 'disabled');
		$('#card-select').attr('disabled', 'disabled');
		$('.alert').remove();
		$('#payment').after('<div class="attention alert alert-warning" style="display: none"><?php echo $text_please_wait; ?></div>');
		$('.attention').fadeIn();
	}
	
	function displayError(message) {
		$('.alert').remove();
		$('#payment').after('<div class="warning alert alert-danger" style="display: none">' + message + '</div>');
		$('.warning').fadeIn();
		$('#button-confirm').attr('onclick', 'confirmOrder()').removeAttr('disabled');
		$('#card-select').removeAttr('disabled');
	}
	
	function chargeToken(token, addresses) {
		$.ajax({
			type: 'POST',
			url: 'index.php?route=<?php echo $type; ?>/<?php echo $name; ?>/send',
			data: {token: token.id, email: token.email, addresses: addresses, store_card: $('#store-card').attr('checked'), embed: <?php echo (int)$embed; ?>},
			dataType: 'json',
			success: function(json) {
				if (json['error']) {
					displayError(json['error']);
				} else if (json['success']) {
					location = json['success'];
				}
			}
		});
	}
//--></script>