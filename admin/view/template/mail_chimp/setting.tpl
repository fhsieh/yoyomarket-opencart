<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<form action="<?php echo $action; ?>" method="post" id="form-mc">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
			</div>
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


		<ul class="nav nav-tabs">
	        <li class="active"><a href="#tab-setting" data-toggle="tab"><?php echo $tab_setting; ?></a></li>
	        <li><a id="help_tab" href="#tab-help" data-toggle="tab"><?php echo $tab_help; ?></a></li>
	        <li><a id="news_and_updates" href="#tab-new_and_updates" data-toggle="tab"><?php echo $tab_new_and_updates; ?></a></li>
      	</ul>
		<div class="tab-content">
            <div class="tab-pane active" id="tab-setting">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h3>
					</div>
					<div class="panel-body">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group required">
					                	<label class="col-sm-2 control-label" for="api-key">
					                		<span data-toggle="tooltip" data-html="true" title="<?=$text_apikey_help?>"><?php echo $entry_api_key; ?>
					                		</span>
					                	</label>
					                	<div class="col-sm-10">
					                		<div class="input-group">
										      	<input placeholder="<?=$text_apikey_placeholder?>" class="form-control" type="text" name="mailchimp_api" value="<?php echo $mailchimp_api; ?>" size="80" id="mc_api" />
										      	<span class="input-group-btn">
										        	<button id="list-load-btn" type="button" class="btn btn-primary disabled" onclick="loadLists();return false;"><?=$text_btn_load_list?></button>
										      	</span>
										    </div><!-- /input-group -->
					                  		<?php if ($error_api) { ?>
					                  		<div class="text-danger"><?php echo $error_api; ?></div>
					                  		<?php } ?>
					                	</div>
					              	</div>
								</div>
							</div>
							<br />
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
					                	<label class="col-sm-2 control-label">
					                		<span data-toggle="tooltip" data-html="true" title="<?=$text_ec360_help?>"><?php echo $entry_ecommerce360; ?>
					                		</span>
					                	</label>
					                	<div class="col-sm-10">
					                		<?php if($mailchimp_ecommerce360 == 1){ ?>
					                		<select name="mailchimp_ecommerce360" class="form-control" style="width:auto">
					                			<option value="1" selected="selected">Enable</option>
					                			<option value="0">Disable</option>
					                		</select>
					                		<?php } else { ?>
					                		<select name="mailchimp_ecommerce360" class="form-control" style="width:auto">
					                			<option value="1">Enable</option>
					                			<option value="0" selected="selected">Disable</option>
					                		</select>
					                		<?php } ?>
					                	</div>
					              	</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<p>&nbsp;</p>
									<div class="table-responsive">
										<table class="table table-hover table-striped table-bordered">
											<thead>
												<tr>
													<td class="text-right"><?php echo $entry_customer_group; ?></td>
													<td><?php echo $entry_list_id; ?></td>
												</tr>
											</thead>
											<tbody>
											<?php
											foreach($customer_groups as $customer_group){
												$error_list_id   = 'error_mc_list_'.$customer_group['customer_group_id'];
											?>
												<tr>
													<td class="text-right" width="150px">
														<label><?=$customer_group['name'];?></label>
													</td>
													<td>
														<select class="mc_lists form-control" name="mailchimp_list_<?=$customer_group['customer_group_id']?>" id="mc_list__<?=$customer_group['customer_group_id']?>"></select>
														<?php if (isset($$error_list_id)) { ?>
														<div class="text-danger"><?php echo $$error_list_id; ?></div>
														<?php } ?>
													</td>
												</tr>
											<?php } ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title"><i class="fa fa-exchange"></i> <?php echo $text_autosync; ?></h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-12">
									<a class="btn btn-primary" href="<?=$autosynclink?>" onclick="return check2proceed();"><?=$text_autosync_btn?></a><br /><br />
									<p class="alert alert-info"><?=$text_autosync_note?></p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="tab-help">
					
				</div>
				<div class="tab-pane" id="tab-new_and_updates">
					
				</div>
			</div>
		</div>
	</form>
</div>
<script type="text/javascript">
/* News and Updates */
$(function(){
	$('a#news_and_updates').bind('click', function(){
		$('#tab-new_and_updates').html('<iframe style="border: 0 none;height: 800px;width: 100%;" src="http://webby-blog.com/extension-news-and-update.html?'+(new Date().getTime())+'"></iframe>');
	});
        
    $('a#help_tab').bind('click', function(){
		$('#tab-help').html('<iframe style="border: 0 none;height: 800px;width: 100%;" src="http://webby-blog.com/mailchimp-update.html?'+(new Date().getTime())+'"></iframe>');
	});
});
/* News and Updates */
function check2proceed(){
	if(confirm('<?=str_replace("'","\'",$text_autosync_notice)?>'))	{
		return true;
	} 
	return false
}
function loadLists(){
		if($.trim($('#mc_api').val()).length>0){
			$.ajax({
				async: true,
				url: 'index.php?route=mail_chimp/setting/getlist&token=<?php echo $token; ?>&api_key=' + $('#mc_api').val(),
				dataType: 'json',
				beforeSend: function() {
					$('.mc_lists').after('<span class="wait_list">&nbsp;<i class="fa fa-spinner fa-spin"></i> <?=$text_loading?></span>').fadeOut(0);
				},		
				complete: function() {
					$('.wait_list').remove();
					$('.mc_lists').fadeIn('slow');
				},			
				success: function(json) {	
					var html = '<option value=""><?php echo $text_select; ?></option>';

					if(json.total == 0){
						alert('Sorry no list found, Please create list in MailChimp then proceed.');
						html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
					} else {
						$.each(json.data, function(mck, mcv){
							html += '<option value="' + mcv.id + '">' + mcv.name + '</option>';
						});
					}

					$('.mc_lists').each(function(){
						$(this).html(html);
					});

					<?php 
					foreach($customer_groups as $customer_group){ 
						$mc_list_custgrp = 'mailchimp_list_'.$customer_group['customer_group_id'];
						$current_val = trim($$mc_list_custgrp);
						if(strlen($current_val) > 0){
					?>
						$('#mc_list__<?=$customer_group['customer_group_id']?>').val('<?=$current_val?>');
					<?php
						}
					} 
					?>
				},
				error: function(xhr, ajaxOptions, thrownError) {
					loadLists();
				}
			});
		}

}
$(function(){
	$('#mc_api').bind('blur', function() {
		
		if($.trim($( "#mc_api" ).val()).length>0){
			$('#list-load-btn').removeClass('disabled');	
		}

		loadLists();
	});	
	$( "#mc_api" ).keyup(function() {
		if($.trim($( "#mc_api" ).val()).length>0){
			$('#list-load-btn').removeClass('disabled');	
		} else {
			$('#list-load-btn').removeClass('disabled').addClass('disabled');	
		}
	});
	if($.trim($('#mc_api').val()).length>0){
		loadLists();
	}
});
</script>
<?php echo $footer; ?>