<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-html" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form method="POST" action="#">
          <input type="hidden" name="mc_api" id="mc_api" value="<?=$mailchimp_api?>" />
        </form>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-html" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              <?php if ($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php } ?>
            </div>
          </div>         
          <div class="tab-pane">
            <ul class="nav nav-tabs" id="language">
              <?php foreach ($languages as $language) { ?>
              <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
              <?php } ?>
            </ul>
            <div class="tab-content">
              <?php foreach ($languages as $language) { ?>
              <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-title<?php echo $language['language_id']; ?>"><?php echo $entry_title; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="module_description[<?php echo $language['language_id']; ?>][title]" placeholder="<?php echo $entry_title; ?>" id="input-heading<?php echo $language['language_id']; ?>" value="<?php echo isset($module_description[$language['language_id']]['title']) ? $module_description[$language['language_id']]['title'] : ''; ?>" class="form-control" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
                  <div class="col-sm-10">
                    <textarea name="module_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>" class="form-control" rows="6"><?php echo isset($module_description[$language['language_id']]['description']) ? $module_description[$language['language_id']]['description'] : ''; ?></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_success_message; ?></label>
                  <div class="col-sm-10">
                    <textarea name="module_description[<?php echo $language['language_id']; ?>][success_message]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>" class="form-control" rows="6"><?php echo isset($module_description[$language['language_id']]['success_message']) ? $module_description[$language['language_id']]['success_message'] : ''; ?></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-entry_button_text<?php echo $language['language_id']; ?>"><?php echo $entry_button_text; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="module_description[<?php echo $language['language_id']; ?>][button_text]" placeholder="<?php echo $placeholder_button_text; ?>" id="input-button_text<?php echo $language['language_id']; ?>" value="<?php echo isset($module_description[$language['language_id']]['button_text']) ? $module_description[$language['language_id']]['button_text'] : ''; ?>" class="form-control" />
                  </div>
                </div>
              </div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="status" id="input-status" class="form-control">
                <?php if ($status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-popup"><?php echo $text_popup_form; ?></label>
            <div class="col-sm-10">
              <select name="popup" id="input-popup" class="form-control">
                <?php if ($popup) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>

          
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-list_id"><?php echo $entry_list; ?></label>
            <div class="col-sm-10">
              <select name="list_id" id="input-list_id" class="form-control">
                <option value=""><?php echo $text_select; ?></option>
                <?php
                foreach($mailchimp_list['data'] as $mc_list){
                ?>
                  <option 
                    value="<?=$mc_list['id']?>" 
                    <?=(($list_id == $mc_list['id'])?' selected="selected" ':'');?>
                  ><?php echo $mc_list['name']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-list_id"><?php echo $entry_list_fields; ?></label>
            <div class="col-sm-10">
              <div id="mc_list_loading"></div>
              <ul id="merge_vars" class="list-unstyled merge_vars">
              <?php if($list_details){ ?>
              <?php 
              foreach ($list_details['data'][0]['merge_vars'] as $key=>$ld) { 
                $checked = '';
                $lf_order = 0;
                foreach ($list_fields as $lf_items) {
                  if(isset($lf_items['tag']) && $ld['tag'] == $lf_items['tag']){
                     $checked = ' checked="checked" ';
                     $lf_order = $lf_items['order']; 
                     break;
                  }
                }
              ?>
                <li class="checkbox">
                  <div class="form-inline" style="clear:both;">
                    <div class="chekbox pull-left">
                    <label><input class="form-control" <?=$checked?> type="checkbox" name="list_fields[<?=$key?>][tag]" value="<?=$ld['tag']?>" /> <?=$text_show?>&nbsp;</label>
                    </div>
                    <div class="chekbox pull-left">
                    <input type="text" name="list_fields[<?=$key?>][order]" value="<?=$lf_order?>" style="width:40px;" class="form-control text-center input-sm" /> <?=$text_sort?>&nbsp;
                    </div>
                    <?php 
                    foreach ($languages as $language) { 
                      $label = $ld['name'];
                      if(isset($list_fields[$key]) && isset($list_fields[$key][$language['language_id']]) && isset($list_fields[$key][$language['language_id']]['name'])){
                        $label = $list_fields[$key][$language['language_id']]['name'];
                      }
                    ?>
                    <div class="pull-left lsfields list-fields-<?=$language['language_id']?>">
                      <input name="list_fields[<?=$key?>][<?php echo $language['language_id']; ?>][name]" type="text" value="<?=$label?>" class="form-control input-sm" />
                    </div>
                    <?php } ?>
                    <div class="pull-left">
                      <?=!empty($ld['req'])?'<div class="text-danger">&nbsp;* required</div>':'';?>
                    </div>
                  </div>
                 </li>
              <?php } ?>
              </ul>
               <?php } ?>
            </div>
          </div>
         
        </form>
      </div>
    </div>
  </div>
  <?php /*
  <script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
$('#input-description<?php echo $language['language_id']; ?>').summernote({height: 300});
<?php } ?>
//--></script> 
*/ ?>
  <script type="text/javascript"><!--
$('#language a:first').tab('show');
//--></script></div>

<script type="text/javascript">
function loadLists(){
    if($.trim($('#mc_api').val()).length>0){
      $('#merge_vars').html('<i class="fa fa-spinner fa-spin"></i>');
      $.ajax({
        async: true,
        url: 'index.php?route=module/mailchimp/getlistInfo&token=<?php echo $token; ?>&list_id='+$('#input-list_id').val()+'&api_key=' + $('#mc_api').val(),
        dataType: 'json',
        beforeSend: function() {
          $('.mc_list_loading').after('<div id="mc_list_loading"><span class="wait_list">&nbsp;<i class="fa fa-spinner fa-spin"></i></span></div>').fadeOut(0);

        },    
        complete: function() {
          $('.wait_list').remove();
          $('.mc_list_loading').fadeIn('slow');
        },      
        success: function(json) { 
          html = '';
          if(json.total != 0){
            $.each(json.data[0].merge_vars, function(mck, mcv){
              html += '<li class="checkbox"><div class="form-inline" style="clear:both;">';
              html += '<div class="chekbox pull-left"><label><input type="checkbox" name="['+mck+'][tag]" value="'+mcv.tag+'" /> <?=$text_show?>&nbsp;</label></div>';
              html += '<div class="chekbox pull-left"><input type="text" name="list_fields['+mck+'][order]" value="'+mcv.id+'" style="width:40px;" class="form-control text-center input-sm" /> <?=$text_sort?>&nbsp;</div>';
              <?php 
              foreach ($languages as $language) { 
              ?>
              html += '<div class="pull-left lsfields list-fields-<?=$language['language_id']?>">';
              html += '<input name="list_fields['+mck+'][<?php echo $language['language_id']; ?>][name]" type="text" value="'+mcv.name+'" class="form-control input-sm" />';
              html += '</div>';
              <?php } ?>

              if(mcv.req){
                html += '<div class="pull-left"><div class="text-danger">&nbsp;* required</div></div>';
              }
               html += '</div></li>';
            });

            $('#merge_vars').html(html);

            showListFields();

          }
        },
        error: function(xhr, ajaxOptions, thrownError) {
          loadLists();
        }
      });
    }

}
$(function(){
  $('#input-list_id').bind('change', function() {
    loadLists();
  }); 

  $('#language a').bind('click', function() {
    var language_id = $(this).attr('href').replace('#language','');
    $('.lsfields').hide();
    $('.list-fields-'+language_id).show();
  }); 

  showListFields();
});

function showListFields(){
  var language_id = $('#language li.active a:first').attr('href').replace('#language','');
  $('.lsfields').hide();
  $('.list-fields-'+language_id).show();
}
</script>
<style type="text/css">
.lsfields{
  display: none;
}
li.checkbox {
  margin-bottom: 0;
  overflow: auto;
}
#merge_vars .pull-left {
  float: left;
  line-height: 30px;
  margin-right: 10px;
}
</style>
<?php echo $footer; ?>