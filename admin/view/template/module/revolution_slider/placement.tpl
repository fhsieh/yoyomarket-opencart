<?php echo $header; ?><?php echo $column_left; ?>
<div id="content"><div class="container-fluid">
	<div class="page-header">
	    <h1>Revolution Slider</h1>
	    <ul class="breadcrumb">
		     <?php foreach ($breadcrumbs as $breadcrumb) { ?>
		      <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
		      <?php } ?>
	    </ul>
	  </div>
    
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:600,500,400' rel='stylesheet' type='text/css'>
	
	<?php if ($error_warning) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
		  <button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
	<?php } elseif ($success) {  ?>
		<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
	<?php } ?>
	
	<!-- Revolution slider -->
	<div class="set-size" id="revolution-slider">
		
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<!-- Content -->
			<div class="content">
				<div>
					<div class="bg-tabs clearfix">
						<div id="tabs">
							<a href="<?php echo $placement; ?>" id="placement" class="active"><span>Module placement</span></a>
							<a href="<?php echo $existing; ?>" id="existing"><span>Existing modules</span></a>
						</div>

						<div class="tab-content2">
							<table id="module-placement">
								<thead>
									<tr>
										<td class="first">Slider</td>
										<td>Layout</td>
										<td>Position</td>
										<td>Sort Order</td>
										<td>Status</td>
										<td>Delete</td>
									</tr>
								</thead>
								<?php $module_row = 0; if($modules != '') { ?>
									<?php foreach($modules as $module) { ?>
									<tbody id="module<?php echo $module_row; ?>">
										<tr>
											<td class="first">
												<select name="revolution_slider_module[<?php echo $module_row; ?>][slider_id]">
													<?php foreach($sliders as $slider) { ?>
													<option value="<?php echo $slider['id']; ?>" <?php if($module['slider_id'] == $slider['id']) { echo 'selected="selected"'; } ?>><?php echo $slider['name']; ?></option>
													<?php } ?>
												</select>
											</td>
											<td>
												<select name="revolution_slider_module[<?php echo $module_row; ?>][layout_id]">
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
												</select>
											</td>
											<td>
												<select name="revolution_slider_module[<?php echo $module_row; ?>][position]">
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
												</select>
											</td>
											<td>
												<input type="text" class="sort" name="revolution_slider_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>">
											</td>
											<td>
												<?php if(isset($module['status'])) { ?>
													<?php if($module['status'] == 0 && $module['status'] != '') { echo '<div class="status status-off" title="0" rel="module_'.$module_row.'_status"></div>'; } else { echo '<div class="status status-on" title="1" rel="module_'.$module_row.'_status"></div>'; } ?>
												<?php } else { echo '<div class="status status-off" title="0" rel="module_'.$module_row.'_status"></div>'; } ?>
												<input name="revolution_slider_module[<?php echo $module_row; ?>][status]" value="<?php if(isset($module['status'])) { echo $module['status']; } else { echo '0'; } ?>" id="module_<?php echo $module_row; ?>_status" type="hidden" />
											</td>
											<td>
												<a onclick="$('#module<?php echo $module_row; ?>').remove();">Remove</a>
											</td>
										</tr>
									</tbody>
									<?php $module_row++; } ?>
								<?php } ?>
								<tfoot></tfoot>
							</table>
							
							<a onclick="addModule();" class="add-module">Add item</a>
							
							<script type="text/javascript"><!--
							var module_row = <?php echo $module_row; ?>;
							
							function addModule() {
								html  = '<tbody id="module' + module_row + '">';
								html += '  <tr>';
								html += '    <td class="first">';
								html += '		<select name="revolution_slider_module[' + module_row + '][slider_id]">';
								<?php foreach($sliders as $slider) { ?>
								html += '			<option value="<?php echo $slider['id']; ?>"><?php echo $slider['name']; ?></option>';
								<?php } ?>
								html += '		</select>';
								html += '    </td>';
								html += '    <td>';
								html += '		<select name="revolution_slider_module[' + module_row + '][layout_id]">';
								html += '			<option value="99999">All pages</option>';
								<?php foreach ($layouts as $layout) { ?>
								html += '           <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
								<?php } ?>
								html += '		</select>';
								html += '    </td>';
								html += '    <td>';
								html += '		<select name="revolution_slider_module[' + module_row + '][position]">';
								html += '       		<option value="menu">Menu</option>';
								html += '				<option value="slideshow">Slideshow</option>';
								html += '			    <option value="breadcrumb_top">Breadcrumb top</option>';
								html += '				<option value="breadcrumb_bottom">Breadcrumb bottom</option>';
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
								html += '		</select>';
								html += '    </td>';
								html += '    <td>';
								html += '		<input type="text" class="sort" name="revolution_slider_module[' + module_row + '][sort_order]">';
								html += '    </td>';
								html += '    <td>';
								html += '		<div class="status status-off" title="0" rel="module_' + module_row + '_status"></div><input name="revolution_slider_module[' + module_row + '][status]" value="0" id="module_' + module_row + '_status" type="hidden" />';
								html += '    </td>';
								html += '    <td><a onclick="$(\'#module' + module_row + '\').remove();">Remove</a></td>';
								html += '  </tr>';
								html += '</tbody>';
								
								$('#module-placement tfoot').before(html);
								
								module_row++;
							}
							//--></script> 
						</div>
					</div>
					
					<div>
						<!-- Buttons -->
						<div class="buttons"><input type="submit" name="button-save" class="button-save" value=""></div>
					</div>
				</div>
			</div>		
		</form>
	</div>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
	
	$('#revolution-slider').on('click', '.status', function () {
		
		var styl = $(this).attr("rel");
		var co = $(this).attr("title");
		
		if(co == 1) {
		
			$(this).removeClass('status-on');
			$(this).addClass('status-off');
			$(this).attr("title", "0");

			$("#"+styl+"").val(0);
		
		}
		
		if(co == 0) {
		
			$(this).addClass('status-on');
			$(this).removeClass('status-off');
			$(this).attr("title", "1");

			$("#"+styl+"").val(1);
		
		}
		
	});

});	
</script>
<?php echo $footer; ?>