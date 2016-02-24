<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
<div class="page-header">
	<div class="container-fluid">
		<div class="pull-right">
			<button type="submit" form="form-pos" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
			<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
		</div>
		<h1><?php echo $heading_title; ?></h1>
		<ul class="breadcrumb">
			<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
			<?php } ?>
		</ul>
	</div>
</div>
<div class="container-fluid">
	<?php if ($error_warning) { ?>
	<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
		<button type="button" class="close" data-dismiss="alert">&times;</button>
	</div>
	<?php } ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit_pos_settings; ?></h3>
		</div>
		<div class="panel-body">
			<?php $payment_type_row_no = 0; ?>
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-pos" class="form-horizontal">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab_settings_payment_type" data-toggle="tab"><?php echo $tab_settings_payment_type; ?></a></li>
				<li><a href="#tab_settings_options" data-toggle="tab"><?php echo $tab_settings_options; ?></a></li>
				<li><a href="#tab_settings_order" data-toggle="tab"><?php echo $tab_settings_order; ?></a></li>
				<li><a href="#tab_settings_customer" data-toggle="tab"><?php echo $tab_settings_customer; ?></a></li>
			</ul>

			<div class="tab-content">
				<div class="tab-pane active settings_div" id="tab_settings_payment_type">
					<table id="payment_type_table" class="table table-striped table-bordered table-hover">
						<col width="70%" />
						<col width="20%" />
						<col width="10%" />
						<thead>
							<tr><td colspan="3" class="text-left" style="background-color: #E7EFEF;"><?php echo $text_payment_type_setting; ?></td></tr>
							<tr>
								<td class="text-left"><?php echo $text_order_payment_type; ?></td>
								<td class="text-left"><?php echo $text_order_payment_eanble; ?></td>
								<td class="text-center"><?php echo $text_action; ?></td>
							</tr>
						</thead>
						<tbody id="payment_type_list">
							<tr class='filter' id="payment_type_add">
								<td class="text-left"><input type="text" name="payment_type" id="payment_type" class="form-control" value="" onkeypress="return addPaymentOnEnter(event)" /></td>
								<td class="text-left"><input type="checkbox" name="payment_type_enable" class="form-control" value="0" /></td>
								<td class="text-center"><a id="button_add_payment_type" onclick="addPaymentType();" class="btn btn-primary"><i class="fa fa-plus-circle fa-lg"></i> <?php echo $button_add_type; ?></a></td>
							</tr>
							<?php
								if (isset($payment_types)) {
								foreach ($payment_types as $payment_type=>$payment_name) {
							?>
							<tr id="<?php echo 'payment_type_'.$payment_type_row_no; ?>">
								<td class="text-left"><?php echo $payment_name; ?></td>
								<td class="text-left"><input type="checkbox" name="payment_type_enables[<?php echo $payment_type; ?>]" class="form-control" value="<?php echo (empty($payment_type_enables[$payment_type])) ? 0 : 1; ?>" <?php if(!empty($payment_type_enables[$payment_type])) {?>checked="checked"<?php }?> /></td>
								<td class="text-center">
									<?php if (!$payment_type || $payment_type != 'cash' && $payment_type != 'credit_card' && $payment_type != 'gift_voucher' && $payment_type != 'reward_points' && $payment_type != 'purchase_order') { ?>
									<a onclick="deletePaymentType('<?php echo 'payment_type_'.$payment_type_row_no; ?>');" class="btn btn-danger btn-sm"><i class="fa fa-trash-o fa-lg"></i> <?php echo $button_remove; ?></a>
									<?php } ?>
									<input type="hidden" name="POS_payment_types[<?php echo $payment_type; ?>]" value="<?php echo $payment_name; ?>"/>
								</td>
							</tr>
							<?php $payment_type_row_no ++; }} ?>
						</tbody>
					</table>
				</div>

				<div class="tab-pane settings_div" id="tab_settings_options">
					<table id="page_display" class="table table-striped table-bordered table-hover">
						<thead>
							<tr><td colspan="2" class="text-left" style="background-color: #E7EFEF;"><?php echo $text_display_setting; ?></td></tr>
						</thead>
						<tbody>
							<tr><td colspan="2" class="text-left">
								<input type="checkbox" name="display_once_login" value="<?php echo $display_once_login; ?>" <?php if($display_once_login=='1') { ?>checked="checked"<?php } ?> />&nbsp;
								<?php echo $text_display_once_login; ?>
							</td></tr>
						</tbody>
					</table>
				</div>
	
				<div class="tab-pane settings_div" id="tab_settings_order">
					<!-- add for order Status begin -->
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr><td colspan="2" class="text-left" style="background-color: #E7EFEF;"><?php echo $text_order_status_setting; ?></td></tr>
						</thead>
						<tbody>
							<tr>
								<td class="text-left"><?php echo $entry_complete_status; ?></td>
								<td class="text-left">
									<select class="form-control" name="complete_status_id">
										<?php foreach ($order_statuses as $order_status) { ?>
											<?php if (!empty($complete_status_id) && $order_status['order_status_id'] == $complete_status_id) { ?>
											<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td class="text-left"><?php echo $entry_parking_status; ?></td>
								<td class="text-left">
									<select class="form-control" name="parking_status_id">
										<?php foreach ($order_statuses as $order_status) { ?>
											<?php if (!empty($parking_status_id) && $order_status['order_status_id'] == $parking_status_id) { ?>
											<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td class="text-left"><?php echo $entry_void_status; ?></td>
								<td class="text-left">
									<select class="form-control" name="void_status_id">
										<?php foreach ($order_statuses as $order_status) { ?>
											<?php if (!empty($void_status_id) && $order_status['order_status_id'] == $void_status_id) { ?>
											<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</td>
							</tr>
						</tbody>
					</table>
					<!-- add for order Status end -->
				</div>
				<div class="tab-pane settings_div" id="tab_settings_customer">
					<!-- add for Default Customer begin -->
					<table id="c_default" class="table table-striped table-bordered table-hover">
						<thead>
							<tr><td colspan="6" class="text-left" style="background-color: #E7EFEF;"><?php echo $text_customer_setting; ?></td></tr>
						</thead>
						<tbody>
							<tr>
								<td class="text-center" size="1"><input type="radio" name="c_type" value="1" <?php if ($c_type == 1) { ?> checked="checked" <?php } ?>/></td>
								<td class="text-left"><?php echo $text_customer_system; ?></td>
								<td class="text-center" size="1"><input type="radio" name="c_type" value="2" <?php if ($c_type == 2) { ?> checked="checked" <?php } ?>/></td>
								<td class="text-left"><?php echo $text_customer_custom; ?></td>
								<td class="text-center" size="1"><input type="radio" name="c_type" value="3" <?php if ($c_type == 3) { ?> checked="checked" <?php } ?>/></td>
								<td class="text-left"><?php echo $text_customer_existing; ?></td>
							</tr>
							<tr>
								<td class="text-left" colspan="6" style="color: #FF802B; font-size: 12px; font-weight: bold; "><?php echo $text_customer_info; ?>
									<input type="hidden" name="c_id" value="<?php echo $c_id; ?>" />
								</td>
							</tr>
							<tr>
								<td class="text-left" colspan="3"><?php echo $text_customer_group; ?></td>
								<td class="text-left" colspan="3">
									<select class="form-control" name="c_group_id">
										<?php foreach ($c_groups as $customer_group) { ?>
											<?php if ($customer_group['customer_group_id'] == $c_group_id) { ?>
											<option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr id="c_autocomplete" <?php if ($c_id ==0) { ?>style="display:none;"<?php } ?>>
								<td class="text-left" colspan="3"><?php echo $text_customer; ?></td>
								<td class="text-left" colspan="3"><input class="form-control" type="text" name="c_name" value="<?php echo $c_name; ?>" />&nbsp;<?php echo '('.$text_autocomplete.')'; ?></td>
							</tr>
							<tr>
								<td class="text-left" colspan="3"><span class="required">*</span> <?php echo $entry_firstname; ?></td>
								<td class="text-left" colspan="3"><input class="form-control" type="text" name="c_firstname" value="<?php echo $c_firstname; ?>" /></td>
							</tr>
							<tr>
								<td class="text-left" colspan="3"><span class="required">*</span> <?php echo $entry_lastname; ?></td>
								<td class="text-left" colspan="3"><input class="form-control" type="text" name="c_lastname" value="<?php echo $c_lastname; ?>" /></td>
							</tr>
							<tr>
								<td class="text-left" colspan="3"><span class="required">*</span> <?php echo $entry_email; ?></td>
								<td class="text-left" colspan="3"><input class="form-control" type="text" name="c_email" value="<?php echo $c_email; ?>" /></td>
							</tr>
							<tr>
								<td class="text-left" colspan="3"><span class="required">*</span> <?php echo $entry_telephone; ?></td>
								<td class="text-left" colspan="3"><input class="form-control" type="text" name="c_telephone" value="<?php echo $c_telephone; ?>" /></td>
							</tr>
							<tr>
								<td class="text-left" colspan="3"><?php echo $entry_fax; ?></td>
								<td class="text-left" colspan="3"><input class="form-control" type="text" name="c_fax" value="<?php echo $c_fax; ?>" /></td>
							</tr>
							<tr>
								<td class="text-left" colspan="6" style="color: #FF802B; font-size: 12px; font-weight: bold; "><?php echo $text_address_info; ?></td>
							</tr>
							<tr>
								<td class="text-left" colspan="3"><span class="required">*</span> <?php echo $entry_firstname; ?></td>
								<td class="text-left" colspan="3"><input class="form-control" type="text" name="a_firstname" value="<?php echo $a_firstname; ?>" /></td>
							</tr>
							<tr>
								<td class="text-left" colspan="3"><span class="required">*</span> <?php echo $entry_lastname; ?></td>
								<td class="text-left" colspan="3"><input class="form-control" type="text" name="a_lastname" value="<?php echo $a_lastname; ?>" /></td>
							</tr>
							<tr>
								<td class="text-left" colspan="3"><span class="required">*</span> <?php echo $entry_address_1; ?></td>
								<td class="text-left" colspan="3"><input class="form-control" type="text" name="a_address_1" value="<?php echo $a_address_1; ?>" /></td>
							</tr>
							<tr>
								<td class="text-left" colspan="3"><?php echo $entry_address_2; ?></td>
								<td class="text-left" colspan="3"><input class="form-control" type="text" name="a_address_2" value="<?php echo $a_address_2; ?>" /></td>
							</tr>
							<tr>
								<td class="text-left" colspan="3"><span class="required">*</span> <?php echo $entry_city; ?></td>
								<td class="text-left" colspan="3"><input class="form-control" type="text" name="a_city" value="<?php echo $a_city; ?>" /></td>
							</tr>
							<tr>
								<td class="text-left" colspan="3"><span id="postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></td>
								<td class="text-left" colspan="3"><input class="form-control" type="text" name="a_postcode" value="<?php echo $a_postcode; ?>" /></td>
							</tr>
							<tr>
								<td class="text-left" colspan="3"><span class="required">*</span> <?php echo $entry_country; ?></td>
								<td class="text-left" colspan="3">
									<select class="form-control" name="a_country_id" onchange="country('<?php echo $a_zone_id; ?>');">
										<option value=""><?php echo $text_select; ?></option>
										<?php foreach ($c_countries as $customer_country) { ?>
											<?php if ($customer_country['country_id'] == $a_country_id) { ?>
											<option value="<?php echo $customer_country['country_id']; ?>" selected="selected"><?php echo $customer_country['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $customer_country['country_id']; ?>"><?php echo $customer_country['name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td class="text-left" colspan="3"><span class="required">*</span> <?php echo $entry_zone; ?></td>
								<td class="text-left" colspan="3"><select class="form-control" name="a_zone_id"></select></td>
							</tr>
						</tbody>
					</table>
					<!-- add for Default Customer end -->
				</div>
			</div>
			</form>
		</div>
	</div>
</div>
</div>
</div>

<link rel="stylesheet" type="text/css" href="view/javascript/jquery/ui/jquery-ui.min.css" />
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui.min.js"></script>
<script type="text/javascript">

$(document).on('click', '.toggle_tab', function() {
	if ($(this).is(':checked')) {
		$(this).val('1');
	} else {
		$(this).val('0');
		$('.nav-tabs a:first').trigger('click');
	}
	$('#tab_'+$(this).attr('name')).toggle();
});

$('input[type=checkbox]').click(function() {
	var inputName = $(this).attr('name');
	if (inputName.indexOf('[]') < 0) {
		if ($(this).is(':checked')) {
			$(this).val('1');
		} else {
			$(this).val('0');
		}
	}
});

var payment_type_row = <?php echo $payment_type_row_no; ?>;

function addPaymentType() {
	var checkValue = checkPaymentType();

	if (checkValue == 1) {
		// already in the list
		warning_tips = '<img src="view/image/warning.png" id="type_warning_tips" alt="<?php echo $text_type_already_exist; ?>" title="<?php echo $text_type_already_exist; ?>" />';
		$('#type_warning_tips').remove();
		$(warning_tips).insertAfter($('#payment_type'));
		return false;
	}

	$('#type_warning_tips').remove();
	var value = $('#payment_type').val();
	var checked = $('input[name=payment_type_enable]').is(':checked');
	var new_payment_type_html = '<tr id="payment_type_' + payment_type_row + '">';
	new_payment_type_html += '<td class="text-left">' + value + '</td>';
	new_payment_type_html += '<td class="text-left"><input type="checkbox" name="payment_type_enables[' + payment_type_row + ']" class="form-control" value="' + (checked ? 1 : 0) + '" ' + (checked ? 'checked="checked"' : '') + ' /></td>';
	new_payment_type_html += '<td class="text-center"><a onclick="deletePaymentType(\'payment_type_' + payment_type_row + '\');" class="btn btn-danger btn-sm"><i class="fa fa-trash-o fa-lg"></i> <?php echo $button_remove; ?></a>';
	new_payment_type_html += '	<input type="hidden" name="POS_payment_types[' + payment_type_row + ']" value="' + value + '"/>';
	new_payment_type_html += '</td></tr>';
	$(new_payment_type_html).insertAfter('#payment_type_add');
	$('#payment_type').val("");
	$('input[name=payment_type_enable]').prop('checked', false);
	payment_type_row ++;

};

function deletePaymentType(rowId) {
	$('#'+rowId).remove();
};

function checkPaymentType() {
	retValue = 0;
	curValue = $('#payment_type').val().toLowerCase();
	$("#payment_type_table tr").each(function(){
		value = $(this).find('td:first-child').text().toLowerCase();
		if (curValue == value) {
			retValue = 1;
		}
	});

	return retValue;
};

function addPaymentOnEnter(e) {
	var key;
	if (window.event)
		key = window.event.keyCode; //IE
	else
		key = e.which; //Firefox & others

	if(key == 13) {
		addPaymentType();
		return false;
	}
}
function country(zone_id) {
  if ($('select[name=a_country_id]').val() != '') {
		$.ajax({
			url: 'index.php?route=sale/customer/country&token=<?php echo $token; ?>&country_id=' + $('select[name=a_country_id]').val(),
			dataType: 'json',
			beforeSend: function() {
				$('select[name=a_country_id]').after('<span class="wait">&nbsp;<img src="view/image/pos/loading.gif" alt="" /></span>');
			},
			complete: function() {
				$('.wait').remove();
			},			
			success: function(json) {
				if (json['postcode_required'] == '1') {
					$('#postcode-required').show();
				} else {
					$('#postcode-required').hide();
				}
				
				html = '<option value=""><?php echo $text_select; ?></option>';
				
				if (json['zone'] != '') {
					for (i = 0; i < json['zone'].length; i++) {
						html += '<option value="' + json['zone'][i]['zone_id'] + '"';
						
						if (json['zone'][i]['zone_id'] == zone_id) {
							html += ' selected="selected"';
						}
		
						html += '>' + json['zone'][i]['name'] + '</option>';
					}
				} else {
					html += '<option value="0"><?php echo $text_none; ?></option>';
				}
				
				$('select[name=a_zone_id]').html(html);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
};

$(document).on('change', 'input[name=c_type]', function(event) {
	if ($('input[name=c_type]:checked').val() == '1') {
		$('#c_autocomplete').hide();
		// disable all values
		$('#c_default input[type=text]').each(function() {
			// disable the input
			$(this).attr('disabled', true);
		});
		$('#c_default select').each(function() {
			// disable the input
			$(this).attr('disabled', true);
		});
		setBuildinValue();
	} else if ($('input[name=c_type]:checked').val() == '2') {
		$('#c_autocomplete').hide();
		// enable all values
		$('#c_default input[type=text]').each(function() {
			// disable the input
			$(this).attr('disabled', false);
		});
		$('#c_default select').each(function() {
			// disable the input
			$(this).attr('disabled', false);
		});
		setConfigValue();
	} else {
		// disable all values
		$('#c_default input[type=text]').each(function() {
			// disable the input
			$(this).attr('disabled', true);
		});
		$('#c_default select').each(function() {
			// disable the input
			$(this).attr('disabled', true);
		});
		$('input[name=c_name]').attr('disabled', false);
		$('#c_autocomplete').show();
		setConfigValue();
	}
});

function setBuildinValue() {
	$('input[name=c_name]').val('<?php echo $buildin['c_name']; ?>');
	$('input[name=c_id]').val('0');
	$('select[name=c_group_id]').val('1');
	$('select[name=c_group_id]').trigger('change');
	$('input[name=c_firstname]').val('<?php echo $buildin['c_firstname']; ?>');
	$('input[name=c_lastname]').val('<?php echo $buildin['c_lastname']; ?>');
	$('input[name=c_email]').val('<?php echo $buildin['c_email']; ?>');
	$('input[name=c_telephone]').val('<?php echo $buildin['c_telephone']; ?>');
	$('input[name=c_fax]').val('<?php echo $buildin['c_fax']; ?>');
	$('select[name=a_country_id]').val('<?php echo $buildin['a_country_id']; ?>');
	$('input[name=a_firstname]').val('<?php echo $buildin['a_firstname']; ?>');
	$('input[name=a_lastname]').val('<?php echo $buildin['a_lastname']; ?>');
	$('input[name=a_address_1]').val('<?php echo $buildin['a_address_1']; ?>');
	$('input[name=a_address_2]').val('<?php echo $buildin['a_address_2']; ?>');
	$('input[name=a_city]').val('<?php echo $buildin['a_city']; ?>');
	$('input[name=a_postcode]').val('<?php echo $buildin['a_postcode']; ?>');
	$('select[name=a_country_id]').attr('onchange', 'country(\'<?php echo $buildin['a_zone_id']; ?>\')');
	$('select[name=a_country_id]').trigger('change');
};

function setConfigValue() {
	$('input[name=c_id]').val('<?php echo $c_id; ?>');
	$('select[name=c_group_id]').val('<?php echo $c_group_id; ?>');
	if ($('input[name=c_type]:checked').val() == '2') {
		$('input[name=c_id]').val('0');
	}
	$('select[name=c_group_id]').trigger('change');
	$('input[name=c_name]').val('<?php echo $c_name; ?>');
	$('input[name=c_firstname]').val('<?php echo $c_firstname; ?>');
	$('input[name=c_lastname]').val('<?php echo $c_lastname; ?>');
	$('input[name=c_email]').val('<?php echo $c_email; ?>');
	$('input[name=c_telephone]').val('<?php echo $c_telephone; ?>');
	$('input[name=c_fax]').val('<?php echo $c_fax; ?>');
	$('select[name=a_country_id]').val('<?php echo $a_country_id; ?>');
	$('input[name=a_firstname]').val('<?php echo $a_firstname; ?>');
	$('input[name=a_lastname]').val('<?php echo $a_lastname; ?>');
	$('input[name=a_address_1]').val('<?php echo $a_address_1; ?>');
	$('input[name=a_address_2]').val('<?php echo $a_address_2; ?>');
	$('input[name=a_city]').val('<?php echo $a_city; ?>');
	$('input[name=a_postcode]').val('<?php echo $a_postcode; ?>');
	$('select[name=a_country_id]').attr('onchange', 'country(\'<?php echo $a_zone_id; ?>\')');
	$('select[name=a_country_id]').trigger('change');
};

$(document).on('focus', 'input[name=c_name]', function(){
	$(this).autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {	
					response($.map(json, function(item) {
						return {
							category: item['customer_group'],
							label: item['name'],
							value: item['customer_id'],
							customer_group_id: item['customer_group_id'],
							firstname: item['firstname'],
							lastname: item['lastname'],
							email: item['email'],
							telephone: item['telephone'],
							fax: item['fax'],
							address: item['address']
						}
					}));
				}
			});
		}, 
		select: function(event, ui) { 
			$('input[name=c_name]').val(ui.item['label']);
			$('input[name=c_id]').val(ui.item['value']);
			$('select[name=c_group_id]').val(ui.item['customer_group_id']);
			$('select[name=c_group_id]').trigger('change');
			$('input[name=c_firstname]').val(ui.item['firstname']);
			$('input[name=c_lastname]').val(ui.item['lastname']);
			$('input[name=c_email]').val(ui.item['email']);
			$('input[name=c_telephone]').val(ui.item['telephone']);
			$('input[name=c_fax]').val(ui.item['fax']);
			
			for (i in ui.item['address']) {
				$('select[name=a_country_id]').val(ui.item['address'][i]['country_id']);
				$('input[name=a_firstname]').val(ui.item['address'][i]['firstname']);
				$('input[name=a_lastname]').val(ui.item['address'][i]['lastname']);
				$('input[name=a_address_1]').val(ui.item['address'][i]['address_1']);
				$('input[name=a_address_2]').val(ui.item['address'][i]['address_2']);
				$('input[name=a_city]').val(ui.item['address'][i]['city']);
				$('input[name=a_postcode]').val(ui.item['address'][i]['postcode']);
				$('select[name=a_country_id]').attr('onchange', 'country(\'' + ui.item['address'][i]['zone_id'] + '\')');
				$('select[name=a_country_id]').trigger('change');
				break;
			}

			return false; 
		},
		focus: function(event, ui) {
			return false;
		}
	});
});
$('input[name=c_type]').trigger('change');
$('select[name=a_country_id]').trigger('change');
</script> 

<?php echo $footer; ?>