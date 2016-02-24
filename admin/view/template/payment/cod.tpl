<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button class="btn btn-primary" type="submit" form="form-cod" data-toggle="tooltip" title="<?php echo $button_save; ?>"><i class="fa fa-save"></i> <?php echo $button_save; ?></button><!-- yym -->
        <a class="btn btn-default" href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>"><i class="fa fa-reply"></i> <?php echo $button_cancel; ?></a><!-- yym -->
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> Edit Collect On Delivery</h3>
      </div>
      <div class="panel-body">
		<?php if ($error_warning) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
        <form class="form-horizontal" id="form-html-content" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
          <ul class="nav nav-tabs" id="cod-tabs">
            <li><a href="#tab-default" data-toggle="tab" style="height:40px;"><?php echo $text_default; ?></a></li>
            <?php foreach ($extensions as $extension) { ?>
            <li><a href="#tab-<?php echo $extension['name']; ?>" data-toggle="tab" style="height:40px;"><?php echo $extension['title']; ?></a></li>
            <?php } ?>
          </ul>
          <div class="tab-content">
            <div class="tab-pane" id="tab-default">
              <div class="col-sm-2">
                <ul class="nav nav-pills nav-stacked" id="module">
                  <li><a href="#tab-default-extension" data-toggle="tab"><?php echo $tab_extension; ?></a></li>
                  <li><a href="#tab-default-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                  <?php foreach ($geo_zones as $geo_zone) { ?>
                  <li><a href="#tab-default-geozone-<?php echo $geo_zone['geo_zone_id']; ?>" data-toggle="tab"><?php echo $geo_zone['name']; ?></a></li>
                  <?php } ?>
                </ul>
              </div>
              <div class="col-sm-10">
                <div class="tab-content">
                  <div class="tab-pane" id="tab-default-extension">
                    <div class="form-group">
                      <label class="col-sm-2 control-label" for="cod_status"><span data-toggle="tooltip" title="<?php echo $entry_extension_status_info; ?>"><?php echo $entry_extension_status; ?></span></label>
                      <div class="col-sm-10">
                        <select class="form-control" name="cod_status">
                          <option value="0" <?php if ($cod_status==0){echo 'selected="selected"';}?>><?php echo $text_disabled; ?></option>
                          <option value="1" <?php if ($cod_status==1){echo 'selected="selected"';}?>><?php echo $text_enabled; ?></option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane" id="tab-default-general">
                    <div class="form-group">
                      <label class="col-sm-2 control-label" for="cod_default_status"><span data-toggle="tooltip" title="<?php echo $entry_status_info; ?>"><?php echo $entry_status; ?></span></label>
                      <div class="col-sm-10">
                        <select class="form-control" name="cod_default_status">
                          <option value="0" <?php if ($cod_default_status==0){echo 'selected="selected"';}?>><?php echo $text_disabled; ?></option>
                          <option value="1" <?php if ($cod_default_status==1){echo 'selected="selected"';}?>><?php echo $text_enabled; ?></option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label" for="cod_default_shipping_geo_zone"><span data-toggle="tooltip" title="<?php echo $entry_shipping_geo_zone_info; ?>"><?php echo $entry_shipping_geo_zone; ?></span></label>
                      <div class="col-sm-10">
                        <select class="form-control" name="cod_default_shipping_geo_zone">
                          <option value="0" <?php if ($cod_default_shipping_geo_zone==0){echo 'selected="selected"';}?>><?php echo $text_disabled; ?></option>
                          <option value="1" <?php if ($cod_default_shipping_geo_zone==1){echo 'selected="selected"';}?>><?php echo $text_enabled; ?></option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label" for="cod_default_sort_order"><span data-toggle="tooltip" title="<?php echo $entry_sort_order_info; ?>"><?php echo $entry_sort_order; ?></span></label>
                      <div class="col-sm-10">
                        <input class="form-control" type="text" name="cod_default_sort_order" value="<?php echo $cod_default_sort_order; ?>" size="1" style="width:35px;" />
                        <?php if ($cod_default_sort_order_error) { ?>
                        <div class="text-danger"><?php echo $cod_default_sort_order_error; ?></div>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                  <?php foreach ($geo_zones as $geo_zone) { ?>
                  <div class="tab-pane" id="tab-default-geozone-<?php echo $geo_zone['geo_zone_id']; ?>">
                    <div class="form-group">
                      <label class="col-sm-2 control-label" for="cod_default_<?php echo $geo_zone['geo_zone_id']; ?>_customer_group"><span data-toggle="tooltip" title="<?php echo $entry_customer_group_info; ?>"><?php echo $entry_customer_group; ?></span></label>
                      <div class="col-sm-10">
                        <select class="form-control" name="cod_default_<?php echo $geo_zone['geo_zone_id']; ?>_customer_group" id="cod_default_<?php echo $geo_zone['geo_zone_id']; ?>_customer_group" onchange="showCustomerGroupOptions('default','<?php echo $geo_zone['geo_zone_id']; ?>')">
                          <?php foreach ($customer_groups as $customer_group) { ?>
                          <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                    <?php
                    $display=true;
                    foreach ($customer_groups as $customer_group) { ?>
                    <div  name="cod_default_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>" id="cod_default_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>" <?php if($display){$display=false;}else{ echo 'style="display:none;"';} ?>>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="cod_default_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_tax_class_id"><span data-toggle="tooltip" title="<?php echo $entry_tax_class_info; ?>"><?php echo $entry_tax_class; ?></span></label>
                        <div class="col-sm-10">
                          <select class="form-control" name="cod_default_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_tax_class_id">
                            <option value="0" <?php if (${'cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_tax_class_id'}==0){echo 'selected="selected"';}?>><?php echo $text_none; ?></option>
                            <?php foreach ($tax_classes as $tax_class) { ?>
                            <option value="<?php echo $tax_class['tax_class_id']; ?>" <?php if(${'cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_tax_class_id'}==$tax_class['tax_class_id']){echo 'selected="selected"';}?>><?php echo $tax_class['title']; ?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="cod_default_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_method"><span data-toggle="tooltip" title="<?php echo $entry_method_info; ?>"><?php echo $entry_method; ?></span></label>
                        <div class="col-sm-10">
                          <input class="radio-inline" type="radio" name="cod_default_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_method" value="0" <?php if(${'cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_method'}==0){echo 'checked';} ?> onClick="showMethodOptions('default','<?php echo $geo_zone['geo_zone_id']; ?>','<?php echo $customer_group['customer_group_id']; ?>')" /><?php echo $entry_flat_rate; ?>
                          <input class="radio-inline" type="radio" name="cod_default_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_method" value="1" <?php if(${'cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_method'}==1){echo 'checked';} ?> onClick="showMethodOptions('default','<?php echo $geo_zone['geo_zone_id']; ?>','<?php echo $customer_group['customer_group_id']; ?>')" /><?php echo $entry_percentage; ?>
                          <input class="radio-inline" type="radio" name="cod_default_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_method" value="2" <?php if(${'cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_method'}==2){echo 'checked';} ?> onClick="showMethodOptions('default','<?php echo $geo_zone['geo_zone_id']; ?>','<?php echo $customer_group['customer_group_id']; ?>')" /><?php echo $entry_custom; ?>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="cod_default_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_flat"><span data-toggle="tooltip" title="<?php echo $entry_flat_rate_info; ?>"><?php echo $entry_flat_rate; ?></span></label>
                        <div class="col-sm-10">
                          <input class="form-control" type="text" name="cod_default_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_flat" value="<?php echo ${'cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat'}?>" />
                          <?php if (${'cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat_error'}) { ?>
                          <div class="text-danger"><?php echo ${'cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat_error'}; ?></div>
                          <?php } ?>
                          <select class="form-control" name="cod_default_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_flat_currency">
                            <?php foreach ($currencies as $currency) { ?>
                            <option value="<?php echo $currency['currency_id']?>" <?php if(${'cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat_currency'}==$currency['currency_id']){echo 'selected="selected"';}?>><?php echo $currency['title']?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="cod_default_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_percent"><span data-toggle="tooltip" title="<?php echo $entry_percentage_info; ?>"><?php echo $entry_percentage; ?></span></label>
                        <div class="col-sm-10">
                          <input class="form-control" type="text" name="cod_default_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_percent" value="<?php echo ${'cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_percent'}?>" />
                          <?php if (${'cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_percent_error'}) { ?>
                          <div class="text-danger"><?php echo ${'cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_percent_error'}; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="cod_default_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_custom"><span data-toggle="tooltip" title="<?php echo $entry_custom_info; ?>"><?php echo $entry_custom; ?></span></label>
                        <div class="col-sm-10">
                          <textarea class="form-control" name="cod_default_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_custom" cols="40" rows="5"><?php echo ${'cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_custom'}; ?></textarea>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="cod_default_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_enable_rule"><span data-toggle="tooltip" title="<?php echo $entry_enable_rule_info; ?>"><?php echo $entry_enable_rule; ?></span></label>
                        <div class="col-sm-10">
                          <textarea class="form-control" name="cod_default_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_enable_rule" cols="40" rows="5"><?php echo ${'cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_enable_rule'}; ?></textarea>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="cod_default_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_order_status_id"><span data-toggle="tooltip" title="<?php echo $entry_order_status_info; ?>"><?php echo $entry_order_status; ?></span></label>
                        <div class="col-sm-10">
                          <select class="form-control" name="cod_default_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_order_status_id">
                            <?php foreach ($order_statuses as $order_status) { ?>
                            <option value="<?php echo $order_status['order_status_id']; ?>" <?php if (${'cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_status_id'}==$order_status['order_status_id']){ echo 'selected="selected"';}?>><?php echo $order_status['name']; ?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="cod_default_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_order_total"><span data-toggle="tooltip" title="<?php echo $entry_order_total_info; ?>"><?php echo $entry_order_total; ?></span></label>
                        <div class="col-sm-10">
                          <select class="form-control" name="cod_default_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_order_total">
                            <option value="0" <?php if (${'cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total'}==0){echo 'selected="selected"';}?>><?php echo $text_disabled; ?></option>
                            <option value="1" <?php if (${'cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total'}==1){echo 'selected="selected"';}?>><?php echo $text_enabled; ?></option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="cod_default_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_order_total_sort_order"><span data-toggle="tooltip" title="<?php echo $entry_order_total_sort_order_info; ?>"><?php echo $entry_order_total_sort_order; ?></span></label>
                        <div class="col-sm-10">
                          <input class="form-control" type="text" name="cod_default_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_order_total_sort_order" value="<?php echo ${'cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total_sort_order'}?>" />
                          <?php if (${'cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total_sort_order_error'}) { ?>
                          <div class="text-danger"><?php echo ${'cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total_sort_order_error'}; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="cod_default_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_status"><span data-toggle="tooltip" title="<?php echo $entry_status_info; ?>"><?php echo $entry_status; ?></span></label>
                        <div class="col-sm-10">
                          <select class="form-control" name="cod_default_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_status">
                            <option value="0" <?php if (${'cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_status'}==0){echo 'selected="selected"';}?>><?php echo $text_disabled; ?></option>
                            <option value="1" <?php if (${'cod_default_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_status'}==1){echo 'selected="selected"';}?>><?php echo $text_enabled; ?></option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div>
              </div>
            </div>
            <?php foreach ($extensions as $extension) { ?>
            <!--Extension <?php echo $extension['name']; ?> Tab-->
            <div class="tab-pane" id="tab-<?php echo $extension['name']; ?>">
              <div class="col-sm-2">
                <ul class="nav nav-pills nav-stacked" id="module">
                  <li><a href="#tab-<?php echo $extension['name']; ?>-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                  <?php foreach ($geo_zones as $geo_zone) { ?>
                  <li><a href="#tab-<?php echo $extension['name']; ?>-geozone-<?php echo $geo_zone['geo_zone_id']; ?>" data-toggle="tab"><?php echo $geo_zone['name']; ?></a></li>
                  <?php } ?>
                </ul>
              </div>
              <div class="col-sm-10">
                <div class="tab-content">
                  <div class="tab-pane" id="tab-<?php echo $extension['name']; ?>-general">
                    <div class="form-group">
                      <label class="col-sm-2 control-label" for="cod_<?php echo $extension['name']; ?>_status"><span data-toggle="tooltip" title="<?php echo $entry_status_info; ?>"><?php echo $entry_status; ?></span></label>
                      <div class="col-sm-10">
                        <select class="form-control" name="cod_<?php echo $extension['name']; ?>_status">
                          <option value="0" <?php if (${'cod_'.$extension['name'].'_status'}==0){echo 'selected="selected"';}?>><?php echo $text_default; ?></option>
                          <option value="1" <?php if (${'cod_'.$extension['name'].'_status'}==1){echo 'selected="selected"';}?>><?php echo $text_disabled; ?></option>
                          <option value="2" <?php if (${'cod_'.$extension['name'].'_status'}==2){echo 'selected="selected"';}?>><?php echo $text_enabled; ?></option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label" for="cod_<?php echo $extension['name']; ?>_shipping_geo_zone"><span data-toggle="tooltip" title="<?php echo $entry_shipping_geo_zone_info; ?>"><?php echo $entry_shipping_geo_zone; ?></span></label>
                      <div class="col-sm-10">
                        <select class="form-control" name="cod_<?php echo $extension['name']; ?>_shipping_geo_zone">
                          <option value="0" <?php if (${'cod_'.$extension['name'].'_shipping_geo_zone'}==0){echo 'selected="selected"';}?>><?php echo $text_default; ?></option>
                          <option value="1" <?php if (${'cod_'.$extension['name'].'_shipping_geo_zone'}==1){echo 'selected="selected"';}?>><?php echo $text_disabled; ?></option>
                          <option value="2" <?php if (${'cod_'.$extension['name'].'_shipping_geo_zone'}==2){echo 'selected="selected"';}?>><?php echo $text_enabled; ?></option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label" for="cod_<?php echo $extension['name']; ?>_sort_order"><span data-toggle="tooltip" title="<?php echo $entry_sort_order_info; ?>"><?php echo $entry_sort_order; ?></span></label>
                      <div class="col-sm-10">
                        <input class="form-control" type="text" name="cod_<?php echo $extension['name']; ?>_sort_order" value="<?php echo ${'cod_'.$extension['name'].'_sort_order'}; ?>" size="1" style="width:35px;" />
                        <?php if (${'cod_'.$extension['name'].'_sort_order_error'}) { ?>
                        <div class="text-danger"><?php echo ${'cod_'.$extension['name'].'_sort_order_error'}; ?></div>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                  <?php foreach ($geo_zones as $geo_zone) { ?>
                  <div class="tab-pane" id="tab-<?php echo $extension['name']; ?>-geozone-<?php echo $geo_zone['geo_zone_id']; ?>">
                    <div class="form-group">
                      <label class="col-sm-2 control-label" for="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>_customer_group"><span data-toggle="tooltip" title="<?php echo $entry_customer_group_info; ?>"><?php echo $entry_customer_group; ?></span></label>
                      <div class="col-sm-10">
                        <select class="form-control" name="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>_customer_group" id="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>_customer_group" onchange="showCustomerGroupOptions('<?php echo $extension['name']; ?>','<?php echo $geo_zone['geo_zone_id']; ?>')">
                          <?php foreach ($customer_groups as $customer_group) { ?>
                          <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                    <?php
                    $display=true;
                    foreach ($customer_groups as $customer_group) { ?>
                    <div  name="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>" id="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>" <?php if($display){$display=false;}else{ echo 'style="display:none;"';} ?>>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_tax_class_id"><span data-toggle="tooltip" title="<?php echo $entry_tax_class_info; ?>"><?php echo $entry_tax_class; ?></span></label>
                        <div class="col-sm-10">
                          <select class="form-control" name="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_tax_class_id">
                            <option value="0" <?php if (${'cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_tax_class_id'}==0){echo 'selected="selected"';}?>><?php echo $text_default; ?></option>
                            <option value="-1" <?php if (${'cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_tax_class_id'}==-1){echo 'selected="selected"';}?>><?php echo $text_none; ?></option>
                            <?php foreach ($tax_classes as $tax_class) { ?>
                            <option value="<?php echo $tax_class['tax_class_id']; ?>" <?php if(${'cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_tax_class_id'}==$tax_class['tax_class_id']){echo 'selected="selected"';}?>><?php echo $tax_class['title']; ?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_method"><span data-toggle="tooltip" title="<?php echo $entry_method_info; ?>"><?php echo $entry_method; ?></span></label>
                        <div class="col-sm-10">
                          <input type="radio" name="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_method" value="0" <?php if(${'cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_method'}==0){echo 'checked';} ?> onClick="showMethodOptions('<?php echo $extension['name']; ?>','<?php echo $geo_zone['geo_zone_id']; ?>','<?php echo $customer_group['customer_group_id']; ?>')" ><?php echo $text_default; ?>
                          <input type="radio" name="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_method" value="1" <?php if(${'cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_method'}==1){echo 'checked';} ?> onClick="showMethodOptions('<?php echo $extension['name']; ?>','<?php echo $geo_zone['geo_zone_id']; ?>','<?php echo $customer_group['customer_group_id']; ?>')" ><?php echo $entry_flat_rate; ?>
                          <input type="radio" name="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_method" value="2" <?php if(${'cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_method'}==2){echo 'checked';} ?> onClick="showMethodOptions('<?php echo $extension['name']; ?>','<?php echo $geo_zone['geo_zone_id']; ?>','<?php echo $customer_group['customer_group_id']; ?>')" ><?php echo $entry_percentage; ?>
                          <input type="radio" name="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_method" value="3" <?php if(${'cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_method'}==3){echo 'checked';} ?> onClick="showMethodOptions('<?php echo $extension['name']; ?>','<?php echo $geo_zone['geo_zone_id']; ?>','<?php echo $customer_group['customer_group_id']; ?>')" ><?php echo $entry_custom; ?>
                        </div>
                      </div>
                      <div name="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_method_0" id="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_method_0" <?php if(${'cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_method'}!=0){echo 'style="display:none;"';}?>>
                      </div>
                      <div name="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_method_1" id="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_method_1" <?php if(${'cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_method'}!=1){echo 'style="display:none;"';}?>>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_flat"><span data-toggle="tooltip" title="<?php echo $entry_flat_rate_info; ?>"><?php echo $entry_flat_rate; ?></span></label>
                          <div class="col-sm-10">
                            <input class="form-control" type="text" name="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_flat" value="<?php echo ${'cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat'}?>" />
                            <?php if (${'cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat_error'}) { ?>
                            <div class="text-danger"><?php echo ${'cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat_error'}; ?></div>
                            <?php } ?>
                            <select class="form-control" name="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_flat_currency">
                              <?php foreach ($currencies as $currency) { ?>
                              <option value="<?php echo $currency['currency_id']?>" <?php if(${'cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_flat_currency'}==$currency['currency_id']){echo 'selected="selected"';}?>><?php echo $currency['title']?></option>
                              <?php } ?>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div name="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_method_2" id="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_method_2" <?php if(${'cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_method'}!=2){echo 'style="display:none;"';}?>>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_percent"><span data-toggle="tooltip" title="<?php echo $entry_percentage_info; ?>"><?php echo $entry_percentage; ?></span></label>
                          <div class="col-sm-10">
                            <input class="form-control" type="text" name="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_percent" value="<?php echo ${'cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_percent'}?>" />
                            <?php if (${'cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_percent_error'}) { ?>
                            <div class="text-danger"><?php echo ${'cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_percent_error'}; ?></div>
                            <?php } ?>
                          </div>
                        </div>
                      </div>
                      <div name="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_method_3" id="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_method_3" <?php if(${'cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_method'}!=3){echo 'style="display:none;"';}?>>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" for="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_custom"><span data-toggle="tooltip" title="<?php echo $entry_custom_info; ?>"><?php echo $entry_custom; ?></span></label>
                          <div class="col-sm-10">
                            <textarea class="form-control" name="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_custom" cols="40" rows="5"><?php echo ${'cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_custom'}; ?></textarea>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_enable_rule"><span data-toggle="tooltip" title="<?php echo $entry_enable_rule_info; ?>"><?php echo $entry_enable_rule; ?></span></label>
                        <div class="col-sm-10">
                          <textarea class="form-control" name="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_enable_rule" cols="40" rows="5"><?php echo ${'cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_enable_rule'}; ?></textarea>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_order_status_id"><span data-toggle="tooltip" title="<?php echo $entry_order_status_info; ?>"><?php echo $entry_order_status; ?></span></label>
                        <div class="col-sm-10">
                          <select class="form-control" name="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_order_status_id">
                            <option value="0" <?php if (${'cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_status_id'}==0){ echo 'selected="selected"';}?>><?php echo $text_default; ?></option>
                            <?php foreach ($order_statuses as $order_status) { ?>
                            <option value="<?php echo $order_status['order_status_id']; ?>" <?php if (${'cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_status_id'}==$order_status['order_status_id']){ echo 'selected="selected"';}?>><?php echo $order_status['name']; ?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_order_total"><span data-toggle="tooltip" title="<?php echo $entry_order_total_info; ?>"><?php echo $entry_order_total; ?></span></label>
                        <div class="col-sm-10">
                          <select class="form-control" name="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_order_total">
                            <option value="0" <?php if (${'cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total'}==0){echo 'selected="selected"';}?>><?php echo $text_default; ?></option>
                            <option value="1" <?php if (${'cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total'}==1){echo 'selected="selected"';}?>><?php echo $text_disabled; ?></option>
                            <option value="2" <?php if (${'cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total'}==2){echo 'selected="selected"';}?>><?php echo $text_enabled; ?></option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_order_total_sort_order"><span data-toggle="tooltip" title="<?php echo $entry_order_total_sort_order_info; ?>"><?php echo $entry_order_total_sort_order; ?></span></label>
                        <div class="col-sm-10">
                          <input class="form-control" type="text" name="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_order_total_sort_order" value="<?php echo ${'cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total_sort_order'}?>" />
                          <?php if (${'cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total_sort_order_error'}) { ?>
                          <div class="text-danger"><?php echo ${'cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_order_total_sort_order_error'}; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_status"><span data-toggle="tooltip" title="<?php echo $entry_status_info; ?>"><?php echo $entry_status; ?></span></label>
                        <div class="col-sm-10">
                          <select class="form-control" name="cod_<?php echo $extension['name']; ?>_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $customer_group['customer_group_id']; ?>_status">
                            <option value="0" <?php if (${'cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_status'}==0){echo 'selected="selected"';}?>><?php echo $text_default; ?></option>
                            <option value="1" <?php if (${'cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_status'}==1){echo 'selected="selected"';}?>><?php echo $text_disabled; ?></option>
                            <option value="2" <?php if (${'cod_'.$extension['name'].'_'.$geo_zone['geo_zone_id'].'_'.$customer_group['customer_group_id'].'_status'}==2){echo 'selected="selected"';}?>><?php echo $text_enabled; ?></option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div>
              </div>
            </div>
            <?php } ?>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function showCustomerGroupOptions(extension_name,geo_zone)
{
	var drop_down_group=document.getElementById("cod_"+extension_name+"_"+geo_zone+"_customer_group");
    for (var i = 0; i < drop_down_group.options.length; i++)
	{
        var drop_down_id = drop_down_group.options[i].value;
		method=document.getElementById("cod_"+extension_name+"_"+geo_zone+"_"+drop_down_id);
		method.setAttribute('style',method.style.cssText);
		method.style.cssText = 'display:none;';
        if (drop_down_group.options[drop_down_group.selectedIndex].value==drop_down_id)
		{
			method.style.cssText = 'display:block;';
		}
	}
}
//--></script>
<script type="text/javascript"><!--
function showMethodOptions(extension_name,geo_zone,customer_group)
{
	radio_group=document.getElementsByName("cod_"+extension_name+"_"+geo_zone+"_"+customer_group+"_method");
    for (var i = 0; i < radio_group.length; i++)
	{
        var button = radio_group[i];
		method=document.getElementById("cod_"+extension_name+"_"+geo_zone+"_"+customer_group+"_method_"+button.value);
		method.setAttribute('style',method.style.cssText);
		method.style.cssText = 'display:none;';

        if (button.checked)
		{
			method.style.cssText = 'display:block;';
		}
	}
}
//--></script>
<script type="text/javascript"><!--
$('#cod-tabs li:first-child a').tab('show');
$('#tab-default li:first-child a').tab('show');
//--></script>
<?php echo $footer; ?>