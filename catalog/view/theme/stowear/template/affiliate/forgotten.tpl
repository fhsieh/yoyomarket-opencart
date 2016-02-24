<?php echo $header; 
$theme_options = $this->registry->get('theme_options');
$config = $this->registry->get('config'); 
include('catalog/view/theme/' . $config->get('config_template') . '/template/new_elements/wrapper_top.tpl'); ?>

<p><?php echo $text_email; ?></p>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
  <fieldset>
    <legend><?php echo $text_your_email; ?></legend>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
      <div class="col-sm-10">
        <input type="text" name="email" value="" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
      </div>
    </div>
  </fieldset>
  <div class="buttons clearfix">
    <div class="pull-left"><a href="<?php echo $back; ?>" class="btn btn-default"><?php echo $button_back; ?></a></div>
    <div class="pull-right">
      <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
    </div>
  </div>
</form>
  
<?php include('catalog/view/theme/' . $config->get('config_template') . '/template/new_elements/wrapper_bottom.tpl'); ?>
<?php echo $footer; ?>