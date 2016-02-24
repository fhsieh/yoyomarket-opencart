<?php echo $header; ?>
<div class="modal fade" id="legal_text" tabindex="-1" role="dialog" aria-labelledby="legal_text_label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="legal_text_label"><?php echo $text_terms; ?></h4>
			</div>
			<div class="modal-body">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default cancel" data-dismiss="modal"><i class="fa fa-times"></i> <?php echo $button_close; ?></button>
			</div>
		</div>
	</div>
</div>
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
							<h1 class="bull5i-navbar-brand"><i class="fa fa-pencil fa-fw ext-icon"></i> <?php echo $heading_title; ?></h1>
						</div>
						<div class="collapse navbar-collapse" id="bull5i-navbar-collapse">
							<ul class="nav navbar-nav">
								<li class="active"><a href="#settings" data-toggle="tab"><!-- ko if: general_errors() --><i class="fa fa-exclamation-circle text-danger hidden" data-bind="css:{'hidden': !general_errors()}"></i> <!-- /ko --><?php echo $tab_settings; ?></a></li>
								<li><a href="#ext-support" data-toggle="tab"><?php echo $tab_support; ?></a></li>
								<li><a href="#about-ext" data-toggle="tab"><?php echo $tab_about; ?></a></li>
							</ul>
							<div class="nav navbar-nav navbar-right btn-group">
								<?php if ($update_pending) { ?><button type="button" data-toggle="tooltip" data-container="body" data-placement="bottom" title="<?php echo $button_upgrade; ?>" class="btn btn-info" id="btn-upgrade" data-url="<?php echo $upgrade; ?>" data-form="#sForm" data-context="#content" data-overlay="#page-overlay" data-loading-text="<i class='fa fa-spinner fa-spin'></i> <span class='visible-lg-inline visible-xs-inline'><?php echo $text_upgrading; ?></span>"><i class="fa fa-arrow-circle-up"></i> <span class="visible-lg-inline visible-xs-inline"><?php echo $button_upgrade; ?></span></button><?php } ?>
								<button type="button" data-toggle="tooltip" data-container="body" data-placement="bottom" title="<?php echo $button_apply; ?>" class="btn btn-success" id="btn-apply" data-url="<?php echo $save; ?>" data-form="#sForm" data-context="#content" data-vm="ExtVM" data-overlay="#page-overlay" data-loading-text="<i class='fa fa-spinner fa-spin'></i> <span class='visible-lg-inline visible-xs-inline'><?php echo $text_saving; ?></span>"<?php echo $update_pending ? ' disabled': ''; ?>><i class="fa fa-check"></i> <span class="visible-lg-inline visible-xs-inline"><?php echo $button_apply; ?></span></button>
								<button type="submit" data-toggle="tooltip" data-container="body" data-placement="bottom" title="<?php echo $button_save; ?>" class="btn btn-primary" id="btn-save" data-url="<?php echo $save; ?>" data-form="#sForm" data-context="#content" data-overlay="#page-overlay" data-loading-text="<i class='fa fa-spinner fa-spin'></i> <span class='visible-lg-inline visible-xs-inline'><?php echo $text_saving; ?></span>" <?php echo $update_pending ? ' disabled': ''; ?>><i class="fa fa-save"></i> <span class="visible-lg-inline visible-xs-inline"><?php echo $button_save; ?></span></button>
								<a href="<?php echo $cancel; ?>" class="btn btn-default" data-toggle="tooltip" data-container="body" data-placement="bottom" title="<?php echo $button_cancel; ?>" id="btn-cancel" data-loading-text="<i class='fa fa-spinner fa-spin'></i> <span class='visible-lg-inline visible-xs-inline'><?php echo $text_canceling; ?></span>"><i class="fa fa-ban"></i> <span class="visible-lg-inline visible-xs-inline"><?php echo $button_cancel; ?></span></a>
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
		<div id="page-overlay" class="bull5i-overlay fade in">
			<div class="page-overlay-progress"><i class="fa fa-refresh fa-spin fa-5x text-muted"></i></div>
		</div>

		<form action="<?php echo $save; ?>" method="post" enctype="multipart/form-data" id="sForm" class="form-horizontal" role="form">
			<div class="tab-content">
				<div class="tab-pane active" id="settings">
					<div class="panel panel-default">
						<div class="panel-heading"><h3 class="panel-title"><i class="fa fa-cog fa-fw"></i> <?php echo $tab_settings; ?></h3></div>
						<div class="panel-body">
							<div class="row">
								<div class="col-sm-12">
									<fieldset>
										<div class="form-group">
											<label class="col-sm-3 col-md-2 control-label" for="aqe_status"><?php echo $entry_extension_status; ?></label>
											<div class="col-sm-2 fc-auto-width">
												<select name="aqe_status" id="aqe_status" data-bind="value: status" class="form-control">
													<option value="1"><?php echo $text_enabled; ?></option>
													<option value="0"><?php echo $text_disabled; ?></option>
												</select>
												<input type="hidden" name="aqe_installed" value="1" />
												<input type="hidden" name="aqe_installed_version" value="<?php echo $installed_version; ?>" />
												<input type="hidden" name="aqe_multilingual_seo" value="<?php echo $aqe_multilingual_seo; ?>" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 col-md-2 control-label" for="aqe_display_in_menu_as0"><?php echo $entry_display_in_menu_as; ?></label>
											<div class="col-sm-9 col-md-10">
												<label class="radio">
													<input type="radio" name="aqe_display_in_menu_as" id="aqe_display_in_menu_as0" value="0" data-bind="checked: display_in_menu_as"> <?php echo $text_catalog_products; ?> <span class="text-muted text-inline-info"><?php echo $text_replace_old; ?></span>
												</label>
												<label class="radio">
													<input type="radio" name="aqe_display_in_menu_as" id="aqe_display_in_menu_as1" value="1" data-bind="checked: display_in_menu_as"> <?php echo $text_catalog_products_qe; ?> <span class="text-muted text-inline-info"><?php echo $text_add_as_new; ?></span>
												</label>
											</div>
										</div>
									</fieldset>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="alert alert-info"><?php echo $text_more_options; ?></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="ext-support">
					<div class="panel panel-default">
						<div class="panel-heading">
							<div class="navbar-header">
								<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#support-navbar-collapse">
									<span class="sr-only"><?php echo $text_toggle_navigation; ?></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
								<h3 class="panel-title"><i class="fa fa-phone fa-fw"></i> <?php echo $tab_support; ?></h3>
							</div>
							<div class="collapse navbar-collapse" id="support-navbar-collapse">
								<ul class="nav navbar-nav">
									<li class="active"><a href="#general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
									<li><a href="#faq" data-toggle="tab" title="<?php echo $text_faq; ?>"><?php echo $tab_faq; ?></a></li>
									<li><a href="#services" data-toggle="tab"><?php echo $tab_services; ?></a></li>
								</ul>
							</div>
						</div>
						<div class="panel-body">
							<div class="tab-content">
								<div class="tab-pane active" id="general">
									<div class="row">
										<div class="col-sm-12">
											<h3>Getting support</h3>
											<p>I consider support a priority of mine, so if you need any help with your purchase you can contact me in one of the following ways:</p>
											<ul>
												<li>Send an email to <a href="mailto:<?php echo $ext_support_email; ?>?subject='<?php echo $text_support_subject; ?>'"><?php echo $ext_support_email; ?></a></li>
												<li>Post in the <a href="<?php echo $ext_support_forum; ?>" target="_blank">extension forum thread</a> or send me a <a href="http://forum.opencart.com/ucp.php?i=pm&mode=compose&u=17771">private message</a></li>
												<!--li><a href="<?php echo $ext_store_url; ?>" target="_blank">Leave a comment</a> in the extension store comments section</li-->
											</ul>
											<p>I usually reply within a few hours, but can take up to 36 hours.</p>
											<p>Please note that all support is free if it is an issue with the product. Only issues due conflicts with other third party extensions/modules or custom front end theme are the exception to free support. Resolving such conflicts, customizing the extension or doing additional bespoke work will be provided with the hourly rate of <span id="hourly_rate">USD 50 / EUR 40</span>.</p>

											<h4>Things to note when asking for help</h4>
											<p>Please describe your problem in as much detail as possible. When contacting, please provide the following information:</p>
											<ul>
												<li>The OpenCart version you are using. <small>This can be found at the bottom of any admin page.</small></li>
												<li>The extension name and version. <small>You can find this information under the About tab.</small></li>
												<li>If you got any error messages, please include them in the message.</li>
												<li>In case the error message is generated by a VQMod cached file, please also attach that file.</li>
											</ul>
											<p>Any additional information that you can provide about the issue is greatly appreciated and will make problem solving much faster.</p>

											<h3 class="page-header">Happy with <?php echo $ext_name; ?>?</h3>
											<p>I would appreciate it very much if you could <a href="<?php echo $ext_store_url; ?>" target="_blank">rate the extension</a> once you've had a chance to try it out. Why not tell everybody how great this extension is by <a href="<?php echo $ext_store_url; ?>" target="_blank">leaving a comment</a> as well.</p>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<div class="alert alert-info">
												<p><?php echo $text_other_extensions; ?></p>
											</div>
										</div>
									</div>
								</div>
								<div class="tab-pane" id="faq">
									<h3><?php echo $text_faq; ?></h3>
									<ul class="media-list" id="faqs">
										<li class="media">
											<div class="pull-left">
												<i class="fa fa-question-circle fa-4x media-object"></i>
											</div>
											<div class="media-body">
												<h4 class="media-heading">Why isn't the extension working?</h4>

												<p class="short-answer">Verify that VQMod is working and check that the extension has been enabled.</p>

												<button type="button" class="btn btn-default btn-sm" data-toggle="collapse" data-target="#not_working" data-parent="#faqs">Show the full answer</button>
												<div class="collapse full-answer" id="not_working">
													<p>There may be a few causes due to which the extension may not appear to be working.</p>

													<ol>
														<li>
															<p>Check tha the extension has been enabled.</p>
														</li>

														<li>
															<p>Verify that VQMod is working, proper VQMod cached files are being generated and the <?php echo $ext_name; ?> VQMod script file is not reporting any errors in the VQMod error log files.</p>
															<p>If VQMod reports errors then these must be addressed. In case proper VQMod cached files are not being generated then VQMod installation needs to be fixed.</p>
														</li>
													</ol>

													<p>In case none of the above helped you to get the extension working please contact me on the support email given on the <a href="#" class="external-tab-link" data-target="#general">General Support</a> section.</p>
												</div>
											</div>
										</li>
										<li class="media">
											<div class="pull-left">
												<i class="fa fa-question-circle fa-4x media-object"></i>
											</div>
											<div class="media-body">
												<h4 class="media-heading">How to edit numeric values relative to original value?</h4>

												<p class="short-answer">Use syntax like <code># + 10%</code>, <code># - 21%</code>, <code># * 3.5</code> or <code># / 1.1</code> for new value</p>

												<button type="button" class="btn btn-default btn-sm" data-toggle="collapse" data-target="#relative_edit" data-parent="#faqs">Show the full answer</button>
												<div class="collapse full-answer" id="relative_edit">
													<p>In order to edit a value relative to the original you need to use the <code># &lt;operation&gt; &lt;operand&gt;</code> special syntax where <code>#</code> denotes the original value, <code>&lt;operation&gt;</code> is either <code>+</code> (addition), <code>-</code> (subtraction), <code>*</code> (multiplication) or <code>/</code> (division) and <code>&lt;operand&gt;</code> is a numeric value with an optional percentage sign.</p>

													<p>You can use relative editing for the following numeric value columns: <em>Quantity</em>, <em>Sort Order</em>, <em>Min Qty</em>, <em>Points</em>, <em>Price</em>, <em>Length</em>, <em>Width</em>, <em>Height</em> and <em>Weight</em>.</p>

													<p><span class="label label-info">Note</span> Relative editing also works in batch editing mode!</p>

													<h5><strong>Some examples</strong></h5>
													<ul>
														<li>To add 13 to the value input <code># + 13</code></li>
														<li>To subtract 3.3 from the value input <code># - 3.3</code></li>
														<li>To increase the value by 5 percent input <code># + 5%</code></li>
														<li>To decrease the value by 1.5% percent input <code># - 1.5%</code></li>
														<li>To multiply the value by 2.5 input <code># * 2.5</code></li>
														<li>To divide the value by 10 input <code># / 10</code></li>
													</ul>

												</div>
											</div>
										</li>
										<li class="media">
											<div class="pull-left">
												<i class="fa fa-question-circle fa-4x media-object"></i>
											</div>
											<div class="media-body">
												<h4 class="media-heading">How to translate the extension to another language?</h4>

												<p class="short-answer">Copy the extension language files (<small><em>admin/language/english/catalog/product_ext.php</em> and <em>admin/language/english/module/admin_quick_edit.php</em></small>) to your language folder and translate the string inside the copied files.</p>

												<button type="button" class="btn btn-default btn-sm" data-toggle="collapse" data-target="#translation" data-parent="#faqs">Show the full answer</button>
												<div class="collapse full-answer" id="translation">
													<ol>
														<li>
															<p><strong>Copy</strong> the following language files <strong>to YOUR_LANGUAGE folder</strong> under the appropriate location as shown below:</p>
															<div class="btm-mgn">
																<em class="text-muted"><small>FROM:</small></em>
																<ul class="list-unstyled">
																	<li>admin/language/english/catalog/product_ext.php</li>
																	<li>admin/language/english/module/admin_quick_edit.php</li>
																</ul>
																<em class="text-muted"><small>TO:</small></em>
																<ul class="list-unstyled">
																	<li>admin/language/YOUR_LANGUAGE/catalog/product_ext.php</li>
																	<li>admin/language/YOUR_LANGUAGE/module/admin_quick_edit.php</li>
																</ul>
															</div>
														</li>

														<li>
															<p><strong>Open</strong> each copied <strong>language file</strong> with a text editor such as <a href="http://www.sublimetext.com/">Sublime Text</a> or <a href="http://notepad-plus-plus.org/">Notepad++</a> and <strong>make the required translations</strong>. You can also leave the files in English.</p>
															<p><span class="label label-info">Note</span> You only need to translate the parts that are to the right of the equal sign.</p>
														</li>
													</ol>
												</div>
											</div>
										</li>
										<li class="media">
											<div class="pull-left">
												<i class="fa fa-question-circle fa-4x media-object"></i>
											</div>
											<div class="media-body">
												<h4 class="media-heading">How to upgrade the extension?</h4>
												<p class="short-answer">Overwrite the current extension files with new ones and click Upgrade on the extension settings page.</p>

												<button type="button" class="btn btn-default btn-sm" data-toggle="collapse" data-target="#upgrade" data-parent="#faqs">Show the full answer</button>
												<div class="collapse full-answer" id="upgrade">
													<ol>
														<li>
															<p><strong>Back up your system</strong> before making any upgrades or changes.</p>
															<p><span class="label label-info">Note</span> Although <?php echo $ext_name; ?> does not overwrite any OpenCart core files, it is always a good practice to create a system backup before making any changes to the system.</p>
														</li>
														<li><strong>Disable</strong> <?php echo $ext_name; ?> <strong>extension</strong> on the module settings page (<em>Extensions > Modules > <?php echo $ext_name; ?></em>) by changing <em>Extension status</em> setting to "Disabled".</li>

														<li>
															<p><strong>Upload</strong> the <strong>extension archive</strong> <em>ProductQuickEditPlus-x.x.x.ocmod.zip</em> using the <a href="<?php echo $extension_installer; ?>" target="_blank">Extension Installer</a>.</p>
															<p><span class="label label-info">Note</span> Do not worry, no OpenCart core files will be replaced! Only the previously installed <?php echo $ext_name; ?> files will be overwritten.</p>
															<p><span class="label label-danger">Important</span> If you have done custom modifications to the extension then back up the modified files and re-apply the modifications after upgrade. To see which files have changed, please take a look at the <a href="#" class="external-tab-link" data-target="#changelog,#about-ext">Changelog</a>.</p>
														</li>

														<li>
															<p><strong>Open</strong> the <?php echo $ext_name; ?> <strong>module settings page</strong> <small>(<em>Extensions > Modules > <?php echo $ext_name; ?></em>)</small> and <strong>refresh the page</strong> by pressing <em>Ctrl + F5</em> twice to force the browser to update the css changes.</p>
														</li>

														<li><p>You should see a notice stating that new version of extension files have been found. <strong>Upgrade the extension</strong> by clicking on the 'Upgrade' button.</p></li>

														<li>After the extension has been successfully upgraded <strong>enable the extension</strong> by changing <em>Extension status</em> setting to "Enabled".</li>
													</ol>
												</div>
											</div>
										</li>
									</ul>
								</div>
								<div class="tab-pane" id="services">
									<h3>Premium Services<button type="button" class="btn btn-default btn-sm pull-right" data-toggle="tooltip" data-container="body" data-placement="bottom" title="<?php echo $button_refresh; ?>" id="btn-refresh-services" data-loading-text="<i class='fa fa-refresh fa-spin'></i> <span class='visible-lg-inline visible-xs-inline'><?php echo $text_loading; ?></span>"><i class="fa fa-refresh"></i> <span class="visible-lg-inline visible-xs-inline"><?php echo $button_refresh; ?></span></button></h3>
									<div id="service-container">
										<p data-bind="visible: service_list_loading()">Loading service list ... <i class="fa fa-refresh fa-spin"></i></p>
										<p data-bind="visible: service_list_loaded() && services().length == 0">There are currently no available services for this extension.</p>
										<table class="table table-hover">
											<tbody data-bind="foreach: services">
												<tr class="srvc">
													<td>
														<h4 class="service" data-bind="html: name"></h4>
														<span class="help-block">
															<p class="description" data-bind="visible: description != '', html: description"></p>
															<p data-bind="visible: turnaround != ''"><strong>Turnaround time</strong>: <span class="turnaround" data-bind="html: turnaround"></span></p>
															<span class="hidden code" data-bind="html: code"></span>
														</span>
													</td>
													<td class="nowrap text-right top-pad"><span class="currency" data-bind="html: currency"></span> <span class="price" data-bind="html: price"></span></td>
													<td class="text-right"><button type="button" class="btn btn-sm btn-primary purchase"><i class="fa fa-shopping-cart"></i> Buy Now</button></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="about-ext">
					<div class="panel panel-default">
						<div class="panel-heading">
							<div class="navbar-header">
								<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#about-navbar-collapse">
									<span class="sr-only"><?php echo $text_toggle_navigation; ?></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
								<h3 class="panel-title"><i class="fa fa-info fa-fw"></i> <?php echo $tab_about; ?></h3>
							</div>
							<div class="collapse navbar-collapse" id="about-navbar-collapse">
								<ul class="nav navbar-nav">
									<li class="active"><a href="#ext_info" data-toggle="tab"><?php echo $tab_extension; ?></a></li>
									<li><a href="#changelog" data-toggle="tab"><?php echo $tab_changelog; ?></a></li>
								</ul>
							</div>
						</div>
						<div class="panel-body">
							<div class="tab-content">
								<div class="tab-pane active" id="ext_info">
									<div class="row">
										<div class="col-sm-12">
											<h3><?php echo $text_extension_information; ?></h3>

											<div class="form-group">
												<label class="col-sm-3 col-md-2 control-label"><?php echo $entry_extension_name; ?></label>
												<div class="col-sm-9 col-md-10">
													<p class="form-control-static"><?php echo $ext_name; ?></p>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 col-md-2 control-label"><?php echo $entry_installed_version; ?></label>
												<div class="col-sm-9 col-md-10">
													<p class="form-control-static"><strong><?php echo $installed_version; ?></strong></p>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 col-md-2 control-label"><?php echo $entry_extension_compatibility; ?></label>
												<div class="col-sm-9 col-md-10">
													<p class="form-control-static"><?php echo $ext_compatibility; ?></p>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 col-md-2 control-label"><?php echo $entry_extension_store_url; ?></label>
												<div class="col-sm-9 col-md-10">
													<p class="form-control-static"><a href="<?php echo $ext_store_url; ?>" target="_blank"><?php echo htmlspecialchars($ext_store_url); ?></a></p>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 col-md-2 control-label"><?php echo $entry_copyright_notice; ?></label>
												<div class="col-sm-9 col-md-10">
													<p class="form-control-static">&copy; 2011 - <?php echo date("Y"); ?> Romi Agar</p>
												</div>
											</div>
											<div class="form-group">
												<div class="col-sm-offset-3 col-sm-9 col-md-offset-2 col-md-10">
													<p class="form-control-static"><a href="#legal_text" id="legal_notice" data-toggle="modal"><?php echo $text_terms; ?></a></p>
												</div>
											</div>

											<h3 class="page-header"><?php echo $text_license; ?></h3>
											<p><?php echo $text_license_text; ?></p>
										</div>
									</div>
								</div>
								<div class="tab-pane" id="changelog">
									<div class="row">
										<div class="col-sm-12">
											<div class="release">
												<h3>Version 1.4.0 <small class="release-date text-muted">31 Aug 2015</small></h3>

												<blockquote>
													<ul class="list-unstyled">
														<li><em class="text-primary">New:</em> (Batch) Edit numeric values relative to the original value</li>
														<li><em class="text-primary">New:</em> Option to show product quick edit page as a new menu item instead of replacing the original</li>
														<li><em class="text-success">Fixed:</em> Overlapping modals</li>
														<li><em class="text-success">Fixed:</em> Some minor UI glitches</li>
													</ul>

													<h4><i class="fa fa-pencil text-primary"></i> Files changed:</h4>

													<ul>
														<li>admin/controller/catalog/product_ext.php</li>
														<li>admin/controller/module/admin_quick_edit.php</li>
														<li>admin/language/english/catalog/product_ext.php</li>
														<li>admin/language/english/module/admin_quick_edit.php</li>
														<li>admin/model/catalog/product_ext.php</li>
														<li>admin/view/javascript/aqe/*.js</li>
														<li>admin/view/stylesheet/aqe/css/*.css</li>
														<li>admin/view/template/catalog/product_ext_list.tpl</li>
														<li>admin/view/template/module/admin_quick_edit.tpl</li>
														<li>system/helper/aqe.php</li>
														<li>vqmod/xml/admin_quick_edit.xml</li>
													</ul>
												</blockquote>
											</div>

											<div class="release">
												<h3>Version 1.3.5 <small class="release-date text-muted">25 May 2015</small></h3>

												<blockquote>
													<ul class="list-unstyled">
														<li><em class="text-success">Fixed:</em> HTML entities are not decoded in product attributes</li>
													</ul>

													<h4><i class="fa fa-pencil text-primary"></i> Files changed:</h4>

													<ul>
														<li>admin/controller/catalog/product_ext.php</li>
														<li>admin/controller/module/admin_quick_edit.php</li>
														<li>admin/view/template/module/admin_quick_edit.tpl</li>
														<li>vqmod/xml/admin_quick_edit.xml</li>
													</ul>
												</blockquote>
											</div>

											<div class="release">
												<h3>Version 1.3.4 <small class="release-date text-muted">23 May 2015</small></h3>

												<blockquote>
													<ul class="list-unstyled">
														<li><em class="text-success">Fixed:</em> Clear cache does not clear browser cache</li>
													</ul>

													<h4><i class="fa fa-pencil text-primary"></i> Files changed:</h4>

													<ul>
														<li>admin/controller/module/admin_quick_edit.php</li>
														<li>admin/view/javascript/aqe/catalog.min.js</li>
														<li>admin/view/template/module/admin_quick_edit.tpl</li>
														<li>vqmod/xml/admin_quick_edit.xml</li>
													</ul>
												</blockquote>
											</div>

											<div class="release">
												<h3>Version 1.3.3 <small class="release-date text-muted">20 May 2015</small></h3>

												<blockquote>
													<ul class="list-unstyled">
														<li><em class="text-success">Fixed:</em> Stock Status language</li>
													</ul>

													<h4><i class="fa fa-pencil text-primary"></i> Files changed:</h4>

													<ul>
														<li>admin/controller/module/admin_quick_edit.php</li>
														<li>admin/model/catalog/product_ext.php</li>
														<li>admin/view/template/module/admin_quick_edit.tpl</li>
														<li>vqmod/xml/admin_quick_edit.xml</li>
													</ul>
												</blockquote>
											</div>

											<div class="release">
												<h3>Version 1.3.2 <small class="release-date text-muted">13 Mar 2015</small></h3>

												<blockquote>
													<ul class="list-unstyled">
														<li><em class="text-success">Fixed:</em> AJAX error when searching</li>
													</ul>

													<h4><i class="fa fa-pencil text-primary"></i> Files changed:</h4>

													<ul>
														<li>admin/controller/module/admin_quick_edit.php</li>
														<li>admin/model/catalog/product_ext.php</li>
														<li>admin/view/template/module/admin_quick_edit.tpl</li>
														<li>vqmod/xml/admin_quick_edit.xml</li>
													</ul>
												</blockquote>
											</div>

											<div class="release">
												<h3>Version 1.3.1 <small class="release-date text-muted">24 Feb 2015</small></h3>

												<blockquote>
													<ul class="list-unstyled">
														<li><em class="text-success">Fixed:</em> Searching and filtering when SEO keywords are multilingual</li>
													</ul>

													<h4><i class="fa fa-pencil text-primary"></i> Files changed:</h4>

													<ul>
														<li>admin/controller/module/admin_quick_edit.php</li>
														<li>admin/model/catalog/product_ext.php</li>
														<li>admin/view/template/module/admin_quick_edit.tpl</li>
														<li>vqmod/xml/admin_quick_edit.xml</li>
													</ul>
												</blockquote>
											</div>

											<div class="release">
												<h3>Version 1.3.0 <small class="release-date text-muted">20 Feb 2015</small></h3>

												<blockquote>
													<ul class="list-unstyled">
														<li><em class="text-primary">New:</em> Client-side cache size configurable</li>
														<li><em class="text-success">Fixed:</em> Client-side caching could not be turned off</li>
														<li><em class="text-success">Fixed:</em> Client-side cache corruption</li>
														<li><em class="text-success">Fixed:</em> Meta Tag Title quick editing</li>
														<li><em class="text-success">Fixed:</em> Actions highlight after quick edit even if highlighting is turned off</li>
														<li><em class="text-success">Fixed:</em> SSL redirection from product edit page to product list page</li>
													</ul>

													<h4><i class="fa fa-pencil text-primary"></i> Files changed:</h4>

													<ul>
														<li>admin/controller/catalog/product_ext.php</li>
														<li>admin/controller/module/admin_quick_edit.php</li>
														<li>admin/language/english/catalog/product_ext.php</li>
														<li>admin/model/catalog/product_ext.php</li>
														<li>admin/view/javascript/aqe/catalog.min.js</li>
														<li>admin/view/stylesheet/aqe/css/catalog.min.css</li>
														<li>admin/view/template/catalog/product_ext_list.tpl</li>
														<li>admin/view/template/module/admin_quick_edit.tpl</li>
														<li>vqmod/xml/admin_quick_edit.xml</li>
													</ul>
												</blockquote>
											</div>

											<div class="release">
												<h3>Version 1.2.1 <small class="release-date text-muted">19 Dec 2014</small></h3>

												<blockquote>
													<ul class="list-unstyled">
														<li><em class="text-success">Fixed:</em> Product Quick Edit Plus incorrectly displayed as a module on the layouts page</li>
													</ul>

													<h4><i class="fa fa-pencil text-primary"></i> Files changed:</h4>

													<ul>
														<li>admin/controller/catalog/product_ext.php</li>
														<li>admin/controller/module/admin_quick_edit.php</li>
														<li>admin/language/english/catalog/product_ext.php</li>
														<li>admin/language/english/module/admin_quick_edit.php</li>
														<li>admin/model/catalog/product_ext.php</li>
														<li>admin/view/javascript/aqe/catalog.min.js</li>
														<li>admin/view/javascript/aqe/module.min.js</li>
														<li>admin/view/stylesheet/aqe/css/catalog.min.css</li>
														<li>admin/view/stylesheet/aqe/css/module.min.css</li>
														<li>admin/view/template/catalog/product_ext_list.tpl</li>
														<li>admin/view/template/module/admin_quick_edit.tpl</li>
														<li>vqmod/xml/admin_quick_edit.xml</li>
													</ul>
												</blockquote>
											</div>

											<div class="release">
												<h3>Version 1.2.0 <small class="release-date text-muted">31 Oct 2014</small></h3>

												<blockquote>
													<ul class="list-unstyled">
														<li><em class="text-primary">New:</em> Option to highlight filtered columns</li>
														<li><em class="text-primary">New:</em> Option to highlight actions that have related data associated</li>
														<li><em class="text-primary">New:</em> URL based filtering, search and pagination support</li>
														<li><em class="text-success">Fixed:</em> Server-side cache not properly updated when product category, filter, download or store links were completely removed</li>
														<li><em class="text-info">Changed:</em> Upgraded third-party libraries</li>
													</ul>

													<h4><i class="fa fa-pencil text-primary"></i> Files changed:</h4>

													<ul>
														<li>admin/controller/catalog/product_ext.php</li>
														<li>admin/controller/module/admin_quick_edit.php</li>
														<li>admin/language/english/catalog/product_ext.php</li>
														<li>admin/language/english/module/admin_quick_edit.php</li>
														<li>admin/model/catalog/product_ext.php</li>
														<li>admin/view/template/catalog/product_ext_list.tpl</li>
														<li>admin/view/template/catalog/product_ext_qe_form.tpl</li>
														<li>admin/view/template/module/admin_quick_edit.tpl</li>
														<li>system/helper/aqe.php</li>
														<li>vqmod/xml/admin_quick_edit.xml</li>
													</ul>

													<h4><i class="fa fa-plus text-success"></i> Files added:</h4>

													<ul>
														<li>admin/view/javascript/aqe/catalog.min.js</li>
														<li>admin/view/javascript/aqe/module.min.js</li>
														<li>admin/view/stylesheet/aqe/css/catalog.min.css</li>
														<li>admin/view/stylesheet/aqe/css/module.min.css</li>
													</ul>

													<h4><i class="fa fa-minus text-danger"></i> Files removed:</h4>

													<ul>
														<li>admin/view/javascript/aqe/custom.min.js</li>
														<li>admin/view/stylesheet/aqe/css/custom.min.css</li>
														<li>admin/view/stylesheet/aqe/fonts/*</li>
													</ul>

												</blockquote>
											</div>

											<div class="release">
												<h3>Version 1.1.5 <small class="release-date text-muted">28 Aug 2014</small></h3>

												<blockquote>
													<ul class="list-unstyled">
														<li><em class="text-success">Fixed:</em> Filtering does not open first result page</li>
													</ul>

													<h4><i class="fa fa-pencil text-primary"></i> Files changed:</h4>

													<ul>
														<li>admin/controller/module/admin_quick_edit.php</li>
														<li>admin/view/javascript/aqe/custom.min.js</li>
														<li>admin/view/template/module/admin_quick_edit.tpl</li>
														<li>vqmod/xml/admin_quick_edit.xml</li>
													</ul>
												</blockquote>
											</div>

											<div class="release">
												<h3>Version 1.1.4 <small class="release-date text-muted">23 Jun 2014</small></h3>

												<blockquote>
													<ul class="list-unstyled">
														<li><em class="text-success">Fixed:</em> MySQL GROUP_CONCAT returning truncated results</li>
													</ul>

													<h4><i class="fa fa-pencil text-primary"></i> Files changed:</h4>

													<ul>
														<li>admin/controller/module/admin_quick_edit.php</li>
														<li>admin/view/template/module/admin_quick_edit.tpl</li>
														<li>vqmod/xml/admin_quick_edit.xml</li>
													</ul>
												</blockquote>
											</div>

											<div class="release">
												<h3>Version 1.1.3 <small class="release-date text-muted">20 Jun 2014</small></h3>

												<blockquote>
													<ul class="list-unstyled">
														<li><em class="text-success">Fixed:</em> UTF-8 encoding issue</li>
													</ul>

													<h4><i class="fa fa-pencil text-primary"></i> Files changed:</h4>

													<ul>
														<li>admin/controller/catalog/product_ext.php</li>
														<li>admin/controller/module/admin_quick_edit.php</li>
														<li>admin/model/catalog/product_ext.php</li>
														<li>admin/view/stylesheet/aqe/css/custom.min.css</li>
														<li>admin/view/template/module/admin_quick_edit.tpl</li>
														<li>vqmod/xml/admin_quick_edit.xml</li>
													</ul>
												</blockquote>
											</div>

											<div class="release">
												<h3>Version 1.1.2 <small class="release-date text-muted">26 Mar 2014</small></h3>

												<blockquote>
													<ul class="list-unstyled">
														<li><em class="text-success">Fixed:</em> Image quick editing support for mobile devices</li>
													</ul>

													<h4><i class="fa fa-pencil text-primary"></i> Files changed:</h4>

													<ul>
														<li>admin/controller/catalog/product_ext.php</li>
														<li>admin/controller/module/admin_quick_edit.php</li>
														<li>admin/language/english/catalog/product_ext.php</li>
														<li>admin/view/stylesheet/aqe/css/custom.min.css</li>
														<li>admin/view/template/catalog/product_ext_list.tpl</li>
														<li>admin/view/template/module/admin_quick_edit.tpl</li>
														<li>vqmod/xml/admin_quick_edit.xml</li>
													</ul>
												</blockquote>
											</div>

											<div class="release">
												<h3>Version 1.1.1 <small class="release-date text-muted">24 Mar 2014</small></h3>

												<blockquote>
													<ul class="list-unstyled">
														<li><em class="text-success">Fixed:</em> Autocomplete does not show new entries</li>
													</ul>

													<h4><i class="fa fa-pencil text-primary"></i> Files changed:</h4>

													<ul>
														<li>admin/controller/catalog/product_ext.php</li>
														<li>admin/controller/module/admin_quick_edit.php</li>
														<li>admin/view/template/catalog/product_ext_list.tpl</li>
														<li>admin/view/template/module/admin_quick_edit.tpl</li>
														<li>vqmod/xml/admin_quick_edit.xml</li>
													</ul>
												</blockquote>
											</div>

											<div class="release">
												<h3>Version 1.1.0 <small class="release-date text-muted">21 Mar 2014</small></h3>

												<blockquote>
													<ul class="list-unstyled">
														<li><em class="text-primary">New:</em> Optional server and client-side caching</li>
													</ul>

													<h4><i class="fa fa-pencil text-primary"></i> Files changed:</h4>

													<ul>
														<li>admin/controller/catalog/product_ext.php</li>
														<li>admin/controller/module/admin_quick_edit.php</li>
														<li>admin/language/english/catalog/product_ext.php</li>
														<li>admin/model/catalog/product_ext.php</li>
														<li>admin/view/javascript/aqe/custom.min.js</li>
														<li>admin/view/template/catalog/product_ext_list.tpl</li>
														<li>admin/view/template/module/admin_quick_edit.tpl</li>
														<li>vqmod/xml/admin_quick_edit.xml</li>
													</ul>
												</blockquote>
											</div>

											<div class="release">
												<h3>Version 1.0.6 <small class="release-date text-muted">12 Mar 2014</small></h3>

												<blockquote>
													<ul class="list-unstyled">
														<li><em class="text-success">Fixed:</em> OpenCart 1.5.2.x support</li>
													</ul>

													<h4><i class="fa fa-pencil text-primary"></i> Files changed:</h4>

													<ul>
														<li>admin/controller/module/admin_quick_edit.php</li>
														<li>admin/view/template/module/admin_quick_edit.tpl</li>
														<li>vqmod/xml/admin_quick_edit.xml</li>
													</ul>
												</blockquote>
											</div>

											<div class="release">
												<h3>Version 1.0.5 <small class="release-date text-muted">06 Mar 2014</small></h3>

												<blockquote>
													<ul class="list-unstyled">
														<li><em class="text-primary">New:</em> Changelog on module settings page under About tab</li>
														<li><em class="text-success">Fixed:</em> Slow page update when batch editing multiple products</li>
													</ul>

													<h4><i class="fa fa-pencil text-primary"></i> Files changed:</h4>

													<ul>
														<li>admin/controller/module/admin_quick_edit.php</li>
														<li>admin/language/english/module/admin_quick_edit.php</li>
														<li>admin/view/javascript/aqe/custom.min.js</li>
														<li>admin/view/stylesheet/aqe/css/custom.min.css</li>
														<li>admin/view/template/catalog/product_ext_list.tpl</li>
														<li>admin/view/template/module/admin_quick_edit.tpl</li>
														<li>vqmod/xml/admin_quick_edit.xml</li>
													</ul>
												</blockquote>
											</div>

											<div class="release">
												<h3>Version 1.0.4 <small class="release-date text-muted">12 Feb 2014</small></h3>

												<blockquote>
													<ul class="list-unstyled">
														<li><em class="text-success">Fixed:</em> Effective changes to core files not applied when extension is in disabled or uninstalled state</li>
													</ul>

													<h4><i class="fa fa-pencil text-primary"></i> Files changed:</h4>

													<ul>
														<li>admin/controller/catalog/product_ext.php</li>
														<li>admin/controller/module/admin_quick_edit.php</li>
														<li>admin/view/javascript/aqe/custom.min.js</li>
														<li>admin/view/template/catalog/product_ext_list.tpl</li>
														<li>admin/view/template/module/admin_quick_edit.tpl</li>
														<li>vqmod/xml/admin_quick_edit.xml</li>
													</ul>
												</blockquote>
											</div>

											<div class="release">
												<h3>Version 1.0.3 <small class="release-date text-muted">10 Feb 2014</small></h3>

												<blockquote>
													<ul class="list-unstyled">
														<li><em class="text-success">Fixed:</em> OpenBay product update on quantity change</li>
													</ul>

													<h4><i class="fa fa-pencil text-primary"></i> Files changed:</h4>

													<ul>
														<li>admin/controller/catalog/product_ext.php</li>
														<li>admin/controller/module/admin_quick_edit.php</li>
														<li>vqmod/xml/admin_quick_edit.xml</li>
													</ul>
												</blockquote>
											</div>

											<div class="release">
												<h3>Version 1.0.2 <small class="release-date text-muted">07 Feb 2014</small></h3>

												<blockquote>
													<ul class="list-unstyled">
														<li><em class="text-success">Fixed:</em> Product tax class cannot be removed</li>
														<li><em class="text-success">Fixed:</em> Helper function for PHP &lt; 5.4</li>
													</ul>

													<h4><i class="fa fa-pencil text-primary"></i> Files changed:</h4>

													<ul>
														<li>admin/controller/catalog/product_ext.php</li>
														<li>admin/controller/module/admin_quick_edit.php</li>
														<li>admin/language/english/catalog/product_ext.php</li>
														<li>admin/model/catalog/product_ext.php</li>
														<li>admin/view/stylesheet/aqe/css/custom.min.css</li>
														<li>admin/view/template/module/admin_quick_edit.tpl</li>
														<li>system/helper/aqe.php</li>
														<li>vqmod/xml/admin_quick_edit.xml</li>
													</ul>
												</blockquote>
											</div>

											<div class="release">
												<h3>Version 1.0.1 <small class="release-date text-muted">08 Jan 2014</small></h3>

												<blockquote>
													<ul class="list-unstyled">
														<li><em class="text-primary">New:</em> FAQ section on module settings page under Support tab</li>
														<li><em class="text-primary">New:</em> Services section on module settings page under Support tab</li>
														<li><em class="text-success">Fixed:</em> Products per page setting value "-1" shows all but the last product</li>
														<li><em class="text-success">Fixed:</em> Sorting duplicates the list entries</li>
													</ul>

													<h4><i class="fa fa-pencil text-primary"></i> Files changed:</h4>

													<ul>
														<li>admin/controller/module/admin_quick_edit.php</li>
														<li>admin/language/english/module/admin_quick_edit.php</li>
														<li>admin/view/javascript/aqe/custom.min.js</li>
														<li>admin/view/static/bull5i_pqe_plus_extension_terms.htm</li>
														<li>admin/view/stylesheet/aqe/css/custom.min.css</li>
														<li>admin/view/template/catalog/product_ext_list.tpl</li>
														<li>admin/view/template/module/admin_quick_edit.tpl</li>
														<li>vqmod/xml/admin_quick_edit.xml</li>
													</ul>

													<h4><i class="fa fa-minus text-danger"></i> Files removed:</h4>

													<ul>
														<li>admin/view/image/aqe/*</li>
													</ul>
												</blockquote>
											</div>

											<div class="release">
												<h3>Version 1.0.0 <small class="release-date text-muted">31 Dec 2013</small></h3>

												<blockquote>
													<ul class="list-unstyled">
														<li>Initial release</li>
													</ul>
												</blockquote>
											</div>

										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript"><!--
!function(e,o,s){var i,t=<?php echo json_encode($errors); ?>;e.texts=o.extend({},e.texts,{error_ajax_request:"<?php echo addslashes($error_ajax_request); ?>"}),e.load_service_list=function(e){var e=e!==s?1*e:0,t=o.Deferred();return i.service_list_loaded()&&!e||i.service_list_loading()?t.reject():(i.service_list_loading(!0),o.when(o.ajax({url:"<?php echo $services; ?>",data:{force:e},dataType:"json"})).then(function(e){i.service_list_loaded(!0),i.service_list_loading(!1),i.clearServices(),e.services&&o.each(e.services,function(e,o){var s=o.code,t=o.name,r=o.description||"",a=o.currency,n=o.price,l=o.turnaround;i.addService(s,t,r,a,n,l)}),e.rate&&o("#hourly_rate").html(e.rate),t.resolve()},function(e,o,s){i.service_list_loaded(!0),i.service_list_loading(!1),t.reject(),window.console&&window.console.log&&window.console.log("Failed to load services list: "+s)})),t.promise()};var r=function(e,o,s,i,t,r){this.code=e,this.name=o,this.description=s,this.currency=i,this.price=t,this.turnaround=r},a=function(){var e=this;this.status=ko.observable("<?php echo $aqe_status; ?>"),this.display_in_menu_as=ko.observable("<?php echo $aqe_display_in_menu_as; ?>"),this.general_errors=ko.computed(function(){return!1}),e.service_list_loaded=ko.observable(!1),e.service_list_loading=ko.observable(!1),e.services=ko.observableArray([]),e.addService=function(o,s,i,t,a,n){e.services.push(new r(o,s,i,t,a,n))},e.clearServices=function(){e.services.removeAll()}};a.prototype=new e.observable_object_methods,o(function(){var s=window.location.hash,r=s.split("?")[0];i=e.view_model=new a,e.view_models=o.extend({},e.view_models,{ExtVM:e.view_model}),i.applyErrors(t),ko.applyBindings(i,o("#content")[0]),o("#legal_text .modal-body").load("view/static/bull5i_pqe_plus_extension_terms.htm"),o("body").on("shown.bs.tab","a[data-toggle='tab'][href='#ext-support'],a[data-toggle='tab'][href='#services']",function(){e.load_service_list()}),e.onComplete(o("#page-overlay"),o("#content")).done(function(){e.activateTab(r)})})}(window.bull5i=window.bull5i||{},jQuery);
//--></script>
<?php echo $footer; ?>
