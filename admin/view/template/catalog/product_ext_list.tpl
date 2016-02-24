<?php echo $header; ?>
<!-- display length configuration -->
<div class="modal fade" id="displayLengthModal" tabindex="-1" role="dialog" aria-labelledby="displayLengthModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="displayLengthModalLabel"><?php echo $text_items_per_page; ?></h4>
			</div>
			<div class="modal-body bull5i-container">
				<div class="notice">
				</div>
				<form method="post" action="<?php echo $settings; ?>" class="form-horizontal ajax-form" role="form" id="displayLengthForm">
					<fieldset>
						<div class="form-group" data-bind="css: {'has-error': aqe_items_per_page.hasError}">
							<label for="displayLength" class="col-sm-4 control-label"><?php echo $entry_products_per_page; ?></label>
							<div class="col-sm-2">
								<input type="text" class="form-control" name="aqe_items_per_page" id="displayLength" data-bind="value: aqe_items_per_page">
							</div>
							<div class="col-sm-offset-4 col-sm-8 error-container" data-bind="visible: aqe_items_per_page.hasError && aqe_items_per_page.errorMsg">
								<span class="help-block error-text" data-bind="text: aqe_items_per_page.errorMsg"></span>
							</div>
						</div>
						<span class="help-block"><?php echo $help_items_per_page; ?></span>
					</fieldset>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default cancel" data-dismiss="modal"><i class="fa fa-times"></i> <?php echo $button_close; ?></button>
				<button type="button" class="btn btn-primary submit" data-form="#displayLengthForm" data-context="#displayLengthModal" data-vm="displayLengthVM" data-loading-text="<i class='fa fa-spinner fa-spin'></i> <?php echo $text_saving; ?>"><i class="fa fa-save"></i> <?php echo $button_save; ?></button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- confirm deletion -->
<div class="modal fade" id="confirmDelete" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="confirmDeleteLabel"><?php echo $text_confirm_delete; ?></h4>
			</div>
			<div class="modal-body">
				<p><?php echo $text_are_you_sure; ?></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban"></i> <?php echo $button_cancel; ?></button>
				<button type="button" class="btn btn-danger delete"><i class="fa fa-trash-o"></i> <?php echo $button_delete; ?></button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- column settings -->
<div class="modal fade" id="pageColumnsModal" tabindex="-1" role="dialog" aria-labelledby="pageColumnsModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="pageColumnsModalLabel"><?php echo $text_choose_columns; ?></h4>
			</div>
			<div class="modal-body bull5i-container">
				<div class="bull5i-overlay fade">
					<div class="page-overlay-progress"><i class="fa fa-refresh fa-spin fa-5x text-muted"></i></div>
				</div>
				<div class="notice">
				</div>
				<form method="post" action="<?php echo $settings; ?>" class="form-horizontal ajax-form" role="form" id="pageColumnsModalForm">
					<fieldset>
						<h4><?php echo $entry_columns; ?></h4>
						<table class="table table-hover table-condensed page-columns sortable">
							<thead>
								<tr>
									<th>#</th>
									<th><?php echo $text_column; ?></th>
									<th class="text-center"><?php echo $text_display; ?></th>
									<th class="text-center"><?php echo $text_editable; ?></th>
								</tr>
							</thead>
							<tbody>
								<!-- ko foreach: product_columns -->
								<tr data-bind="attr: {'data-id': column}, css: {'danger': !visible()}">
									<td><i class="fa fa-arrows-v"></i></td>
									<td><!-- ko text: name --><!-- /ko --><input data-bind="value: index, attr: {name:'index[columns][' + column +']'}" type="hidden" class="column-index" /></td>
									<td class="text-center"><input data-bind="checked: visible, attr: {name:'display[columns][' + column +']'}" type="checkbox" class="column-display" /></td>
									<td class="text-center"><!-- ko if: editable --><?php echo $text_yes; ?><!-- /ko --><!-- ko ifnot: editable --><?php echo $text_no; ?><!-- /ko --></td>
								</tr>
								<!-- /ko -->
							</tbody>
						</table>
						<h4><?php echo $entry_actions; ?></h4>
						<table class="table table-hover table-condensed page-actions sortable">
							<thead>
								<tr>
									<th>#</th>
									<th><?php echo $text_action; ?></th>
									<th class="text-center"><?php echo $text_display; ?></th>
								</tr>
							</thead>
							<tbody>
								<!-- ko foreach: product_actions -->
								<tr data-bind="attr: {'data-id': column}, css: {'danger': !visible()}">
									<td><i class="fa fa-arrows-v"></i></td>
									<td><!-- ko text: name --><!-- /ko --><input data-bind="value: index, attr: {name:'index[actions][' + column +']'}" type="hidden" class="column-index" /></td>
									<td class="text-center"><input data-bind="checked: visible, attr: {name:'display[actions][' + column +']'}" type="checkbox" class="column-display" /></td>
								</tr>
								<!-- /ko -->
							</tbody>
						</table>
					</fieldset>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default cancel" data-dismiss="modal"><i class="fa fa-times"></i> <?php echo $button_close; ?></button>
				<button type="button" class="btn btn-primary submit" data-form="#pageColumnsModalForm" data-context="#pageColumnsModal" data-vm="pageColumnsVM" data-loading-text="<i class='fa fa-spinner fa-spin'></i> <?php echo $text_saving; ?>"><i class="fa fa-save"></i> <?php echo $button_save; ?></button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- other settings -->
<div class="modal fade" id="otherSettingsModal" tabindex="-1" role="dialog" aria-labelledby="otherSettingsModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="otherSettingsModalLabel"><?php echo $text_other_settings; ?></h4>
			</div>
			<div class="modal-body bull5i-container">
				<div class="notice">
				</div>
				<form method="post" action="<?php echo $settings; ?>" class="form-horizontal ajax-form" role="form" id="otherSettingsForm">
					<fieldset>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="aqe_list_view_image_width" data-bind="css: {'has-error': aqe_list_view_image_width.hasError || aqe_list_view_image_height.hasError}"><?php echo $entry_list_view_image_size; ?></label>
							<div class="col-sm-8">
								<div class="input-group">
									<input type="text" class="form-control text-right" name="aqe_list_view_image_width" id="aqe_list_view_image_width" data-bind="value: aqe_list_view_image_width, css: {'has-error': aqe_list_view_image_width.hasError}">
									<span class="input-group-addon"><i class="fa fa-times"></i></span>
									<input type="text" class="form-control" name="aqe_list_view_image_height" id="aqe_list_view_image_height" data-bind="value: aqe_list_view_image_height, css: {'has-error': aqe_list_view_image_height.hasError}">
								</div>
							</div>
							<div class="col-sm-offset-4 col-sm-8 error-container has-error" data-bind="visible: aqe_list_view_image_width.hasError && aqe_list_view_image_width.errorMsg">
								<span class="help-block error-text" data-bind="text: aqe_list_view_image_width.errorMsg"></span>
							</div>
							<div class="col-sm-offset-4 col-sm-8 error-container has-error" data-bind="visible: aqe_list_view_image_height.hasError && aqe_list_view_image_height.errorMsg">
								<span class="help-block error-text" data-bind="text: aqe_list_view_image_height.errorMsg"></span>
							</div>
						</div>
						<div class="form-group">
							<label for="quickEditOn" class="col-sm-4 control-label"><?php echo $entry_quick_edit_on; ?></label>
							<div class="col-sm-3 fc-auto-width">
								<select name="aqe_quick_edit_on" id="quickEditOn" class="form-control" data-bind="value: aqe_quick_edit_on">
									<option value="click"><?php echo $text_single_click; ?></option>
									<option value="dblclick"><?php echo $text_double_click; ?></option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="serverSideCaching" class="col-sm-4 control-label"><?php echo $entry_server_side_caching; ?></label>
							<div class="col-sm-8">
								<label class="radio-inline">
									<input type="radio" name="aqe_server_side_caching" id="serverSideCaching" value="1" data-bind="checked: aqe_server_side_caching"> <?php echo $text_yes; ?>
								</label>
								<label class="radio-inline">
									<input type="radio" name="aqe_server_side_caching" id="serverSideCachingNo" value="0" data-bind="checked: aqe_server_side_caching"> <?php echo $text_no; ?>
								</label>
							</div>
							<div class="col-sm-offset-4 col-sm-8 help-container">
								<span class="help-block help-text"><?php echo $help_server_side_caching; ?></span>
							</div>
						</div>
						<div class="form-group">
							<label for="clientSideCaching" class="col-sm-4 control-label"><?php echo $entry_client_side_caching; ?></label>
							<div class="col-sm-8">
								<label class="radio-inline">
									<input type="radio" name="aqe_client_side_caching" id="clientSideCaching" value="1" data-bind="checked: aqe_client_side_caching"> <?php echo $text_yes; ?>
								</label>
								<label class="radio-inline">
									<input type="radio" name="aqe_client_side_caching" id="clientSideCachingNo" value="0" data-bind="checked: aqe_client_side_caching"> <?php echo $text_no; ?>
								</label>
							</div>
							<div class="col-sm-offset-4 col-sm-8 help-container">
								<span class="help-block help-text"><?php echo $help_client_side_caching; ?></span>
							</div>
						</div>
						<!-- ko if: aqe_client_side_caching() == '1' -->
						<div class="form-group" data-bind="css: {'has-error': aqe_cache_size.hasError}">
							<label for="clientSideCacheSize" class="col-sm-4 control-label"><?php echo $entry_client_side_cache_size; ?></label>
							<div class="col-sm-4">
								<input type="text" class="form-control text-right" name="aqe_cache_size" id="clientSideCacheSize" data-bind="value: aqe_cache_size">
							</div>
							<div class="col-sm-offset-4 col-sm-8 error-container" data-bind="visible: aqe_cache_size.hasError && aqe_cache_size.errorMsg">
								<span class="help-block error-text" data-bind="text: aqe_cache_size.errorMsg"></span>
							</div>
							<div class="col-sm-offset-4 col-sm-8 help-container">
								<span class="help-block help-text"><?php echo $help_client_side_cache_size; ?></span>
							</div>
							<input type="hidden" name="aqe_cache_size" data-bind="value: aqe_cache_size">
						</div>
						<!-- /ko -->
						<!-- ko if: aqe_client_side_caching() != '1' -->
						<input type="hidden" name="aqe_cache_size" data-bind="value: aqe_cache_size">
						<!-- /ko -->
						<div class="form-group">
							<label for="alternateRowColour" class="col-sm-4 control-label"><?php echo $entry_alternate_row_colour; ?></label>
							<div class="col-sm-8">
								<label class="radio-inline">
									<input type="radio" name="aqe_alternate_row_colour" id="alternateRowColour" value="1" data-bind="checked: aqe_alternate_row_colour"> <?php echo $text_yes; ?>
								</label>
								<label class="radio-inline">
									<input type="radio" name="aqe_alternate_row_colour" id="alternateRowColourNo" value="0" data-bind="checked: aqe_alternate_row_colour"> <?php echo $text_no; ?>
								</label>
							</div>
						</div>
						<div class="form-group">
							<label for="rowHoverHighlighting" class="col-sm-4 control-label"><?php echo $entry_row_hover_highlighting; ?></label>
							<div class="col-sm-8">
								<label class="radio-inline">
									<input type="radio" name="aqe_row_hover_highlighting" id="rowHoverHighlighting" value="1" data-bind="checked: aqe_row_hover_highlighting"> <?php echo $text_yes; ?>
								</label>
								<label class="radio-inline">
									<input type="radio" name="aqe_row_hover_highlighting" id="rowHoverHighlightingNo" value="0" data-bind="checked: aqe_row_hover_highlighting"> <?php echo $text_no; ?>
								</label>
							</div>
							<div class="col-sm-offset-4 col-sm-8 help-container">
								<span class="help-block help-text"><?php echo $help_row_hover_highlighting; ?></span>
							</div>
						</div>
						<div class="form-group">
							<label for="highlightStatus" class="col-sm-4 control-label"><?php echo $entry_highlight_status; ?></label>
							<div class="col-sm-8">
								<label class="radio-inline">
									<input type="radio" name="aqe_highlight_status" id="highlightStatus" value="1" data-bind="checked: aqe_highlight_status"> <?php echo $text_yes; ?>
								</label>
								<label class="radio-inline">
									<input type="radio" name="aqe_highlight_status" id="highlightStatusNo" value="0" data-bind="checked: aqe_highlight_status"> <?php echo $text_no; ?>
								</label>
							</div>
							<div class="col-sm-offset-4 col-sm-8 help-container">
								<span class="help-block help-text"><?php echo $help_highlight_status; ?></span>
							</div>
						</div>
						<div class="form-group">
							<label for="highlightFilteredColumns" class="col-sm-4 control-label"><?php echo $entry_highlight_filtered_columns; ?></label>
							<div class="col-sm-8">
								<label class="radio-inline">
									<input type="radio" name="aqe_highlight_filtered_columns" id="highlightFilteredColumns" value="1" data-bind="checked: aqe_highlight_filtered_columns"> <?php echo $text_yes; ?>
								</label>
								<label class="radio-inline">
									<input type="radio" name="aqe_highlight_filtered_columns" id="highlightFilteredColumnsNo" value="0" data-bind="checked: aqe_highlight_filtered_columns"> <?php echo $text_no; ?>
								</label>
							</div>
							<div class="col-sm-offset-4 col-sm-8 help-container">
								<span class="help-block help-text"><?php echo $help_highlight_filtered_columns; ?></span>
							</div>
						</div>
						<div class="form-group">
							<label for="highlightActions" class="col-sm-4 control-label"><?php echo $entry_highlight_actions; ?></label>
							<div class="col-sm-8">
								<label class="radio-inline">
									<input type="radio" name="aqe_highlight_actions" id="highlightActions" value="1" data-bind="checked: aqe_highlight_actions"> <?php echo $text_yes; ?>
								</label>
								<label class="radio-inline">
									<input type="radio" name="aqe_highlight_actions" id="highlightActionsNo" value="0" data-bind="checked: aqe_highlight_actions"> <?php echo $text_no; ?>
								</label>
							</div>
							<div class="col-sm-offset-4 col-sm-8 help-container">
								<span class="help-block help-text"><?php echo $help_highlight_actions; ?></span>
							</div>
						</div>
						<div class="form-group">
							<label for="filterSubCategory" class="col-sm-4 control-label"><?php echo $entry_filter_sub_category; ?></label>
							<div class="col-sm-8">
								<label class="radio-inline">
									<input type="radio" name="aqe_filter_sub_category" id="filterSubCategory" value="1" data-bind="checked: aqe_filter_sub_category"> <?php echo $text_yes; ?>
								</label>
								<label class="radio-inline">
									<input type="radio" name="aqe_filter_sub_category" id="filterSubCategoryNo" value="0" data-bind="checked: aqe_filter_sub_category"> <?php echo $text_no; ?>
								</label>
							</div>
							<div class="col-sm-offset-4 col-sm-8 help-container">
								<span class="help-block help-text"><?php echo $help_filter_sub_category; ?></span>
							</div>
						</div>
						<!-- ko if: aqe_debug_mode() == '1' -->
						<div class="form-group">
							<label for="debugMode" class="col-sm-4 control-label"><?php echo $entry_debug_mode; ?></label>
							<div class="col-sm-8">
								<label class="radio-inline">
									<input type="radio" name="aqe_debug_mode" id="debugMode" value="1" data-bind="checked: aqe_debug_mode"> <?php echo $text_yes; ?>
								</label>
								<label class="radio-inline">
									<input type="radio" name="aqe_debug_mode" id="debugModeNo" value="0" data-bind="checked: aqe_debug_mode"> <?php echo $text_no; ?>
								</label>
							</div>
						</div>
						<!-- /ko -->
						<!-- ko if: aqe_debug_mode() != '1' -->
						<input type="hidden" name="aqe_debug_mode" value="0" data-bind="checked: aqe_debug_mode">
						<!-- /ko -->
					</fieldset>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default cancel" data-dismiss="modal"><i class="fa fa-times"></i> <?php echo $button_close; ?></button>
				<button type="button" class="btn btn-primary submit" data-form="#otherSettingsForm" data-context="#otherSettingsModal" data-vm="otherSettingsVM" data-loading-text="<i class='fa fa-spinner fa-spin'></i> <?php echo $text_saving; ?>"><i class="fa fa-save"></i> <?php echo $button_save; ?></button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php if (in_array("image", $columns) || in_array("images", $actions)) { ?>
<!-- image manager -->
<div class="modal fade" id="modal-image" tabindex="-1" role="dialog" aria-labelledby="imageManagerModalLabel" aria-hidden="true">
</div><!-- /.modal -->
<?php } ?>

<!-- action menu modal -->
<div class="modal fade modal-60p" id="actionQuickEditModal" tabindex="-1" role="dialog" aria-labelledby="actionQuickEditModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="actionQuickEditModalLabel"></h4>
			</div>
			<div class="modal-body bull5i-container">
				<div class="notice">
				</div>
				<form enctype="multipart/form-data" id="actionQuickEditForm" onsubmit="return false;">
					<fieldset>
					</fieldset>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default cancel" data-dismiss="modal"><i class="fa fa-times"></i> <?php echo $button_close; ?></button>
				<button type="button" class="btn btn-primary submit" data-form="#actionQuickEditForm" data-context="#actionQuickEditModal" data-vm="actionQuickEditVM" data-loading-text="<i class='fa fa-spinner fa-spin'></i> <?php echo $text_saving; ?>"><i class="fa fa-save"></i> <?php echo $button_save; ?></button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<ul class="breadcrumb bull5i-breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li<?php echo ($breadcrumb['active']) ? ' class="active"' : ''; ?>><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>
			<div class="navbar-placeholder">
				<nav class="navbar navbar-bull5i" role="navigation" id="bull5i-navbar">
					<div class="nav-container">

						<div class="navbar-header">
							<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bull5i-navbar-collapse">
								<span class="sr-only"><?php echo $text_toggle_navigation; ?></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							<h1 class="bull5i-navbar-brand"><i class="fa fa-cubes fa-fw ext-icon"></i> <?php echo $heading_title; ?></h1>
						</div>
						<div class="collapse navbar-collapse" id="bull5i-navbar-collapse">
							<div class="navbar-right">
								<div class="nav navbar-nav navbar-checkbox hidden" id="batch-edit-container">
									<div class="checkbox">
										<label>
											<input type="checkbox" id="batch-edit"> <?php echo $text_batch_edit; ?>
										</label>
									</div>
								</div>
								<div class="nav navbar-nav navbar-form" id="nav-search">
									<div class="form-group search-form">
										<label for="global-search" class="sr-only"><?php echo $text_search; ?></label>
										<div class="search">
											<div class="input-group">
												<input type="text" name="search" class="form-control" placeholder="<?php echo $text_search;?>" id="global-search" value="<?php echo $search; ?>">
												<span class="input-group-btn">
													<button type="button" class="btn btn-default" data-toggle="tooltip" data-container="body" data-placement="bottom" title="<?php echo $text_search; ?>" id="global-search-btn"><i class="fa fa-search fa-fw"></i></button>
													<!-- ko if: value() != '' --><button type="button" class="btn btn-default" data-bind="tooltip: {container: 'body', placement: 'bottom'}" title="<?php echo $text_clear_search; ?>" id="clear-global-search-btn"><i class="fa fa-times fa-fw"></i></button><!-- /ko -->
												</span>
											</div>
										</div>
									</div>
								</div>
								<div class="nav navbar-nav navbar-btn bull5i-navbar-buttons btn-group">
									<button type="button" class="btn btn-primary" data-toggle="tooltip" data-container="body" data-placement="bottom" title="<?php echo $button_add; ?>" data-url="<?php echo $add; ?>" id="btn-insert" data-form="#pqe-list-form" data-context="#content"><i class="fa fa-plus"></i> <span class="visible-lg-inline visible-xs-inline"><?php echo $button_add; ?></span></button>
									<button type="button" class="btn btn-default" data-toggle="tooltip" data-container="body" data-placement="bottom" title="<?php echo $button_copy; ?>" data-url="<?php echo $copy; ?>" id="btn-copy" data-form="#pqe-list-form" data-context="#content" data-loading-text="<i class='fa fa-spinner fa-spin'></i> <?php echo $text_copying; ?>" disabled><i class="fa fa-files-o"></i> <span class="visible-lg-inline visible-xs-inline"><?php echo $button_copy; ?></span></button>
									<button type="button" class="btn btn-danger" data-toggle="tooltip" data-container="body" data-placement="bottom" title="<?php echo $button_delete; ?>" data-url="<?php echo $delete; ?>" id="btn-delete" data-form="#pqe-list-form" data-context="#content" data-loading-text="<i class='fa fa-spinner fa-spin'></i> <?php echo $text_deleting; ?>" disabled><i class="fa fa-trash-o"></i> <span class="visible-lg-inline visible-xs-inline"><?php echo $button_delete; ?></span></button>
								</div>
								<ul class="nav navbar-nav navbar-btn bull5i-navbar-buttons">
									<li class="dropdown">
										<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" id="btn-settings"><i class="fa fa-cog"></i> <span class="visible-lg-inline visible-xs-inline"><?php echo $text_settings; ?></span> <b class="caret"></b></button>
										<ul class="dropdown-menu" role="menu">
											<li><a href="#" data-toggle="modal" data-target="#displayLengthModal"><?php echo $text_items_per_page; ?></a></li>
											<li><a href="#" data-toggle="modal" data-target="#pageColumnsModal"><?php echo $text_choose_columns; ?></a></li>
											<li class="divider"></li>
											<li><a href="#" data-toggle="modal" data-target="#otherSettingsModal"><?php echo $text_other_settings; ?></a></li>
											<li class="divider"></li>
											<li><a href="<?php echo $clear_cache; ?>" id="clearCache"><?php echo $text_clear_cache; ?></a></li>
										</ul>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</nav>
			</div>
		</div>
	</div>

	<div class="alerts">
		<div class="container-fluid" id="alerts">
			<?php foreach ($alerts as $type => $_alerts) { ?>
				<?php foreach ((array)$_alerts as $alert) { ?>
					<?php if ($alert) { ?>
			<div class="alert alert-<?php echo ($type == "error") ? "danger" : $type; ?> fade in">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<i class="fa <?php echo $alert_icon($type); ?>"></i><?php echo $alert; ?>
			</div>
					<?php } ?>
				<?php } ?>
			<?php } ?>
		</div>
	</div>

	<div class="container-fluid bull5i-content bull5i-container">
		<div id="dT_processing" class="dataTables_processing fade"><i class="fa fa-refresh fa-spin fa-5x"></i></div>
		<form method="post" enctype="multipart/form-data" id="pqe-list-form" class="form-horizontal" role="form">
			<fieldset>
				<div class="table-responsive">
					<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-condensed<?php echo ($aqe_row_hover_highlighting) ? ' table-hover' : ''; ?><?php echo ($aqe_alternate_row_colour) ? ' table-striped' : ''; ?>" id="dT">
						<thead>
							<tr>
								<?php foreach ($columns as $col) {
								 switch($col) {
									case 'selector': ?>
								<th class="<?php echo $column_info[$col]['align']; ?> col_<?php echo $col; ?>" width="1"><input type="checkbox" id="dT-selector" /></th>
									<?php break;
									case 'image': ?>
								<th class="<?php echo $column_info[$col]['align']; ?> col_<?php echo $col; ?>" width="1"><?php echo $column_info[$col]['name']; ?></th>
									<?php break;
									default: ?>
								<th class="<?php echo $column_info[$col]['align']; ?> col_<?php echo $col; ?>"><?php echo $column_info[$col]['name']; ?></th>
									<?php break;
								 } ?>
								<?php } ?>
							</tr>
							<tr class="filters">
								<?php foreach ($columns as $col) {
								 switch($col) {
									case 'view_in_store':
									case 'selector':
									case 'image': ?>
								<th></th>
									<?php break;
									case 'status': ?>
								<th class="<?php echo $column_info[$col]['align']; ?>">
									<select name="filter_<?php echo $col; ?>" class="form-control input-sm search_init fltr <?php echo $col; ?>" data-column="<?php echo $col; ?>">
										<option value="" selected></option>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
									</select>
								</th>
									<?php break;
									case 'subtract':
									case 'shipping': ?>
								<th class="<?php echo $column_info[$col]['align']; ?>">
									<select name="filter_<?php echo $col; ?>" class="form-control input-sm search_init fltr <?php echo $col; ?>" data-column="<?php echo $col; ?>">
										<option value="" selected></option>
										<option value="1"><?php echo $text_yes; ?></option>
										<option value="0"><?php echo $text_no; ?></option>
									</select>
								</th>
									<?php break;
									case 'action': ?>
								<th class="<?php echo $column_info[$col]['align']; ?>">
									<div class="btn-group btn-group-flex">
										<button type="button" class="btn btn-sm btn-default" id="filter" data-toggle="tooltip" data-container="body" title="<?php echo $text_filter; ?>"><i class="fa fa-filter fa-fw"></i></button>
										<button type="button" class="btn btn-sm btn-default" id="clear-filter" data-toggle="tooltip" data-container="body" title="<?php echo $text_clear_filter; ?>"><i class="fa fa-times fa-fw"></i></button>
									</div>
								</th>
									<?php break;
									case 'manufacturer': ?>
									<?php if (in_array($col, array_keys($typeahead))) { ?>
								<th class="<?php echo $column_info[$col]['align']; ?>">
									<input type="text" class="form-control input-sm fltr <?php echo $col; ?> typeahead id_based" placeholder="<?php echo $text_autocomplete; ?>">
									<input type="hidden" name="filter_<?php echo $col; ?>" class="search_init fltr <?php echo $col; ?>" data-column="<?php echo $col; ?>">
								</th>
									<?php } else { ?>
								<th class="<?php echo $column_info[$col]['align']; ?>">
									<select name="filter_<?php echo $col; ?>" class="form-control input-sm search_init fltr <?php echo $col; ?>" data-column="<?php echo $col; ?>">
										<option value="" selected></option>
										<option value="*"><?php echo $text_none; ?></option>
										<?php foreach ($manufacturers as $m) { ?>
										<option value="<?php echo $m['manufacturer_id']; ?>"><?php echo $m['name']; ?></option>
										<?php } ?>
									</select>
								</th>
									<?php } ?>
									<?php break;
									case 'category': ?>
									<?php if (in_array($col, array_keys($typeahead))) { ?>
								<th class="<?php echo $column_info[$col]['align']; ?>">
									<input type="text" class="form-control input-sm fltr <?php echo $col; ?> typeahead id_based" placeholder="<?php echo $text_autocomplete; ?>">
									<input type="hidden" name="filter_<?php echo $col; ?>" class="search_init fltr <?php echo $col; ?>" data-column="<?php echo $col; ?>">
								</th>
									<?php } else { ?>
								<th class="<?php echo $column_info[$col]['align']; ?>">
									<select name="filter_<?php echo $col; ?>" class="form-control input-sm search_init fltr <?php echo $col; ?>" data-column="<?php echo $col; ?>">
										<option value="" selected></option>
										<option value="*"><?php echo $text_none; ?></option>
										<?php foreach ($categories as $c) { ?>
										<option value="<?php echo $c['category_id']; ?>"><?php echo $c['name']; ?></option>
										<?php } ?>
									</select>
								</th>
									<?php } ?>
									<?php break;
									case 'download': ?>
									<?php if (in_array($col, array_keys($typeahead))) { ?>
								<th class="<?php echo $column_info[$col]['align']; ?>">
									<input type="text" class="form-control input-sm fltr <?php echo $col; ?> typeahead id_based" placeholder="<?php echo $text_autocomplete; ?>">
									<input type="hidden" name="filter_<?php echo $col; ?>" class="search_init fltr <?php echo $col; ?>" data-column="<?php echo $col; ?>">
								</th>
									<?php } else { ?>
								<th class="<?php echo $column_info[$col]['align']; ?>">
									<select name="filter_<?php echo $col; ?>" class="form-control input-sm search_init fltr <?php echo $col; ?>" data-column="<?php echo $col; ?>">
										<option value="" selected></option>
										<option value="*"><?php echo $text_none; ?></option>
										<?php foreach ($downloads as $dl) { ?>
										<option value="<?php echo $dl['download_id']; ?>"><?php echo $dl['name']; ?></option>
										<?php } ?>
									</select>
								</th>
									<?php } ?>
									<?php break;
									case 'filter': ?>
									<?php if (in_array($col, array_keys($typeahead))) { ?>
								<th class="<?php echo $column_info[$col]['align']; ?>">
									<input type="text" class="form-control input-sm fltr <?php echo $col; ?> typeahead id_based" placeholder="<?php echo $text_autocomplete; ?>">
									<input type="hidden" name="filter_<?php echo $col; ?>" class="search_init fltr <?php echo $col; ?>" data-column="<?php echo $col; ?>">
								</th>
									<?php } else { ?>
								<th class="<?php echo $column_info[$col]['align']; ?>">
									<select name="filter_<?php echo $col; ?>" class="form-control input-sm search_init fltr <?php echo $col; ?>" data-column="<?php echo $col; ?>">
										<option value="" selected></option>
										<option value="*"><?php echo $text_none; ?></option>
										<?php foreach ($filters as $fg) { ?>
										<optgroup label="<?php echo addslashes($fg['name']); ?>">
										<?php foreach ($fg['filters'] as $f) { ?>
											<option value="<?php echo $f['filter_id']; ?>"><?php echo $f['name']; ?></option>
										<?php } ?>
										</optgroup>
										<?php } ?>
									</select>
								</th>
									<?php } ?>
									<?php break;
									case 'store': ?>
								<th class="<?php echo $column_info[$col]['align']; ?>">
									<select name="filter_<?php echo $col; ?>" class="form-control input-sm search_init fltr <?php echo $col; ?>" data-column="<?php echo $col; ?>">
										<option value="" selected></option>
										<option value="*"><?php echo $text_none; ?></option>
										<?php foreach ($stores as $s) { ?>
										<option value="<?php echo $s['store_id']; ?>"><?php echo $s['name']; ?></option>
										<?php } ?>
									</select>
								</th>
									<?php break;
									case 'length_class': ?>
								<th class="<?php echo $column_info[$col]['align']; ?>">
									<select name="filter_<?php echo $col; ?>" class="form-control input-sm search_init fltr <?php echo $col; ?>" data-column="<?php echo $col; ?>">
										<option value="" selected></option>
										<?php foreach ($length_classes as $lc) { ?>
										<option value="<?php echo $lc['length_class_id']; ?>"><?php echo $lc['title']; ?></option>
										<?php } ?>
									</select>
								</th>
									<?php break;
									case 'weight_class': ?>
								<th class="<?php echo $column_info[$col]['align']; ?>">
									<select name="filter_<?php echo $col; ?>" class="form-control input-sm search_init fltr <?php echo $col; ?>" data-column="<?php echo $col; ?>">
										<option value="" selected></option>
										<?php foreach ($weight_classes as $wc) { ?>
										<option value="<?php echo $wc['weight_class_id']; ?>"><?php echo $wc['title']; ?></option>
										<?php } ?>
									</select>
								</th>
									<?php break;
									case 'stock_status': ?>
								<th class="<?php echo $column_info[$col]['align']; ?>">
									<select name="filter_<?php echo $col; ?>" class="form-control input-sm search_init fltr <?php echo $col; ?>" data-column="<?php echo $col; ?>">
										<option value="" selected></option>
										<?php foreach ($stock_statuses as $ss) { ?>
										<option value="<?php echo $ss['stock_status_id']; ?>"><?php echo $ss['name']; ?></option>
										<?php } ?>
									</select>
								</th>
									<?php break;
									case 'tax_class': ?>
								<th class="<?php echo $column_info[$col]['align']; ?>">
									<select name="filter_<?php echo $col; ?>" class="form-control input-sm search_init fltr <?php echo $col; ?>" data-column="<?php echo $col; ?>">
										<option value="" selected></option>
										<option value="*"><?php echo $text_none; ?></option>
										<?php foreach ($tax_classes as $tc) { ?>
										<option value="<?php echo $tc['tax_class_id']; ?>"><?php echo $tc['title']; ?></option>
										<?php } ?>
									</select>
								</th>
									<?php break;
									case 'price': ?>
								<th class="<?php echo $column_info[$col]['align']; ?>">
									<div class="input-group">
										<input type="text" name="filter_<?php echo $col; ?>" class="form-control input-sm search_init fltr <?php echo $col; ?>" id="filter_price" data-column="<?php echo $col; ?>">
										<input type="hidden" value="" id="filter_special_price">
										<div class="input-group-btn" data-toggle="buttons">
											<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" tabindex="-1">
												<span class="caret"></span>
												<span class="sr-only"><?php echo $text_toggle_dropdown; ?></span>
											</button>
											<ul class="dropdown-menu text-left pull-right price" role="menu">
												<li class="active"><a href="#" class="filter-special-price" data-value="" id="special-price-off"><i class="fa fa-fw fa-check"></i> <?php echo $text_special_off; ?></a></li>
												<li><a href="#" class="filter-special-price" data-value="active"><i class="fa fa-fw"></i> <?php echo $text_special_active; ?></a></li>
												<li><a href="#" class="filter-special-price" data-value="expired"><i class="fa fa-fw"></i> <?php echo $text_special_expired; ?></a></li>
												<li><a href="#" class="filter-special-price" data-value="future"><i class="fa fa-fw"></i> <?php echo $text_special_future; ?></a></li>
											</ul>
										</div>
									</div>
								</th>
									<?php break;
									case 'name':
									case 'model':
									case 'sku':
									case 'upc':
									case 'ean':
									case 'jan':
									case 'isbn':
									case 'mpn':
									case 'location':
									case 'seo': ?>
								<th class="<?php echo $column_info[$col]['align']; ?>"><input type="text" name="filter_<?php echo $col; ?>" class="form-control input-sm search_init fltr <?php echo $col; ?> typeahead" placeholder="<?php echo $text_autocomplete; ?>" data-column="<?php echo $col; ?>"></th>
									<?php break;
									default: ?>
								<th class="<?php echo $column_info[$col]['align']; ?>"><input type="text" name="filter_<?php echo $col; ?>" class="form-control input-sm search_init fltr <?php echo $col; ?>" data-column="<?php echo $col; ?>"></th>
									<?php break;
								 } ?>
								<?php } ?>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</fieldset>
		</form>
	</div>

</div>
<div class="row-fluid"><div class="col-xs-12 text-center"><small class="text-muted" id="ajax-request-container"></small></div></div>
<div class="row-fluid"><div class="col-xs-12 text-center"><small class="text-muted"><?php echo $text_page_created; ?></small></div></div>
<script type="text/javascript"><!--
(function(bull5i,$,undefined){var errors=<?php echo json_encode($errors); ?>,related=<?php echo json_encode($related); ?>,active_filters=<?php echo json_encode($active_filters); ?>,values={displayLengthModal:{aqe_items_per_page:parseInt("<?php echo (int)$items_per_page; ?>")},otherSettingsModal:{aqe_list_view_image_width:parseInt("<?php echo (int)$aqe_list_view_image_width; ?>"),aqe_list_view_image_height:parseInt("<?php echo (int)$aqe_list_view_image_height; ?>"),aqe_quick_edit_on:"<?php echo addslashes($aqe_quick_edit_on); ?>",aqe_server_side_caching:"<?php echo $aqe_server_side_caching; ?>",aqe_client_side_caching:"<?php echo $aqe_client_side_caching; ?>",aqe_cache_size:parseInt("<?php echo (int)$aqe_cache_size ? $aqe_cache_size : 1000; ?>"),aqe_alternate_row_colour:"<?php echo $aqe_alternate_row_colour; ?>",aqe_row_hover_highlighting:"<?php echo $aqe_row_hover_highlighting; ?>",aqe_highlight_status:"<?php echo $aqe_highlight_status; ?>",aqe_highlight_filtered_columns:"<?php echo $aqe_highlight_filtered_columns; ?>",aqe_highlight_actions:"<?php echo $aqe_highlight_actions; ?>",aqe_filter_sub_category:"<?php echo $aqe_filter_sub_category; ?>",aqe_debug_mode:"<?php echo (int)$aqe_debug_mode; ?>"},pageColumnsModal:{product_actions:<?php echo json_encode($product_actions); ?>,product_columns:<?php echo json_encode($product_columns); ?>},search:{value:"<?php echo addslashes($search); ?>"}},ta_sources=[];bull5i.token="<?php echo $token; ?>",bull5i.debugging=!!parseInt("<?php echo (int)$aqe_debug_mode; ?>"),bull5i.clearCache=parseInt("<?php echo (int)$clear_dt_cache; ?>"),bull5i.highlight_filtered_columns=parseInt(values.otherSettingsModal.aqe_highlight_filtered_columns),bull5i.texts=$.extend({},bull5i.texts,{text_request_handled:"<?php echo addslashes($text_request_handled); ?>",error_ajax_request:"<?php echo addslashes($error_ajax_request); ?>",error_items_per_page:"<?php echo addslashes($error_items_per_page); ?>",error_image_width:"<?php echo addslashes($error_image_width); ?>",error_image_height:"<?php echo addslashes($error_image_height); ?>",error_cache_size:"<?php echo addslashes($error_cache_size); ?>"});var Column=function(e,i,t,a,s){this.column=e,this.index=ko.observable(i),this.name=t,this.editable=ko.observable(s),this.visible=ko.observable(a)},displayLengthModalViewModel=function(){var e=this;validate_items_per_page=function(e){isNaN(parseInt(e))||0==parseInt(e)||parseInt(e)<-1?(this.target.hasError(!0),this.target.errorMsg(this.message)):(this.target.hasError(!1),this.target.errorMsg(""))},this.aqe_items_per_page=ko.observable(0).extend({numeric:{precision:0,context:e},validate:{message:bull5i.texts.error_items_per_page,context:e,method:validate_items_per_page}}),this.aqe_items_per_page.subscribe(function(e){void 0!=bull5i.dtApi&&bull5i.dtApi.page.len(parseInt(e))})};displayLengthModalViewModel.prototype=new bull5i.observable_object_methods;var otherSettingsModalViewModel=function(){var e=this;validate_image_size=function(e){isNaN(parseInt(e))||parseInt(e)<1?(this.target.hasError(!0),this.target.errorMsg(this.message)):(this.target.hasError(!1),this.target.errorMsg(""))},validate_cache_size=function(e){isNaN(parseInt(e))||parseInt(e)<1||bull5i.view_models&&bull5i.view_models.displayLengthVM&&bull5i.view_models.displayLengthVM.aqe_items_per_page()>0&&parseInt(e)<bull5i.view_models.displayLengthVM.aqe_items_per_page()?(this.target.hasError(!0),this.target.errorMsg(this.message)):(this.target.hasError(!1),this.target.errorMsg(""))},this.aqe_list_view_image_width=ko.observable(40).extend({numeric:{precision:0,context:e},validate:{message:bull5i.texts.error_image_width,context:e,method:validate_image_size}}),this.aqe_list_view_image_height=ko.observable(40).extend({numeric:{precision:0,context:e},validate:{message:bull5i.texts.error_image_height,context:e,method:validate_image_size}}),this.aqe_quick_edit_on=ko.observable("click"),this.aqe_server_side_caching=ko.observable("0"),this.aqe_client_side_caching=ko.observable("0"),this.aqe_cache_size=ko.observable(0).extend({numeric:{precision:0,context:e},validate:{message:bull5i.texts.error_cache_size,context:e,method:validate_cache_size}}),this.aqe_alternate_row_colour=ko.observable("0"),this.aqe_row_hover_highlighting=ko.observable("0"),this.aqe_highlight_status=ko.observable("0"),this.aqe_highlight_filtered_columns=ko.observable("0"),this.aqe_highlight_actions=ko.observable("0"),this.aqe_filter_sub_category=ko.observable("0"),this.aqe_debug_mode=ko.observable(0),this.aqe_list_view_image_width.subscribe(function(){void 0!=bull5i.dtApi&&bull5i.dtApi.clearPipeline()}),this.aqe_list_view_image_height.subscribe(function(){void 0!=bull5i.dtApi&&bull5i.dtApi.clearPipeline()}),this.aqe_debug_mode.subscribe(function(e){bull5i.debugging=!!parseInt(e)}),this.aqe_alternate_row_colour.subscribe(function(e){var i=$("#dT");parseInt(e)?i.addClass("table-striped"):i.removeClass("table-striped")}),this.aqe_row_hover_highlighting.subscribe(function(e){var i=$("#dT");parseInt(e)?i.addClass("table-hover"):i.removeClass("table-hover")}),this.aqe_highlight_status.subscribe(function(){void 0!=bull5i.dtApi&&bull5i.dtApi.clearPipeline()}),this.aqe_highlight_filtered_columns.subscribe(function(e){bull5i.highlight_filtered_columns=parseInt(e)}),this.aqe_highlight_actions.subscribe(function(){void 0!=bull5i.dtApi&&bull5i.dtApi.clearPipeline()}),this.aqe_filter_sub_category.subscribe(function(){void 0!=bull5i.dtApi&&bull5i.dtApi.clearPipeline()})};otherSettingsModalViewModel.prototype=new bull5i.observable_object_methods;var pageColumnsModalViewModel=function(){var e=this;this.product_actions=ko.observableArray([]),this.product_columns=ko.observableArray([]),e.updateValues=function(i){for(var t in e)ko.isWriteableObservable(e[t])&&i.hasOwnProperty(t)&&e[t]($.map(i[t],function(e,i){return new Column(i,e.hasOwnProperty("index")?e.index:0,e.hasOwnProperty("name")?e.name:"<unknown>",e.hasOwnProperty("display")?e.display:1,e.hasOwnProperty("editable")?e.editable:1)}))}},searchViewModel=function(){var e=this;this.value=ko.observable(""),e.updateValues=function(i){for(var t in e)ko.isWriteableObservable(e[t])&&i.hasOwnProperty(t)&&("function"==typeof e[t].updateValues?e[t].updateValues(i[t]):e[t](i[t]))},e.setSearch=function(i){e.value(i)}};bull5i.view_models=$.extend({},bull5i.view_models,{displayLengthVM:new displayLengthModalViewModel,otherSettingsVM:new otherSettingsModalViewModel,pageColumnsVM:new pageColumnsModalViewModel,searchVM:new searchViewModel}),bull5i.view_models.displayLengthVM.updateValues(values.displayLengthModal),bull5i.view_models.displayLengthVM.applyErrors(errors),bull5i.view_models.otherSettingsVM.updateValues(values.otherSettingsModal),bull5i.view_models.otherSettingsVM.applyErrors(errors),bull5i.view_models.pageColumnsVM.updateValues(values.pageColumnsModal),bull5i.view_models.searchVM.updateValues(values.search),ko.applyBindings(bull5i.view_models.displayLengthVM,$("#displayLengthModal")[0]),ko.applyBindings(bull5i.view_models.otherSettingsVM,$("#otherSettingsModal")[0]),ko.applyBindings(bull5i.view_models.pageColumnsVM,$("#pageColumnsModal")[0]),ko.applyBindings(bull5i.view_models.searchVM,$("#nav-search")[0]),$(".sortable").sortable({containerSelector:"table",itemPath:"> tbody",itemSelector:"tr",placeholder:'<tr class="placeholder"/>',distance:5,onDragStart:function(e){e.children().each(function(){$(this).width($(this).width())}),e.addClass("dragged"),$("body").addClass("dragging")},onDrag:function(e,i){i.left=0,e.css(i)},onDrop:function(e,i){e.children().each(function(){$(this).removeAttr("style")}),e.removeClass("dragged").removeAttr("style"),$("body").removeClass("dragging"),$("tbody tr",$(i.el[0])).each(function(e){var i=ko.dataFor(this);i.index(e)})}});
<?php foreach ($typeahead as $column => $attr) { ?>
<?php switch ($column) {
		case 'category': ?>
ta_sources['<?php echo $column; ?>']=new Bloodhound({<?php if (isset($attr['prefetch'])) { ?>prefetch:'<?php echo $attr['prefetch']; ?>',<?php }; if (isset($attr['remote'])) { ?>remote:'<?php echo $attr['remote']; ?>',<?php } ?>datumTokenizer:Bloodhound.tokenizers.obj.whitespace('value'),queryTokenizer:Bloodhound.tokenizers.whitespace,dupDetector:function(remoteMatch,localMatch){return remoteMatch.id&&localMatch.id&&remoteMatch.id==localMatch.id;},limit:10,});ta_sources["<?php echo $column; ?>"].initialize(),$(".<?php echo $column; ?>.typeahead").typeahead({hint:!0,highlight:!0},{name:"<?php echo $column; ?>",source:ta_sources["<?php echo $column; ?>"].ttAdapter(),templates:{empty:['<div class="tt-no-suggestion"><?php echo addslashes($text_no_records_found); ?></div>'].join("\n"),suggestion:Handlebars.compile('<p><span class="tt-nowrap"><span class="tt-secondary">{{path}}</span>{{value}}</span></p>')}});
	<?php break;
		case 'filter': ?>
ta_sources['<?php echo $column; ?>']=new Bloodhound({<?php if (isset($attr['prefetch'])) { ?>prefetch:'<?php echo $attr['prefetch']; ?>',<?php }; if (isset($attr['remote'])) { ?>remote:'<?php echo $attr['remote']; ?>',<?php } ?>datumTokenizer:Bloodhound.tokenizers.obj.whitespace('value'),queryTokenizer:Bloodhound.tokenizers.whitespace,dupDetector:function(remoteMatch,localMatch){return remoteMatch.id&&localMatch.id&&remoteMatch.id==localMatch.id;},limit:10,});ta_sources["<?php echo $column; ?>"].initialize(),$(".<?php echo $column; ?>.typeahead").typeahead({hint:!0,highlight:!0},{name:"<?php echo $column; ?>",source:ta_sources["<?php echo $column; ?>"].ttAdapter(),templates:{empty:['<div class="tt-no-suggestion"><?php echo addslashes($text_no_records_found); ?></div>'].join("\n"),suggestion:Handlebars.compile('<p><span class="tt-nowrap"><span class="tt-secondary">{{group}}</span>{{value}}</span></p>')}});
	<?php break;
		case 'name':
		case 'model':
		case 'sku':
		case 'upc':
		case 'ean':
		case 'jan':
		case 'isbn':
		case 'mpn':
		case 'location':
		case 'seo':
		case 'download':
		case 'manufacturer': ?>
	ta_sources['<?php echo $column; ?>']=new Bloodhound({<?php if (isset($attr['prefetch'])) { ?>prefetch:'<?php echo $attr['prefetch']; ?>',<?php }; if (isset($attr['remote'])) { ?>remote:'<?php echo $attr['remote']; ?>',<?php } ?>datumTokenizer:Bloodhound.tokenizers.obj.whitespace('value'),queryTokenizer:Bloodhound.tokenizers.whitespace,dupDetector:function(remoteMatch,localMatch){return remoteMatch.id&&localMatch.id&&remoteMatch.id==localMatch.id;},limit:10,});ta_sources["<?php echo $column; ?>"].initialize(),$(".<?php echo $column; ?>.typeahead").typeahead({hint:!0,highlight:!0},{name:"<?php echo $column; ?>",source:ta_sources["<?php echo $column; ?>"].ttAdapter(),templates:{empty:['<div class="tt-no-suggestion"><?php echo addslashes($text_no_records_found); ?></div>'].join("\n"),suggestion:Handlebars.compile('<p><span class="tt-nowrap">{{value}}</span></p>')}});
	<?php break;
		default:?>
	<?php break;
} ?>
<?php } ?>
function response_handler(r,e){var e="undefined"==typeof e?!0:e;return bull5i.display_alerts(r.alerts,!0),r.success?!0:r.errors&&r.errors.error?r.errors.error:(e&&$(this).editable("hide"),!1)}
function setup_editables(table){var defaultParams={ajaxOptions:{type:"POST",dataType:"json",cache:!1},type:"text",url:"<?php echo $update; ?>",toggle:"<?php echo $aqe_quick_edit_on; ?>",highlight:!1,container:"body",title:"",emptytext:"",pk:bull5i.get_pk_params,value:function(){return table.cell(this).data()},params:function(e){var a={};return a.id=e.pk.id,a.column=e.pk.column,a.old=e.pk.old,a.value=$.isArray(e.value)&&0==e.value.length?null:e.value,$("#batch-edit").is(":checked")&&$("input[name*='selected']:checked").length&&(a.ids=$("input[name*='selected']:checked").serializeObject().selected),a},success:function(e,a){var t=response_handler.call(this,e);if(t===!0){var l=table.cell(this).index().column,n=table.column(l).dataSrc();return e.value&&(a=e.value),$.isArray(e.results.done)&&($.each(e.results.done,function(t,l){var u=$("#p_"+l).get(0);if(u){var r=table.row(u).data();r[n]=a,void 0!=e.values&&(e.values.hasOwnProperty("*")&&$.extend(r,e.values["*"]),e.values.hasOwnProperty(l)&&$.extend(r,e.values[l])),"function"==typeof table.updatePipeline&&table.updatePipeline(table.row(u).index(),r)}}),e.results.done.length&&update_related(n,e.results.done)===!1&&table.draw(!1)),{newValue:a}}return t===!1?{newValue:$(this).html()}:t},display:function(){return"abrakadabra"}}
<?php if (array_reduce(array("status_qe", "yes_no_qe", "manufac_qe", "stock_qe", "tax_cls_qe", "length_qe", "weight_qe"), function($result, $type) use ($types) { return $result | in_array($type, $types); }, false) !== false) { ?>,selectParams={type:'select',showbuttons:true}<?php } ?>
<?php if (array_reduce(array("cat_qe", "dl_qe", "filter_qe", "store_qe"), function($result, $type) use ($types) { return $result | in_array($type, $types); }, false) !== false) { ?>,select2Params={type:'select2',select2:{multiple:true,allowClear:true,placeholder:'<?php echo $text_autocomplete; ?>',searchInputPlaceholder:'<?php echo $text_autocomplete; ?>',},viewseparator:'<br/>'}<?php } ?>;
<?php if (in_array("seo_qe", $types) && !$multilingual_seo) { ?>$("td.seo_qe").editable(defaultParams);<?php } ?>
<?php if (in_array("qe", $types)) { ?>$("td.qe").editable(defaultParams);<?php } ?>
<?php if (in_array("date_qe", $types)) { ?>$("td.date_qe").editable($.extend(!0,{},defaultParams,{type:"combodate",format:"YYYY-MM-DD",template:"D / MMMM / YYYY",combodate:{smartDays:!0,maxYear:<?php echo date("Y") + 5; ?>}}));<?php } ?>
<?php if (in_array("datetime_qe", $types)) { ?>$("td.datetime_qe").editable($.extend(!0,{},defaultParams,{type:"combodate",format:"YYYY-MM-DD HH:mm:ss",template:"D / MMM / YYYY  HH:mm:ss",combodate:{smartDays:!0,maxYear:<?php echo date("Y") + 5; ?>,minuteStep:1}}));<?php } ?>
<?php if (in_array("status_qe", $types)) { ?>$("td.status_qe").editable($.extend({},defaultParams,selectParams,{source:[{value:0,text:"<?php echo addslashes($text_disabled); ?>"},{value:1,text:"<?php echo addslashes($text_enabled); ?>"}]}));<?php } ?>
<?php $multilingual_text = array(); foreach (array('name_qe', 'tag_qe', 'seo_qe') as $type) { if (in_array($type, $types) && ($type == 'seo_qe' && $multilingual_seo || $type != 'seo_qe')) $multilingual_text[] = 'td.' . $type; } ?>
<?php if ($multilingual_text) { ?>$("<?php echo implode(',', $multilingual_text); ?>").editable($.extend({},defaultParams,{type:"multilingual_text",source:"<?php echo $load; ?>",sourceOptions:function(){var e=table.cell(this).index().row,r=table.cell(this).index().column,a={type:"POST",dataType:"json",data:{id:table.row(e).data().id,column:table.column(r).dataSrc()}};return a},sourceLoaded:function(e){return bull5i.display_alerts(e.alerts,!0),e.success?e.data:e.errors&&e.errors.error?e.errors.error:null},showbuttons:"bottom",value:null,success:function(e,r){if(result=response_handler.call(this,e,!1),result===!0){var a=table.cell(this).index().column,l=table.column(a).dataSrc();return e.value&&(r=e.value),$.isArray(e.results.done)&&($.each(e.results.done,function(a,t){var s=$("#p_"+t).get(0);if(s){var o=table.row(s).data();o[l]=r,void 0!=e.values&&(e.values.hasOwnProperty("*")&&$.extend(!0,o,e.values["*"]),e.values.hasOwnProperty(t)&&$.extend(!0,o,e.values[t])),"function"==typeof table.updatePipeline&&table.updatePipeline(table.row(s).index(),o)}}),e.results.done.length&&update_related(l,e.results.done)===!1&&table.draw(!1)),{newValue:r}}var t=$(this).data("editable").input.$tpl;return t&&(t.find(".form-group").removeClass("has-error"),t.find(".form-group .help-block").remove()),result===!1?e.errors&&$.isArray(e.errors.value)&&t?($.each(e.errors.value,function(e,r){var a=r.lang,l=r.text,s=t.find('.form-group[data-lang="'+a+'"]');s.addClass("has-error").append($("<span/>",{"class":"help-block"}).html(l))}),!1):{newValue:$(this).html()}:result}}));<?php } ?>
<?php if (in_array("yes_no_qe", $types)) { ?>$("td.yes_no_qe").editable($.extend({},defaultParams,selectParams,{source:[{value:0,text:"<?php echo addslashes($text_no); ?>"},{value:1,text:"<?php echo addslashes($text_yes); ?>"}]}));<?php } ?>
<?php if (in_array("manufac_qe", $types)) { ?>$("td.manufac_qe").editable($.extend({},defaultParams,selectParams,{<?php if ($manufacturer_select !== false) { ?>source:<?php echo $manufacturer_select; ?>,prepend:[{value:0,text:'<?php echo addslashes($text_none); ?>'}]<?php } else { ?>type:"typeaheadjs",showbuttons:!0,typeahead:{options:{hint:!0,highlight:!0},dataset:{name:"manufacturer",source:ta_sources.manufacturer.ttAdapter(),templates:{empty:['<div class="tt-no-suggestion"><?php echo addslashes($text_no_records_found); ?></div>'].join("\n"),suggestion:Handlebars.compile('<p><span class="tt-nowrap">{{value}}</span></p>')}}},tpl:'<input type="text" placeholder="<?php echo $text_autocomplete; ?>">',value2input:function(t){var e=this.options.scope,a=bull5i.dtApi.cell(e).index().row,n=bull5i.dtApi.cell(e).index().column,i=bull5i.dtApi.row(a).data(),p=bull5i.dtApi.column(n).dataSrc(),l="";return l=""!=i[p+"_text"]?i[p+"_text"]:"<?php echo $text_none; ?>",this.$input.data("ta-selected",{value:l,id:t}),l},input2value:function(){var t=this.$input.data("ta-selected");return"undefined"!=typeof t?"*"!=t.id?t.id:0:null}<?php } ?>}));<?php } ?>
<?php if (in_array("cat_qe", $types)) { ?>$("td.cat_qe").editable($.extend(true,{},defaultParams,select2Params,{<?php if ($category_select !== false) { ?>source:<?php echo $category_select; ?>,<?php } else { ?>select2:{minimumInputLength:1,ajax:{type:"GET",url:"<?php echo $filter; ?>",dataType:"json",cache:!1,quietMillis:150,data:function(e){return{query:e,type:"category",token:"<?php echo $token; ?>"}},results:function(e){var t=[];return $.each(e,function(e,n){t.push({id:n.id,text:n.full_name})}),{results:t}}},initSelection:function(e,t){var n=[];$(e.val().split(",")).each(function(){n.push(this)}),$.ajax({type:"GET",url:"<?php echo $filter; ?>",dataType:"json",data:{query:n,type:"category",multiple:!0,token:"<?php echo $token; ?>"}}).done(function(e){var n=[];$.each(e,function(e,t){n.push({id:t.id,text:t.full_name})}),t(n)})}}<?php } ?>}));<?php } ?>
<?php if (in_array("dl_qe", $types)) { ?>$("td.dl_qe").editable($.extend(true,{},defaultParams,select2Params,{<?php if ($download_select !== false) { ?>source:<?php echo $download_select; ?>,<?php } else { ?>select2:{minimumInputLength:1,ajax:{type:"GET",url:"<?php echo $filter; ?>",dataType:"json",cache:!1,quietMillis:150,data:function(e){return{query:e,type:"download",token:"<?php echo $token; ?>"}},results:function(e){var t=[];return $.each(e,function(e,n){t.push({id:n.id,text:n.value})}),{results:t}}},initSelection:function(e,t){var n=[];$(e.val().split(",")).each(function(){n.push(this)}),$.ajax({type:"GET",url:"<?php echo $filter; ?>",dataType:"json",data:{query:n,type:"download",multiple:!0,token:"<?php echo $token; ?>"}}).done(function(e){var n=[];$.each(e,function(e,t){n.push({id:t.id,text:t.value})}),t(n)})}}<?php } ?>}));<?php } ?>
<?php if (in_array("filter_qe", $types)) { ?>$("td.filter_qe").editable($.extend(true,{},defaultParams,select2Params,{<?php if ($filter_select !== false) { ?>source:<?php echo $filter_select; ?>,<?php } else { ?>select2:{minimumInputLength:1,ajax:{type:"GET",url:"<?php echo $filter; ?>",dataType:"json",cache:!1,quietMillis:150,data:function(e){return{query:e,type:"filter",token:"<?php echo $token; ?>"}},results:function(e){var t=[],n=[];return $.each(e,function(e,u){var i=$.inArray(u.group_name);-1==i&&(i=n.length,n.push(u.group_name),t[i]={text:u.group_name,children:[]}),t[i].children.push({id:u.id,text:u.value,group:u.group})}),{results:t}}},initSelection:function(e,t){var n=[];$(e.val().split(",")).each(function(){n.push(this)}),$.ajax({type:"GET",url:"<?php echo $filter; ?>",dataType:"json",data:{query:n,type:"filter",multiple:!0,token:"<?php echo $token; ?>"}}).done(function(e){var n=[];$.each(e,function(e,t){n.push({id:t.id,text:t.full_name})}),t(n)})},formatSelection:function(e){return void 0!=e.group?e.group+e.text:e.text}}<?php } ?>}));<?php } ?>
<?php if (in_array("store_qe", $types)) { ?>$("td.store_qe").editable($.extend({},defaultParams,select2Params,{source:<?php echo $store_select; ?>}));<?php } ?>
<?php if (in_array("stock_qe", $types)) { ?>$("td.stock_qe").editable($.extend({},defaultParams,selectParams,{source:<?php echo $stock_status_select; ?>}));<?php } ?>
<?php if (in_array("tax_cls_qe", $types)) { ?>$("td.tax_cls_qe").editable($.extend({},defaultParams,selectParams,{source:<?php echo $tax_class_select; ?>}));<?php } ?>
<?php if (in_array("length_qe", $types)) { ?>$("td.length_qe").editable($.extend({},defaultParams,selectParams,{source:<?php echo $length_class_select; ?>}));<?php } ?>
<?php if (in_array("weight_qe", $types)) { ?>$("td.weight_qe").editable($.extend({},defaultParams,selectParams,{source:<?php echo $weight_class_select; ?>}));<?php } ?>
<?php if (in_array("qty_qe", $types)) { ?>$("td.qty_qe").editable($.extend({},defaultParams));<?php } ?>
<?php if (in_array("image_qe", $types)) { ?>$("td.image_qe").editable($.extend(!0,{},defaultParams,{type:"image",noImage:"<?php echo $no_image; ?>",clearText:"<?php echo $text_clear; ?>",browseText:"<?php echo $text_browse; ?>",value:function(){var e={},a=bull5i.dtApi.cell(this).data(),t=bull5i.dtApi.cell(this).index().row,i=bull5i.dtApi.row(t).data();return e.image=a,e.thumbnail=i.image_thumb,e.alt=i.image_alt,e.title=i.image_title,e},params:function(e){var a={};return a.id=e.pk.id,a.column=e.pk.column,a.old=e.pk.old,a.value=e.value,$("#batch-edit").is(":checked")&&$("input[name*='selected']:checked").length&&(a.ids=$("input[name*='selected']:checked").serializeObject().selected),a},chooseImage:function(e,a){var t="quick-edit-image",i="quick-edit-thumbnail",o="",l=$.Deferred();return $("#"+t).val(""),$.ajax({url:"index.php?route=common/filemanager&token=<?php echo $token; ?>&target="+encodeURIComponent(t),dataType:"html"}).done(function(l){$("#modal-image").append(l),$("#modal-image").modal("show"),$("#modal-image").on("hide.bs.modal",function(){var l=$("#"+i+" .content-overlay");$("#"+t).val()?(o=$("#"+t).val(),$.ajax({url:"index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image="+encodeURIComponent(o)+"&width=<?php echo $list_view_image_width; ?>&height=<?php echo $list_view_image_height; ?>",dataType:"text",beforeSend:function(){l&&l.addClass("in")}}).done(function(t){e.image=o,e.thumbnail=t,a.call(null,e)}).always(function(){l&&l.removeClass("in")})):"function"==typeof a&&a.call(null,null),$("#modal-image").off("hide.bs.modal")}).on("hidden.bs.modal",function(){$("#modal-image").empty(),$("#modal-image").off("hidden.bs.modal")})}).always(function(){l.resolve()}),l.promise()}}));<?php } ?>
<?php if (in_array("price_qe", $types)) { ?>$("td.price_qe").editable($.extend({},defaultParams));<?php } ?>
}
function show_quick_edit_modal(i,o){var l=$.Deferred();$("#actionQuickEditModal .modal-body form fieldset").html(i.data),i.title&&$("#actionQuickEditModal .modal-title").html(i.title),$("#actionQuickEditModal").modal("show"),$("#actionQuickEditModal").on("click",".modal-footer .cancel",function(){l.resolve()}).on("click",".modal-footer .submit",function(){if("function"==typeof o){{$("#actionQuickEditModal .modal-body form").serializeObject()}o.call(this,$("#actionQuickEditModal .modal-body form")).done(function(){l.resolve()})}else l.resolve()}),$("#actionQuickEditModal").on("hide.bs.modal",function(){$("#actionQuickEditModal").off("hide.bs.modal")}).on("hidden.bs.modal",function(){$("#actionQuickEditModal .modal-body form fieldset").empty(),$("#actionQuickEditModal .modal-body .notice").empty(),$("#actionQuickEditModal .modal-title").empty(),$("#actionQuickEditModal").off("hidden.bs.modal"),$("#actionQuickEditModal").off("click",".modal-footer .cancel"),$("#actionQuickEditModal").off("click",".modal-footer .submit")}),l.always(function(){$("#actionQuickEditModal").modal("hide")})}function update_related(i,o){if(o&&related[i]&&related[i].length){var l={};return $.each(related[i],function(i,t){l[t]=o}),bull5i.processing&&bull5i.processing(!0,"related"),$.ajax({url:"<?php echo $reload; ?>",type:"POST",cache:!1,dataType:"json",data:{data:l}}).done(function(i){i.values&&void 0!=bull5i.dtApi&&$.each(i.values,function(i,o){var l=$("#p_"+i).get(0);if(l){var t=bull5i.dtApi.row(l).data();$.extend(t,o),"function"==typeof bull5i.dtApi.updatePipeline&&bull5i.dtApi.updatePipeline(bull5i.dtApi.row(l).index(),t)}}),bull5i.display_alerts(i.alerts,!0,$("#alerts"))}).fail($.proxy(bull5i.ajax_fail,{alerts:$("#alerts")})).always(function(){void 0!=bull5i.dtApi&&bull5i.dtApi.draw(!1),bull5i.processing&&bull5i.processing(!1,"related")}),!0}return!1}
$(function(){$('#dT').DataTable({dom:"t<'row-fluid'<'col-xs-6'i><'col-xs-6 text-right'p>>",serverSide:true,<?php if ((int)$aqe_client_side_caching) { ?>ajax:$.fn.dataTable.pipeline({url:"<?php echo $source; ?>",data:function(e){var a=$("#filter_special_price");e.token="<?php echo $token; ?>",a.length&&a.val()&&(e.filter_special_price=a.val())},pages:parseInt("<?php echo ((int)$aqe_cache_size && ceil((int)$aqe_cache_size / (int)$items_per_page) > 0) ? ceil((int)$aqe_cache_size / (int)$items_per_page) : 1; ?>")}),<?php } else { ?>ajax:{type:"POST",url:"<?php echo $source; ?>",data:function(e){var a=$("#filter_special_price");e.token="<?php echo $token; ?>",a.length&&a.val()&&(e.filter_special_price=a.val())}},<?php } ?>
initComplete:function(e){if(""!=e.oPreviousSearch.sSearch){var a=ko.contextFor($("#global-search-btn").get(0));$("#global-search").val(e.oPreviousSearch.sSearch),a&&a.$root.setSearch(e.oPreviousSearch.sSearch)}for(i=0,len=e.aoColumns.length;len>i;i++)e.aoPreSearchCols[i].sSearch.length>0&&$("tr.filters .fltr."+e.aoColumns[i].name+":input").not(".typeahead").val(e.aoPreSearchCols[i].sSearch);if(e.oLoadedState&&void 0!=e.oLoadedState.specialPrice&&""!=e.oLoadedState.specialPrice&&($special=$('ul.price .filter-special-price[data-value="'+e.oLoadedState.specialPrice+'"]'),$special.length&&bull5i.update_special_price_menu($special)),e.oLoadedState&&void 0!=e.oLoadedState.typeaheads&&void 0!=e.oLoadedState.typeaheads)for(key in e.oLoadedState.typeaheads)$(".fltr.typeahead.tt-input."+key).data("ta-selected",{value:e.oLoadedState.typeaheads[key]}).data("ta-name",key).typeahead("val",e.oLoadedState.typeaheads[key])},stateSaveParams:function(e,a){a.specialPrice=$("#filter_special_price").length?$("#filter_special_price").val():"",a.typeaheads={},$(".fltr.typeahead.tt-input").each(function(){var t=$(this).typeahead("val");name=e.aoColumns[$(this).closest("th").index()].name,a.typeaheads[name]=t})},stateLoadParams:function(e,a){if($.query.get("dTc")){var t=$.query.get("search");if(void 0!=a.specialPrice&&""!=a.specialPrice&&$("#filter_special_price").length&&$("#filter_special_price").val(a.specialPrice),void 0!=a.typeaheads)for(key in a.typeaheads)$(".fltr.typeahead.tt-input."+key).typeahead("val",a.typeaheads[key]);t&&(a.search.search=t)}else{var s=$.query,i=s.get("page"),l=new $.fn.dataTable.Api(e);a.search.search=s.get("search"),a.start=i&&parseInt(i)?parseInt(i-1)*a.length:0;for(var o=0,d=a.columns.length;d>o;o++)try{var p=l.column(o).dataSrc(),r=s.get("filter_"+p);input=$("tr.filters [name=filter_"+p+"]:input"),a.columns[o].search.search=r,input&&(input.hasClass("typeahead")?input.typeahead("val",r):input.val(r)),active_filters.hasOwnProperty(p)&&$(".fltr.typeahead.tt-input."+p).data("ta-selected",{value:active_filters[p].value}).data("ta-name",p).typeahead("val",active_filters[p].value)}catch(h){}void 0!=a.typeaheads&&delete a.typeaheads,bull5i.clearCache=!0}},rowCallback:function(e,a){$(e).data("id",a.id)},drawCallback:function(e){var a=void 0!=bull5i.dtApi?bull5i.dtApi:new $.fn.dataTable.Api(e);setup_editables(a),bull5i.update_nav_checkboxes(),bull5i.update_nav_controls()},language:{aria:{sortAscending:"<?php echo addslashes($text_sort_ascending); ?>",sortDescending:"<?php echo addslashes($text_sort_descending); ?>"},paginate:{first:"<?php echo addslashes($text_first_page); ?>",last:"<?php echo addslashes($text_last_page); ?>",next:"<?php echo addslashes($text_next_page); ?>",previous:"<?php echo addslashes($text_previous_page); ?>"},emptyTable:"<?php echo addslashes($text_empty_table); ?>",info:"<?php echo addslashes($text_showing_info); ?>",infoEmpty:"<?php echo addslashes($text_showing_info_empty); ?>",infoFiltered:"<?php echo addslashes($text_showing_info_filtered); ?>",infoPostFix:"",infoThousands:" ",loadingRecords:"<?php echo addslashes($text_loading_records); ?>",zeroRecords:"<?php echo addslashes($text_no_matching_records); ?>"},deferRender:!0,processing:!1,stateSave:!0,autoWidth:!1,sortCellsTop:!0,pageLength:parseInt("<?php echo (int)$items_per_page; ?>"),
columnDefs:[
<?php if (in_array("selector", $columns)) { ?>{targets:["col_selector"],createdCell:function(e,t,c){$(e).html($("<input/>",{type:"checkbox",name:"selected[]",value:c.id}))}},<?php } ?>
<?php if (in_array("view_in_store", $columns)) { ?>{targets:["col_view_in_store"],createdCell:function(e,t){for(var l=$("<select/>",{"class":"form-control view_in_store"}).append($("<option/>",{value:""}).html("<?php echo addslashes($text_select); ?>")),o=0;o<t.length;o++)l.append($("<option/>",{value:t[o].url}).html(t[o].name));$(e).html(l)}},<?php } ?>
<?php if (in_array("action", $columns)) { ?>{targets:["col_action"],createdCell:function(t,n,a){for(var e=$("<div/>",{"class":"btn-group btn-group-flex"}),l=0;l<n.length;l++)$btn=n[l].url?$("<a/>",{href:n[l].url,"class":"btn btn-default btn-xs",id:n[l].action+"-"+a.id,title:n[l].title}):$("<button/>",{type:"button","class":"btn btn-default btn-xs action",id:n[l].action+"-"+a.id,title:n[l].title}).data("column",n[l].action),n[l].type&&($btn.addClass(n[l].type),"view"==n[l].type&&$btn.attr("target","_blank")),a[n[l].action+"_exist"]&&$btn.addClass("btn-warning"),n[l].class&&$btn.addClass(n[l].class),n[l].name&&$btn.html(n[l].name),n[l].ref&&$btn.data("ref",n[l].ref),n[l].icon&&$btn.prepend($("<i/>",{"class":"fa fa-"+n[l].icon})),e.append($btn);$(t).html(e)}},<?php } ?>
<?php if (in_array("image", $columns)) { ?>{targets:["col_image"],createdCell:function(t,a,i){$(t).html($("<img/>",{src:i.image_thumb,alt:i.image_alt,title:i.image_title,"class":"img-thumbnail"}).data("id",i.id))}},<?php } ?>
<?php if (in_array("price", $columns)) { ?>{targets:["col_price"],createdCell:function(e,l,t){t.special?$(e).html($("<s/>").html(l)).append($("<br/>")).append($("<span/>",{"class":"text-danger"}).html(t.special)):$(e).html(l)}},<?php } ?>
<?php if (in_array("status", $columns)) { ?>{targets:["col_status"],createdCell:function(t,s,a){$(t).html(a.status_class?$("<span/>",{"class":"label label-"+a.status_class}).html(a.status_text):a.status_text)}},<?php } ?>
<?php foreach (array("category", "download", "filter", "store") as $col) { ?><?php if (in_array($col, $columns)) { ?>{targets:["col_<?php echo $col; ?>"],createdCell:function(c,t,e){var o=[];$.each(e.<?php echo $col; ?>_data,function(c,t){o.push(t.text)}),$(c).html(o.join("<br/>"))}},<?php } ?><?php } ?>
<?php foreach (array("shipping", "subtract", "stock_status", "tax_class", "length_class", "weight_class", "manufacturer", "date_added", "date_modified", "date_available") as $col) { ?><?php if (in_array($col, $columns)) { ?>{targets:["col_<?php echo $col; ?>"],createdCell:function(c,e,t){$(c).html(t.<?php echo $col; ?>_text)}},<?php } ?><?php } ?>
<?php if (in_array("quantity", $columns)) { ?>{targets:["col_quantity"],createdCell:function(t,a){var e=$("<span/>").html(a),a=parseInt(a);e.addClass(0>a?"text-danger":5>a?"text-warning":"text-success"),$(t).html(e)}},<?php } ?>
{orderable:false,targets:<?php echo $non_sortable_columns; ?>},{searchable:false,targets:['col_selector','col_action']}
],order:[],columns:[<?php foreach ($column_classes as $idx => $class) { ?><?php if ($class) { ?>{data:"<?php echo $columns[$idx]; ?>",name:"<?php echo $columns[$idx]; ?>",className:"<?php echo $class; ?>"},<?php } else { ?>{data:"<?php echo $columns[$idx]; ?>",name:"<?php echo $columns[$idx]; ?>"},<?php } ?><?php } ?>]
});
$("body").on("keydown","#otherSettingsModal",function(e){if(e.altKey&&e.shiftKey&&68==e.keyCode){var o=ko.dataFor(this);o.aqe_debug_mode("0"==o.aqe_debug_mode()?"1":"0")}}).on("click","td button.action",function(){if(void 0!=bull5i.dtApi){var e=$(this).closest("td").get(0),t=$(this).data("column"),o=bull5i.dtApi.cell(e).index().row,l=bull5i.dtApi.row(o).data(),i=l.id;void 0!=i&&void 0!=t&&(bull5i.processing&&bull5i.processing(!0,"action"),$.ajax({url:"<?php echo $load; ?>",type:"POST",cache:!1,dataType:"json",data:{id:i,column:t}}).done(function(e){e.success&&show_quick_edit_modal(e,function(e){var o=this,l=$.Deferred(),a=e.serializeObject(),n={id:i,column:t,value:$.isEmptyObject(a)?null:a,old:""},s={self:o,dfd:l,btn:$(this),form:e,vm:$(this).data("vm"),context:$($(this).data("context")),alerts:$.merge($("#alerts"),$("div.notice",$($(this).data("context"))))};return $("#batch-edit").is(":checked")&&$("input[name*='selected']:checked").length&&(n.ids=$("input[name*='selected']:checked").serializeObject().selected),$.ajax({type:"POST",url:"<?php echo $update; ?>",dataType:"json",data:n,beforeSend:function(){s.btn.button("loading"),$("fieldset",s.form).attr("disabled",!0)}}).done(function(e){s.vm&&e.values&&bull5i.view_models&&bull5i.view_models[s.vm]&&bull5i.view_models[s.vm].updateValues&&bull5i.view_models[s.vm].updateValues(e.values),s.vm&&bull5i.view_models&&bull5i.view_models[s.vm]&&bull5i.view_models[s.vm].applyErrors&&bull5i.view_models[s.vm].applyErrors(e.errors?e.errors:{}),e.success&&(void 0!=bull5i.dtApi&&void 0!=e.value&&e.results&&$.isArray(e.results.done)&&($.each(e.results.done,function(o,l){var i=$("#p_"+l).get(0);if(i){var a=bull5i.dtApi.row(i).data();a[t+"_exist"]=e.value,"function"==typeof bull5i.dtApi.updatePipeline&&bull5i.dtApi.updatePipeline(bull5i.dtApi.row(i).index(),a)}}),e.results.done.length&&update_related(t,e.results.done)===!1&&bull5i.dtApi.draw(!1)),l.resolve()),bull5i.display_alerts(e.alerts,!0,s.alerts)}).fail($.proxy(bull5i.ajax_fail,s)).always(function(){s.btn.button("reset"),$("fieldset",s.form).attr("disabled",!1)}),l.promise()}),bull5i.display_alerts(e.alerts,!0)}).fail($.proxy(bull5i.ajax_fail,{alerts:$("#alerts")})).always(function(){bull5i.processing&&bull5i.processing(!1,"action")}))}}),$.extend($.fn.select2.defaults,{formatNoMatches:function(){return"<?php echo addslashes($text_no_matches_found); ?>"},formatInputTooShort:function(e,t){var o=t-e.length;return"<?php echo addslashes($text_input_too_short); ?>".replace("%d",o)},formatInputTooLong:function(e,t){var o=e.length-t;return"<?php echo addslashes($text_input_too_long); ?>".replace("%d",o)},formatSelectionTooBig:function(e){return"<?php echo addslashes($text_selection_too_big); ?>".replace("%d",e)},formatLoadMore:function(){return"<?php echo addslashes($text_loading_more_results); ?>"},formatSearching:function(){return"<?php echo addslashes($text_searching); ?>"}}),void 0!=moment&&moment.locale("<?php echo $code; ?>"),$(document).on("click","a[data-toggle='im']",function(e){e.preventDefault(),$(this).popover({html:!0,placement:"right",trigger:"manual",content:function(){return['<button type="button" class="btn btn-primary btn-browse-image" data-toggle="tooltip" title="<?php echo $text_browse; ?>" data-loading-text="<i class=\'fa fa-fw fa-spinner fa-spin\'></i>"><i class="fa fa-pencil"></i></button>','<button type="button" class="btn btn-danger btn-clear-image" data-toggle="tooltip" title="<?php echo $text_clear; ?>"><i class="fa fa-fw fa-ban"></i></button>'].join(" ")}}),$(this).on("shown.bs.popover",function(){$("[data-toggle='tooltip']").tooltip({container:"body"})}).on("hidden.bs.popover",function(){$(this).popover("destroy")}),$(this).popover("toggle")});
});}(window.bull5i=window.bull5i||{},jQuery));
//--></script>
<?php echo $footer; ?>
