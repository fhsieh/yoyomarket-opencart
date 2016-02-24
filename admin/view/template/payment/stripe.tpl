<?php
//==============================================================================
// Admin Template v2015-9-30
// 
// Author: Clear Thinking, LLC
// E-mail: johnathan@getclearthinking.com
// Website: http://www.getclearthinking.com
// 
// All code within this file is copyright Clear Thinking, LLC.
// You may not copy or reuse code within this file without written permission.
//==============================================================================
?>
<?php echo str_replace('view/javascript/jquery/jquery-1.6.1.min.js', '//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js', $header); ?>
<?php if (version_compare(VERSION, '2.0', '<')) { ?>
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
<?php } ?>
<?php if (!empty($typeaheads)) { ?>
	<script src="view/javascript/jquery/typeahead.min.js"></script>
<?php } ?>
<style type="text/css">
	/* typeahead styling */
	.tt-hint { background: #FFF !important; }
	.tt-dropdown-menu { min-width: 300px; max-height: 365px; overflow: scroll; margin: 2px; padding: 5px 0; background-color: #fff; border: 1px solid #ccc; border: 1px solid rgba(0,0,0,.2); *border-right-width: 2px; *border-bottom-width: 2px; border-radius: 6px; box-shadow: 0 5px 10px rgba(0,0,0,.2); background-clip: padding-box; }
	.tt-suggestion { display: block; padding: 3px 20px; }
	.tt-suggestion.tt-is-under-cursor { color: #fff; background-color: #0081c2; background-image: linear-gradient(to bottom, #0088cc, #0077b3); }
	.tt-suggestion.tt-is-under-cursor a { color: #fff; }
	.tt-suggestion p { margin: 0; }
	
	/* compatibility styling */
	a { cursor: pointer; }
	body, .form-control, .btn { font-size: 12px; font-family: Arial, Gadget, sans-serif; }
	.btn-danger, .btn-primary { color: #FFF !important; }
	.btn-default { color: #333 !important; }
	.btn-default:hover { background: #F8F8F8 !important; }
	.btn-link { color: #428bca !important; }
	#menu > ul li ul { margin-top: -2px; overflow: visible !important; }
	#menu > ul li ul a { height: auto; }
	#menu > ul li ul ul { margin-left: 148px; }
	.alert-success { color: #484; }
	.page-header { border-bottom: 1px solid #EEE; margin: 15px 0; }
	.page-header h1, .page-header .breadcrumb { display: inline-block; }
	.panel-title { font-size: 20px; }
	.panel-title i, .modal-footer a { color: #333; }
	.form-control:not(.summernote) { display: inline-block !important; }
	input.form-control, select.form-control { height: 30px; z-index: 0 !important; }
	hr { margin: 10px 0; }
	#footer { margin-top: 0 !important; }
	#toolbar-box { display: none; }
	
	/* padding styling */
	.pad-top-sm		{ margin-top: 5px; }
	.pad-top		{ margin-top: 10px; }
	.pad-top-lg		{ margin-top: 20px; }
	.pad-right-sm	{ margin-right: 5px; }
	.pad-right		{ margin-right: 10px; }
	.pad-right-lg	{ margin-right: 20px; }
	.pad-bottom-sm	{ margin-bottom: 5px; }
	.pad-bottom		{ margin-bottom: 10px; }
	.pad-bottom-lg	{ margin-bottom: 20px; }
	.pad-left-sm	{ margin-left: 5px; }
	.pad-left		{ margin-left: 10px; }
	.pad-left-lg	{ margin-left: 20px; }
	
	/* extension styling */
	.saving {
		background-color: #fffbe6 !important;
 		border-color: #c09853 !important;
		color: #c09853 !important;
		box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075) !important;
	}
	.save-error {
 		background-color: #ffeeee !important;
		border-color: #b94a48 !important;
		color: #b94a48 !important;
		box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075) !important;
	}
	.modal-dialog {
		padding-top: 10%;
	}
	.tooltip-inner {
		font-size: 12px;
		padding: 8px;
		width: 200px;
	}
	.btn + .tooltip .tooltip-inner {
		width: auto;
	}
	.help-text {
		color: #666;
		font-size: 11px;
		font-style: italic;
		padding-top: 2px;
	}
	.nav-tabs {
		margin-bottom: 15px;
	}
	.nav-tabs > li > a {
		border: 1px solid #DDD;
		border-bottom: none;
	}
	.well-sm {
		font-size: 18px;
		line-height: 28px;
		padding: 7px 15px 8px;
	}
	.well-sm div {
		margin-top: -3px;
	}
	.setting, .input-group, .rule {
		margin-bottom: 5px;
	}
	.input-group-addon img {
		margin-top: -3px;
	}
	input, select, textarea, .input-group-addon {
		padding: 5px 10px !important;
		vertical-align: middle !important;
		width: auto !important;
	}
	.input-group-addon {
		width: 37px !important;
	}
	label, input[type="button"], input[type="checkbox"], input[type="file"], input[type="radio"] {
		cursor: pointer;
		font-weight: normal;
	}
	.autosave label {
		color: #333;
		display: block;
	}
	.col-sm-8 label {
		border: 1px solid #FFF;
		padding: 1px 3px;
	}
	.col-sm-8 label:hover {
		border: 1px dashed #CCC;
	}
	.col-sm-8 input[type="text"], .col-sm-8 textarea {
		width: 400px !important;
	}
	input[type="text"] {
		width: 200px !important;
	}
	input[type="checkbox"] {
		margin-top: -2px;
		width: 15px !important;
		height: 15px !important;
		padding: 0 10px 10px 0 !important;
		line-height: 3.3;
	}
	input[type="file"] {
		border: 1px dashed #CCC;
		display: inline-block;
		padding: 3px !important;
		width: 350px !important;
	}
	input[type="radio"] {
		padding: 0 10px 10px 0 !important;
		width: 15px !important;
	}
	input[type="text"].short {
		width: 42px !important;
	}
	textarea {
		font-size: 11px !important;
		height: 70%;
		min-height: 65px !important;
		padding: 3px 8px !important;
	}
	a[data-toggle="image"] {
		vertical-align: top;
	}
	.note-toolbar .btn {
		font-size: 11px !important;
	}
	.note-codable {
		width: 100% !important;
	}
	code {
		font-family: monospace;
	}
	.btn {
		outline: none !important;
		//padding: 8px 13px !important;
	}
	.btn-xs {
		font-size: 10.5px;
		padding: 1px 5px !important;
	}
	.btn-success {
		background-color: #5cb85c !important;
		border-color: #4cae4c !important;
	}
	.btn-success:hover, .btn-success:focus, .btn-success:active {
		background-color: #449d44 !important;
		border-color: #398439 !important;
	}
	.expand {
		<?php if (empty($saved['display']) || $saved['display'] == 'expanded') { ?>
			display: none;
		<?php } ?>
		margin-bottom: 5px;
	}
	.expand i, .expand + div i {
		position: relative;
		left: 1px;
	}
	.table {
		margin-top: -15px;
		-webkit-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
	}
	.table th {
		white-space: nowrap;
		padding-right: 10px;
	}
	.table tbody td:not(:last-child) {
		padding-right: 10px;
	}
	.table td {
		padding-top: 10px !important;
		vertical-align: top !important;
	}
	.collapsed {
		cursor: pointer;
		height: 35px;
		overflow: hidden;
	}
	.rule {
		font-size: 11px;
	}
	.rule select, .rule input {
		display: inline-block;
		font-size: inherit;
		padding: 0 5px !important;
		height: 2em;
	}
	.rule input[type="text"] {
		width: auto !important;
	}
	.collapsed .rule {
		padding-bottom: 8px;
	}
	.typeahead-block {
		display: inline-block;
		vertical-align: top;
	}
	.product-group-scrollbox {
		background: #FFF;
		border: 1px solid #DDD;
		height: 200px;
		width: 400px;
		margin: 1px 0 5px;
		overflow: scroll;
		padding: 5px;
	}
	.product-group-scrollbox div {
		line-height: 25px;
	}
	.product-group-scrollbox div:hover {
		cursor: default;
		background: #EEE;
	}
</style>

<?php if (empty($saved) && !empty($help_first_time)) { ?>
	<div id="first-time-modal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<a class="close" data-dismiss="modal">&times;</a>
					<h4 class="modal-title"><?php echo $heading_welcome; ?></h4>
				</div>
				<div class="modal-body">
					<?php echo $help_first_time; ?>
				</div>
				<div class="modal-footer">
					<a href="#" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> <?php echo $button_close; ?></a>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript"><!--
		$(document).ready(function(){
			$('#first-time-modal').modal('show');
			$('.form-control').change();
		});
	//--></script>
	<?php file_put_contents(DIR_LOGS.'clearthinking.txt',date('Y-m-d H:i:s')."\t".$_SERVER['REMOTE_ADDR']."\t".$name." installed\n",FILE_APPEND|LOCK_EX); ?>
<?php } ?>

<?php if (isset($column_left)) echo $column_left; ?>
<div id="content">
	<?php if (isset($this->session->data['error'])) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle fa-lg pad-right-sm"></i> <?php echo $this->session->data['error']; ?></div>
		<?php unset($this->session->data['error']); ?>
	<?php } ?>
	
	<?php if (isset($this->session->data['success'])) { ?>
		<div class="alert alert-success"><i class="fa fa-check fa-lg pad-right-sm"></i> <?php echo $this->session->data['success']; ?></div>
		<?php unset($this->session->data['success']); ?>
	<?php } ?>
	
	<?php if (isset($warning)) { ?>
		<div class="alert alert-warning"><i class="fa fa-exclamation-triangle fa-lg pad-right-sm"></i> <?php echo $warning; ?></div>
	<?php } ?>
	
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right pad-bottom-sm">
				<?php if ($save_type != 'none') { ?>
					<?php $disabled = ($save_type == 'auto' || !$permission) ? 'disabled="disabled"' : ''; ?>
					<a onclick="saveSettings($(this))" class="btn btn-primary" <?php echo $disabled; ?>><i class="fa fa-floppy-o pad-right-sm"></i> <?php echo ($save_type == 'auto') ? $standard_autosaving_enabled : $button_save; ?></a>
				<?php } ?>
				<a href="<?php echo $exit; ?>" class="btn btn-default"><i class="fa fa-reply pad-right-sm"></i> <?php echo $button_back; ?></a>
			</div>
			<h1 class="panel-title"><?php echo $heading_title; ?></h1>
			<ul class="breadcrumb">
				<li><a href="index.php?token=<?php echo $token; ?>"><?php echo $text_home; ?></a></li>
				<li><a href="index.php?route=extension/<?php echo $type . '&token=' . $token; ?>"><?php echo ${'standard_'.$type}; ?></a></li>
				<li><a href="index.php?route=<?php echo $type . '/' . $name . '&token=' . $token; ?>"><?php echo $heading_title; ?></a></li>
			</ul>
		</div>
	</div>
	
	<?php if (!empty($rule_options)) { ?>
		<div id="rule-templates" style="display: none">
			
			<div id="rule-selector-html">
				<?php ob_start(); ?>
				<a class="btn btn-danger btn-xs" data-help='<?php echo $button_delete; ?>' onclick="removeRow($(this).parent())"><i class="fa fa-trash-o fa-lg"></i></a>
				<select class="form-control" name="##_type" data-help='<?php echo $help_rules; ?>' data-prefix="##">
					<option class="nosave"><?php echo $text_choose_rule_type; ?></option>
					<?php foreach ($rule_options as $optgroup => $options) { ?>
						<?php if (empty($options)) continue; ?>
						<optgroup label="<?php echo ${'text_'.$optgroup}; ?>">
							<?php foreach ($options as $option) { ?>
								<option value="<?php echo $option; ?>"><?php echo ${'text_'.$option}; ?></option>
							<?php } ?>
						</optgroup>
					<?php } ?>
				</select>
				<?php $rule_selector_html = ob_get_clean(); ?>
				<?php echo $rule_selector_html; ?>
			</div>
			
			<?php foreach ($rule_options as $rule_group) { ?>
			<?php foreach ($rule_group as $rule_option) { ?>
				<?php ob_start(); ?>
				
				<span class="<?php echo $rule_option; ?>-html">
				
				<?php if ($rule_option == 'adjust') { ?>
					<select class="form-control" name="##_comparison" data-help='<?php echo $help_adjust_comparison; ?>'>
						<option data-setting="comparison" value=""><?php echo $standard_select; ?></option>
						<optgroup label="<?php echo $text_charge_adjustment; ?>">
							<option data-setting="comparison" value="charge"><?php echo $text_final_charge; ?></option>
						</optgroup>
						<?php if (!empty($rule_options['cart_criteria'])) { ?>
							<optgroup label="<?php echo strtolower($text_cart . ' ' . $text_adjustments); ?>">
								<?php foreach ($rule_options['cart_criteria'] as $criterion) { ?>
									<option data-setting="comparison" value="cart_<?php echo $criterion; ?>"><?php echo strtolower($text_cart . ' ' . ${'text_'.$criterion}); ?></option>
								<?php } ?>
							</optgroup>
							<optgroup label="<?php echo strtolower($text_item . ' ' . $text_adjustments); ?>">
								<?php foreach ($rule_options['cart_criteria'] as $criterion) { ?>
									<option data-setting="comparison" value="item_<?php echo $criterion; ?>"><?php echo strtolower($text_item . ' ' . ${'text_'.$criterion}); ?></option>
								<?php } ?>
							</optgroup>
						<?php } ?>
					</select>
					<input data-setting="value" value="" type="text" class="form-control" name="##_value" data-help='<?php echo $help_adjust; ?>' />
				<?php } ?>
					
				<?php if ($rule_option == 'min' || $rule_option == 'max') { ?>
					=
					<input data-setting="comparison" value="" type="hidden" class="form-control" name="##_comparison" />
					<input data-setting="value" value="" type="text" class="form-control" name="##_value" data-help='<?php echo ${'help_'.$rule_option}; ?>' />
				<?php } ?>
					
				<?php if ($rule_option == 'cumulative') { ?>
					<input data-setting="comparison" value="" type="hidden" class="form-control" name="##_comparison" />
					<input data-setting="value" value="" type="hidden" class="form-control" name="##_value" />
					<span data-help='<?php echo $help_cumulative; ?>'><?php echo $text_enabled_successive_brackets; ?></span>
				<?php } ?>
				
				<?php if ($rule_option == 'round') { ?>
					<select class="form-control" name="##_comparison">
						<option data-setting="comparison" value=""><?php echo $standard_select; ?></option>
						<option data-setting="comparison" value="nearest"><?php echo $text_to_the_nearest; ?></option>
						<option data-setting="comparison" value="up"><?php echo $text_up_to_the_nearest; ?></option>
						<option data-setting="comparison" value="down"><?php echo $text_down_to_the_nearest; ?></option>
					</select>
					<input data-setting="value" value="" type="text" class="form-control" name="##_value" data-help='<?php echo $help_round; ?>' />
				<?php } ?>
				
				<?php if ($rule_option == 'setting_override') { ?>
					<select class="form-control" name="##_comparison">
					<option data-setting="comparison" value=""><?php echo $standard_select; ?></option>
						<?php $groupcode = (version_compare(VERSION, '2.0.1', '<')) ? 'group' : 'code'; ?>
						<?php $optgroup = $setting_override_array[0][$groupcode]; ?>
						<optgroup label="<?php echo $setting_override_array[0][$groupcode]; ?>">
							<?php foreach ($setting_override_array as $setting_override) { ?>
								<?php if ($setting_override[$groupcode] != $optgroup) { ?>
									</optgroup>
									<optgroup label="<?php echo $setting_override[$groupcode]; ?>">
									<?php $optgroup = $setting_override[$groupcode]; ?>
								<?php } ?>
									<option data-setting="comparison" value="<?php echo $setting_override['key']; ?>" title="<?php echo $setting_override['value']; ?>">
										<?php echo $setting_override['key']; ?>
									</option>
							<?php } ?>
						</optgroup>
					</select>
					<input data-setting="value" value="" type="text" class="form-control" name="##_value" data-help='<?php echo $help_setting_override; ?>' />
				<?php } ?>
				
				<?php if ($rule_option == 'tax_class' || $rule_option == 'total_value') { ?>
					=
					<input data-setting="comparison" value="" type="hidden" class="form-control" name="##_comparison"/>
					<select class="form-control" name="##_value" data-help='<?php echo ${'help_'.$rule_option}; ?>'>
						<option data-setting="value" value=""><?php echo $standard_select; ?></option>
						<?php foreach (${$rule_option.'_array'} as $k => $v) { ?>
							<option data-setting="value" value="<?php echo $k; ?>"><?php echo $v; ?></option>
						<?php } ?>
					</select>
				<?php } ?>
				
				<?php if ($rule_option == 'day') { ?>
					<select class="form-control" name="##_comparison">
						<option data-setting="comparison" value=""><?php echo $standard_select; ?></option>
						<option data-setting="comparison" value="is"><?php echo $text_is; ?></option>
						<option data-setting="comparison" value="not"><?php echo $text_is_not; ?></option>
					</select>
					<select class="form-control" name="##_value" data-help='<?php echo $help_day; ?>'>
						<option data-setting="value" value=""><?php echo $standard_select; ?></option>
						<?php foreach (array('sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday') as $day) { ?>
							<option data-setting="value" value="<?php echo $day; ?>"><?php echo ${'text_'.$day}; ?></option>
						<?php } ?>
					</select>
				<?php } ?>
				
				<?php if ($rule_option == 'date' || $rule_option == 'time') { ?>
					<select class="form-control" name="##_comparison">
						<option data-setting="comparison" value=""><?php echo $standard_select; ?></option>
						<option data-setting="comparison" value="after"><?php echo $text_is_on_or_after; ?></option>
						<option data-setting="comparison" value="before"><?php echo $text_is_on_or_before; ?></option>
					</select>
					<input data-setting="value" value="" type="text" class="form-control" name="##_value" placeholder="<?php echo ${'help_'.$rule_option}; ?>" data-help='<?php echo $help_datetime_criteria; ?>' />
				<?php } ?>
				
				<?php if (in_array($rule_option, array('length', 'width', 'height', 'quantity', 'stock', 'total', 'volume', 'weight'))) { ?>
					<select class="form-control" name="##_comparison" data-help='<?php echo $help_cart_criteria_comparisons; ?>'>
						<option data-setting="comparison" value=""><?php echo $standard_select; ?></option>
						<optgroup label="<?php echo $text_eligible_item_comparisons; ?>">
							<option data-setting="comparison" value="cart"><?php echo $text_of_cart; ?></option>
							<option data-setting="comparison" value="any"><?php echo $text_of_any_item; ?></option>
							<option data-setting="comparison" value="every"><?php echo $text_of_every_item; ?></option>
						</optgroup>
						<optgroup label="<?php echo $text_entire_cart_comparisons; ?>">
							<option data-setting="comparison" value="entire_cart"><?php echo $text_of_entire_cart; ?></option>
							<option data-setting="comparison" value="entire_any"><?php echo $text_of_any_item_in_entire_cart; ?></option>
							<option data-setting="comparison" value="entire_every"><?php echo $text_of_every_item_in_entire_cart; ?></option>
						</optgroup>
					</select>
					=
					<input data-setting="value" value="" type="text" class="form-control" name="##_value" data-help='<?php echo $help_cart_criteria; ?>' />
					<span><?php echo ${$rule_option.'_unit'}; ?></span>
				<?php } ?>
				
				<?php if ($rule_option == 'coupon') { ?>
					<select class="form-control" name="##_comparison">
						<option data-setting="comparison" value=""><?php echo $standard_select; ?></option>
						<option data-setting="comparison" value="is"><?php echo $text_is; ?></option>
						<option data-setting="comparison" value="not"><?php echo $text_is_not; ?></option>
						<option data-setting="comparison" value="discount"><?php echo $text_discount_is; ?></option>
					</select>
					<input data-setting="value" value="" type="text" class="form-control" name="##_value" data-help='<?php echo $help_coupon; ?>' />
				<?php } ?>
				
				<?php if ($rule_option == 'gift_voucher') { ?>
					<select class="form-control" name="##_comparison">
						<option data-setting="comparison" value=""><?php echo $standard_select; ?></option>
						<option data-setting="comparison" value="is"><?php echo $text_is; ?></option>
						<option data-setting="comparison" value="not"><?php echo $text_is_not; ?></option>
					</select>
					<input data-setting="value" value="" type="text" class="form-control" name="##_value" data-help='<?php echo $help_gift_voucher; ?>' />
				<?php } ?>
				
				<?php if ($rule_option == 'reward_points') { ?>
					<select class="form-control" name="##_comparison">
						<option data-setting="comparison" value=""><?php echo $standard_select; ?></option>
						<option data-setting="comparison" value="applied"><?php echo $text_applied_to_cart; ?></option>
						<option data-setting="comparison" value="products"><?php echo $text_of_products_in_cart; ?></option>
					</select>
					=
					<input data-setting="value" value="" type="text" class="form-control" name="##_value" data-help='<?php echo $help_reward_points; ?>' />
				<?php } ?>
				
				<?php if (in_array($rule_option, array('city', 'distance', 'postcode', 'shipping_rate'))) { ?>
					<select class="form-control" name="##_comparison">
						<option data-setting="comparison" value=""><?php echo $standard_select; ?></option>
						<option data-setting="comparison" value="is"><?php echo $text_is; ?></option>
						<option data-setting="comparison" value="not"><?php echo $text_is_not; ?></option>
					</select>
					<input data-setting="value" value="" type="text" class="form-control" name="##_value" data-help='<?php echo ${'help_'.$rule_option}; ?>' />
				<?php } ?>
				
				<?php if ($rule_option == 'location_comparison') { ?>
					=
					<input data-setting="comparison" value="" type="hidden" class="form-control" name="##_comparison" />
					<select class="form-control" name="##_value" data-help='<?php echo $help_location_comparison; ?>'>
						<option data-setting="value" value=""><?php echo $standard_select; ?></option>
						<option data-setting="value" value="payment"><?php echo $text_payment_address; ?></option>
						<option data-setting="value" value="shipping"><?php echo $text_shipping_address; ?></option>
					</select>
				<?php } ?>
				
				<?php if ($rule_option == 'shipping_cost') { ?>
					=
					<input data-setting="comparison" value="" type="hidden" class="form-control" name="##_comparison" />
					<input data-setting="value" value="" type="text" class="form-control" name="##_value" data-help='<?php echo $help_shipping_cost; ?>' />
				<?php } ?>
				
				<?php if (in_array($rule_option, array('currency', 'customer_group', 'geo_zone', 'language', 'payment_extension', 'shipping_extension', 'store'))) { ?>
					<select class="form-control" name="##_comparison">
						<option data-setting="comparison" value=""><?php echo $standard_select; ?></option>
						<option data-setting="comparison" value="is"><?php echo $text_is; ?></option>
						<option data-setting="comparison" value="not"><?php echo $text_is_not; ?></option>
					</select>
					<select class="form-control" name="##_value" data-help='<?php echo ${'help_'.$rule_option}; ?>'>
						<option data-setting="value" value=""><?php echo $standard_select; ?></option>
						<?php foreach (${$rule_option.'_array'} as $k => $v) { ?>
							<option data-setting="value" value="<?php echo $k; ?>"><?php echo $v; ?></option>
						<?php } ?>
					</select>
				<?php } ?>
				
				<?php if ($rule_option == 'past_orders') { ?>
					<select class="form-control" name="##_comparison" data-help='<?php echo $help_past_orders_dropdown; ?>'>
						<option data-setting="comparison" value=""><?php echo $standard_select; ?></option>
						<option data-setting="comparison" value="days"><?php echo $text_days; ?></option>
						<option data-setting="comparison" value="quantity"><?php echo $text_quantity; ?></option>
						<option data-setting="comparison" value="total"><?php echo $text_total; ?></option>
					</select>
					=
					<input data-setting="value" value="" type="text" class="form-control" name="##_value" data-help='<?php echo $help_past_orders; ?>' />
				<?php } ?>
				
				<?php if (in_array($rule_option, array('attribute', 'attribute_group', 'category', 'manufacturer', 'product', 'customer'))) { ?>
					<select class="form-control" name="##_comparison">
						<option data-setting="comparison" value=""><?php echo $standard_select; ?></option>
						<option data-setting="comparison" value="is"><?php echo $text_is; ?></option>
						<option data-setting="comparison" value="not"><?php echo $text_is_not; ?></option>
					</select>
					<input data-setting="value" value="" type="text" class="form-control typeahead" data-type="<?php echo $rule_option; ?>" name="##_value" data-help='<?php echo ${'help_'.$rule_option}; ?>' />
				<?php } ?>
				
				<?php if ($rule_option == 'option') { ?>
					<input data-setting="comparison" value="" type="text" class="form-control typeahead" data-type="option" name="##_comparison" data-help='<?php echo $help_option; ?>' />
					=
					<input data-setting="value" value="" type="text" class="form-control" name="##_value" data-help='<?php echo $help_option_value; ?>' />
				<?php } ?>
				
				<?php if ($rule_option == 'product_group') { ?>
					<?php echo $text_cart_has_items_from; ?>
					<select class="form-control pad-left-sm" name="##_comparison" data-help='<?php echo $help_product_group_comparison; ?>'>
						<option data-setting="comparison" value=""><?php echo $standard_select; ?></option>
						<option data-setting="comparison" value="any"><?php echo $text_any; ?></option>
						<option data-setting="comparison" value="all"><?php echo $text_all; ?></option>
						<option data-setting="comparison" value="not"><?php echo $text_not; ?></option>
						<option data-setting="comparison" value="onlyany"><?php echo $text_only_any; ?></option>
						<option data-setting="comparison" value="onlyall"><?php echo $text_only_all; ?></option>
						<option data-setting="comparison" value="none"><?php echo $text_none_of_the; ?></option>
					</select>
					<?php echo $text_members_of; ?>
					<select class="form-control pad-left-sm" name="##_value" data-dropdown-value="" data-help='<?php echo $help_product_group; ?>'>
						<option data-setting="value" value=""><?php echo $standard_select; ?></option>
						<?php foreach ($product_groups as $k => $v) { ?>
							<option data-setting="value" value="<?php echo $k; ?>"><?php echo $v; ?></option>
						<?php } ?>
					</select>
				<?php } ?>
				
				<?php if ($rule_option == 'other_product_data') { ?>
					<select class="form-control" name="##_comparison" data-help='<?php echo $help_other_product_data_column; ?>'>
						<option data-setting="comparison" value=""><?php echo $standard_select; ?></option>
						<?php foreach ($product_columns as $column) { ?>
							<option data-setting="comparison" value="<?php echo $column; ?>"><?php echo $column; ?></option>
						<?php } ?>
					</select>
					<input data-setting="value" value="" type="text" class="form-control" name="##_value" data-help='<?php echo $help_other_product_data; ?>' />
				<?php } ?>
				
				<?php if ($rule_option == 'rule_set') { ?>
					<input data-setting="comparison" type="hidden" class="form-control" name="##_comparison" value="" />
					<select class="form-control" name="##_value" data-dropdown-value="" data-help='<?php echo $help_rule_set; ?>'>
						<option data-setting="value" value=""><?php echo $standard_select; ?></option>
						<?php foreach ($rule_sets as $k => $v) { ?>
							<option data-setting="value" value="<?php echo $k; ?>"><?php echo $v; ?></option>
						<?php } ?>
					</select>
				<?php } ?>		
				
				</span>
				
				<?php $rule_html[$rule_option] = ob_get_clean(); ?>
				<?php echo $rule_html[$rule_option]; ?>
			<?php } ?>
			<?php } ?>
			
		</div>
	<?php } ?>
	
	<div class="container-fluid">
		<form id="form" action="" class="form-horizontal autosave" autocomplete="off">
			
			<?php $no_setting = array('' => array()); ?>
			
			<?php foreach ($settings as $setting) { ?>
				
				<?php
				$key = (isset($setting['key']) ? $setting['key'] : '');
				
				$attributes = '';
				if (isset($setting['attributes'])) {
					foreach ($setting['attributes'] as $attr => $val) {
						$attributes .= $attr . '="' . $val . '" ';
					}
				}
				?>
				
				<?php if ($setting['type'] == 'tabs') { ?>
					
					<ul class="nav nav-tabs">
						<?php $active_tab = ''; ?>
						<?php foreach ($setting['tabs'] as $tab) { ?>
							<li <?php if (!$active_tab) echo 'class="active"'; ?>><a data-toggle="tab" href="#<?php echo $tab; ?>"><?php echo ${'tab_'.$tab}; ?></a></li>
							<?php if (!$active_tab) $active_tab = $tab; ?>
						<?php } ?>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="<?php echo $active_tab; ?>">
					<?php continue; ?>
					
				<?php } elseif ($setting['type'] == 'tab') { ?>
					
					</div>
					<div class="tab-pane" id="<?php echo $key; ?>">
					<?php continue; ?>
					
				<?php } elseif ($setting['type'] == 'heading') { ?>
					
					<div class="lead well well-sm text-info" <?php echo $attributes; ?>>
						<?php if (isset($setting['buttons'])) { ?>
							<div class="pull-right">
								<?php if ($setting['buttons'] == 'expand_collapse') { ?>
									<a onclick="parent = $(this).parent().parent().parent(); parent.find('.expand').hide(); parent.find('.collapsed').children().unwrap(); 
									(parent);" class="btn btn-default" data-help='<?php echo $help_expand_all; ?>'><i class="fa fa-caret-square-o-down pad-right-sm"></i> <?php echo $button_expand_all; ?></a>
									<a onclick="$(this).parent().parent().parent().find('.expand').show(); $(this).parent().parent().parent().find('tbody td').wrapInner('<div class=\'collapsed\' />');" class="btn btn-default" data-help='<?php echo $help_collapse_all; ?>'><i class="fa fa-caret-square-o-right pad-right-sm"></i> <?php echo $button_collapse_all; ?></a>
								<?php } elseif ($permission && $setting['buttons'] == 'backup_restore') { ?>
									<?php $settings_buttons = true; ?>
									<a onclick="backupSettings()" class="btn btn-default"><i class="fa fa-floppy-o pad-right-sm"></i> <?php echo $button_backup_settings; ?></a>
									<a href="#restore-settings-modal" data-toggle="modal" class="btn btn-default"><i class="fa fa-undo pad-right-sm"></i> <?php echo $button_restore_settings; ?></a>
								<?php } else { ?>
									<?php echo $setting['buttons']; ?>
								<?php } ?>
							</div>
						<?php } ?>
						<small><?php echo (isset($setting['text'])) ? $setting['text'] : ${'heading_'.$key}; ?></small>
					</div>
					<?php continue; ?>
				
				<?php } elseif ($setting['type'] == 'table_start') { ?>
					
					<?php $table_class = $key; ?>
					<?php $table_columns = count($setting['columns']); ?>
					<table class="table table-hover table-condensed" <?php echo $attributes; ?>>
						<?php if ($table_columns) { ?>
							<thead>
								<tr>
									<?php foreach ($setting['columns'] as $column) { ?>
										<th><?php echo ${'column_'.$column}; ?></th>
									<?php } ?>
								</tr>
							</thead>
						<?php } ?>
						<tbody>
							<?php if (!empty($setting['buttons'])) { ?>
								<tr>
									<td colspan="<?php echo $table_columns; ?>">
										<?php if ($setting['buttons'] == 'add_row') { ?>
											<a class="btn btn-primary add-button" onclick="newRow = clearRow(copyRow($(this).parents('table').find('tbody tr:nth-child(2)'))); newRow.insertBefore(newRow.prev()); saveRow(newRow);"><i class="fa fa-plus pad-right-sm"></i> <?php echo (isset($setting['text'])) ? ${$setting['text']} : ${'button_' . $setting['buttons']}; ?></a>
										<?php } ?>
									</td>
								</tr>
							<?php } ?>
					<?php continue; ?>
					
				<?php } elseif ($setting['type'] == 'table_end') { ?>
					
						</tbody>
						<?php if ($table_columns && isset($setting['buttons'])) { ?>
							<tfoot>
								<tr>
									<td colspan="<?php echo $table_columns; ?>">
										<?php if ($setting['buttons'] == 'add_row') { ?>
											<a class="btn btn-primary add-button" onclick="saveRow(clearRow(copyRow($(this).parents('table').find('tbody tr:last-child'))))"><i class="fa fa-plus pad-right-sm"></i> <?php echo (isset($setting['text'])) ? ${$setting['text']} : ${'button_' . $setting['buttons']}; ?></a>
										<?php } ?>
									</td>
								</tr>
							</tfoot>
						<?php } ?>
					</table>
					<?php continue; ?>

				<?php } elseif ($setting['type'] == 'row_start') { ?>
					
					<tr class="<?php echo $table_class; ?>" <?php echo $attributes; ?>>
						<td>
							<?php if (isset($saved['display']) && $saved['display'] == 'collapsed') { ?>
								<div class="collapsed">
							<?php } ?>
					<?php continue; ?>
					
				<?php } elseif ($setting['type'] == 'row_end') { ?>
					
							<?php if (isset($saved['display']) && $saved['display'] == 'collapsed') { ?>
								</div>
							<?php } ?>
						</td>
					</tr>
					<?php continue; ?>
					
				<?php } elseif ($setting['type'] == 'column') { ?>
					
					<?php if (isset($saved['display']) && $saved['display'] == 'collapsed') { ?>
							</div>
						</td>
						<td>
							<div class="collapsed">
					<?php } else { ?>
						</td>
						<td>
					<?php } ?>
					<?php continue; ?>
					
				<?php } ?>
				
				
				<?php
				$base_key = preg_replace('/_(\d+)_/', '_', $key);
				$help_text = (isset(${'help_'.$base_key}) ? ${'help_'.$base_key} : '');
				
				$default = (isset($setting['default'])) ? $setting['default'] : '';
				$value = (isset($saved[$key])) ? $saved[$key] : $default;
				$class = (isset($setting['class'])) ? $setting['class'] : '';
				
				if ($setting['type'] == 'multilingual_text' || $setting['type'] == 'multilingual_textarea') {
					foreach ($language_array as $language_code => $language_name) {
						if (!empty($saved) && !isset($saved[$key.'_'.$language_code])) {
							$no_setting[''][] = $key.'_'.$language_code;
						}
					}
				} elseif (!empty($saved) && !isset($saved[$key]) && !in_array($setting['type'], array('button', 'html', 'rule', 'typeahead'))) {
					$no_setting[''][] = $key;
				}
				
				$all_attributes = 'class="form-control ' . $class . '" name="' . $key . '" id="input-' . $key . '"';
				$all_attributes .= ($default && !is_array($default)) ? 'data-default="' . $default . '" ' : '';
				$all_attributes .= ($help_text) ? "data-help='" . $help_text . "' " : '';
				$all_attributes .= $attributes;
				?>
				
				
				<?php if (isset($setting['title']) || isset(${'entry_'.$base_key})) { ?>
					<div class="form-group">
						<label class="control-label col-sm-4" for="input-<?php echo $key; ?>"><?php echo (isset($setting['title'])) ? $setting['title'] : ${'entry_'.$base_key}; ?></label>
						<div class="col-sm-8">
				<?php } else { ?>
					<div class="setting">
				<?php } ?>
				
				<?php if (isset($setting['before'])) { ?>
					<?php echo $setting['before'] . ' '; ?>
				<?php } ?>
				
				<?php if ($setting['type'] == 'button') { ?>
					
					<?php if ($key == 'expand_collapse') { ?>
						<div class="expand"><a class="btn btn-default" data-help='<?php echo $text_expand; ?>' onclick="$(this).parent().hide(); $(this).parents('tr').find('.collapsed').children().unwrap();"><i class="fa fa-caret-square-o-right fa-lg fa-fw"></i></a></div>
						<div><a class="btn btn-default" data-help='<?php echo $text_collapse; ?>' onclick="$(this).parent().prev().show(); $(this).parents('tr').find('td').wrapInner('<div class=\'collapsed\' />');"><i class="fa fa-caret-square-o-down fa-lg fa-fw"></i></a></div>
					<?php } elseif ($key == 'copy') { ?>
						<a class="btn btn-warning add-button" data-help='<?php echo $text_copy; ?>' onclick="saveRow(copyRow($(this).parents('tr'))); $('.tooltip').hide();"><i class="fa fa-files-o fa-lg fa-fw"></i></a>
					<?php } elseif ($key == 'delete') { ?>
						<a class="btn btn-danger" data-help='<?php echo $button_delete; ?>' onclick="<?php if ($save_type == 'auto') echo "if (confirm('" . $standard_confirm . "'))"; ?> removeRow($(this).parents('tr'))"><i class="fa fa-trash-o fa-lg fa-fw"></i></a>
					<?php } elseif ($key == 'save') { ?>
						<a class="btn btn-primary" onclick="saveSettings($(this))"><i class="fa fa-floppy-o pad-right-sm"></i> <?php echo $data['button_save']; ?></a>
					<?php } elseif ($key == 'module_link') { ?>
						<a class="btn btn-link" href="index.php?route=<?php echo $type . '/' . $name . '&module_id=' . $setting['module_id'] . '&token=' . $token; ?>"><?php echo $setting['text']; ?></a>
					<?php } elseif ($key == 'edit_module') { ?>
						<a class="btn btn-primary" href="index.php?route=<?php echo $type . '/' . $name . '&module_id=' . $setting['module_id'] . '&token=' . $token; ?>"><i class="fa fa-pencil fa-fw"></i> <?php echo $button_edit; ?></a>
					<?php } elseif ($key == 'copy_module') { ?>
						<a class="btn btn-warning add-button" onclick="modifyModule('copy', '<?php echo $setting['module_id']; ?>')"><i class="fa fa-files-o fa-fw"></i> <?php echo $button_copy; ?></a>
					<?php } elseif ($key == 'delete_module') { ?>
						<a class="btn btn-danger add-button" onclick="if (confirm('<?php echo $standard_confirm; ?>')) modifyModule('delete', '<?php echo $setting['module_id']; ?>')"><i class="fa fa-trash-o fa-fw"></i> <?php echo $button_delete; ?></a>
					<?php } ?>
					
				<?php } elseif ($setting['type'] == 'checkboxes') { ?>
					
					<?php $values = (is_array($value)) ? $value : array_map('trim', explode(';', $value)); ?>
					<?php foreach ($setting['options'] as $val => $text) { ?>
						<label <?php if ($help_text) echo "data-help='" . $help_text . "'"; ?>><input type="checkbox" class="form-control <?php echo $class; ?>" name="<?php echo $key; ?>[]" <?php echo $attributes . ($default && !is_array($default) ? 'data-default="' . $default . '" ' : ''); ?> <?php if (in_array($val, $values)) echo 'checked="checked"'; ?> value="<?php echo $val; ?>" /> <?php echo $text; ?></label>
					<?php } ?>
					
				<?php } elseif ($setting['type'] == 'html') { ?>
					
					<?php echo $setting['content']; ?>
					
				<?php } elseif ($setting['type'] == 'image') { ?>
					
					<a href="" id="thumb-image" data-toggle="image" <?php echo $attributes; ?> class="img-thumbnail <?php echo $class; ?>">
						<img src="<?php echo $setting['cached']; ?>" data-placeholder="<?php echo $image_placeholder; ?>" />
					</a>
					<input type="hidden" id="input-image" name="<?php echo $key; ?>" value="<?php echo $value; ?>" />
					
				<?php } elseif ($setting['type'] == 'multilingual_text' || $setting['type'] == 'multilingual_textarea') { ?>
					
					<?php $text = ($setting['type'] == 'multilingual_text'); ?>
					<?php if (!empty($setting['admin_ref'])) { ?>
						<?php $value = (isset($saved[$key.'_admin'])) ? $saved[$key.'_admin'] : (isset($setting['default']) ? $setting['default'] : ''); ?>
						<div class="input-group" data-help='<?php echo $help_admin_reference; ?>'>
							<span class="input-group-addon"><i class="fa fa-compass fa-lg"></i></span>
							<?php echo ($text) ? '<input type="text"' : '<textarea'; ?> class="form-control <?php echo $class; ?>" placeholder="<?php echo $text_admin_reference; ?>" name="<?php echo $key; ?>_admin" <?php echo $attributes; ?> <?php echo ($text) ? " value='" : '>'; ?><?php echo str_replace("'", "&apos;", $value); ?><?php echo ($text) ? "' />" : '</textarea>'; ?>
						</div>
					<?php } ?>
					<?php foreach ($language_array as $language_code => $language_name) { ?>
						<?php $value = (isset($saved[$key.'_'.$language_code])) ? $saved[$key.'_'.$language_code] : (isset($setting['default']) ? $setting['default'] : ''); ?>
						<div class="input-group" <?php if ($help_text) echo "data-help='" . $help_text . ' ' . $language_name . "'"; ?>>
							<span class="input-group-addon"><img src="view/image/flags/<?php echo $language_flags[$language_code]; ?>" /></span>
							<?php echo ($text) ? '<input type="text"' : '<textarea'; ?> class="form-control <?php echo $class; ?>" placeholder="<?php echo (isset(${'placeholder_'.$base_key})) ? $language_name . ' ' . ${'placeholder_'.$base_key} : $language_name; ?>" name="<?php echo $key . '_' . $language_code; ?>" <?php echo $attributes; ?> <?php echo ($text) ? " value='" : '>'; ?><?php echo str_replace("'", "&apos;", $value); ?><?php echo ($text) ? "' />" : '</textarea>'; ?>
						</div>
						
					<?php } ?>
					
				<?php } elseif ($setting['type'] == 'rule') { ?>
					
					<?php foreach ($setting['rules'] as $rule) { ?>
						<?php foreach (array('type', 'comparison', 'value') as $field) {
							if (isset($saved[$key.'_'.$rule.'_'.$field])) {
								${'rule_'.$field} = $saved[$key.'_'.$rule.'_'.$field];
							} else {
								${'rule_'.$field} = '';
								if ($rule_type) $no_setting[$rule_type] = $key.'_'.$rule.'_'.$field;
							}
						}
						
						$rule_type_html = $rule_selector_html;
						$rule_type_html = str_replace('<option value="' . $rule_type . '"', '<option value="' . $rule_type . '" selected="selected"', $rule_type_html);
						$rule_type_html = str_replace('##', $key . '_' . $rule, $rule_type_html);
						
						$rule_comparison_value_html = $rule_html[$rule_type];
						$rule_comparison_value_html = str_replace('<input data-setting="comparison" value=""', '<input data-setting="comparison" value="' . $rule_comparison . '"', $rule_comparison_value_html);
						$rule_comparison_value_html = str_replace('<option data-setting="comparison" value="' . $rule_comparison . '"', '<option data-setting="comparison" value="' . $rule_comparison . '" selected="selected"', $rule_comparison_value_html);
						$rule_comparison_value_html = str_replace('<input data-setting="value" value=""', '<input data-setting="value" value="' . $rule_value . '"', $rule_comparison_value_html);
						$rule_comparison_value_html = str_replace('<option data-setting="value" value="' . $rule_value . '"', '<option data-setting="value" value="' . $rule_value . '" selected="selected"', $rule_comparison_value_html);
						$rule_comparison_value_html = str_replace('##', $key . '_' . $rule, $rule_comparison_value_html);
						?>
						
						<div class="rule">
							<?php echo $rule_type_html; ?>
							<?php echo $rule_comparison_value_html; ?>
						</div>
						
					<?php } ?>
					
					<a class="btn btn-success btn-xs add-button pad-top-sm" onclick="addRule($(this))" data-help='<?php echo $help_add_rule; ?>' data-prefix="<?php echo $key; ?>"><i class="fa fa-plus"></i> <?php echo $button_add_rule; ?></a>
					
				<?php } elseif ($setting['type'] == 'select') { ?>
						
					<select <?php echo $all_attributes; ?>>
						<?php foreach ($setting['options'] as $val => $text) { ?>
							<?php $optgroup = false; ?>
							<?php if (!$text) { ?>
								<?php if ($optgroup) { ?>
									</optgroup>
								<?php } ?>
								<optgroup label="<?php echo (isset(${$val})) ? ${$val} : $val; ?>">
								<?php $optgroup = true; ?>
							<?php } else { ?>
								<option value="<?php echo $val; ?>" <?php if ($val == $value) echo 'selected="selected"'; ?>><?php echo $text; ?></option>
							<?php } ?>
						<?php } ?>
					</select>
					
				<?php } elseif ($setting['type'] == 'textarea') { ?>
					
					<textarea <?php echo $all_attributes; ?>><?php echo $value; ?></textarea>
					
				<?php } elseif ($setting['type'] == 'typeahead') { ?>
					
						<div class="setting typeahead-block">
							<?php if (empty($setting['typeahead'])) { ?>
								<?php echo $text_autocomplete_from; ?><br />
								<select class="nosave form-control" style="margin: 0 120px 5px 0" data-help='<?php echo $help_autocomplete_from; ?>'>
									<option value="all"><?php echo $text_all_database_tables; ?></option>
									<?php foreach ($typeaheads as $typeahead) { ?>
										<option value="<?php echo $typeahead; ?>"><?php echo ($typeahead == 'category') ? 'Categories' : ucwords(str_replace('_', ' ', $typeahead)) . 's'; ?></option>
									<?php } ?>
								</select>
								<br />
							<?php } ?>
							<input type="text" class="nosave form-control typeahead" data-key="<?php echo $key; ?>" data-type="<?php echo (isset($setting['typeahead'])) ? $setting['typeahead'] : 'typeahead'; ?>" placeholder="<?php echo $placeholder_typeahead; ?>" data-help='<?php echo $help_typeahead; ?>' />
						</div>
						<div class="product-group-scrollbox typeahead-block">
							<?php
							$scrollbox_list = array();
							foreach($saved as $saved_key => $saved_value) {
								if (strpos($saved_key, $key) !== 0) continue;
								if (is_array($saved_value)) {
									foreach ($saved_value as $key => $value) {
										$scrollbox_list[$value] = $saved_key . '[]';
									}
								} else {
									$scrollbox_list[$saved_value] = $saved_key;
								}
							}
							uksort($scrollbox_list, 'strcasecmp');
							?>
							
							<?php foreach ($scrollbox_list as $saved_value => $saved_key) { ?>
								<div><a class="btn btn-danger btn-xs" data-help='<?php echo $button_delete; ?>' onclick="removeRow($(this).parent())"><i class="fa fa-trash-o fa-lg"></i></a>
									&nbsp;<?php echo $saved_value; ?><input type="hidden" class="form-control" name="<?php echo $saved_key; ?>" value="<?php echo $saved_value; ?>" />
									<span style="display: none"><?php echo $saved_value; ?></span>
								</div>
							<?php } ?>
						</div>
						
				<?php } else { ?>
					
					<input type="<?php echo $setting['type']; ?>" <?php echo $all_attributes; ?> value='<?php echo str_replace("'", "&apos;", $value); ?>' />
				
				<?php } ?>
				
				<?php if (isset($setting['after'])) { ?>
					<?php echo ' ' . $setting['after']; ?>
				<?php } ?>
				
				<?php if (isset($key) && (isset($setting['title']) || isset(${'entry_'.$base_key}))) { ?>
						</div> <!-- .form-control -->
					</div> <!-- .form-group -->
				<?php } else { ?>
					</div> <!-- .setting -->
				<?php } ?>
				
			<?php } /* end $settings foreach loop */ ?>
			
			<?php if (isset($active_tab)) { ?>
					</div>
				</div>
			<?php } ?>
			
		</form>
		
		<?php if (!empty($settings_buttons)) { ?>
			<div id="restore-settings-modal" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<a class="close" data-dismiss="modal">&times;</a>
							<h4 class="modal-title"><?php echo $button_restore_settings; ?></h4>
						</div>
						<div class="modal-body">
							<form action="index.php?route=<?php echo $type; ?>/<?php echo $name; ?>/restoreSettings&token=<?php echo $token; ?>" method="post" enctype="multipart/form-data">
								<p><?php echo $text_restore_from_your; ?></p>
								<p style="margin-bottom: 13px">
									<label><input type="radio" name="from" class="pad-right-sm" value="auto" checked="checked" /> <?php echo $text_automatic_backup; ?> &nbsp; <b><?php echo $autobackup_time; ?></b></label>
								</p>
								<p><label><input type="radio" name="from" class="pad-right-sm" value="manual" /> <?php echo $text_manual_backup; ?> &nbsp; <b id="manual-backup-time"><?php echo $backup_time; ?></b></label></p>
								<p><label><input type="radio" name="from" class="pad-right-sm" value="file" /> <?php echo $text_backup_file; ?> &nbsp; <input type="file" name="backup_file" onclick="$(this).parent().click()" /></label></p>
								<p><a onclick="$(this).hide(500, function(){ $(this).next().show(); });" class="btn btn-primary"><i class="fa fa-undo pad-right-sm"></i> <?php echo $button_restore; ?></a>
									<a style="display: none" onclick="$(this).hide(500, function(){ $(this).next().show(); }); $('#restore-settings-modal form').submit();" class="btn btn-danger"><?php echo $text_this_will_overwrite_settings; ?></a>
									<a style="display: none" class="btn btn-warning"><?php echo $text_restoring; ?></a>
								</p>
							</form>
						</div>
						<div class="modal-footer">
							<a href="#" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times pad-right-sm"></i> <?php echo $button_cancel; ?></a>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
		
	</div>
	
	<?php echo $copyright; ?>
	
</div>


<script type="text/javascript"><!--
	// Settings Functions
	var retries = [];
	
	<?php if (!$permission) { ?>
		$(':input').attr('disabled', 'disabled');
	<?php } elseif ($save_type == 'auto') { ?>
		$(document).on('change', '#form.autosave :input', function(){
			var element = $(this);
			if (element.hasClass('nosave') || element.find('option:selected').hasClass('nosave') || element.attr('name').indexOf('_0_') != -1) {
				return;
			}
			element.addClass('saving').removeClass('save-error');
			if (retries[element.attr('name')] == undefined) {
				retries[element.attr('name')] = 0;
			}
			var checkboxes = $('input[name="' + element.attr('name') + '"]:checked').map(function(){ return this.value; }).get().join(';');
			$.ajax({
				type: 'POST',
				url: 'index.php?route=<?php echo $type; ?>/<?php echo $name; ?>/saveSettings&saving=auto&token=<?php echo $token; ?>',
				data: (element.attr('type') == 'checkbox' ? element.attr('name').replace('[]', '') + '=' + checkboxes : element),
				success: function(error) {
					$('.add-button').removeAttr('disabled');
					element.removeClass('saving').removeClass('save-error');
					if (error) {
						if (retries[element.attr('name')] < 5) {
							retries[element.attr('name')]++;
							element.change();
						} else if (error == 'PermissionError') {
							element.addClass('save-error');
							$('.alert').remove();
							$('.autosave').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle fa-lg pad-right-sm"></i> <?php echo $standard_error; ?></div>').fadeIn();
						} else {
							alert('Please try saving setting "' + element.attr('name') + '" again. There was an error saving the setting:' + "\n\n" + error);
						}
					} else {
						retries[element.attr('name')] = 0;
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					element.removeClass('saving').addClass('save-error');
					alert('Please try saving setting "' + element.attr('name') + '" again. There was an error saving the setting:' + "\n\n" + jqXHR.status + ' (' + errorThrown + ')');
				}
			});
		});
	<?php } elseif ($save_type != 'none') { ?>
		function saveSettings(element) {
			element.attr('disabled', 'disabled').html('<i class="fa fa-refresh fa-spin pad-right-sm"></i> <?php echo $standard_saving; ?>');
			$('.summernote').each(function(){
				$(this).val($(this).code());
			});
			$.ajax({
				type: 'POST',
				url: 'index.php?route=<?php echo $type; ?>/<?php echo $name; ?>/saveSettings&saving=manual&token=<?php echo $token; ?>',
				data: $('#form :input:not(:checkbox, .nosave), #form :checkbox:checked:not(.nosave)'),
				success: function(error) {
					element.addClass('btn-success').removeAttr('disabled').html('<i class="fa fa-check pad-right-sm"></i> <?php echo $standard_saved; ?>');
					if (error) alert(error);
					<?php if ($save_type == 'standard' || (isset($module_id) && $module_id == 0)) { ?>
						location = 'index.php?route=<?php echo $type . '/' . $name . '&token=' . $token; ?>';
					<?php } ?>
					setTimeout(function(){
						element.removeClass('btn-success').html('<i class="fa fa-floppy-o pad-right-sm"></i> <?php echo $button_save; ?>');
					}, 2000);
				},
				error: function(jqXHR, textStatus, errorThrown) {
					element.removeAttr('disabled').html('<i class="fa fa-floppy-o pad-right-sm"></i> <?php echo $button_save; ?>');
					alert("There was an error saving the settings:\n\n" + jqXHR.status + ' (' + errorThrown + ')');
				}
			});
		}
	<?php } ?>
	
	<?php if ($save_type == 'auto') { ?>
		<?php foreach ($no_setting as $rule_type => $setting_to_save) { ?>
			<?php if (empty($rule_type)) { ?>
				<?php foreach ($setting_to_save as $unsaved_setting) { ?>
					$('*[name=<?php echo $unsaved_setting; ?>]').change();
				<?php } ?>
			<?php } else { ?>
				$('.<?php echo $rule_type; ?>-html *[name=<?php echo $setting_to_save; ?>]').change();
			<?php } ?>
		<?php } ?>
	<?php } ?>
	
	<?php if (!empty($settings_buttons)) { ?>
		function backupSettings() {
			if (!$('#manual-backup-time').text() || confirm('<?php echo $text_this_will_overwrite_your; ?>')) {
				$.ajax({
					url: 'index.php?route=<?php echo $type; ?>/<?php echo $name; ?>/backupSettings&token=<?php echo $token; ?>',
					success: function(time) {
						if (!time) {
							$('.alert').remove();
							$('.autosave').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle fa-lg"></i> <?php echo $standard_error; ?></div>').fadeIn();
						} else {
							$('#manual-backup-time').html(time);
							$('#restore-button').removeClass('disabled');
							$('.alert').remove();
							$('.autosave').before('<div class="alert alert-success"><i class="fa fa-check fa-lg pad-right-sm"></i> <?php echo $text_backup_saved_to; ?> ' + time + ' &nbsp; (<a target="_blank" href="index.php?route=<?php echo $type; ?>/<?php echo $name; ?>/viewBackup&token=<?php echo $token; ?>"><?php echo $text_view_backup; ?></a>) &nbsp; (<a href="index.php?route=<?php echo $type; ?>/<?php echo $name; ?>/downloadBackup&token=<?php echo $token; ?>"><?php echo $text_download_backup_file; ?></a>)</div>').fadeIn();
						}
					}
				});
			}
		}
	<?php } ?>
	
	// UI Functions
	<?php if ($save_type == 'auto') { ?>
		$(document).on('click', '.add-button', function(){
			$(this).attr('disabled', 'disabled');
		});
	<?php } ?>
	
	$(document).on('dblclick', '.collapsed', function(){
		$(this).parents('tr').find('.expand a').click();
	});
	
	// Tooltip Functions
	function attachTooltips(element) {
		$('.tooltip').hide();
		element.find('*[data-help]').each(function(){
			if ($('select[name="tooltips"]').val() == 1 || <?php echo (!isset($saved['tooltips'])) ? 'true' : 'false'; ?>) {
				$(this).attr('title', $(this).attr('data-help')).tooltip({'animation': false, 'placement': ($(this).attr('data-help').length < 400) ? 'top' : 'right', 'html':true});
			} else {
				$(this).tooltip('destroy');
			}
		});
	}
	
	$(document).on('change', 'select[name="tooltips"]', function(){
		attachTooltips($('body'));
	});
	
	<?php if (!isset($saved['tooltips']) || $saved['tooltips']) { ?>
		attachTooltips($('body'));
	<?php } ?>
	
	
	// Typeahead Functions
	<?php if (!empty($saved['autocomplete_preloading'])) { ?>
		var localdata = {
			<?php foreach (array_merge(array('all'), $typeaheads) as $typeahead) { ?>
				'<?php echo $typeahead; ?>': [<?php echo ${$typeahead.'_preload'}; ?>],
			<?php } ?>
		};
	<?php } ?>
	
	var currentTypeaheadValue = '';
	
	<?php if (!empty($typeaheads)) { ?>
		$(document).on('click', '.typeahead', function(){
			var element = $(this);
			if (element.hasClass('tt-query') && element.attr('data-type') != 'typeahead') return;
			var type = (element.attr('data-type') == 'typeahead') ? element.parents('td').find('select').val() : element.attr('data-type');
			element.typeahead('destroy').typeahead({
				limit: 100,
				<?php if (!empty($saved['autocomplete_preloading'])) { ?>
					local: localdata[type],
				<?php } ?>
				remote: 'index.php?route=<?php echo $type; ?>/<?php echo $name; ?>/typeahead&token=<?php echo $token; ?>&type=' + type + '&q=%QUERY'
			}).on('keydown', function(e) {
				if (e.which == 13 && $('.tt-is-under-cursor').length < 1) {
					currentTypeaheadValue = '';
					element.parent().find('.tt-suggestion:first-child').click();
				}
			}).on('keyup', function(e) {
				currentTypeaheadValue = element.val();
			}).on('typeahead:selected', function(obj, datum) {
				if (element.val().indexOf('[') != -1) {
					var scrollbox = element.parent().parent().next();
					if (scrollbox.hasClass('product-group-scrollbox')) {
						if (!scrollbox.find('input[value="' + element.val() + '"]').length) {
							<?php if ($save_type == 'auto') { ?>
								scrollbox.addClass('saving');
							<?php } ?>
							var firstUnusedNumber = 1;
							var keySplit = element.attr('data-key').split('_').pop();
							while (scrollbox.html().indexOf('_' + keySplit + '_' + firstUnusedNumber) != -1) {
								firstUnusedNumber++;
							}
							var nameEnding = '_' + firstUnusedNumber;
							scrollbox.append('<div><a class="btn btn-danger btn-xs" data-help="<?php echo $button_delete; ?>" onclick="removeRow($(this).parent())"><i class="fa fa-trash-o fa-lg"></i></a> &nbsp;' + element.val() + '<input type="hidden" class="form-control" name="' + element.attr('data-key') + nameEnding + '" value="' + element.val() + '" /><span style="display: none">' + element.val() + '</span></div>').find('input').last().change();
							scrollbox.append(scrollbox.children().sort(function(a, b) { A = $('input', a).val().toLowerCase(); B = $('input', b).val().toLowerCase(); return (A < B) ? -1 : (A > B) ? 1 : 0; }));
							setTimeout(function(){ scrollbox.removeClass('saving'); }, 500);
						}
						element.typeahead('setQuery', currentTypeaheadValue).focus();
					} else {
						element.typeahead('setQuery', element.val().replace(/\[.*:/, '[')).change();
					}
				}
			});
			element.focus();
		});
	<?php } ?>
	
	// Row Functions
	function removeRow(row) {
		if (row.parent().find('tr').length != 1) {
			var i = 0;
			row.addClass('save-error').find(':input').each(function(index, element) {
				$.get('index.php?route=<?php echo $type; ?>/<?php echo $name; ?>/deleteSetting&setting=' + $(this).attr('name') + '&token=<?php echo $token; ?>', function(data) {
					i++;
					if (i == row.find(':input').length) {
						row.remove();
					}
				});
			});
		} else {
			clearRow(row);
		}
	}
	
	function clearRow(row) {
		row.find(':input').each(function(){
			$.get('index.php?route=<?php echo $type; ?>/<?php echo $name; ?>/deleteSetting&setting=' + $(this).attr('name') + '&token=<?php echo $token; ?>');
		});
		row.find('input[type=text], textarea').val('');
		row.find(':checked').removeAttr('checked');
		row.find(':selected').removeAttr('selected');
		row.find('.rule').remove();
		row.find('.product-group-scrollbox div').remove();
		row.find('input[data-default]').each(function(){
			$(this).val($(this).attr('data-default'));
		});
		row.find(':checkbox[data-default]').each(function(){
			$(this).prop('checked', 'checked');
		});
		row.find('select[data-default]').each(function(){
			$(this).find('option[value="' + $(this).attr('data-default') + '"]').attr('selected', 'selected');
		});
		return row;
	}
	
	function copyRow(row) {
		setInputAttributes(row);
		var clone = row.clone();
		if (row.parents('table').attr('data-autoincrement') != undefined) {
			var firstUnusedNumber = row.parents('table').attr('data-autoincrement');
			row.parents('table').attr('data-autoincrement', parseInt(firstUnusedNumber) + 1);
		} else {
			var firstUnusedNumber = 1;
			while (row.parent().html().indexOf(row.attr('class') + '_' + firstUnusedNumber + '_') != -1) {
				firstUnusedNumber++;
			}
		}
		clone.html(clone.html().replace(new RegExp(row.attr('class') + '_(\\d+)_', 'g'), row.attr('class') + '_' + firstUnusedNumber + '_'));
		attachTooltips(clone);
		clone.find('.tt-query').siblings().remove();
		clone.find('.tt-query').removeClass('tt-query').removeAttr('style').unwrap();
		clone.insertAfter(row).show();
		return row.next();
	}
	
	function saveRow(row) {
		<?php if ($save_type == 'auto') { ?>
			row.find('.form-control').each(function(){
				$(this).change();
			});
		<?php } ?>
	}
	
	// Helper Functions
	function setInputAttributes(element) {
		element.find('input[type="text"]').each(function(){
			$(this).attr('value', $(this).val());
		});
		element.find('input[type="checkbox"]').each(function(){
			if ($(this).is(':checked')) {
				$(this).attr('checked', 'checked');
			} else {
				$(this).removeAttr('checked');
			}
		});
		element.find('textarea').each(function(){
			$(this).html($(this).val());
		});
		element.find('select').each(function(){
			var selectValue = $(this).val();
			$(this).find('option[value="' + selectValue + '"]').attr('selected', 'selected').siblings().removeAttr('selected');
			$(this).val(selectValue);
		});
	}
	
	// Rule Functions
	function loadDropdown(element) {
		$.ajax({
			async: false,
			url: 'index.php?route=<?php echo $type; ?>/<?php echo $name; ?>/loadDropdown&type=' + element.parent().attr('class').replace('-html', '') + '&token=<?php echo $token; ?>',
			success: function(data) {
				element.html(data);
			}
		});
	}
	
	$(document).on('change', 'select[data-dropdown-value]', function(){
		$(this).attr('data-dropdown-value', $(this).val());
	});
	
	function addRule(element) {
		var firstUnusedNumber = 1;
		while (element.parent().html().indexOf('_' + firstUnusedNumber + '_type') != -1) {
			firstUnusedNumber++;
		}
		element.before('<div class="rule">' + $('#rule-selector-html').html().replace(/##/g, element.attr('data-prefix') + '_' + firstUnusedNumber) + '</div>');
		attachTooltips(element.prev());
	}
	
	$(document).on('change', '.rule > select', function(){
		$(this).nextAll().remove();
		$(this).after($('#rule-templates .' + $(this).val() + '-html')[0].outerHTML.replace(/##/g, $(this).attr('data-prefix')));
		if ($(this).val() == 'rule_set' || $(this).val() == 'product_group') {
			loadDropdown($(this).parent().find('select[data-dropdown-value]'));
		}
		attachTooltips($(this).next());
		saveRow($(this).next());
	});
	
	// Module Functions
	function modifyModule(action, module_id) {
		element = $(event.target);
		element.attr('disabled', 'disabled').find('i').removeClass('fa-files-o fa-trash-o').addClass('fa-refresh fa-spin');
		$.get('index.php?route=<?php echo $type; ?>/<?php echo $name; ?>/' + action + 'Module&module_id=' + module_id + '&token=<?php echo $token; ?>',
			function(data) {
				if (data == 'error') {
					alert('Error');
					element.removeAttr('disabled').find('i').removeClass('fa-refresh fa-spin');
					if (action == 'copy') {
						element.find('i').addClass('fa-files-o');
					} else {
						element.find('i').addClass('fa-trash-o');
					}
				} else {
					location.reload();
				}
			}
		);
	}
//--></script>
<?php echo $footer; ?>