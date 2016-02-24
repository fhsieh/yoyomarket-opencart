<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-cod_fee" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $button_save; ?></button><!-- yym -->
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
        <div class="panel-title"><i class="fa fa-pencil"></i> Edit Collect On Delivery Fee</div>
      </div>
      <div class="panel-body">
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-cod_fee" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="cod_fee_status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="cod_fee_status" id="cod_fee_status" class="form-control">>
                <option value="1" <?php if ($cod_fee_status==1) { echo 'selected="selected"';}?>><?php echo $text_enabled; ?></option>
                <option value="0" <?php if ($cod_fee_status==0) { echo 'selected="selected"';}?>><?php echo $text_disabled; ?></option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="cod_fee_sort_order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="cod_fee_sort_order" value="<?php echo $cod_fee_sort_order; ?>" size="1" class="form-control "/><!-- yym -->
            </div>
          </div>
		</form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>