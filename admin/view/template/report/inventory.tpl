<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <span class="pull-right">
              <a href="<?php echo $internal_href; ?>" target="_blank"><i class="fa fa-print fa-fw"></i></a>
            </span>
            <h3 class="panel-title"><i class="fa fa-cubes"></i> <?php echo $text_internal; ?></h3>
          </div>
          <div class="table-responsive">
            <table class="table table-condensed table-scroll">
              <thead<?php if (count($products_int) < 9) echo ' style="width:100%;"'; ?>>
                <tr>
                  <th class="col-xs-2 text-left"><?php echo $column_model; ?></th>
                  <th class="col-xs-7 text-left"><?php echo $column_name; ?></th>
                  <th class="col-xs-1 text-center"><?php echo $column_cost; ?></th>
                  <th class="col-xs-1 text-center"><?php echo $column_quantity; ?></th>
                  <th class="col-xs-1 text-center"><?php echo $column_total; ?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($products_int as $product) { ?>
                <tr>
                  <td class="col-xs-2 text-left"><span class="text-<?php echo $product['color']; ?>"><i class="fa fa-circle"></i></span>&ensp;<a href="<?php echo $product['href']; ?>"><?php echo $product['model']; ?></a></td>
                  <td class="col-xs-7 text-left"><?php echo $product['name']; ?></td>
                  <td class="col-xs-1 text-center"><?php echo $product['cost']; ?></td>
                  <td class="col-xs-1 text-center"><?php echo $product['quantity']; ?></td>
                  <td class="col-xs-1 text-center"><?php echo $product['total']; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
          <div class="panel-footer">
            <?php echo $internal_count; ?>
            <span class="pull-right"><?php echo $internal_total; ?></span>
          </div>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-plus"></i> <?php echo $text_markup; ?></h3>
          </div>
          <div class="table-responsive">
            <table class="table table-condensed table-scroll">
              <thead<?php if (count($products_low) < 9) echo ' style="width:100%;"'; ?>>
                <tr>
                  <th class="col-xs-2 text-left"><?php echo $column_model; ?></th>
                  <th class="col-xs-9 text-left"><?php echo $column_name; ?></th>
                  <th class="col-xs-1 text-center"><?php echo $column_profit; ?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($products_low as $product) { ?>
                <tr>
                  <td class="col-xs-2 text-left"><span class="text-<?php echo $product['color']; ?>"><i class="fa fa-circle"></i></span>&ensp;<a href="<?php echo $product['href']; ?>"><?php echo $product['model']; ?></a></td>
                  <td class="col-xs-9 text-left"><?php echo $product['name']; ?></td>
                  <td class="col-xs-1 text-center"><span class="label label-<?php echo $product['class']; ?>"><?php echo $product['profit']; ?></span></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
          <div class="panel-footer">
            <?php echo $markup_count; ?>
            <span class="pull-right"><?php echo $markup_total; ?></span>
          </div>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-ban"></i> <?php echo $text_outofstock; ?></h3>
          </div>
          <div class="table-responsive">
            <table class="table table-condensed table-scroll">
              <thead<?php if (count($products_out) < 9) echo ' style="width:100%;"'; ?>>
                <tr>
                  <th class="col-xs-2 text-left"><?php echo $column_model; ?></th>
                  <th class="col-xs-7 text-left"><?php echo $column_name; ?></th>
                  <th class="col-xs-3 text-center"><?php echo $column_supplier; ?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($products_out as $product) { ?>
                <tr>
                  <td class="col-xs-2 text-left"><span class="text-<?php echo $product['color']; ?>"><i class="fa fa-circle"></i></span>&ensp;<a href="<?php echo $product['href']; ?>"><?php echo $product['model']; ?></a></td>
                  <td class="col-xs-7 text-left"><?php echo $product['name']; ?></td>
                  <td class="col-xs-3 text-center"><?php echo $product['supplier']; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
          <div class="panel-footer">
            <?php echo $outofstock_count; ?>
            <span class="pull-right"><?php echo $outofstock_total; ?></span>
          </div>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-certificate"></i> <?php echo $text_onsale; ?></h3>
          </div>
          <div class="table-responsive">
            <table class="table table-condensed table-scroll">
              <thead<?php if (count($products_sale) < 9) echo ' style="width:100%;"'; ?>>
                <tr>
                  <th class="col-xs-2 text-left"><?php echo $column_model; ?></th>
                  <th class="col-xs-4 text-left"><?php echo $column_name; ?></th>
                  <th class="col-xs-2 text-left"><?php echo $column_type; ?></th>
                  <th class="col-xs-1 text-left"><?php echo $column_price; ?></th>
                  <th class="col-xs-1 text-left">&nbsp;</th>
                  <th class="col-xs-2 text-center"><?php echo $column_date_end; ?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($products_sale as $product) { ?>
                <tr>
                  <td class="col-xs-2 text-left"><span class="text-<?php echo $product['color']; ?>"><i class="fa fa-circle"></i></span>&ensp;<a href="<?php echo $product['href']; ?>"><?php echo $product['model']; ?></a></td>
                  <td class="col-xs-4 text-left"><?php echo $product['name']; ?></td>
                  <td class="col-xs-2 text-left"><?php echo $product['sale_type']; ?></td>
                  <td class="col-xs-1 text-left"><s><?php echo $product['price']; ?></s></td>
                  <td class="col-xs-1 text-left"><?php echo $product['sale_price']; ?></td>
                  <td class="col-xs-2 text-center"><?php echo $product['sale_end']; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
          <div class="panel-footer">
            <?php echo $special_count; ?>
            <span class="pull-right"><?php echo $special_total; ?></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<style>
  .table-scroll thead,
  .table-scroll tbody,
  .table-scroll tr,
  .table-scroll th,
  .table-scroll td {
    display: block;
  }
  .table-scroll > thead {
    width: 98.5%;
  }
  .table-scroll > thead > tr > th {
    float: left;
    border-bottom: 1px #ddd solid;
  }
  .table-scroll > tbody {
    height: 250px;
    overflow-y: auto;
    width: 100%;
  }
  .table-scroll > tbody > tr > td {
    float: left;
    border-bottom-width: 0;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
  }
  .table-scroll > tbody > tr:first-child > td {
    border-top-width: 0;
  }
</style>

<?php echo $footer; ?>