<?php echo $header; ?><?php echo $column_left; ?>
<div id="content"><div class="container-fluid">
	<div class="page-header">
	    <h1>Carousel item</h1>
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

	<?php $element = 1; ?>
	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
		<div class="set-size" id="carousel_item">
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
							<div class="input clearfix">
								<p>Block heading:</p>
								<div class="list-language">
									<?php foreach ($languages as $language) { $lang_id = $language['language_id']; ?>
									<div class="language">
										<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" width="16px" height="11px" />
										<input type="text" name="carousel_item_module[<?php echo $module_row; ?>][heading][<?php echo $language['language_id']; ?>]" value="<?php if(isset($module['heading'][$lang_id])) { echo $module['heading'][$lang_id]; } ?>" />
									</div>
									<?php } ?>
								</div>
							</div>
						
							<h4>Add items to the module</h4>
							<div id="carousel_<?php echo $module_row; ?>_items" class="tabs_add_element clearfix">
								<?php $i = 1; ?>
								<?php if(isset($module['items'])) { foreach($module['items'] as $item) { ?>
									<a href="#carousel-<?php echo $module_row; ?>-element-<?php echo $i; ?>" id="element-<?php echo $element; ?>"><?php echo $i; ?> &nbsp;<img src="view/image/module_template/delete-slider.png" alt="" onclick="$('#carousel_<?php echo $module_row; ?>_items a:first').trigger('click'); $('#element-<?php echo $element; ?>').remove(); $('#carousel-<?php echo $module_row; ?>-element-<?php echo $i; ?>').remove(); return false;" /></a>
								<?php $i++; $element++; ?>
								<?php } } ?>
								<img src="view/image/module_template/add.png" alt="" onclick="addElement(<?php echo $module_row; ?>);">
							</div>
							
							<?php $i = 1; ?>
							<?php if(isset($module['items'])) { foreach($module['items'] as $item) { ?>
								<div id="carousel-<?php echo $module_row; ?>-element-<?php echo $i; ?>" style="padding-top:20px">
									<div id="language-<?php echo $module_row; ?>-element-<?php echo $i; ?>" class="htabs">
										<?php foreach ($languages as $language) { ?>
											<a href="#tab-language-<?php echo $module_row; ?>-<?php echo $i; ?>-<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
										<?php } ?>
									</div>
									
									<?php foreach ($languages as $language) { $lang_id = $language['language_id']; ?>
									<div id="tab-language-<?php echo $module_row; ?>-<?php echo $i; ?>-<?php echo $language['language_id']; ?>">
										<div class="input clearfix">
											<p>HTML:</p>
											<textarea rows="0" cols="0" name="carousel_item_module[<?php echo $module_row; ?>][items][<?php echo $i; ?>][<?php echo $language['language_id']; ?>][html]"><?php if(isset($item[$lang_id]['html'])) { echo $item[$lang_id]['html']; } ?></textarea>
										</div>
									</div>
									<?php } ?>
								</div>
								
								<script type="text/javascript">
								$('#language-<?php echo $module_row; ?>-element-<?php echo $i; ?> a').tabs();	
								</script>
								<?php $i++; ?>
							<?php } } ?>
							<script type="text/javascript"> 
							$('#carousel_<?php echo $module_row; ?>_items a').tabs();	
							</script>
							
							<div id="carousel_<?php echo $module_row; ?>_items_add"></div>

							<table class="form" style="margin-top:20px">
							  <tr>
							    <td>Layout:</td>
							    <td><select name="carousel_item_module[<?php echo $module_row; ?>][layout_id]">
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
							    <td><select name="carousel_item_module[<?php echo $module_row; ?>][position]">
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
							    <td><select name="carousel_item_module[<?php echo $module_row; ?>][status]">
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
							    <td><input type="text" name="carousel_item_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
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
	
		html += '	<div class="input clearfix">';
		html += '		<p>Block heading:</p>';
		html += '		<div class="list-language">';
		<?php foreach ($languages as $language) { ?>
		html += '			<div class="language">';
		html += '				<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" width="16px" height="11px" />';
		html += '				<input type="text" name="carousel_item_module[' + module_row + '][heading][<?php echo $language['language_id']; ?>]" value="" />';
		html += '			</div>';
		<?php } ?>
		html += '		</div>';
		html += '	</div>';
		
		html += '	<h4>Add items to the module</h4>';
		html += '	<div id="carousel_'+module_row+'_items" class="tabs_add_element clearfix">';
		html += '		<img src="view/image/module_template/add.png" alt="" onclick="addElement('+module_row+');">';
		html += '	</div>';
		
		html += '	<div id="carousel_'+module_row+'_items_add"></div>';
	
		html += '  <table class="form" style="margin-top:20px">';
		html += '    <tr>';
		html += '      <td>Layout:</td>';
		html += '      <td><select name="carousel_item_module[' + module_row + '][layout_id]">';
		html += '           <option value="99999">All pages</option>';
		<?php foreach ($layouts as $layout) { ?>
		html += '           <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
		<?php } ?>
		html += '      </select></td>';
		html += '    </tr>';
		html += '    <tr>';
		html += '      <td>Position:</td>';
		html += '      <td><select name="carousel_item_module[' + module_row + '][position]">';
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
		html += '      <td><select name="carousel_item_module[' + module_row + '][status]">';
		html += '        <option value="1">Enabled</option>';
		html += '        <option value="0">Disabled</option>';
		html += '      </select></td>';
		html += '    </tr>';
		html += '    <tr>';
		html += '      <td>Sort Order:</td>';
		html += '      <td><input type="text" name="carousel_item_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
		html += '    </tr>';
		html += '  </table>'; 
	html += '</div>';
	
	$('.tabs').append(html);
	
	$('#module-add').before('<a href="#tab-module-' + module_row + '" id="module-' + module_row + '">Module ' + module_row + ' &nbsp;<img src="view/image/module_template/delete-slider.png" alt="" onclick="$(\'.vtabs a:first\').trigger(\'click\'); $(\'#module-' + module_row + '\').remove(); $(\'#tab-module-' + module_row + '\').remove(); return false;" /></a>');
	
	$('.main-tabs a').tabs();
	
	$('#module-' + module_row).trigger('click');
	
	module_row++;
}
//--></script> 
<script type="text/javascript"><!--
var elements = <?php echo $element; ?>;
function addElement(module_row) {
	html = '<div id="carousel-' + module_row + '-element-' + elements + '" style="padding-top:20px">';
	html += '  <div id="language-' + module_row + '-element-' + elements + '" class="htabs">';
	<?php foreach ($languages as $language) { ?>
	html += '    <a href="#tab-language-'+ module_row + '-' + elements + '-<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>';
	<?php } ?>
	html += '  </div>';
	
	<?php foreach ($languages as $language) { ?>
	html += '    <div id="tab-language-'+ module_row + '-' + elements + '-<?php echo $language['language_id']; ?>">';
	html += '		<div class="input clearfix">';
	html += '			<p>HTML:</p>';
	html += '			<textarea rows="0" cols="0" name="carousel_item_module['+ module_row +'][items][' + elements + '][<?php echo $language['language_id']; ?>][html]"></textarea>';
	html += '		</div>';
	html += '    </div>';
	<?php } ?>
	html += '</div>';
		
	$('#carousel_' + module_row + '_items > img').before('<a href="#carousel-' + module_row + '-element-' + elements + '" id="element-' + elements + '">' + elements + ' &nbsp;<img src="view/image/module_template/delete-slider.png" alt="" onclick="$(\'#carousel_'+ module_row +'_items a:first\').trigger(\'click\'); $(\'#element-' + elements+ '\').remove(); $(\'#carousel-' + module_row + '-element-' + elements + '\').remove(); return false;" /></a>');
	
	$('#carousel_' + module_row + '_items_add').before(html);
	$('#carousel_' + module_row + '_items a').tabs();	
	$('#element-' + elements).trigger('click');
	
	$('#language-' + module_row + '-element-' + elements + ' a').tabs();	
	
	elements++;
}
</script>
<?php echo $footer; ?>