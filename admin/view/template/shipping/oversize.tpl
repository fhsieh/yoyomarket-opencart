<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-flat" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $button_save; ?></button><!-- yym -->
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i> <?php echo $button_cancel; ?></a><!-- yym -->
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-flat" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="oversize_status" id="input-status" class="form-control">
              <?php if ($oversize_status) { ?>
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
            <label class="col-sm-2 control-label"><?php echo $entry_geo_zone; ?></label>
            <div class="col-sm-10">
              <div class="well well-sm" style="height: 150px; overflow: auto;">
                <div class="checkbox">
                  <label>
                  <?php if (in_array(0, $oversize_geo_zone_id)) { ?>
                    <input type="checkbox" name="oversize_geo_zone_id[]" value="0" checked="checked" />
                    <?php echo $text_all_zones; ?>
                  <?php } else { ?>
                    <input type="checkbox" name="oversize_geo_zone_id[]" value="0" />
                    <?php echo $text_all_zones; ?>
                  <?php } ?>
                  </label>
                </div>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <div class="checkbox">
                  <label>
                  <?php if (in_array($geo_zone['geo_zone_id'], $oversize_geo_zone_id)) { ?>
                    <input type="checkbox" name="oversize_geo_zone_id[]" value="<?php echo $geo_zone['geo_zone_id']; ?>" checked="checked" /><?php echo $geo_zone['name']; ?>
                  <?php } else { ?>
                    <input type="checkbox" name="oversize_geo_zone_id[]" value="<?php echo $geo_zone['geo_zone_id']; ?>" /><?php echo $geo_zone['name']; ?>
                  <?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
              <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-total"><span data-toggle="tooltip" title="<?php echo $help_text_label; ?>"><?php echo $entry_text_label; ?></span></label>
            <div class="col-sm-10">
            <?php foreach ($languages as $language) { ?>
              <div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                <input type="text" name="oversize_text_label[<?php echo $language['language_id']; ?>][label]" value="<?php echo isset($oversize_text_label[$language['language_id']]) ? $oversize_text_label[$language['language_id']]['label'] : $default_text_label; ?>" placeholder="<?php echo $entry_text_label; ?>" class="form-control" />
              </div>
              <?php if (isset($error_name[$language['language_id']])) { ?>
              <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
              <?php } ?>
            <?php } ?>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="oversize_sort_order" value="<?php echo $oversize_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>
		  <?php if($oversize_shipping_products){?>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_oversize_shipping_products; ?></label>
            <div class="col-sm-10">
              <ul>
              <?php foreach($oversize_shipping_products as $oversize_shipping_product){?>
                <li><a href="<?php echo $oversize_shipping_product['href']?>" title="Click to open edit page">[ <?php echo $oversize_shipping_product['product_id']?> ] <?php echo $oversize_shipping_product['name']?></a></li>
              <?php }?>
              </ul>
            </div>
          </div>
          <?php }?>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>