<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success"><i class="fa fa-save"></i> <?php echo $button_save; ?></button><!-- yym -->
        <!--<a onclick="$('#form').attr('action', '<?php echo $continue; ?>');$('#form').submit();" data-toggle="tooltip" title="<?php echo $button_continue; ?>" class="btn btn-primary"><i class="fa fa-check"></i></a>--><!-- yym -->
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i> <?php echo $button_cancel; ?></a><!-- yym --></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<div class="alert alert-info">
	  <?php echo $entry_store; ?>
	  <select onchange="store();" name="store_id">
		<option value="0"<?php echo $store_id == 0 ? ' selected="selected"' : ''; ?>><?php echo $text_default_store; ?></option>
		<?php foreach ($stores as $store) { ?>
		<option value="<?php echo $store['store_id']; ?>"<?php echo $store_id == $store['store_id'] ? ' selected="selected"' : ''; ?>><?php echo $store['name']; ?></option>
		<?php } ?>
	  </select>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
		  <ul class="nav nav-tabs">
			<li class="active"><a href="#tab-home" data-toggle="tab"><i data-toggle="tooltip" title="<?php echo $tab_home; ?>" class="fa fa-home"></i></a></li>
			<li><a href="#tab-general" data-toggle="tab"><i data-toggle="tooltip" title="<?php echo $tab_general; ?>" class="fa fa-gear"></i></a></li>
			<li><a href="#tab-technical" data-toggle="tab"><i data-toggle="tooltip" title="<?php echo $tab_technical; ?>" class="fa fa-wrench"></i></a></li>
			<li><a href="#tab-field" data-toggle="tab"><i data-toggle="tooltip" title="<?php echo $tab_field; ?>" class="fa fa-bars"></i></a></li>
			<li><a href="#tab-module" data-toggle="tab"><i data-toggle="tooltip" title="<?php echo $tab_module; ?>" class="fa fa-puzzle-piece"></i></a></li>
			<li><a href="#tab-survey" data-toggle="tab"><i data-toggle="tooltip" title="<?php echo $tab_survey; ?>" class="fa fa-edit"></i></a></li>
			<li><a href="#tab-delivery" data-toggle="tab"><i data-toggle="tooltip" title="<?php echo $tab_delivery; ?>" class="fa fa-truck"></i></a></li>
			<li><a href="#tab-countdown" data-toggle="tab"><i data-toggle="tooltip" title="<?php echo $tab_countdown; ?>" class="fa fa-clock-o"></i></a></li>
			<li><a href="#tab-license" data-toggle="tab"><i data-toggle="tooltip" title="License" class="fa fa-key"></i></a></li>
			<li><a href="#tab-about" data-toggle="tab"><i data-toggle="tooltip" title="About" class="fa fa-question"></i></a></li>
          </ul>
		  <div class="tab-content">
			<div class="tab-pane active" id="tab-home">
			  <div class="row">
				<div class="col-xs-3 text-center">
				  <a href="#tab-general" data-toggle="tab" onclick="show('#tab-general');"><i title="<?php echo $text_general; ?>" data-toggle="tooltip" class="fa fa-gear fa-5x fa-fw"></i></a>
				</div>
				<div class="col-xs-3 text-center">
				  <a href="#tab-technical" data-toggle="tab" onclick="show('#tab-technical')"><i title="<?php echo $text_technical; ?>" data-toggle="tooltip" class="fa fa-wrench fa-5x "></i></a>
				</div>
				<div class="col-xs-3 text-center">
				  <a href="#tab-field" data-toggle="tab" onclick="show('#tab-field')"><i title="<?php echo $text_field; ?>" data-toggle="tooltip" class="fa fa-bars fa-5x fa-fw"></i></a>
				</div>
				<div class="col-xs-3 text-center">
				  <a href="#tab-module" data-toggle="tab" onclick="show('#tab-module')"><i title="<?php echo $text_module_home; ?>" data-toggle="tooltip" class="fa fa-puzzle-piece fa-5x fa-fw"></i></a>
				</div>
			  </div><br />
			  <div class="row">
				<div class="col-xs-3 text-center">
				  <a href="#tab-survey" data-toggle="tab" onclick="show('#tab-survey')"><i title="<?php echo $text_survey; ?>" data-toggle="tooltip" class="fa fa-edit fa-5x fa-fw"></i></a>
				</div>
				<div class="col-xs-3 text-center">
				  <a href="#tab-delivery" data-toggle="tab" onclick="show('#tab-delivery')"><i title="<?php echo $text_delivery; ?>" data-toggle="tooltip" class="fa fa-truck fa-5x fa-fw"></i></a>
				</div>
				<div class="col-xs-3 text-center">
				  <a href="#tab-countdown" data-toggle="tab" onclick="show('#tab-countdown')"><i title="<?php echo $text_countdown; ?>" data-toggle="tooltip" class="fa fa-clock-o fa-5x fa-fw"></i></a>
				</div>
				<div class="col-xs-3 text-center">
				  <a href="#tab-support" data-toggle="tab" onclick="show('#tab-support')"><i title="<?php echo $text_support; ?>" data-toggle="tooltip" class="fa fa-ticket fa-5x fa-fw"></i></a>
				</div>
			  </div>
			</div>
            <div class="tab-pane" id="tab-general">
			  <div class="row">
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-status"><span title="<?php echo $help_status; ?>" data-toggle="tooltip"><?php echo $entry_status; ?></span></label>
				  <div class="col-sm-8">
					<select name="quickcheckout_status" id="input-status" class="form-control">
					  <option value="1"<?php echo $quickcheckout_status ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
					  <option value="0"<?php echo $quickcheckout_status ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
					</select>
				  </div>
				</div>
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-load-screen"><span title="<?php echo $help_load_screen; ?>" data-toggle="tooltip"><?php echo $entry_load_screen; ?></span></label>
				  <div class="col-sm-8">
					<select name="quickcheckout_load_screen" id="input-load-screen" class="form-control">
					  <option value="1"<?php echo $quickcheckout_load_screen ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
					  <option value="0"<?php echo $quickcheckout_load_screen ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
					</select>
				  </div>
				</div>
			  </div>
			  <div class="row">
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-payment-logo"><span title="<?php echo $help_payment_logo; ?>" data-toggle="tooltip"><?php echo $entry_payment_logo; ?></span></label>
				  <div class="col-sm-8">
					<select name="quickcheckout_payment_logo" id="input-payment-logo" class="form-control">
					  <option value="1"<?php echo $quickcheckout_payment_logo ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
					  <option value="0"<?php echo $quickcheckout_payment_logo ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
					</select>
				  </div>
				</div>
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-payment"><span title="<?php echo $help_payment; ?>" data-toggle="tooltip"><?php echo $entry_payment; ?></span></label>
				  <div class="col-sm-8">
					<select name="quickcheckout_payment" id="input-payment" class="form-control">
					  <option value="1"<?php echo $quickcheckout_payment ? ' selected="selected"' : ''; ?>><?php echo $text_radio_type; ?></option>
					  <option value="0"<?php echo $quickcheckout_payment ? '' : ' selected="selected"'; ?>><?php echo $text_select_type; ?></option>
					</select>
				  </div>
				</div>
			  </div>
			  <div class="row">
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-shipping"><span title="<?php echo $help_shipping; ?>" data-toggle="tooltip"><?php echo $entry_shipping; ?></span></label>
				  <div class="col-sm-8">
					<select name="quickcheckout_shipping" id="input-shipping" class="form-control">
					  <option value="1"<?php echo $quickcheckout_shipping ? ' selected="selected"' : ''; ?>><?php echo $text_radio_type; ?></option>
					  <option value="0"<?php echo $quickcheckout_shipping ? '' : ' selected="selected"'; ?>><?php echo $text_select_type; ?></option>
					</select>
				  </div>
				</div>
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-edit-cart"><span title="<?php echo $help_edit_cart; ?>" data-toggle="tooltip"><?php echo $entry_edit_cart; ?></span></label>
				  <div class="col-sm-8">
					<select name="quickcheckout_edit_cart" id="input-edit-cart" class="form-control">
					  <option value="1"<?php echo $quickcheckout_edit_cart ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
					  <option value="0"<?php echo $quickcheckout_edit_cart ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
					</select>
				  </div>
				</div>
			  </div>
			  <div class="row">
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-highlight-error"><span title="<?php echo $help_highlight_error; ?>" data-toggle="tooltip"><?php echo $entry_highlight_error; ?></span></label>
				  <div class="col-sm-8">
					<select name="quickcheckout_highlight_error" id="input-highlight-error" class="form-control">
					  <option value="1"<?php echo $quickcheckout_highlight_error ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
					  <option value="0"<?php echo $quickcheckout_highlight_error ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
					</select>
				  </div>
				</div>
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-text-error"><span title="<?php echo $help_text_error; ?>" data-toggle="tooltip"><?php echo $entry_text_error; ?></span></label>
				  <div class="col-sm-8">
					<select name="quickcheckout_text_error" id="input-text-error" class="form-control">
					  <option value="1"<?php echo $quickcheckout_text_error ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
					  <option value="0"<?php echo $quickcheckout_text_error ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
					</select>
				  </div>
				</div>
			  </div>
			  <div class="row">
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-layout"><span title="<?php echo $help_layout; ?>" data-toggle="tooltip"><?php echo $entry_layout; ?></span></label>
				  <div class="col-sm-8">
					<select name="quickcheckout_layout" id="input-layout" class="form-control">
					  <option value="1"<?php echo $quickcheckout_layout == 1 ? ' selected="selected"' : ''; ?>><?php echo $text_one_column; ?></option>
					  <option value="2"<?php echo $quickcheckout_layout == 2 ? ' selected="selected"' : ''; ?>><?php echo $text_two_column; ?></option>
					  <option value="3"<?php echo $quickcheckout_layout == 3 ? ' selected="selected"' : ''; ?>><?php echo $text_three_column; ?></option>
					</select>
				  </div>
				</div>
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-slide-effect"><span title="<?php echo $help_slide_effect; ?>" data-toggle="tooltip"><?php echo $entry_slide_effect; ?></span></label>
				  <div class="col-sm-8">
					<select name="quickcheckout_slide_effect" id="input-slide-effect" class="form-control">
					  <option value="1"<?php echo $quickcheckout_slide_effect ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
					  <option value="0"<?php echo $quickcheckout_slide_effect ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
					</select>
				  </div>
				</div>
			  </div>
			  <div class="row">
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-minimum-order"><span title="<?php echo $help_minimum_order; ?>" data-toggle="tooltip"><?php echo $entry_minimum_order; ?></span></label>
				  <div class="col-sm-8">
					<input type="text" name="quickcheckout_minimum_order" value="<?php echo $quickcheckout_minimum_order; ?>" class="form-control" />
				  </div>
				</div>
			  </div>
			</div>
			<div class="tab-pane" id="tab-technical">
			  <div class="row">
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-debug"><span title="<?php echo $help_debug; ?>" data-toggle="tooltip"><?php echo $entry_debug; ?></span></label>
				  <div class="col-sm-8">
					<select name="quickcheckout_debug" id="input-debug" class="form-control">
					  <option value="1"<?php echo $quickcheckout_debug ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
					  <option value="0"<?php echo $quickcheckout_debug ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
					</select>
				  </div>
				</div>
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-auto-submit"><span title="<?php echo $help_auto_submit; ?>" data-toggle="tooltip"><?php echo $entry_auto_submit; ?></span></label>
				  <div class="col-sm-8">
					<select name="quickcheckout_auto_submit" id="input-auto-submit" class="form-control">
					  <option value="1"<?php echo $quickcheckout_auto_submit ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
					  <option value="0"<?php echo $quickcheckout_auto_submit ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
					</select>
				  </div>
				</div>
			  </div>
			  <div class="row">
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-responsive"><span title="<?php echo $help_responsive; ?>" data-toggle="tooltip"><?php echo $entry_responsive; ?></span></label>
				  <div class="col-sm-8">
					<select name="quickcheckout_responsive" id="input-responsive" class="form-control">
					  <option value="1"<?php echo $quickcheckout_responsive ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
					  <option value="0"<?php echo $quickcheckout_responsive ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
					</select>
				  </div>
				</div>
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-country-reload"><span title="<?php echo $help_country_reload; ?>" data-toggle="tooltip"><?php echo $entry_country_reload; ?></span></label>
				  <div class="col-sm-8">
					<select name="quickcheckout_country_reload" id="input-country-reload" class="form-control">
					  <option value="1"<?php echo $quickcheckout_country_reload ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
					  <option value="0"<?php echo $quickcheckout_country_reload ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
					</select>
				  </div>
				</div>
			  </div>
			  <div class="row">
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-payment-reload"><span title="<?php echo $help_payment_reload; ?>" data-toggle="tooltip"><?php echo $entry_payment_reload; ?></span></label>
				  <div class="col-sm-8">
					<select name="quickcheckout_payment_reload" id="input-payment-reload" class="form-control">
					  <option value="1"<?php echo $quickcheckout_payment_reload ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
					  <option value="0"<?php echo $quickcheckout_payment_reload ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
					</select>
				  </div>
				</div>
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-shipping-reload"><span title="<?php echo $help_shipping_reload; ?>" data-toggle="tooltip"><?php echo $entry_shipping_reload; ?></span></label>
				  <div class="col-sm-8">
					<select name="quickcheckout_shipping_reload" id="input-shipping-reload" class="form-control">
					  <option value="1"<?php echo $quickcheckout_shipping_reload ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
					  <option value="0"<?php echo $quickcheckout_shipping_reload ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
					</select>
				  </div>
				</div>
			  </div>
			</div>
			<div class="tab-pane table-responsive" id="tab-field">
			  <table class="table table-bordered table-hover table-striped">
				<tr>
				  <td></td>
				  <td class="text-center"><?php echo $text_display; ?></td>
				  <td class="text-center"><?php echo $text_required; ?></td>
				  <td><?php echo $text_default; ?></td>
				  <td><?php echo $text_sort_order; ?></td>
				</tr>
				<?php foreach ($fields as $field) { ?>
				  <?php if ($field == 'country') { ?>
				  <tr>
					<td><?php echo ${'entry_field_' . $field}; ?></td>
					<td class="text-center"><input type="checkbox" name="quickcheckout_field_<?php echo $field; ?>[display]"<?php echo !empty(${'quickcheckout_field_' . $field}['display']) ? ' checked' : ''; ?> /></td>
					<td class="text-center"><input type="checkbox" name="quickcheckout_field_<?php echo $field; ?>[required]"<?php echo !empty(${'quickcheckout_field_' . $field}['required']) ? ' checked' : ''; ?> /></td>
					<td><select name="quickcheckout_field_<?php echo $field; ?>[default]" class="form-control">
						<option value=""><?php echo $text_select; ?></option>
						<?php foreach ($countries as $country) { ?>
						  <?php if (!empty(${'quickcheckout_field_' . $field}['default']) && ${'quickcheckout_field_' . $field}['default'] == $country['country_id']) { ?>
						  <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
						  <?php } else { ?>
						  <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
						  <?php } ?>
						<?php } ?>
					  </select></td>
					<td><input type="text" name="quickcheckout_field_<?php echo $field; ?>[sort_order]" value="<?php echo isset(${'quickcheckout_field_' . $field}['sort_order']) ? ${'quickcheckout_field_' . $field}['sort_order'] : ''; ?>" class="form-control" /></td>
				  </tr>
				  <?php } elseif ($field == 'zone') { ?>
				  <tr>
					<td><?php echo ${'entry_field_' . $field}; ?></td>
					<td class="text-center"><input type="checkbox" name="quickcheckout_field_<?php echo $field; ?>[display]"<?php echo !empty(${'quickcheckout_field_' . $field}['display']) ? ' checked' : ''; ?> /></td>
					<td class="text-center"><input type="checkbox" name="quickcheckout_field_<?php echo $field; ?>[required]"<?php echo !empty(${'quickcheckout_field_' . $field}['required']) ? ' checked' : ''; ?> /></td>
					<td><select name="quickcheckout_field_<?php echo $field; ?>[default]" class="form-control"></select></td>
					<td><input type="text" name="quickcheckout_field_<?php echo $field; ?>[sort_order]" value="<?php echo isset(${'quickcheckout_field_' . $field}['sort_order']) ? ${'quickcheckout_field_' . $field}['sort_order'] : ''; ?>" class="form-control" /></td>
				  </tr>
				  <?php } elseif ($field == 'customer_group') { ?>
				  <tr>
					<td><?php echo ${'entry_field_' . $field}; ?></td>
					<td class="text-center"><input type="checkbox" name="quickcheckout_field_<?php echo $field; ?>[display]"<?php echo !empty(${'quickcheckout_field_' . $field}['display']) ? ' checked' : ''; ?> /></td>
					<td class="text-center">NA</td>
					<td class="text-center">NA</td>
					<td><input type="text" name="quickcheckout_field_<?php echo $field; ?>[sort_order]" value="<?php echo isset(${'quickcheckout_field_' . $field}['sort_order']) ? ${'quickcheckout_field_' . $field}['sort_order'] : ''; ?>" class="form-control" /></td>
				  </tr>
				  <?php } elseif ($field == 'register' || $field == 'newsletter') { ?>
				  <tr>
					<td><?php echo ${'entry_field_' . $field}; ?></td>
					<td class="text-center"><input type="checkbox" name="quickcheckout_field_<?php echo $field; ?>[display]"<?php echo !empty(${'quickcheckout_field_' . $field}['display']) ? ' checked' : ''; ?> /></td>
					<td class="text-center"><input type="checkbox" name="quickcheckout_field_<?php echo $field; ?>[required]"<?php echo !empty(${'quickcheckout_field_' . $field}['required']) ? ' checked' : ''; ?> /></td>
					<td class="text-center"><input type="checkbox" name="quickcheckout_field_<?php echo $field; ?>[default]"<?php echo !empty(${'quickcheckout_field_' . $field}['default']) ? ' checked' : ''; ?> /></td>
					<td><input type="text" name="quickcheckout_field_<?php echo $field; ?>[sort_order]" value="" style="display:none;" /></td>
				  </tr>
				  <?php } else { ?>
				  <tr>
					<td><?php echo ${'entry_field_' . $field}; ?></td>
					<td class="text-center"><input type="checkbox" name="quickcheckout_field_<?php echo $field; ?>[display]"<?php echo !empty(${'quickcheckout_field_' . $field}['display']) ? ' checked' : ''; ?> /></td>
					<?php if ($field == 'postcode') { ?>
					<td class="text-center">NA</td>
					<?php } else { ?>
					<td class="text-center"><input type="checkbox" name="quickcheckout_field_<?php echo $field; ?>[required]"<?php echo !empty(${'quickcheckout_field_' . $field}['required']) ? ' checked' : ''; ?> /></td>
					<?php } ?>
					<td><input type="text" name="quickcheckout_field_<?php echo $field; ?>[default]" value="<?php echo !empty(${'quickcheckout_field_' . $field}['default']) ? ${'quickcheckout_field_' . $field}['default'] : ''; ?>" class="form-control" /></td>
					<td><input type="text" name="quickcheckout_field_<?php echo $field; ?>[sort_order]" value="<?php echo isset(${'quickcheckout_field_' . $field}['sort_order']) ? ${'quickcheckout_field_' . $field}['sort_order'] : ''; ?>" class="form-control" /></td>
				  </tr>
				  <?php } ?>
				<?php } ?>
			  </table>
			</div>
			<div class="tab-pane" id="tab-module">
			  <div class="row">
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-coupon"><span title="<?php echo $help_coupon; ?>" data-toggle="tooltip"><?php echo $entry_coupon; ?></span></label>
				  <div class="col-sm-8">
					<select name="quickcheckout_coupon" id="input-coupon" class="form-control">
					  <option value="1"<?php echo $quickcheckout_coupon ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
					  <option value="0"<?php echo $quickcheckout_coupon ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
					</select>
				  </div>
				</div>
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-voucher"><span title="<?php echo $help_voucher; ?>" data-toggle="tooltip"><?php echo $entry_voucher; ?></span></label>
				  <div class="col-sm-8">
					<select name="quickcheckout_voucher" id="input-voucher" class="form-control">
					  <option value="1"<?php echo $quickcheckout_voucher ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
					  <option value="0"<?php echo $quickcheckout_voucher ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
					</select>
				  </div>
				</div>
			  </div>
			  <div class="row">
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-reward"><span title="<?php echo $help_reward; ?>" data-toggle="tooltip"><?php echo $entry_reward; ?></span></label>
				  <div class="col-sm-8">
					<select name="quickcheckout_reward" id="input-reward" class="form-control">
					  <option value="1"<?php echo $quickcheckout_reward ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
					  <option value="0"<?php echo $quickcheckout_reward ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
					</select>
				  </div>
				</div>
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-cart"><span title="<?php echo $help_cart; ?>" data-toggle="tooltip"><?php echo $entry_cart; ?></span></label>
				  <div class="col-sm-8">
					<select name="quickcheckout_cart" id="input-cart" class="form-control">
					  <option value="1"<?php echo $quickcheckout_cart ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
					  <option value="0"<?php echo $quickcheckout_cart ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
					</select>
				  </div>
				</div>
			  </div>
			  <div class="row">
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-shipping-module"><span title="<?php echo $help_shipping_module; ?>" data-toggle="tooltip"><?php echo $entry_shipping_module; ?></span></label>
				  <div class="col-sm-8">
					<select name="quickcheckout_shipping_module" id="input-shipping-module" class="form-control">
					  <option value="1"<?php echo $quickcheckout_shipping_module ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
					  <option value="0"<?php echo $quickcheckout_shipping_module ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
					</select>
				  </div>
				</div>
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-payment-module"><span title="<?php echo $help_payment_module; ?>" data-toggle="tooltip"><?php echo $entry_payment_module; ?></span></label>
				  <div class="col-sm-8">
					<select name="quickcheckout_payment_module" id="input-payment-module" class="form-control">
					  <option value="1"<?php echo $quickcheckout_payment_module ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
					  <option value="0"<?php echo $quickcheckout_payment_module ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
					</select>
				  </div>
				</div>
			  </div>
			  <div class="row">
				<div class="form-group col-sm-12">
				  <label class="col-sm-2 control-label" for="input-login-module"><span title="<?php echo $help_login_module; ?>" data-toggle="tooltip"><?php echo $entry_login_module; ?></span></label>
				  <div class="col-sm-10">
					<select name="quickcheckout_login_module" id="input-login-module" class="form-control">
					  <option value="1"<?php echo $quickcheckout_login_module ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
					  <option value="0"<?php echo $quickcheckout_login_module ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
					</select>
				  </div>
				</div>
			  </div>
			  <div class="row">
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-html-header"><span title="<?php echo $help_html_header; ?>" data-toggle="tooltip"><?php echo $entry_html_header; ?></span></label>
				  <div class="col-sm-8">
					<?php foreach ($languages as $language) { ?>
					  <div class="input-group">
						<span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
						<textarea name="quickcheckout_html_header[<?php echo $language['language_id']; ?>]" rows="5" cols="30" class="form-control"><?php echo !empty($quickcheckout_html_header[$language['language_id']]) ? $quickcheckout_html_header[$language['language_id']] : ''; ?></textarea>
					  </div>
					<?php } ?>
				  </div>
				</div>
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-html-footer"><span title="<?php echo $help_html_footer; ?>" data-toggle="tooltip"><?php echo $entry_html_footer; ?></span></label>
				  <div class="col-sm-8">
					<?php foreach ($languages as $language) { ?>
					  <div class="input-group">
						<span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
						<textarea name="quickcheckout_html_footer[<?php echo $language['language_id']; ?>]" rows="5" cols="30" class="form-control"><?php echo !empty($quickcheckout_html_footer[$language['language_id']]) ? $quickcheckout_html_footer[$language['language_id']] : ''; ?></textarea>
					  </div>
					<?php } ?>
				  </div>
				</div>
			  </div>
			</div>
			<div class="tab-pane" id="tab-survey">
			  <div class="row">
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-survey"><?php echo $entry_survey; ?></label>
				  <div class="col-sm-8">
					<select name="quickcheckout_survey" id="input-survey" class="form-control">
					  <option value="1"<?php echo $quickcheckout_survey ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
					  <option value="0"<?php echo $quickcheckout_survey ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
					</select>
				  </div>
				</div>
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-survey-required"><span title="<?php echo $help_survey_required; ?>" data-toggle="tooltip"><?php echo $entry_survey_required; ?></span></label>
				  <div class="col-sm-8">
					<select name="quickcheckout_survey_required" id="input-survey-required" class="form-control">
					  <option value="1"<?php echo $quickcheckout_survey_required ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
					  <option value="0"<?php echo $quickcheckout_survey_required ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
					</select>
				  </div>
				</div>
			  </div>
			  <div class="row">
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-survey-text"><span title="<?php echo $help_survey_text; ?>" data-toggle="tooltip"><?php echo $entry_survey_text; ?></span></label>
				  <div class="col-sm-8">
					<?php foreach ($languages as $language) { ?>
					  <div class="input-group">
						<span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
						<input type="text" name="quickcheckout_survey_text[<?php echo $language['language_id']; ?>]" value="<?php echo !empty($quickcheckout_survey_text[$language['language_id']]) ? $quickcheckout_survey_text[$language['language_id']] : ''; ?>" class="form-control" />
					  </div>
					<?php } ?>
				  </div>
				</div>
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-survey-type"><span title="<?php echo $help_survey_type; ?>" data-toggle="tooltip"><?php echo $entry_survey_type; ?></span></label>
				  <div class="col-sm-8">
					<select name="quickcheckout_survey_type" id="input-survey-type" class="form-control">
					  <option value="1"<?php echo $quickcheckout_survey_type ? ' selected="selected"' : ''; ?>><?php echo $text_select_type; ?></option>
					  <option value="0"<?php echo $quickcheckout_survey_type ? '' : ' selected="selected"'; ?>><?php echo $text_text_type; ?></option>
					</select>
				  </div>
				</div>
			  </div>
			  <div class="table-responsive">
				<table id="survey-answer" class="table table-hover table-bordered">
				  <thead>
				  <tr>
					<td class="text-left" colspan="2"><?php echo $entry_survey_answer; ?></td>
				  </tr>
				  </thead>
				  <tbody>
				  <?php $survey_answer_row = 0; ?>
				  <?php foreach ($quickcheckout_survey_answers as $survey_answer) { ?>
				  <tr id="survey-answer-<?php echo $survey_answer_row; ?>">
					<td class="text-left"><?php foreach ($languages as $language) { ?>
					  <div class="input-group">
						<span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
						<input type="text-text" name="quickcheckout_survey_answers[<?php echo $survey_answer_row; ?>][<?php echo $language['language_id']; ?>]" value="<?php echo !empty($survey_answer[$language['language_id']]) ? $survey_answer[$language['language_id']] : ''; ?>" class="form-control" />
					  </div>
					<?php } ?></td>
					<td class="text-right"><a class="btn btn-danger" onClick="$('#survey-answer-<?php echo $survey_answer_row; ?>').remove();"><?php echo $button_remove; ?></a></td>
					<?php $survey_answer_row++; ?>
				  </tr>
				  <?php } ?>
				  </tbody>
				  <tfoot>
				  <tr>
					<td class="text-right" colspan="2"><a class="btn btn-success" onClick="addAnswer();"><?php echo $button_add; ?></a></td>
				  </tr>
				  </tfoot>
				</table>
			  </div>
			</div>
			<div class="tab-pane" id="tab-delivery">
			  <div class="row">
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-delivery"><span title="<?php echo $help_delivery; ?>" data-toggle="tooltip"><?php echo $entry_delivery; ?></span></label>
				  <div class="col-sm-8">
					<select name="quickcheckout_delivery" id="input-delivery" class="form-control">
					  <option value="1"<?php echo $quickcheckout_delivery ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
					  <option value="0"<?php echo $quickcheckout_delivery ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
					</select>
				  </div>
				</div>
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-delivery-time"><span title="<?php echo $help_delivery_time; ?>" data-toggle="tooltip"><?php echo $entry_delivery_time; ?></span></label>
				  <div class="col-sm-8">
					<select name="quickcheckout_delivery_time" id="input-delivery-time" class="form-control">
					  <option value="1"<?php echo $quickcheckout_delivery_time == 1 ? ' selected="selected"' : ''; ?>><?php echo $text_choose; ?></option>
					  <option value="2"<?php echo $quickcheckout_delivery_time == 2 ? ' selected="selected"' : ''; ?>><?php echo $text_estimate; ?></option>
					  <option value="3"<?php echo $quickcheckout_delivery_time == 3 ? ' selected="selected"' : ''; ?>><?php echo $text_select_type; ?></option>
					  <option value="0"<?php echo $quickcheckout_delivery_time == 0 ? ' selected="selected"' : ''; ?>><?php echo $text_disabled; ?></option>
					</select>
				  </div>
				</div>
			  </div>
			  <div class="row">
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-delivery-required"><span title="<?php echo $help_delivery_required; ?>" data-toggle="tooltip"><?php echo $entry_delivery_required; ?></span></label>
				  <div class="col-sm-8">
					<select name="quickcheckout_delivery_required" id="input-delivery-required" class="form-control">
					  <option value="1"<?php echo $quickcheckout_delivery_required ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
					  <option value="0"<?php echo $quickcheckout_delivery_required ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
					</select>
				  </div>
				</div>
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-delivery-unavailable"><span title="<?php echo $help_delivery_unavailable; ?>" data-toggle="tooltip"><?php echo $entry_delivery_unavailable; ?></span></label>
				  <div class="col-sm-8">
					<textarea name="quickcheckout_delivery_unavailable" rows="5" class="form-control"><?php echo $quickcheckout_delivery_unavailable; ?></textarea>
				  </div>
				</div>
			  </div>
			  <div class="row">
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-delivery-min"><span title="<?php echo $help_delivery_min; ?>" data-toggle="tooltip"><?php echo $entry_delivery_min; ?></span></label>
				  <div class="col-sm-8">
					<input type="text" name="quickcheckout_delivery_min" value="<?php echo $quickcheckout_delivery_min; ?>" class="form-control" />
				  </div>
				</div>
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-delivery-max"><span title="<?php echo $help_delivery_max; ?>" data-toggle="tooltip"><?php echo $entry_delivery_max; ?></span></label>
				  <div class="col-sm-8">
					<input type="text" name="quickcheckout_delivery_max" value="<?php echo $quickcheckout_delivery_max; ?>" class="form-control" />
				  </div>
				</div>
			  </div>
			  <div class="row">
				<div class="form-group col-sm-12">
				  <label class="col-sm-2 control-label" for="input-delivery-days-of-week"><span title="<?php echo $help_delivery_days_of_week; ?>" data-toggle="tooltip"><?php echo $entry_delivery_days_of_week; ?></span></label>
				  <div class="col-sm-10">
					<input type="text" name="quickcheckout_delivery_days_of_week" value="<?php echo $quickcheckout_delivery_days_of_week; ?>" class="form-control" />
				  </div>
				</div>
			  </div>
			  <div class="table-responsive">
				<table id="delivery-time" class="table table-bordered table-hover">
				  <thead>
				  <tr>
					<td class="text-left" colspan="2"><?php echo $entry_delivery_times; ?></td>
				  </tr>
				  </thead>
				  <tbody>
				  <?php $delivery_time_row = 0; ?>
				  <?php foreach ($quickcheckout_delivery_times as $delivery_time) { ?>
				  <tr id="delivery-time-<?php echo $delivery_time_row; ?>">
				    <td class="text-left"><?php foreach ($languages as $language) { ?>
					  <div class="input-group">
						<span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
						<input type="text" name="quickcheckout_delivery_times[<?php echo $delivery_time_row; ?>][<?php echo $language['language_id']; ?>]" value="<?php echo !empty($delivery_time[$language['language_id']]) ? $delivery_time[$language['language_id']] : ''; ?>" class="form-control" />
					  </div>
					<?php } ?></td>
					<td class="text-right"><a class="btn btn-danger" onClick="$('#delivery-time-<?php echo $delivery_time_row; ?>').remove();"><?php echo $button_remove; ?></a></td>
					<?php $delivery_time_row++; ?>
				  </tr>
				  <?php } ?>
				  </tbody>
				  <tfoot>
				  <tr>
				    <td class="text-right" colspan="2"><a class="btn btn-success" onClick="addTime();"><?php echo $button_add; ?></a></td>
				  </tr>
				  </tfoot>
				</table>
			  </div>
			</div>
			<div class="tab-pane" id="tab-countdown">
			  <div class="row">
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-countdown"><span title="<?php echo $help_countdown; ?>" data-toggle="tooltip"><?php echo $entry_countdown; ?></span></label>
				  <div class="col-sm-8">
					<select name="quickcheckout_countdown" id="input-countdown" class="form-control">
					  <option value="1"<?php echo $quickcheckout_countdown ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
					  <option value="0"<?php echo $quickcheckout_countdown ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
					</select>
				  </div>
				</div>
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-countdown-start"><span title="<?php echo $help_countdown_start; ?>" data-toggle="tooltip"><?php echo $entry_countdown_start; ?></span></label>
				  <div class="col-sm-8">
					<select name="quickcheckout_countdown_start" id="input-countdown-start" class="form-control">
					  <option value="1"<?php echo $quickcheckout_countdown_start ? ' selected="selected"' : ''; ?>><?php echo $text_day; ?></option>
					  <option value="0"<?php echo $quickcheckout_countdown_start ? '' : ' selected="selected"'; ?>><?php echo $text_specific; ?></option>
					</select>
				  </div>
				</div>
			  </div>
			  <div class="row" id="countdown-date">
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-countdown-date-start"><span title="<?php echo $help_countdown_date_start; ?>" data-toggle="tooltip"><?php echo $entry_countdown_date_start; ?></span></label>
				  <div class="col-sm-8">
					<input type="text" name="quickcheckout_countdown_date_start" value="<?php echo $quickcheckout_countdown_date_start; ?>" class="date form-control" date-date-format="YYYY-MM-DD HH:mm:ss" />
				  </div>
				</div>
				<div class="form-group col-sm-6">
				  <label class="col-sm-4 control-label" for="input-countdown-date-end"><span title="<?php echo $help_countdown_date_end; ?>" data-toggle="tooltip"><?php echo $entry_countdown_date_end; ?></span></label>
				  <div class="col-sm-8">
					<input type="text" name="quickcheckout_countdown_date_end" value="<?php echo $quickcheckout_countdown_date_end; ?>" class="date form-control" date-date-format="YYYY-MM-DD HH:mm:ss" />
				  </div>
				</div>
			  </div>
			  <div class="row" id="countdown-time">
				<div class="form-group col-sm-12">
				  <label class="col-sm-2 control-label" for="input-countdown-time"><span title="<?php echo $help_countdown_time; ?>" data-toggle="tooltip"><?php echo $entry_countdown_time; ?></span></label>
				  <div class="col-sm-10">
					<input type="text" name="quickcheckout_countdown_time" value="<?php echo $quickcheckout_countdown_time; ?>" class="form-control" />
				  </div>
				</div>
			  </div>
			  <div class="row">
				<div class="form-group col-sm-12">
				  <label class="col-sm-2 control-label" for="input-countdown-text"><span title="<?php echo $help_countdown_text; ?>" data-toggle="tooltip"><?php echo $entry_countdown_text; ?></span></label>
				  <div class="col-sm-10">
					<?php foreach ($languages as $language) { ?>
					  <div class="input-group">
					    <span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
						<textarea name="quickcheckout_countdown_text[<?php echo $language['language_id']; ?>]" class="form-control" rows="5"><?php echo !empty($quickcheckout_countdown_text[$language['language_id']]) ? $quickcheckout_countdown_text[$language['language_id']] : ''; ?></textarea>
					  </div>
					<?php } ?>
				  </div>
				</div>
			  </div>
			</div>
			<?php require_once(DIR_TEMPLATE . 'module/quickcheckout_about.tpl'); ?>
		  </div>
		</form>
      </div>
    </div>
	<div style="color:#222222;text-align:center;"><?php echo $heading_title; ?> v<?php echo $version; ?> by <a href="http://www.marketinsg.com" target="_blank">MarketInSG</a></div>
  </div>
</div>
<script type="text/javascript"><!--
function show(element) {
	$(element).tab('show');

	$('a[href=\'' + element + '\']').parent('li').siblings().removeClass('active');

	$('a[href=\'' + element + '\']').parent('li').addClass('active');

	return false;
}

$(document).ready(function() {
	$('.date').datetimepicker();
});

$('select[name=\'quickcheckout_field_country[default]\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=module/quickcheckout/country&token=<?php echo $token; ?>&country_id=' + this.value,
		dataType: 'json',
		success: function(json) {
			html = '<option value=""><?php echo $text_select; ?></option>';

			if (json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
        			html += '<option value="' + json['zone'][i]['zone_id'] + '"';

					if (json['zone'][i]['zone_id'] == '<?php echo !empty($quickcheckout_field_zone['default']) ? $quickcheckout_field_zone['default'] : ''; ?>') {
	      				html += ' selected="selected"';
	    			}

	    			html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}

			$('select[name=\'quickcheckout_field_zone[default]\']').html(html);
		}
	});
});

$('select[name=\'quickcheckout_field_country[default]\']').trigger('change');

$('select[name=\'quickcheckout_countdown_start\']').change(function() {
	if ($('select[name=\'quickcheckout_countdown_start\']').val() == '1') {
		$('#countdown-time').fadeIn();
		$('#countdown-date').fadeOut();
	} else {
		$('#countdown-date').fadeIn();
		$('#countdown-time').fadeOut();
	}
});

$('select[name=\'quickcheckout_countdown_start\']').trigger('change');

$('select[name=\'quickcheckout_survey_type\']').change(function() {
	if ($('select[name=\'quickcheckout_survey_type\']').val() == '1') {
		$('#survey-answer').fadeIn();
	} else {
		$('#survey-answer').fadeOut();
	}
});

$('select[name=\'quickcheckout_survey_type\']').trigger('change');

var survey_answer_row = <?php echo $survey_answer_row; ?>;

function addAnswer() {
	html  = '<tr id="survey-answer-' + survey_answer_row + '">';
	html += '  <td class="left">';
	<?php foreach ($languages as $language) { ?>
	html += '<div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>';
	html += '<input type="text-text" name="quickcheckout_survey_answers[' + survey_answer_row + '][<?php echo $language['language_id']; ?>]" value="" class="form-control" />';
	html += '</div>';
	<?php } ?>
	html += '  </td>';
	html += '  <td class="text-right"><a class="btn btn-danger" onClick="$(\'#survey-answer-' + survey_answer_row + '\').remove();"><?php echo $button_remove; ?></a></td>';
	html += '</tr>';

	$('#survey-answer tbody').append(html);

	survey_answer_row++;
}

$('select[name=\'quickcheckout_delivery_time\']').change(function() {
	if ($('select[name=\'quickcheckout_delivery_time\']').val() == '3') {
		$('#delivery-time').fadeIn();
	} else {
		$('#delivery-time').fadeOut();
	}
});

$('select[name=\'quickcheckout_delivery_time\']').trigger('change');

var delivery_time_row = <?php echo $delivery_time_row; ?>;

function addTime() {
	html  = '<tr id="delivery-time-' + delivery_time_row + '">';
	html += '  <td class="left">';
	<?php foreach ($languages as $language) { ?>
	html += '<div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>';
	html += '<input type="text" name="quickcheckout_delivery_times[' + delivery_time_row + '][<?php echo $language['language_id']; ?>]" value="" class="form-control" />';
	html += '</div>';
	<?php } ?>
	html += '  </td>';
	html += '  <td class="text-right"><a class="btn btn-danger" onClick="$(\'#delivery-time-' + delivery_time_row + '\').remove();"><?php echo $button_remove; ?></a></td>';
	html += '</tr>';

	$('#delivery-time tbody').append(html);

	delivery_time_row++;
}

function store() {
	location = 'index.php?route=module/quickcheckout&token=<?php echo $token; ?>&store_id=' + $('select[name=\'store_id\']').val();
}
//--></script>
<?php echo $footer; ?>