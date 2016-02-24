<?php echo $header; ?><?php echo $column_left; ?>
<div id="content"><div class="container-fluid">
	<div class="page-header">
	    <h1>Product Tabs</h1>
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
		<div class="set-size" id="product_tabs">
			<div class="content">
				<div>
					<div class="tabs clearfix">
						<!-- Tabs module -->
						<div id="tabs" class="htabs main-tabs">
							<?php $module_row = 1; ?>
							<?php foreach ($modules as $module) { ?>
							<a href="#tab-module-<?php echo $module_row; ?>" id="module-<?php echo $module_row; ?>">Tab <?php echo $module_row; ?> &nbsp;<img src="view/image/module_template/delete-slider.png"  alt="" onclick="$('.vtabs a:first').trigger('click'); $('#module-<?php echo $module_row; ?>').remove(); $('#tab-module-<?php echo $module_row; ?>').remove(); return false;" /></a>
							<?php $module_row++; ?>
							<?php } ?>
							<span id="module-add">Add Tab &nbsp;<img src="view/image/module_template/add.png" alt="" onclick="addModule();" /></span>
						</div>
						
						<?php $module_row = 1; ?>
						<?php foreach ($modules as $module) { ?>
						<div id="tab-module-<?php echo $module_row; ?>" class="tab-content">
							<div class="input clearfix">
								<p>Global tag:</p>
								<?php if(isset($module['status'])) { ?>
									<?php if($module['status'] == 1) { 
										echo '<div class="status status-on" id="'.$module_row.'" title="1" rel="module_'.$module_row.'_status"></div>'; 
									} else { 
										echo '<div class="status status-off" id="'.$module_row.'" title="0" rel="module_'.$module_row.'_status"></div>'; 
									} ?>
									<input name="product_tabs[<?php echo $module_row; ?>][status]" value="<?php echo $module['status']; ?>" id="module_<?php echo $module_row; ?>_status" type="hidden" />
								<?php } else { ?>
									<?php echo '<div class="status status-off" title="0" rel="module_'.$module_row.'_status"></div>'; ?>
									<input name="product_tabs[<?php echo $module_row; ?>][status]" value="0" id="module_<?php echo $module_row; ?>_status" type="hidden" />
								<?php } ?>
							</div>
							
							<?php $display_autocomplete = true;  if(isset($module['status'])) { if($module['status'] == 1) { $display_autocomplete = false; } } ?>
							<div class="input clearfix" id="product-autocomplete-<?php echo $module_row; ?>" <?php if($display_autocomplete == false) { echo 'style="display:none"'; } ?>>
								<p>Product name:<br><span style="font-size:11px;color:#808080">(Autocomplete)</span></p>
								<input type="hidden" name="product_tabs[<?php echo $module_row; ?>][product_id]" value="<?php echo $module['product_id']; ?>" />
								<input type="text" id="product-autocomplete-input-<?php echo $module_row; ?>" name="product_tabs[<?php echo $module_row; ?>][product_name]" title="<?php echo $module_row; ?>" value="<?php echo $module['product_name']; ?>">
							</div>
							
							<script type="text/javascript">
								$('#product-autocomplete-input-<?php echo $module_row; ?>').autocomplete({
									delay: 500,
									source: function(request, response) {		
										$.ajax({
											url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
											dataType: 'json',
											success: function(json) {
												response($.map(json, function(item) {
													return {
														label: item['name'],
														value: item['product_id']
													}
												}));
											}
										});
									},
									select: function(item) {
										$('input[name=\'product_tabs[<?php echo $module_row; ?>][product_name]\']').val(item['label']);
										$('input[name=\'product_tabs[<?php echo $module_row; ?>][product_id]\']').val(item['value']);
										
										return false;
									}
								});
							</script>

							<h4>Add tabs to the product</h4>
							<div id="carousel_<?php echo $module_row; ?>_items" class="tabs_add_element clearfix">
								<?php $i = 1; ?>
								<?php if(isset($module['tabs'])) { foreach($module['tabs'] as $item) { ?>
									<a href="#carousel-<?php echo $module_row; ?>-element-<?php echo $i; ?>" id="element-<?php echo $element; ?>"><?php echo $i; ?> &nbsp;<img src="view/image/module_template/delete-slider.png" alt="" onclick="$('#carousel_<?php echo $module_row; ?>_items a:first').trigger('click'); $('#element-<?php echo $element; ?>').remove(); $('#carousel-<?php echo $module_row; ?>-element-<?php echo $i; ?>').remove(); return false;" /></a>
								<?php $i++; $element++; ?>
								<?php } } ?>
								<img src="view/image/module_template/add.png" alt="" onclick="addElement(<?php echo $module_row; ?>);">
							</div>
							
							<?php $i = 1; ?>
							<?php if(isset($module['tabs'])) { foreach($module['tabs'] as $item) { ?>
								<div id="carousel-<?php echo $module_row; ?>-element-<?php echo $i; ?>" style="padding-top:20px">
									<div class="input clearfix">
										<p>Status:</p>
										<?php if(isset($item['status'])) { ?>
											<?php if($item['status'] == 1) { 
												echo '<div class="status status-on" title="1" rel="module_'.$module_row.'_tabs_'.$i.'_status"></div>'; 
											} else { 
												echo '<div class="status status-off" title="0" rel="module_'.$module_row.'_tabs_'.$i.'_status"></div>'; 
											} ?>
											<input name="product_tabs[<?php echo $module_row; ?>][tabs][<?php echo $i; ?>][status]" value="<?php echo $item['status']; ?>" id="module_<?php echo $module_row; ?>_tabs_<?php echo $i; ?>_status" type="hidden" />
										<?php } else { ?>
											<?php echo '<div class="status status-off" title="0" rel="module_'.$module_row.'_tabs_'.$i.'_status"></div>'; ?>
											<input name="product_tabs[<?php echo $module_row; ?>][tabs][<?php echo $i; ?>][status]" value="0" id="module_<?php echo $module_row; ?>_tabs_<?php echo $i; ?>_status" type="hidden" />
										<?php } ?>
									</div>
									
									<div class="input clearfix">
										<p>Sort order:</p>
										<input type="text" name="product_tabs[<?php echo $module_row; ?>][tabs][<?php echo $i; ?>][sort_order]" value="<?php if(isset($item['sort_order'])) { echo $item['sort_order']; } ?>" style="width:45px" />	
									</div>
									
									<div id="language-<?php echo $module_row; ?>-element-<?php echo $i; ?>" class="htabs" style="margin-top:20px">
										<?php foreach ($languages as $language) { ?>
											<a href="#tab-language-<?php echo $module_row; ?>-<?php echo $i; ?>-<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
										<?php } ?>
									</div>
									
									<?php foreach ($languages as $language) { $lang_id = $language['language_id']; ?>
									<div id="tab-language-<?php echo $module_row; ?>-<?php echo $i; ?>-<?php echo $language['language_id']; ?>">
										<div class="input clearfix">
											<p>Name:</p>
											<input type="text" name="product_tabs[<?php echo $module_row; ?>][tabs][<?php echo $i; ?>][<?php echo $lang_id; ?>][name]" value="<?php if(isset($item[$lang_id]['name'])) { echo $item[$lang_id]['name']; } ?>" />
										</div>
										
										<div class="input clearfix">
											<textarea rows="0" cols="0" id="product_tabs_<?php echo $module_row; ?>_<?php echo $i; ?>_<?php echo $lang_id; ?>_html" name="product_tabs[<?php echo $module_row; ?>][tabs][<?php echo $i; ?>][<?php echo $lang_id; ?>][html]"><?php if(isset($item[$lang_id]['html'])) { echo $item[$lang_id]['html']; } ?></textarea>
										</div>
										
										<script type="text/javascript">
											$('#product_tabs_<?php echo $module_row; ?>_<?php echo $i; ?>_<?php echo $lang_id; ?>_html').summernote({
												height: 300
											});
										</script>	
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
<?php $module_row++; ?>
<?php } ?> 
//--></script> 

<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = '<div id="tab-module-' + module_row + '" class="tab-content">';
		html += '	<div class="input clearfix">';
		html += '		<p>Global tag:</p>';
		html += '		<div class="status status-off" id="' + module_row + '" title="0" rel="module_' + module_row + '_status"></div><input name="product_tabs[' + module_row + '][status]" value="0" id="module_' + module_row + '_status" type="hidden" />';	
		html += '	</div>';
		
		html += '	<div class="input clearfix" id="product-autocomplete-' + module_row + '">';
		html += '		<p>Product name:<br><span style="font-size:11px;color:#808080">(Autocomplete)</span></p>';
		html += '		<input type="hidden" name="product_tabs[' + module_row + '][product_id]" value="" />';
		html += '		<input type="text" id="product-autocomplete-input-' + module_row + '" name="product_tabs[' + module_row + '][product_name]" title="' + module_row + '" value="">';
		html += '	</div>';
		
		html += '	<h4>Add tabs to the product</h4>';
		html += '	<div id="carousel_'+module_row+'_items" class="tabs_add_element clearfix">';
		html += '		<img src="view/image/module_template/add.png" alt="" onclick="addElement('+module_row+');">';
		html += '	</div>';
		
		html += '	<div id="carousel_'+module_row+'_items_add"></div>';
	html += '</div>';
	
	$('.tabs').append(html);
	
	var module_row2 = module_row;
	
	$('#product-autocomplete-input-' + module_row + '').autocomplete({
		delay: 500,
		source: function(request, response) {		
			$.ajax({
				url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['name'],
							value: item['product_id']
						}
					}));
				}
			});
		},
		select: function(item) {
			$('input[name=\'product_tabs[' + module_row2 + '][product_name]\']').val(item['label']);
			$('input[name=\'product_tabs[' + module_row2 + '][product_id]\']').val(item['value']);
			
			return false;
		}
	});
	
	$('#module-add').before('<a href="#tab-module-' + module_row + '" id="module-' + module_row + '">Tab ' + module_row + ' &nbsp;<img src="view/image/module_template/delete-slider.png" alt="" onclick="$(\'.vtabs a:first\').trigger(\'click\'); $(\'#module-' + module_row + '\').remove(); $(\'#tab-module-' + module_row + '\').remove(); return false;" /></a>');
	
	$('.main-tabs a').tabs();
	
	$('#module-' + module_row).trigger('click');
	
	module_row++;
}
//--></script> 
<script type="text/javascript"><!--
var elements = <?php echo $element; ?>;
function addElement(module_row) {
	html = '<div id="carousel-' + module_row + '-element-' + elements + '" style="padding-top:20px">';
	html += '	<div class="input clearfix">';
	html += '		<p>Status:</p>';
	html += '		<div class="status status-off" title="0" rel="module_' + module_row + '_tabs_' + elements + '_status"></div><input name="product_tabs[' + module_row + '][tabs][' + elements + '][status]" value="0" id="module_' + module_row + '_tabs_' + elements + '_status" type="hidden" />';	
	html += '	</div>';
	html += '	<div class="input clearfix">';
	html += '		<p>Sort order:</p>';
	html += '		<input type="text" name="product_tabs['+ module_row +'][tabs][' + elements + '][sort_order]" value="" style="width:45px" />';	
	html += '	</div>';
	
	html += '  <div id="language-' + module_row + '-element-' + elements + '" class="htabs" style="margin-top:20px">';
	<?php foreach ($languages as $language) { ?>
	html += '    <a href="#tab-language-'+ module_row + '-' + elements + '-<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>';
	<?php } ?>
	html += '  </div>';
	
	<?php foreach ($languages as $language) { ?>
	html += '    <div id="tab-language-'+ module_row + '-' + elements + '-<?php echo $language['language_id']; ?>">';
	html += '		<div class="input clearfix">';
	html += '			<p>Name:</p>';
	html += '			<input type="text" name="product_tabs['+ module_row +'][tabs][' + elements + '][<?php echo $language['language_id']; ?>][name]" value="" />';
	html += '		</div>';
	
	html += '		<div class="input clearfix">';
	html += '			<textarea rows="0" cols="0" id="product_tabs_' + module_row + '_' + elements + '_<?php echo $language['language_id']; ?>_html" name="product_tabs['+ module_row +'][tabs][' + elements + '][<?php echo $language['language_id']; ?>][html]"></textarea>';
	html += '		</div>';
	html += '    </div>';
	<?php } ?>
	html += '</div>';
		
	$('#carousel_' + module_row + '_items > img').before('<a href="#carousel-' + module_row + '-element-' + elements + '" id="element-' + elements + '">' + elements + ' &nbsp;<img src="view/image/module_template/delete-slider.png" alt="" onclick="$(\'#carousel_'+ module_row +'_items a:first\').trigger(\'click\'); $(\'#element-' + elements+ '\').remove(); $(\'#carousel-' + module_row + '-element-' + elements + '\').remove(); return false;" /></a>');
	
	$('#carousel_' + module_row + '_items_add').before(html);
	$('#carousel_' + module_row + '_items a').tabs();	
	$('#element-' + elements).trigger('click');
	
	$('#language-' + module_row + '-element-' + elements + ' a').tabs();
	
	<?php foreach ($languages as $language) { ?>
		$('#product_tabs_' + module_row + '_' + elements + '_<?php echo $language['language_id']; ?>_html').summernote({
			height: 300
		});
	<?php } ?>	
	
	elements++;
}
</script>
<script type="text/javascript">
jQuery(document).ready(function($) {
	
	$('#product_tabs').on('click', '.status', function () {
		
		var styl = $(this).attr("rel");
		var co = $(this).attr("title");
		var product_autocomplete = $(this).attr("id");
		
		if(co == 1) {
			
			if(product_autocomplete != '') {
				$("#product-autocomplete-" + product_autocomplete).show();
			}
			$(this).removeClass('status-on');
			$(this).addClass('status-off');
			$(this).attr("title", "0");

			$("#"+styl+"").val(0);
		
		}
		
		if(co == 0) {
			
			if(product_autocomplete != '') {
				$("#product-autocomplete-" + product_autocomplete).hide();
			}
			$(this).addClass('status-on');
			$(this).removeClass('status-off');
			$(this).attr("title", "1");

			$("#"+styl+"").val(1);
		
		}
		
	});

});	
</script>
<?php echo $footer; ?>