<?php echo $header;
$theme_options = $this->registry->get('theme_options');
$config = $this->registry->get('config');
include('catalog/view/theme/' . $config->get('config_template') . '/template/new_elements/wrapper_top.tpl'); ?>

<h2><?php echo $text_address_book; ?></h2>
<?php if ($addresses) { ?>
  <?php $i = 0; ?>
  <div class="row">
  <?php foreach ($addresses as $result) { ?>
    <?php $i++; ?>
    <div class="col-sm-3">
      <div class="panel">
        <div class="panel-body" style="min-height: 160px;">
          <?php echo $result['address']; ?>
        </div>
        <div class="panel-footer">
          <a href="<?php echo $result['update']; ?>" data-toggle="tooltip" data-original-title="<?php echo $button_edit; ?>" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
          <a href="<?php echo $result['delete']; ?>" data-toggle="tooltip" data-original-title="<?php echo $button_delete; ?>" class="btn btn-default btn-xs"><i class="fa fa-times"></i></a>
        </div>
      </div>
    </div>
    <?php if ($i % 4 == 0) { ?>
  </div>
  <div class="row">
    <?php } ?>
  <?php } ?>
  </div>
<?php } else { ?>
<p><?php echo $text_empty; ?></p>
<?php } ?>
<div class="buttons clearfix">
  <div class="pull-left"><a href="<?php echo $back; ?>" class="btn btn-default"><?php echo $button_back; ?></a></div>
  <div class="pull-right"><a href="<?php echo $add; ?>" class="btn btn-primary"><?php echo $button_new_address; ?></a></div>
</div>

<?php include('catalog/view/theme/' . $config->get('config_template') . '/template/new_elements/wrapper_bottom.tpl'); ?>
<?php echo $footer; ?>
