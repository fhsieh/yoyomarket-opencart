<?php echo $header; ?><?php echo $column_left; ?>
<div id="content"><div class="container-fluid">
	<div class="page-header">
	    <h1>Category Wall</h1>
	    <ul class="breadcrumb">
		     <?php foreach ($breadcrumbs as $breadcrumb) { ?>
		      <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
		      <?php } ?>
	    </ul>
	  </div>
    
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:600,500,400' rel='stylesheet' type='text/css'>
	
	<script type="text/javascript">
	$.fn.tabs = function() {
		var selector = this;
		
		this.each(function() {
			var obj = $(this); 
			
			$(obj.attr('href')).hide();
			
			$(obj).click(function() {
				$(selector).removeClass('selected');
				
				$(selector).each(function(i, element) {
					$($(element).attr('href')).hide();
				});
				
				$(this).addClass('selected');
				
				$($(this).attr('href')).show();
				
				return false;
			});
		});
	
		$(this).show();
		
		$(this).first().click();
	};
	</script>

	<?php if ($error_warning) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
		  <button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
	<?php } elseif ($success) {  ?>
		<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
	<?php } ?>
	
	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
		<div class="set-size" id="category_wall">
			<div class="content">
				<div>
					<div class="tabs clearfix">
						<!-- Tabs module -->
						<div id="tabs" class="htabs main-tabs">
							<?php $module_row = 1; ?>
							<?php foreach ($modules as $module) { ?>
							<a href="#tab-module-<?php echo $module_row; ?>" id="module-<?php echo $module_row; ?>">Module <?php echo $module_row; ?> &nbsp;<img src="view/image/module_template/delete-slider.png"  alt="" onclick="$('.vtabs a:first').trigger('click'); $('#module-<?php echo $module_row; ?>').remove(); $('#tab-module-<?php echo $module_row; ?>').remove(); return false;" /></a>
							<?php $module_row++; ?>
							<?php } ?>
							<span id="module-add">Add Module &nbsp;<img src="view/image/module_template/add.png" alt="" onclick="addModule();" /></span>
						</div>
						
						<?php $module_row = 1; ?>
						<?php foreach ($modules as $module) { ?>
						<div id="tab-module-<?php echo $module_row; ?>" class="tab-content">
						
							<h4>Category Wall</h4>
							<table class="form">
								<tr>
									<td>More text translation:</td>
									<td><div class="list-language">
										<?php foreach ($languages as $language) { $lang_id = $language['language_id']; ?>
											<div class="language">
												<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" width="16px" height="11px" />
												<input type="text" name="category_wall_module[<?php echo $module_row; ?>][more][<?php echo $language['language_id']; ?>]" value="<?php if(isset($module['more'][$lang_id])) { echo $module['more'][$lang_id]; } ?>" />
											</div>
										<?php } ?>
									</div></td>
								</tr>
								<tr>
									<td>Show category icon:</td>
									<td>
										<select name="category_wall_module[<?php echo $module_row; ?>][status_category_icon]">
											<option value="1" <?php if($module['status_category_icon'] == 1) { echo 'selected="selected"'; } ?>>Yes</option>
											<option value="2" <?php if($module['status_category_icon'] == 2) { echo 'selected="selected"'; } ?>>No</option>
										</select>
									</td>
								</tr>
								<tr>
									<td>Dimesion category icon (W x H):</td>
									<td><input type="text" name="category_wall_module[<?php echo $module_row; ?>][category_icon_width]" value="<?php echo $module['category_icon_width']; ?>" size="3" style="float: left;margin-right: 10px" /> <div style="float:left;padding-right: 20px;padding-top: 6px">px</div> <input type="text" name="category_wall_module[<?php echo $module_row; ?>][category_icon_height]" style="float: left;margin-right: 10px" value="<?php echo $module['category_icon_height']; ?>" size="3" /> <div style="float:left;padding-right: 20px;padding-top:6px">px</div></td>
								</tr>
								<tr>
									<td>Categories number per row:</td>
									<td>
										<select name="category_wall_module[<?php echo $module_row; ?>][category_number]">
											<?php for($i = 2; $i < 7; $i++) { 
												echo '<option value="' . $i . '" ' . ($module['category_number'] == $i ? 'selected="selected"' : '') . '>' . $i . '</option>';
											}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td>Subcategories number per column:</td>
									<td>
										<select name="category_wall_module[<?php echo $module_row; ?>][subcategory_number]">
											<?php for($i = 2; $i < 11; $i++) { 
												echo '<option value="' . $i . '" ' . ($module['subcategory_number'] == $i ? 'selected="selected"' : '') . '>' . $i . '</option>';
											}
											?>
										</select>
									</td>
								</tr>
							  <tr>
							    <td>Layout:</td>
							    <td><select name="category_wall_module[<?php echo $module_row; ?>][layout_id]">
							    	<?php if (99999 == $module['layout_id']) { ?>
							    	<option value="99999" selected="selected">All pages</option>
							    	<?php } else { ?>
							    	<option value="99999">All pages</option>
							    	<?php } ?>
							        <?php foreach ($layouts as $layout) { ?>
							        <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
							        <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
							        <?php } else { ?>
							        <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
							        <?php } ?>
							        <?php } ?>
							      </select></td>
							  </tr>
							  <tr>
							    <td>Position:</td>
							    <td><select name="category_wall_module[<?php echo $module_row; ?>][position]">
							    	<?php if ($module['position'] == 'menu') { ?>
							    	<option value="menu" selected="selected">Menu</option>
							    	<?php } else { ?>
							    	<option value="menu">Menu</option>
							    	<?php } ?>
							    	<?php if ($module['position'] == 'slideshow') { ?>
							    	<option value="slideshow" selected="selected">Slideshow</option>
							    	<?php } else { ?>
							    	<option value="slideshow">Slideshow</option>
							    	<?php } ?>
							    	<?php if ($module['position'] == 'preface_left') { ?>
							    	<option value="preface_left" selected="selected">Preface left</option>
							    	<?php } else { ?>
							    	<option value="preface_left">Preface left</option>
							    	<?php } ?>
							    	<?php if ($module['position'] == 'preface_right') { ?>
							    	<option value="preface_right" selected="selected">Preface right</option>
							    	<?php } else { ?>
							    	<option value="preface_right">Preface right</option>
							    	<?php } ?>
							    	<?php if ($module['position'] == 'preface_fullwidth') { ?>
							    	<option value="preface_fullwidth" selected="selected">Preface fullwidth</option>
							    	<?php } else { ?>
							    	<option value="preface_fullwidth">Preface fullwidth</option>
							    	<?php } ?>
							    	<?php if ($module['position'] == 'column_left') { ?>
							    	<option value="column_left" selected="selected">Column left</option>
							    	<?php } else { ?>
							    	<option value="column_left">Column left</option>
							    	<?php } ?>
							    	<?php if ($module['position'] == 'content_big_column') { ?>
							    	<option value="content_big_column" selected="selected">Content big column</option>
							    	<?php } else { ?>
							    	<option value="content_big_column">Content big column</option>
							    	<?php } ?>
							    	<?php if ($module['position'] == 'content_top') { ?>
							    	<option value="content_top" selected="selected">Content top</option>
							    	<?php } else { ?>
							    	<option value="content_top">Content top</option>
							    	<?php } ?>
							    	<?php if ($module['position'] == 'column_right') { ?>
							    	<option value="column_right" selected="selected">Column right</option>
							    	<?php } else { ?>
							    	<option value="column_right">Column right</option>
							    	<?php } ?>
							    	<?php if ($module['position'] == 'content_bottom') { ?>
							    	<option value="content_bottom" selected="selected">Content bottom</option>
							    	<?php } else { ?>
							    	<option value="content_bottom">Content bottom</option>
							    	<?php } ?>
							    	<?php if ($module['position'] == 'customfooter_top') { ?>
							    	<option value="customfooter_top" selected="selected">CustomFooter Top</option>
							    	<?php } else { ?>
							    	<option value="customfooter_top">CustomFooter Top</option>
							    	<?php } ?>
							    	<?php if ($module['position'] == 'customfooter_bottom') { ?>
							    	<option value="customfooter_bottom" selected="selected">CustomFooter Bottom</option>
							    	<?php } else { ?>
							    	<option value="customfooter_bottom">CustomFooter Bottom</option>
							    	<?php } ?>
							    	<?php if ($module['position'] == 'footer_top') { ?>
							    	<option value="footer_top" selected="selected">Footer top</option>
							    	<?php } else { ?>
							    	<option value="footer_top">Footer top</option>
							    	<?php } ?>
							    	<?php if ($module['position'] == 'footer_left') { ?>
							    	<option value="footer_left" selected="selected">Footer left</option>
							    	<?php } else { ?>
							    	<option value="footer_left">Footer left</option>
							    	<?php } ?>
							    	<?php if ($module['position'] == 'footer_right') { ?>
							    	<option value="footer_right" selected="selected">Footer right</option>
							    	<?php } else { ?>
							    	<option value="footer_right">Footer right</option>
							    	<?php } ?>
							    	<?php if ($module['position'] == 'footer_bottom') { ?>
							    	<option value="footer_bottom" selected="selected">Footer bottom</option>
							    	<?php } else { ?>
							    	<option value="footer_bottom">Footer bottom</option>
							    	<?php } ?>
							    	<?php if ($module['position'] == 'bottom') { ?>
							    	<option value="bottom" selected="selected">Bottom</option>
							    	<?php } else { ?>
							    	<option value="bottom">Bottom</option>
							    	<?php } ?>
							      </select></td>
							  </tr>
							  <tr>
							    <td>Status:</td>
							    <td><select name="category_wall_module[<?php echo $module_row; ?>][status]">
							        <?php if ($module['status']) { ?>
							        <option value="1" selected="selected">Enabled</option>
							        <option value="0">Disabled</option>
							        <?php } else { ?>
							        <option value="1">Enabled</option>
							        <option value="0" selected="selected">Disabled</option>
							        <?php } ?>
							      </select></td>
							  </tr>
							  <tr>
							    <td>Sort Order:</td>
							    <td><input type="text" name="category_wall_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
							  </tr>
							</table>
						</div>
						<?php $module_row++; ?>
						<?php } ?>
					</div>
					
					<!-- Buttons -->
					<div class="buttons"><input type="submit" name="button-save" class="button-save" value=""></div>
				</div>
			</div>
		</div>
	</form>
</div>
<script type="text/javascript"><!--
$('.main-tabs a').tabs();
//--></script> 

<script type="text/javascript"><!--
<?php $module_row = 1; ?>
<?php foreach ($modules as $module) { ?>
$('#language-<?php echo $module_row; ?> a').tabs();
<?php $module_row++; ?>
<?php } ?> 
//--></script> 

<script type="text/javascript"><!--
<?php $module_row = 1; ?>
<?php foreach ($modules as $module) { ?>
<?php $module_row++; ?>
<?php } ?>
//--></script> 

<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = '<div id="tab-module-' + module_row + '" class="tab-content">';
		
		html += '<h4>Category Wall</h4>';
		html += '  <table class="form">';
		html += '	 <tr>';
		html += '		<td>More text translation:</td>';
		html += '		<td><div class="list-language">';
		<?php foreach ($languages as $language) { ?>
		html += '			<div class="language">';
		html += '				<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" width="16px" height="11px" />';
		html += '				<input type="text" name="category_wall_module[' + module_row + '][more][<?php echo $language['language_id']; ?>]" value="" />';
		html += '			</div>';
		<?php } ?>
		html += '		</div></td>';
		html += '	 </tr>';
		html += '	 <tr>';
		html += '			<td>Show category icon:</td>';
		html += '			<td>';
		html += '				<select name="category_wall_module[' + module_row + '][status_category_icon]">';
		html += '					<option value="1" selected="selected">Yes</option>';
		html += '					<option value="2">No</option>';
		html += '				</select>';
		html += '			</td>';
		html += '	 </tr>';
		html += '    <tr>';
		html += '      <td>Dimesion category icon (W x H):</td>';
		html += '      <td><input type="text" name="category_wall_module[' + module_row + '][category_icon_width]" value="200" size="3" style="float: left;margin-right: 10px" /> <div style="float:left;padding-right: 20px;padding-top: 6px">px</div> <input type="text" name="category_wall_module[' + module_row + '][category_icon_height]" style="float: left;margin-right: 10px" value="200" size="3" /> <div style="float:left;padding-right: 20px;padding-top:6px">px</div> </td>';
		html += '    </tr>';
		html += '	 <tr>';
		html += '			<td>Categories number per row:</td>';
		html += '			<td>';
		html += '				<select name="category_wall_module[' + module_row + '][category_number]">';
		html += '					<option value="2">2</option>';
		html += '					<option value="3">3</option>';
		html += '					<option value="4" selected="selected">4</option>';
		html += '					<option value="5">5</option>';
		html += '					<option value="6">6</option>';
		html += '				</select>';
		html += '			</td>';
		html += '	 </tr>';
		html += '	 <tr>';
		html += '			<td>Subcategories number per column:</td>';
		html += '			<td>';
		html += '				<select name="category_wall_module[' + module_row + '][subcategory_number]">';
		html += '					<option value="2">2</option>';
		html += '					<option value="3">3</option>';
		html += '					<option value="4">4</option>';
		html += '					<option value="5" selected="selected">5</option>';
		html += '					<option value="6">6</option>';
		html += '					<option value="7">7</option>';
		html += '					<option value="8">8</option>';
		html += '					<option value="9">9</option>';
		html += '					<option value="10">10</option>';
		html += '				</select>';
		html += '			</td>';
		html += '	 </tr>';
		html += '    <tr>';
		html += '      <td>Layout:</td>';
		html += '      <td><select name="category_wall_module[' + module_row + '][layout_id]">';
		html += '           <option value="99999">All pages</option>';
		<?php foreach ($layouts as $layout) { ?>
		html += '           <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
		<?php } ?>
		html += '      </select></td>';
		html += '    </tr>';
		html += '    <tr>';
		html += '      <td>Position:</td>';
		html += '      <td><select name="category_wall_module[' + module_row + '][position]">';
		html += '       		<option value="menu">Menu</option>';
		html += '				<option value="slideshow">Slideshow</option>';
		html += '				<option value="preface_left">Preface left</option>';
		html += '				<option value="preface_right">Preface right</option>';
		html += '				<option value="preface_fullwidth">Preface fullwidth</option>';
		html += '				<option value="column_left">Column left</option>';
		html += '				<option value="content_big_column">Content big column</option>';
		html += '				<option value="content_top">Content top</option>';
		html += '				<option value="column_right">Column right</option>';
		html += '				<option value="content_bottom">Content bottom</option>';
		html += '				<option value="customfooter_top">CustomFooter Top</option>';
		html += '				<option value="customfooter_bottom">CustomFooter Bottom</option>';
		html += '				<option value="footer_top">Footer top</option>';
		html += '				<option value="footer_left">Footer left</option>';
		html += '				<option value="footer_right">Footer right</option>';
		html += '				<option value="footer_bottom">Footer bottom</option>';
		html += '				<option value="bottom">Bottom</option>';
		html += '      </select></td>';
		html += '    </tr>';
		html += '    <tr>';
		html += '      <td>Status:</td>';
		html += '      <td><select name="category_wall_module[' + module_row + '][status]">';
		html += '        <option value="1">Enabled</option>';
		html += '        <option value="0">Disabled</option>';
		html += '      </select></td>';
		html += '    </tr>';
		html += '    <tr>';
		html += '      <td>Sort Order:</td>';
		html += '      <td><input type="text" name="category_wall_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
		html += '    </tr>';
		html += '  </table>'; 
	html += '</div>';
	
	$('.tabs').append(html);
	
	$('#language-' + module_row + ' a').tabs();

	$('#module-add').before('<a href="#tab-module-' + module_row + '" id="module-' + module_row + '">Module ' + module_row + ' &nbsp;<img src="view/image/module_template/delete-slider.png" alt="" onclick="$(\'.vtabs a:first\').trigger(\'click\'); $(\'#module-' + module_row + '\').remove(); $(\'#tab-module-' + module_row + '\').remove(); return false;" /></a>');
	
	$('.main-tabs a').tabs();
	
	$('#module-' + module_row).trigger('click');
	
	module_row++;
}
//--></script> 
<?php echo $footer; ?>