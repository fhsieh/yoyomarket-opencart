<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<!-- yym_custom: fix span->div -->
<div id="modal-info" class="modal <?php if ($OC_V2) echo ' fade'; ?>" tabindex="-1" role="dialog" aria-hidden="true"><div class="modalContent"></div></div>
<?php if ($OC_V2) { ?>
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $button_save; ?></button><!-- yym -->
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i> <?php echo $button_cancel; ?></a><!-- yym -->
			</div>
			<h1><?php echo $heading_title; ?></h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
					<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
<?php } else { ?>
	<div class="breadcrumb">
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
	<?php } ?>
	</div>
<?php } ?>
  <div class="container-fluid">
	<?php if (isset($success) && $success) { ?><div class="alert alert-success success"><i class="fa fa-check-circle"></i> <?php echo $success; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div><script type="text/javascript">setTimeout("$('.alert-success').slideUp();",5000);</script><?php } ?>
	<?php if (isset($info) && $info) { ?><div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $info; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div><?php } ?>
	<?php if (isset($error) && $error) { ?><div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div><?php } ?>
    <?php if (isset($error_warning) && $error_warning) { ?><div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div><?php } ?>
<div class="<?php if(!$OC_V2) echo 'box'; ?> panel panel-default">
	<div class="heading panel-heading">
		<h3 class="panel-title"><i class="fa fa-forward"></i> <?php echo $heading_title; ?></h3>
		<div class="buttons"><a onclick="$('#form').submit();" class="button blue"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button red"><span><?php echo $button_cancel; ?></span></a></div>
	</div>
	<div class="content panel-body">
		<ul class="nav nav-tabs">
    		<li class="active"><a href="#tab-0" data-toggle="tab"><i class="fa fa-cog"></i><?php echo $_language->get('text_tab_0'); ?></a></li>
			<li><a href="#tab-1" data-toggle="tab"><i class="fa fa-map-marker"></i><?php echo $_language->get('text_tab_1'); ?></a></li>
			<li><a href="#tab-2" data-toggle="tab"><i class="fa fa-list"></i><?php echo $_language->get('text_tab_2'); ?></a></li>
			<li><a href="#tab-3" data-toggle="tab"><i class="fa fa-tags"></i><?php echo $_language->get('text_tab_3'); ?></a></li>
			<li><a href="#tab-about" data-toggle="tab"><i class="fa fa-info"></i><?php echo $_language->get('text_tab_about'); ?></a></li>
		</ul>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
		<div class="tab-content">
		<div class="tab-pane active" id="tab-0">
			<table class="form">
				<tr>
				  <td>
            <button type="button" class="btn btn-default btn-xs info-btn" data-toggle="modal" data-target="#modal-info" data-info="color_mode"><i class="fa fa-info"></i></button>
            <?php echo $_language->get('entry_bg_mode'); ?>
          </td>
				  <td>
            <select name="qosu_bg_mode" class="form-control">
              <option value="" <?php if($qosu_bg_mode == '') echo 'selected="selected"'; ?>><?php echo $_language->get('entry_bg_mode_text'); ?></option>
              <option value="cell" <?php if($qosu_bg_mode == 'cell') echo 'selected="selected"'; ?>><?php echo $_language->get('entry_bg_mode_cell'); ?></option>
              <option value="row" <?php if($qosu_bg_mode == 'row') echo 'selected="selected"'; ?>><?php echo $_language->get('entry_bg_mode_row'); ?></option>
            </select>
          </td>
				</tr>
        <tr>
				  <td><?php echo $_language->get('entry_notify'); ?></td>
				  <td><input class="switch" type="checkbox" name="qosu_notify" id="qosu_notify" value="1" <?php if($qosu_notify) echo 'checked="checked"'; ?>/></td>
				</tr>
        <tr>
				  <td><?php echo $_language->get('entry_barcode'); ?></td>
				  <td><input class="switch" type="checkbox" name="qosu_barcode" id="qosu_barcode" value="1" <?php if($qosu_barcode) echo 'checked="checked"'; ?>/></td>
				</tr>
        <tr>
				  <td><?php echo $_language->get('entry_barcode_enabled'); ?></td>
				  <td><input class="switch" type="checkbox" name="qosu_barcode_enabled" id="qosu_barcode_enabled" value="1" <?php if($qosu_barcode_enabled) echo 'checked="checked"'; ?>/></td>
				</tr>
        <tr>
				  <td>
            <button type="button" class="btn btn-default btn-xs info-btn" data-toggle="modal" data-target="#modal-info" data-info="extra_info"><i class="fa fa-info"></i></button>
            <?php echo $_language->get('entry_extra_info'); ?>
          </td>
				  <td>
            <input type="text" class="form-control" name="qosu_extra_info" value="<?php echo $qosu_extra_info; ?>" />
          </td>
				</tr>
			</table>
		</div>

		<div class="tab-pane clearfix" id="tab-1">
			<input type="hidden" name="qosu_shipping" value=""/>
			<ul id="qosu_shipping" class="nav nav-pills nav-stacked col-md-2">
			  <?php $shipping_row = 1; ?>
			  <?php foreach ($qosu_shipping as $shipping) { ?>
				<li <?php if($shipping_row === 1) echo 'class="active"'; ?> id="shipping-<?php echo $shipping_row; ?>"><a href="#tab-shipping-<?php echo $shipping_row; ?>" data-toggle="pill"><?php echo $shipping['title']; ?>&nbsp;<i class="fa fa-minus-circle" onclick="$('#shipping-<?php echo $shipping_row; ?>').remove(); $('#tab-shipping-<?php echo $shipping_row; ?>').remove(); $('#qosu_shipping a:first').trigger('click'); return false;"></i></a></li>
				<?php $shipping_row++; ?>
			  <?php } ?>
			  <li id="shipping-add"><a><?php echo $_language->get('tab_add_shipping'); ?>&nbsp;<i class="fa fa-plus-circle" onclick="addShipping();"></i></a></li>
			</ul>
			<div class="tab-content col-md-10">
			<?php $shipping_row = 1; ?>
			<?php foreach ($qosu_shipping as $shipping) { ?>
			<div class="tab-pane <?php if($shipping_row === 1) echo ' active'; ?>" id="tab-shipping-<?php echo $shipping_row; ?>">
			  <table class="form">
				<tr>
				  <td><?php echo $_language->get('entry_shipping_title'); ?></td>
				  <td><input type="text" class="form-control" name="qosu_shipping[<?php echo $shipping_row; ?>][title]" value="<?php echo $shipping['title']; ?>" size="50"/></td>
				</tr>
				<tr>
				  <td>
            <button type="button" class="btn btn-default btn-xs info-btn" data-toggle="modal" data-target="#modal-info" data-info="tracking_url"><i class="fa fa-info"></i></button>
            <?php echo $_language->get('entry_shipping_url'); ?>
          </td>
				  <td><input type="text" class="form-control" name="qosu_shipping[<?php echo $shipping_row; ?>][url]" value="<?php echo $shipping['url']; ?>" size="50"/></td>
				</tr>
			  </table>
			  <table class="form">
				<tr class="info">
					<td><i class='iconic info'></i></td>
					<td colspan="2" style="color:#555;padding:40px 100px 40px 0;"><?php echo $_language->get('text_info_tracking'); ?></td>
				</tr>
				</table>
			</div>
			<?php $shipping_row++; ?>
			<?php } ?>
		</div>
		</div>
		<div class="tab-pane clearfix" id="tab-2">
			<ul id="qosu_statuses" class="nav nav-pills nav-stacked col-md-2">
			  <?php $f=1; foreach ($order_statuses as $status) { ?>
				<li <?php if($f) echo 'class="active"'; $f=0; ?>><a href="#tab-status-<?php echo $status['order_status_id']; ?>" id="status-<?php echo $status['order_status_id']; ?>" data-toggle="pill" <?php if(isset($status['color']) && $status['color'] != '#000000') { ?>style="color:<?php echo $status['color']; ?>"<?php } ?>><i class="fa fa-arrows-v"></i><?php echo $status['name']; ?></a></li>
			  <?php } ?>
			 </ul>
			 <div class="tab-content col-md-10">
				<?php $f=1; foreach ($order_statuses as $status) { ?>
				<div id="tab-status-<?php echo $status['order_status_id']; ?>" class="tab-pane <?php if($f) echo ' active'; $f=0; ?>">
				  <ul id="status-lang-<?php echo $status['order_status_id']; ?>" class="nav nav-tabs">
					<?php $f=1; foreach ($languages as $language) { ?>
					<li <?php if($f) echo 'class="active"'; $f=0; ?>><a href="#tab-status-lang-<?php echo $status['order_status_id']; ?>-<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" alt=""/> <?php echo $language['name']; ?></a></li>
					<?php } ?>
				  </ul>
				  <div class="tab-content">
					  <?php $f=1; foreach ($languages as $language) { ?>
					  <div id="tab-status-lang-<?php echo $status['order_status_id']; ?>-<?php echo $language['language_id']; ?>" class="tab-pane <?php if($f) echo ' active'; $f=0; ?>">
						<table class="form">
						  <tr>
							<td>
                <button type="button" class="btn btn-default btn-xs info-btn" data-toggle="modal" data-target="#modal-info" data-info="tags_full"><i class="fa fa-info"></i></button>
                <?php echo $_language->get('entry_message'); ?>
              </td>
							<td><textarea class="form-control" name="qosu_order_statuses[<?php echo $status['order_status_id']; ?>][description][<?php echo $language['language_id']; ?>]" id="description-<?php echo $status['order_status_id']; ?>-<?php echo $language['language_id']; ?>" rows="12" cols="120"><?php echo isset($status['description'][$language['language_id']]) ? $status['description'][$language['language_id']] : ''; ?></textarea></td>
						  </tr>
						</table>
					  </div>
					  <?php } ?>
				  </div>
				  <table class="form">
					<tr>
						  <td><?php echo $_language->get('entry_next_status'); ?></td>
						  <td><select name="qosu_order_statuses[<?php echo $status['order_status_id']; ?>][next_status]" class="form-control">
							  <?php foreach ($order_statuses as $s) { ?>
							  <?php if (isset($status['next_status']) && ($s['order_status_id'] == $status['next_status'])) { ?>
							  <option value="<?php echo $s['order_status_id']; ?>" <?php if(isset($s['color']) && $s['color'] != '#000000') { ?>style="color:<?php echo $s['color']; ?>"<?php } ?> selected="selected"><?php echo $s['name']; ?></option>
							  <?php } else { ?>
							  <option value="<?php echo $s['order_status_id']; ?>" <?php if(isset($s['color']) && $s['color'] != '#000000') { ?>style="color:<?php echo $s['color']; ?>"<?php } ?>><?php echo $s['name']; ?></option>
							  <?php } ?>
							  <?php } ?>
							</select></td>
						</tr>
						<tr>
							<td><?php echo $_language->get('entry_status_color'); ?></td>
							<td><input name="qosu_order_statuses[<?php echo $status['order_status_id']; ?>][color]" class="colorpicker" value="<?php echo isset($status['color']) ? $status['color'] : '#000000'; ?>" /></td>
						  </tr>
				  </table>
				  <input type="hidden" name="qosu_order_statuses[<?php echo $status['order_status_id']; ?>][sort_order]" value="<?php echo isset($status['sort_order']) ? $status['sort_order'] : ''; ?>"/>
				</div>
				<?php } ?>
			</div>
		</div>

		<div class="tab-pane clearfix" id="tab-3">
			<input type="hidden" name="qosu_inputs" value=""/>
			<ul id="qosu_inputs" class="nav nav-pills nav-stacked col-md-2">
			  <?php $module_row = 1; ?>
			  <?php foreach ($qosu_inputs as $module) { ?>
			  <li <?php if($shipping_row === 1) echo 'class="active"'; ?>><a href="#tab-module-<?php echo $module_row; ?>" id="module-<?php echo $module_row; ?>"><?php echo $module['title']; ?>&nbsp;<i class="fa fa-minus-circle" onclick="$('#module-<?php echo $module_row; ?>').remove(); $('#tab-module-<?php echo $module_row; ?>').remove(); $('#qosu_inputs a:first').trigger('click'); return false;"></i></a></li>
			  <?php $module_row++; ?>
			  <?php } ?>
			  <li id="module-add"><a><?php echo $_language->get('tab_add_input'); ?>&nbsp;<i class="fa fa-plus-circle" onclick="addModule();"></i></a></li>
			</ul>
			<div class="tab-content col-md-10">
				<?php $module_row = 1; ?>
				<?php foreach ($qosu_inputs as $module) { ?>
				<div id="tab-module-<?php echo $module_row; ?>" class="tab-pane <?php if($module_row === 1) echo ' active'; ?>">
				  <table class="form">
					<tr>
					  <td><?php echo $_language->get('entry_input_title'); ?></td>
					  <td><input type="text" class="form-control" name="qosu_inputs[<?php echo $module_row; ?>][title]" value="<?php echo $module['title']; ?>" size="50"/></td>
					</tr>
					<tr class="form-inline">
					  <td><?php echo $_language->get('entry_input_tag'); ?></td>
					  <td><div class="input-group"><span class="input-group-addon">{</span><input type="text" class="form-control" name="qosu_inputs[<?php echo $module_row; ?>][tag]" value="<?php echo $module['tag']; ?>"/><span class="input-group-addon">}</span></div></td>
					</tr>
				  </table>
				</div>
				<?php $module_row++; ?>
				<?php } ?>
			</div>
		</div>
		<div class="tab-pane" id="tab-about">
			<table class="form about">
				<tr>
					<td colspan="2" style="text-align:center;padding:30px 0 50px"><img src="view/quick_status_updater/img/logo.gif" alt="Quick Order Status Updater"/></td>
				</tr>
				<tr>
					<td>Version</td>
					<td><?php echo $module_version; ?></td>
				</tr>
				<tr>
					<td>Free support</td>
					<td>I take care of maintaining my modules at top quality and affordable price.<br/>In case of bug, incompatibility, or if you want a new feature, just contact me on my mail.</td>
				</tr>
				<tr>
					<td>Contact</td>
					<td><a href="mailto:sirius_box-dev@yahoo.fr">sirius_box-dev@yahoo.fr</a></td>
				</tr>
				<tr>
					<td>Links</td>
					<td>
						If you like this module, please consider to make a star rating <span style="position:relative;top:3px;width:80px;height:17px;display:inline-block;background:url(data:data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAARCAYAAADUryzEAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEgAACxIB0t1+/AAAABZ0RVh0Q3JlYXRpb24gVGltZQAwNy8wNy8xMrG4sToAAAAcdEVYdFNvZnR3YXJlAEFkb2JlIEZpcmV3b3JrcyBDUzbovLKMAAACr0lEQVQ4jX1US0+TURA98/Xri0KBYqG8BDYItBoIBhFBBdRNTTQx0Q0gujBiAkEXxoXxD6iJbRcaY1iQEDXqTgwQWkWDIBU3VqWQoEgECzUU+n5910VbHhacZHLvzD05c+fMzaVhgxYJIwIYi+8B8FJ5bzjob9ucB4DmLttGMGyoAGMsyc1G7bEvA91roz2NL7Y7TziHHSxFmWsorbuUFgn79BaTLnMn3LYEZqPukCKruFAUGEd54w1ekqK69x8CSkoqMnJv72noTmN+O9Q5KlE44GqxmHTS7Qho5MH+X8SJUuMhAIbM/CrS1tSnCYsmkOoUnO7SiP3dHV8Mw5AoKkRCfTwR96ei+ZZGVVDDJQhIWAVbfhjDe8eQnd/Aq8+/VAIsAcGbR8ejQiR8jcwGbYZEkTFVd7I9B4IXcL+GEPwdK4SN0XJSDaCoAvHZsA4/93hWHNVNnbZpjoG5gl7XvpFnxggxAZRaA0rokliIAIkaxMnwdWLE7XW77jd12qYBgCMiNHfZlhgTCkZfPfUDBAYGItoiL0lK8N0+51txzD1u7Ji8njTGpk6bg/iUhSiU4GT5YOtPL940AOfiDyHod9/dMsYEzmLS5bBoKE/ES8ECCyACSF4IFledAdhd2SIFUdtmAp7i92QM+uKqVg6RJXDKakCcjyjSwcldMUDgG7I0h8WKdI0ewM2kFuTpmlb1bp2UMYBJyjBjm/FYh57MjA/1+1wuESNZOfjoLPwe516zUSdLIgi6l+sl3CIW5leD7/v7HPNTE+cOtr8tDXhWy+zWAcvnDx/XoiEPiirPBomgXxd32KAFEWp3FR0YdP60pop4sfHI5cmr+MfMRl2tXKnqzS5pyFuaHRusu2A5EyeoAEAQS2Q94VDg4pY/YUOf9ZgxnBaJJSeOdny6AgB/AYEpKtpaTusRAAAAAElFTkSuQmCC)"></span> on the module page :]<br/><br/>
						<b>Module page :</b> <a target="new" href="http://www.opencart.com/index.php?route=extension/extension/info&extension_id=">Quick Order Status Updater</a><br/>
						<b>Other modules :</b> <a target="new" href="http://www.opencart.com/index.php?route=extension/extension&filter_username=woodruff">My modules on opencart</a><br/>
					</td>
				</tr>
			</table>
		</div>
      </form>
	  </div>
	  </div>
  </div>
</div>
<script type="text/javascript"><!--
$('input.switch').iToggle({easing: 'swing',speed: 200});
--></script>
<script type="text/javascript"><!--
$('.inlineEdit .switchBtn').click(function(){
	$(this).toggle();
	$(this).next().toggle();
});
$(".colorpicker").spectrum({
	preferredFormat: "hex",
    showInput: true,
    allowEmpty:true,
	clickoutFiresChange: true,
	showInitial: true,
	showButtons: false
	//showPalette: true
});
--></script>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<!-- custom blocks -->
<script type="text/javascript"><!--
<?php foreach ($order_statuses as $status) { ?>
	<?php /*ckeditor disabled// foreach ($languages as $language) { ?>
	CKEDITOR.replace('description-<?php echo $status['order_status_id']; ?>-<?php echo $language['language_id']; ?>', {
		filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
	});
	<?php }*/ ?>
<?php } ?>
//--></script>
<script type="text/javascript"><!--
var shipping_row = <?php echo count($qosu_shipping)+1; ?>;
function addShipping() {
	html  = '<div id="tab-shipping-' + shipping_row + '" class="tab-pane">';
	html += '  <table class="form">';
	html += '    <tr>';
	html += '      <td><?php echo $_language->get('entry_shipping_title'); ?></td>';
	html += '      <td><input type="text" class="form-control" name="qosu_shipping[' + shipping_row + '][title]" value="" size="50"/></td>';
	html += '    </tr>';
	html += '    <tr>';
	html += '      <td><?php echo $_language->get('entry_shipping_url'); ?></td>';
	html += '      <td><input type="text" class="form-control" name="qosu_shipping[' + shipping_row + '][url]" value="" size="50"/></td>';
	html += '    </tr>';
	html += '  </table>';
	html += '</div>';

	$('#tab-1 > .tab-content').append(html);

	$('#shipping-add').before('<li><a href="#tab-shipping-' + shipping_row + '" id="shipping-' + shipping_row + '" data-toggle="pill">' + shipping_row + '&nbsp;<i class="fa fa-minus-circle" onclick="$(\'#qosu_shipping a:first\').trigger(\'click\'); $(\'#shipping-' + shipping_row + '\').remove(); $(\'#tab-shipping-' + shipping_row + '\').remove(); return false;"></i></a></li>');

	$('#shipping-' + shipping_row).trigger('click');

	shipping_row++;
}

var module_row = <?php echo count($qosu_inputs)+1; ?>;
function addModule() {
	html  = '<div id="tab-module-' + module_row + '" class="tab-pane">';
	html += '  <table class="form">';
	html += '    <tr>';
	html += '      <td><?php echo $_language->get('entry_input_title'); ?></td>';
	html += '      <td><input type="text" class="form-control" name="qosu_inputs[' + module_row + '][title]" value="" size="50"/></td>';
	html += '    </tr>';
	html += '    <tr class="form-inline">';
	html += '      <td><?php echo $_language->get('entry_input_tag'); ?></td>';
	html += '      <td>{ <input type="text" class="form-control" name="qosu_inputs[' + module_row + '][tag]" value="" size="47"/> }</td>';
	html += '    </tr>';
	html += '  </table>';
	html += '</div>';

	$('#tab-3 > .tab-content').append(html);

	$('#module-add').before('<li><a href="#tab-module-' + module_row + '" id="module-' + module_row + '" data-toggle="pill">' + module_row + '&nbsp;<i class="fa fa-minus-circle" onclick="$(\'#qosu_inputs a:first\').trigger(\'click\'); $(\'#module-' + module_row + '\').remove(); $(\'#tab-module-' + module_row + '\').remove(); return false;"></i></a></li>');

	$('#module-' + module_row).trigger('click');

	module_row++;
}
//--></script>
<script type="text/javascript"><!--
$('body').on('click', '.info-btn', function() {
  $('#modal-info .modalContent').html('<div style="text-align:center"><img src="view/quick_status_updater/img/loader.gif" alt=""/></div>');
  $('#modal-info .modalContent').load('index.php?route=module/quick_status_updater/modal_info&token=<?php echo $token; ?>', {'info': $(this).attr('data-info')});
});

var list = document.getElementById("qosu_statuses");
Sortable.create(list, {
  animation: 150,
  //handle: ".tile__title",
  onUpdate: function (e){
    $('#qosu_statuses li').each(function(i, v) {
		$('input[name="qosu_order_statuses['+$(v).find('a').attr('id').replace('status-','')+'][sort_order]"]').val(i+1);
	});
  }
});
//--></script>
<!-- /custom blocks -->
<?php echo $footer; ?>