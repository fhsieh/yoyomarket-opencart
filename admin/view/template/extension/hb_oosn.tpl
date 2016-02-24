<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
      	<?php if ($products) { ?><a class="btn btn-success" onclick="manualrun()"><i class="fa fa-envelope"></i> <?php echo $text_notify_customers; ?></a><?php } ?>
        <button type="submit" form="form-latest" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i> <?php echo $button_cancel; ?></a></div>
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
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <center><div id='loadgif' style='display:none;'><img src='view/image/loader.gif'/></div></center>
    <div id="msgoutput"></div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
          <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-language" class="form-horizontal">
	         <ul class="nav nav-tabs">
                <li class="active"><a href="#tab-records" data-toggle="tab"><?php echo $tab_records; ?></a></li>
                <li><a href="#tab-demand" data-toggle="tab"><?php echo $tab_demand; ?></a></li>
                <li><a href="#tab-email" data-toggle="tab"><?php echo $tab_email; ?></a></li>
                <li><a href="#tab-setting" data-toggle="tab"><?php echo $tab_setting; ?></a></li>
  	          </ul>

			<div class="tab-content">

	            <div class="tab-pane active" id="tab-records">
                    <div class="row">
                      <div class="col-lg-4 col-md-4 col-sm-12">
                      		<div class="tile">
                              <div class="tile-heading"><?php echo $tile_all; ?>
                              <span class="pull-right"><?php echo $total_alert;?> / <?php echo $total_responded;?></span></div>
                              <div class="tile-body"><i class="fa fa-bell"></i>
                                <h4 class="pull-right text-right"><?php echo $text_total_alert;?><?php echo $total_alert;?><br>
                                <?php echo $text_total_responded;?><?php echo $total_responded;?></h4>
                              </div>
                              <div class="tile-footer">
                              	<a href="<?php echo $action;?>&filteroption=all" ><?php echo $text_show_all_reports;?></a>
                              	<a class="pull-right" onclick="confirm('Are you sure?') ? window.location.href ='<?php echo $action;?>&delete=all' : false;"><?php echo $text_reset_all;?></a></div>
                            </div>
                      </div>
                      <div class="col-lg-4 col-md-4 col-sm-12">
                      		<div class="tile">
                              <div class="tile-heading"><?php echo $tile_awaiting; ?>
                              <span class="pull-right"><?php echo $awaiting_notification;?> / <?php echo $product_requested;?></span></div>
                              <div class="tile-body"><i class="fa fa-refresh"></i>
                                <h4 class="pull-right text-right"><?php echo $text_customers_awaiting_notification;?><?php echo $awaiting_notification;?><br>
									<?php echo $text_number_of_products_demanded;?><?php echo $product_requested;?></h4>
                              </div>
                              <div class="tile-footer">
                              	<a href="<?php echo $action;?>&filteroption=awaiting"><?php echo $text_show_awaiting_reports;?></a>
                                <a class="pull-right"  onclick="confirm('Are you sure?') ? window.location.href ='<?php echo $action;?>&delete=awaiting' : false;"><?php echo $text_reset_awaiting;?></a></div>
                            </div>
                      </div>
                      <div class="col-lg-4 col-md-4 col-sm-12">
                      		<div class="tile">
                              <div class="tile-heading"><?php echo $tile_archive; ?>
                              <span class="pull-right"><?php echo $total_responded;?> / <?php echo $customer_notified;?></span></div>
                              <div class="tile-body"><i class="fa fa-check"></i>
                                <h4 class="pull-right text-right"><?php echo $text_archive_records;?><?php echo $total_responded;?><br>
									<?php echo $text_customers_notified;?><?php echo $customer_notified;?></h4>
                              </div>
                              <div class="tile-footer">
                              	<a href="<?php echo $action;?>&filteroption=archive"><?php echo $text_show_archive_reports;?></a>
                                <a class="pull-right" onclick="confirm('Are you sure?') ? window.location.href ='<?php echo $action;?>&delete=archive' : false;"><?php echo $text_reset_archive;?></a></div>
                            </div>
                      </div>
                    </div>

                    <div class="row">
                   	  <div class="col-lg-12 col-md-12 col-sm-12 col-sx-12">
                      <h4><?php echo $current_report; ?></h4>
                      <div class="table-responsive">
                      	<table class="table table-bordered">
                        <thead>
                            <tr>
                            <td class="text-left"><?php echo $column_product_id; ?></td>
                            <td class="text-left"><?php echo $column_product_name; ?></td>
                            <td class="text-left"><?php echo $column_option; ?></td>
                            <td class="text-left"><?php echo $column_all_option; ?></td>
                            <td class="text-left"><?php echo $column_email; ?></td>
                            <td class="text-left"><?php echo $column_name; ?></td>
                            <td class="text-left"><?php echo $column_phone; ?></td>
                            <td class="text-left"><?php echo $column_language; ?></td>
                            <td class="text-left"><?php echo $column_enquiry_date; ?></td>
                            <td class="text-left"><?php echo $column_notify_date; ?></td>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if ($products) { ?>
                          <?php foreach ($products as $product) { ?>
                          <tr>
                            <td class="text-right"><?php echo $product['product_id']; ?></td>
                            <td class="text-left"><a href="<?php echo $product['product_link']; ?>" target="_blank"><?php echo $product['name']; ?></a></td>
                            <td class="text-left"><?php echo $product['selected_option']; ?></td>
                            <td class="text-left"><?php echo $product['all_selected_option']; ?></td>
                            <td class="text-left"><?php echo $product['email']; ?></td>
                            <td class="text-left"><?php echo $product['fname']; ?></td>
                            <td class="text-left"><?php echo $product['phone']; ?></td>
                            <td class="text-left"><?php echo $product['language_code']; ?></td>
                            <td class="text-right"><?php echo $product['enquiry_date']; ?></td>
                            <td class="text-right"><?php echo $product['notify_date']; ?></td>
                          </tr>
                          <?php } ?>
                          <?php } else { ?>
                          <tr>
                            <td class="center" colspan="10"><?php echo $text_no_results; ?></td>
                          </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                      </div>
                      <div class="row">
                          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                        </div>
                      </div><!-- END OF COLUMN 1 REPORT -->

                    </div>

                </div> <!-- tab 1 end -->

                <div class="tab-pane" id="tab-demand">
                <h4><?php echo $text_product_in_demand; ?></h4>
                            <div class="row">
                   	  			<div class="col-lg-12 col-md-12 col-sm-12 col-sx-12">
                            <div class="table-responsive">
                      		<table class="table table-bordered">
                            <thead>
                                        <tr>
                                        <td class="text-left"><?php echo $column_product_id; ?></td>
                                        <td class="text-left"><?php echo $column_product_name; ?></td>
                                        <td class="right"><?php echo $column_count; ?></td>
                                      </tr>
                                    </thead>
                                    <tbody>

                            <?php if ($demands) {
                            foreach ($demands as $demand) {
                            echo '<tr>';
                            echo '<td class="text-left">'.$demand['pid'].'</td>';
                            echo '<td class="text-left">'.$demand['name'].'</td>';
                            echo '<td class="right">'.$demand['count'].'</td>';
                            echo '</tr>';
                            }
                            }else { ?>
                                      <tr>
                                        <td class="center" colspan="3"><?php echo $text_no_results; ?></td>
                                      </tr>
                            <?php } ?>
                            </tbody>
                            </table></div>
                            </div> <!-- col end-->
                            </div>

                </div>

				<div class="tab-pane" id="tab-email">

					<ul class="nav nav-tabs" id="languages">
	                <?php foreach ($languages as $language) { ?>
	                <li><a href="#languages<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
	                <?php } ?>
	              	</ul>

                    <div class="tab-content"> <!-- language tab content -->
                        <?php foreach ($languages as $language) {
                        $language_code = md5($language['code']);
                        ?>
                            <div class="tab-pane" id="languages<?php echo $language['language_id']; ?>">
                           	 <h3><?php echo $text_header_admin; ?></h3>
                             <div class="form-group required">
		               			 <h4 class="col-sm-4 control-label"><?php echo $entry_subject; ?></h4>
		                		<div class="col-sm-8">
									<input type="text" class="form-control" name="hb_oosn_admin_email_subject_<?php echo $language_code; ?>" value="<?php echo ${'hb_oosn_admin_email_subject_'.$language_code};?>" />
                         		</div>
		             		</div>
                           	 <div class="form-group required">
		               			 <h4 class="col-sm-4 control-label"><?php echo $entry_body; ?><br><small><div class="alert alert-info"><?php echo $entry_store_codes; ?></div></small></h4>
		                		<div class="col-sm-8">
                                    <textarea name="hb_oosn_admin_email_body_<?php echo $language_code; ?>" class="form-control" rows="7"><?php echo ${'hb_oosn_admin_email_body_'.$language_code};?></textarea>
                         		</div>
		             		</div>
		            		<hr>

                            <h3><?php echo $text_header_customer; ?></h3>
							<div class="form-group required">
		               			 <h4 class="col-sm-4 control-label"><?php echo $entry_subject; ?></h4>
		                		<div class="col-sm-8">
									<input type="text" class="form-control" name="hb_oosn_customer_email_subject_<?php echo $language['language_id']; ?>" value="<?php echo ${'hb_oosn_customer_email_subject_'.$language['language_id']};?>" />
                         		</div>
		             		</div>
                           	 <div class="form-group required">
		               			 <h4 class="col-sm-4 control-label"><?php echo $entry_body; ?><br><small><div class="alert alert-info"><?php echo $entry_customer_codes; ?></div></small></h4>
		                		<div class="col-sm-8">
                                    <textarea name="hb_oosn_customer_email_body_<?php echo $language['language_id']; ?>" id="email<?php echo $language['language_id']; ?>"><?php echo ${'hb_oosn_customer_email_body_'.$language['language_id']};?></textarea>
                         		</div>
		             		</div>
		            		<hr>
                            <div class="form-group required">
		               			 <label class="col-sm-4 control-label"><?php echo $text_product_image_size; ?></label>
		                		<div class="col-sm-4">
									<input type="text" class="form-control" name="hb_oosn_product_image_h_<?php echo $language['language_id']; ?>" placeholder="Height" value="<?php echo ${'hb_oosn_product_image_h_'.$language['language_id']};?>" />
                         		</div>
                                <div class="col-sm-4">
									<input type="text" class="form-control" name="hb_oosn_product_image_w_<?php echo $language['language_id']; ?>" placeholder="Width" value="<?php echo ${'hb_oosn_product_image_w_'.$language['language_id']};?>" />
                         		</div>
		             		</div>
                            <hr class="hide">
                            <h3 class="hide"><?php echo $text_header_sms; ?></h3>
                            <div class="form-group required hide">
		               			 <h4 class="col-sm-4 control-label"><?php echo $sms_body; ?><br><small><div class="alert alert-info"><?php echo $entry_sms_codes; ?></div></small></h4>
		                		<div class="col-sm-8">
                                    <textarea name="hb_oosn_customer_sms_body_<?php echo $language['language_id']; ?>" class="form-control" rows="7"><?php echo ${'hb_oosn_customer_sms_body_'.$language['language_id']};?></textarea>
                         		</div>
		             		</div>

                             </div>
                        <?php } ?>
                    </div>


	            </div>

	            <div class="tab-pane" id="tab-setting">
				 <h2><?php echo $text_header_common; ?></h2>
                 <table class="table table-hover">
                    <tr>
                        <td><?php echo $text_header_installation; ?></td>
                        <td><div class="buttons">
                        <?php if ($hb_oosn_installed == 1){ ?>
                            <span class="text-success"><i class="fa fa-check"></i></span> Installed <a onclick="location = '<?php echo $uninstall; ?>';" class="btn btn-danger pull-right"><i class="fa fa-times"></i> Uninstall</a>
                        <?php } else { ?>
                            <span class="text-danger"><i class="fa fa-exclamation-triangle"></i></span> Not Installed <a onclick="location = '<?php echo $install; ?>';" class="btn btn-success pull-right"><i class="fa fa-cog"></i> Install</a>
                        <?php }  ?>
                            </div></td>
                    </tr>

                    <tr class="hide">
                        <td colspan="2"><h4><?php echo $text_header_form; ?></h4></td>
                    </tr>

                    <tr>
                              <td><?php echo $entry_enable_name; ?></td>
                              <td><select name="hb_oosn_name_enable"  class="form-control">
                              <option value="y" <?php echo ($hb_oosn_name_enable == 'y')? 'selected':''; ?> >Yes</option>
                              <option value="n" <?php echo ($hb_oosn_name_enable == 'n')? 'selected':''; ?> >No</option>
                              </select></td>
                    </tr>

                    <tr class="hide">
                              <td><?php echo $entry_enable_mobile; ?></td>
                              <td><select name="hb_oosn_mobile_enable"  class="form-control">
                              <option value="y" <?php echo ($hb_oosn_mobile_enable == 'y')? 'selected':''; ?> >Yes</option>
                              <option value="n" <?php echo ($hb_oosn_mobile_enable == 'n')? 'selected':''; ?> >No</option>
                              </select></td>
                    </tr>

                    <tr class="hide">
                              <td><?php echo $entry_enable_sms; ?></td>
                              <td><select name="hb_oosn_sms_enable"  class="form-control">
                              <option value="y" <?php echo ($hb_oosn_sms_enable == 'y')? 'selected':''; ?> >Yes</option>
                              <option value="n" <?php echo ($hb_oosn_sms_enable == 'n')? 'selected':''; ?> >No</option>
                              </select></td>
                    </tr>
                    <tr class="hide">
                        <td><?php echo $text_mobile_validation; ?></td>
                        <td><input name="hb_oosn_mobile_validation"  class="form-control" type="text" value="<?php echo $hb_oosn_mobile_validation;?>"></td>
                    </tr>

                    <tr class="hide">
                      <td><?php echo $entry_animation; ?></td>
                      <td><select name="hb_oosn_animation"  class="form-control">
                      <option value="mfp-zoom-in" <?php echo ($hb_oosn_animation == 'mfp-zoom-in')? 'selected':''; ?> >Zoom</option>
                      <option value="mfp-newspaper" <?php echo ($hb_oosn_animation == 'mfp-newspaper')? 'selected':''; ?> >Newspaper</option>
                      <option value="mfp-move-horizontal" <?php echo ($hb_oosn_animation == 'mfp-move-horizontal')? 'selected':''; ?>>Horizontal Move</option>
                      <option value="mfp-move-from-top" <?php echo ($hb_oosn_animation == 'mfp-move-from-top')? 'selected':''; ?>>Move from top</option>
                      <option value="mfp-3d-unfold" <?php echo ($hb_oosn_animation == 'mfp-3d-unfold')? 'selected':''; ?>>3D unfold</option>
                      <option value="mfp-zoom-out" <?php echo ($hb_oosn_animation == 'mfp-zoom-out')? 'selected':''; ?>>Zoom-out</option>

                      </select></td>
                    </tr>

                    <tr class="hide">
                            <td><?php echo $entry_css; ?></td>
                            <td><textarea name="hb_oosn_css"  class="form-control" rows="10" cols="40" style="width: 90%"><?php echo $hb_oosn_css;?></textarea></td>
                          </tr>

                    <tr class="hide">
                        <td colspan="2"><h4><?php echo $text_header_condition; ?></h4></td>
                    </tr>

                    <tr>
                        <td><?php echo $text_product_qty; ?></td>
                        <td><input name="hb_oosn_product_qty"  class="form-control" type="text" value="<?php echo $hb_oosn_product_qty;?>"></td>
                    </tr>

                    <tr>
                        <td><?php echo $text_product_stock_status; ?></td>
                        <td><select name="hb_oosn_stock_status"  class="form-control">

                        <option value="0" <?php echo ($hb_oosn_stock_status ==  '0')? 'selected':''; ?> >Disabled</option>
                        <?php foreach ($stock_statuses as $stock_status) { ?>
                              <option value="<?php echo $stock_status['stock_status_id']; ?>" <?php echo ($hb_oosn_stock_status ==  $stock_status['stock_status_id'])? 'selected':''; ?> ><?php echo $stock_status['name']; ?></option>
                              <?php }?>
                              </select>
                        </td>
                    </tr>


                  </table>
				   <div class="alert alert-info"><b>Cron Command (Auto Notify):</b> wget --quiet --delete-after "<?php echo HTTP_CATALOG;?>index.php?route=product/product_oosn/autonotify"</div>

	            </div>


            </div>

          </form>

      </div>
    </div>
  </div>
  <div class="container-fluid"> <!--Huntbee copyrights-->
 <center>
  <span class="help hide">PRODUCT STOCK NOTIFICATION PRO VERSION 6.1 &copy; <a href="http://www.huntbee.com/">HUNTBEE.COM</a> | <a href="http://www.huntbee.com/index.php?route=account/support">SUPPORT</a></span></center>
</div><!--Huntbee copyrights end-->
</div>

<script type="text/javascript"><!--
$('#languages a:first').tab('show');
//--></script>
 <script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
$('#email<?php echo $language['language_id']; ?>').summernote({height: 300});
<?php } ?>
//--></script>
<script type="text/javascript">
function manualrun(){
	$('#msgoutput').html('');
	$('#loadgif').show();
	$.ajax({
		  type: 'post',
		  url: 'index.php?route=extension/hb_oosn/manualrun&token=<?php echo $token; ?>',
		  dataType: 'json',
		  success: function(json) {
				if (json['success']) {
					  $('#msgoutput').html(json['success']);
					  $('#loadgif').hide();
					  $("html, body").animate({ scrollTop: 0 }, "slow");
					  window.setTimeout(function(){location.reload()},2000)
				}
		  },
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}

	 });
}
</script>
<?php echo $footer; ?>