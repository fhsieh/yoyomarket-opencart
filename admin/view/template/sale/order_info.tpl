<?php echo $header; ?>
<div class="modal fade" id="quick-status-dialog" tabindex="-1" role="dialog"><div class="modalContent"></div></div>
<?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <div class="btn-group">
          <a href="<?php echo $shopping; ?>" target="_blank" data-toggle="tooltip" title="<?php echo $button_shopping_print; ?>" class="btn btn-info"><i class="fa fa-shopping-cart"></i> Shopping</a>
          <a href="<?php echo $shipping; ?>" target="_blank" data-toggle="tooltip" title="<?php echo $button_shipping_print; ?>" class="btn btn-info"><i class="fa fa-dropbox"></i> Packing</a>
          <div class="btn-group">
            <a href="<?php echo $invoice; ?>" target="_blank" data-toggle="tooltip" title="<?php echo $button_invoice_print; ?>" class="btn btn-info"><i class="fa fa-file-text"></i> Receipt</a>
            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-caret-down"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-right" role="menu">
              <li>
                <a class="dropdown-item" href="<?php echo $gift; ?>" target="_blank">Gift Receipt</a>
              </li>
            </ul>
          </div>
        </div>
        <!--<a href="<?php echo $edit; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i> <?php echo $button_edit; ?></a>--><!-- yym -->
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i> <?php echo $button_cancel; ?></a>
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
    <div class="row">
      <div class="col-sm-4 col-sm-push-8">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-info"></i> Order Information</h3>
          </div>
          <table class="table table-bordered table-condensed">
            <tr>
              <td class="col-xs-4"><?php echo $text_order_id; ?></td>
              <td class="clipboard"><span><?php echo $order_id; ?></span></td>
            </tr>
            <tr>
              <td><?php echo $text_date_added; ?></td>
              <td><?php echo $date_added; ?></td>
            </tr>
            <?php if ($order_status) { ?>
            <tr>
              <td><?php echo $text_order_status; ?></td>
              <td class="qosu-cell" order_id="<?php echo $order_id; ?>" title="<?php echo $text_qosu_add_history; ?>">
                <?php
                  $btn_class = array(
                    'Pending'     => 'btn-default',
                    'Approved'    => 'btn-default',
                    'Processing'  => 'btn-warning',
                    'On Hold'     => 'btn-warning',
                    'Packing'     => 'btn-success',
                    'Shipped'     => 'btn-primary',
                    'Cancelled'   => 'btn-info',
                    'Refunded'    => 'btn-info',
                    'Void'        => 'btn-danger'
                  );
                ?>
                <a class="btn btn-xs <?php echo $btn_class[$order_status]; ?>"><i class="fa fa-arrow-circle-right"></i> <?php echo $order_status; ?></a>
              </td>
            </tr>
            <?php } ?>
            <tr>
              <td><?php echo $text_invoice_no; ?></td>
              <td><?php if ($invoice_no) { ?>
                <?php echo $invoice_no; ?>
                <?php } else { ?>
                <button id="button-invoice" class="btn btn-success btn-xs"><i class="fa fa-cog"></i> <?php echo $button_generate; ?></button>
                <?php } ?></td>
            </tr>
            <tr id="order-payment_method" data-table="order" data-id="payment_method" data-value="<?php echo $payment_method; ?>">
              <td>
                <?php echo $text_payment_method; ?>
                <a role="button" data-toggle="popover" class="action-update btn btn-info btn-xs pull-right"><i class="fa fa-pencil"></i></a>
              </td>
              <td class="value"><?php echo $payment_method; ?></td>
            </tr>
            <?php if ($shipping_method) { ?>
            <tr id="order-shipping" data-table="order" data-id="shipping" data-value="<?php echo $shipping_method; ?>">
              <td><?php echo $text_shipping_method; ?></td>
              <td class="value"><?php echo $shipping_method; ?></td>
            </tr>
            <?php } ?>
            <tr id="order-delivery_preference" data-table="order" data-id="delivery_preference" data-value="<?php echo $delivery_preference; ?>">
              <td>
                <?php echo $text_delivery_preference; ?>
                <a role="button" data-toggle="popover" class="action-update btn btn-info btn-xs pull-right"><i class="fa fa-pencil"></i></a>
              </td>
              <td class="value"><?php echo $delivery_preference; ?></td>
            </tr>
            <tr id="order-delivery_telephone" data-table="order" data-id="delivery_telephone" data-value="<?php echo $delivery_telephone; ?>">
              <td>
                <?php echo $text_delivery_telephone; ?>
                <a role="button" data-toggle="popover" class="action-update btn btn-info btn-xs pull-right"><i class="fa fa-pencil"></i></a>
              </td>
              <td class="clipboard"><span class="value"><?php echo $delivery_telephone; ?></span></td>
            </tr>
            <tr id="order-shipping_date" data-table="order" data-id="shipping_date" data-value="<?php echo $shipping_date; ?>">
              <td>
                <?php echo $text_shipping_date; ?>
              </td>
              <td class="value">
                <?php if (!empty($shipping_date)) { ?>
                  <a role="button" data-toggle="popover" class="action-update btn btn-xs btn-<?php echo strtolower($shipping_dow); ?>"><?php echo $shipping_dow; ?></a>
                  <span class="date"><?php echo $shipping_date; ?></span>
                <?php } else { ?>
                  <a role="button" data-toggle="popover" class="action-update btn btn-xs btn-default">Set Date</a>
                <?php } ?>
              </td>
            </tr>
            <?php if (count($tracking_numbers)) { ?>
            <tr>
              <td>Tracking No.</td>
              <td style="padding: 0;">
                <table class="table table-condensed" style="margin-top: -1px; margin-bottom: 0;">
                  <?php foreach ($tracking_numbers as $tracking_number) { ?>
                  <!-- yym_todo: make editable? -->
                  <tr id="tracking-<?php echo $tracking_number['tracking_number_id']; ?>" data-table="tracking" data-id="<?php echo $tracking_number['tracking_number_id']; ?>" data-method-id="" data-number="">
                    <td><?php echo $tracking_number['description']; ?></td>
                    <td class="clipboard">
                      <span><?php echo $tracking_number['number']; ?></span>
                    </td>
                  </tr>
                  <?php }?>
                </table>
              </td>
            </tr>
            <?php } ?>
            <?php if ($customer) { ?>
            <tr>
              <td><?php echo $text_customer; ?></td>
              <td class="clipboard"><a href="<?php echo $customer; ?>" target="_blank"><?php echo $firstname; ?> <?php echo $lastname; ?></a></td>
            </tr>
            <?php } else { ?>
            <tr>
              <td><?php echo $text_customer; ?></td>
              <td class="clipboard"><?php echo $firstname; ?> <?php echo $lastname; ?></td>
            </tr>
            <?php } ?>
            <tr>
              <td><?php echo $text_email; ?></td>
              <td class="clipboard"><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></td>
            </tr>
            <?php if ($customer_group) { ?>
            <tr>
              <td><?php echo $text_customer_group; ?></td>
              <td><?php echo $customer_group; ?></td>
            </tr>
            <?php } ?>
            <?php if ($fax) { ?>
            <tr>
              <td><?php echo $text_fax; ?></td>
              <td><?php echo $fax; ?></td>
            </tr>
            <?php } ?>
            <?php foreach ($account_custom_fields as $custom_field) { ?>
            <tr>
              <td><?php echo $custom_field['name']; ?>:</td>
              <td><?php echo $custom_field['value']; ?></td>
            </tr>
            <?php } ?>
            <?php if ($customer && $reward) { ?>
            <tr>
              <td><?php echo $text_reward; ?></td>
              <td><?php echo $reward; ?>&ensp;
                <?php if (!$reward_total) { ?>
                <button id="button-reward-add" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i> <?php echo $button_reward_add; ?></button>
                <?php } else { ?>
                <button id="button-reward-remove" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-danger btn-xs"><i class="fa fa-minus-circle"></i> <?php echo $button_reward_remove; ?></button>
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php if ($comment) { ?>
            <tr>
              <td><?php echo $text_comment; ?></td>
              <td><?php echo $comment; ?></td>
            </tr>
            <?php } ?>
            <?php if ($affiliate) { ?>
            <tr>
              <td><?php echo $text_affiliate; ?></td>
              <td><a href="<?php echo $affiliate; ?>"><?php echo $affiliate_firstname; ?> <?php echo $affiliate_lastname; ?></a></td>
            </tr>
            <tr>
              <td><?php echo $text_commission; ?></td>
              <td><?php echo $commission; ?>
                <?php if (!$commission_total) { ?>
                <button id="button-commission-add" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i> <?php echo $button_commission_add; ?></button>
                <?php } else { ?>
                <button id="button-commission-remove" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-danger btn-xs"><i class="fa fa-minus-circle"></i> <?php echo $button_commission_remove; ?></button>
                <?php } ?></td>
            </tr>
            <?php } ?>
          </table>
        </div><!-- /.panel -->
        <br />
        <!-- below Order Information -->
        <div id="history"></div>
        <br />
        <?php /* yym_custom: remove default add history for qosu ?>
        <div class="well well-sm">
          <fieldset>
            <legend><?php echo $text_history; ?></legend>
            <form class="form-horizontal">
              <div class="form-group">
                <label class="col-sm-3 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
                <div class="col-sm-9">
                  <select name="order_status_id" id="input-order-status" class="form-control">
                    <?php foreach ($order_statuses as $order_statuses) { ?>
                    <?php if ($order_statuses['order_status_id'] == $order_status_id) { ?>
                    <option value="<?php echo $order_statuses['order_status_id']; ?>" selected="selected"><?php echo $order_statuses['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_statuses['order_status_id']; ?>"><?php echo $order_statuses['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label" for="input-notify"><?php echo $entry_notify; ?></label>
                <div class="col-sm-9">
                  <input type="checkbox" name="notify" value="1" id="input-notify" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label" for="input-comment"><?php echo $entry_comment; ?></label>
                <div class="col-sm-9">
                  <textarea name="comment" rows="8" id="input-comment" class="form-control"></textarea>
                </div>
              </div>
            </form>
            <div class="text-right">
              <button id="button-history" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_history_add; ?></button>
            </div>
          </fieldset>
        </div><!-- /.well -->
        <?php */ ?>
        <?php if ($payment_action) { ?>
        <div class="tab-pane" id="tab-action"><?php echo $payment_action; ?></div>
        <?php } ?>
        <?php if ($frauds) { ?>
        <?php foreach ($frauds as $fraud) { ?>
        <div class="tab-pane" id="tab-<?php echo $fraud['code']; ?>">
          <?php echo $fraud['content']; ?>
        </div>
        <?php } ?>
        <?php } ?>
      </div><!-- /.col-sm-4 -->
      <div class="col-sm-8 col-sm-pull-4">
        <div class="row">
          <div class="col-sm-6">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-user"></i> Payment Information</h3>
              </div>
              <table class="table table-bordered table-condensed">
                <tr id="order-payment_firstname" data-table="order" data-id="payment_firstname" data-value="<?php echo $payment_firstname; ?>">
                  <td class="col-xs-4 editable">
                    <?php echo $text_firstname; ?>
                  </td>
                  <td class="clipboard"><span class="value"><?php echo $payment_firstname; ?></span></td>
                </tr>
                <tr id="order-payment_lastname" data-table="order" data-id="payment_lastname" data-value="<?php echo $payment_lastname; ?>">
                  <td class="editable">
                    <?php echo $text_lastname; ?>
                  </td>
                  <td class="clipboard"><span class="value"><?php echo $payment_lastname; ?></span></td>
                </tr>
                <?php if ($payment_company) { ?>
                <tr id="order-payment_company" data-table="order" data-id="payment_company" data-value="<?php echo $payment_company; ?>">
                  <td class="editable"><?php echo $text_company; ?></td>
                  <td class="clipboard"><span class="value"><?php echo $payment_company; ?></span></td>
                </tr>
                <?php } ?>
                <tr id="order-payment_address_1" data-table="order" data-id="payment_address_1" data-value="<?php echo $payment_address_1; ?>">
                  <td class="editable"><?php echo $text_address_1; ?></td>
                  <td class="clipboard"><span class="value"><?php echo $payment_address_1; ?></span></td>
                </tr>
                <?php if ($payment_address_2) { ?>
                <tr id="order-payment_address_2" data-table="order" data-id="payment_address_2" data-value="<?php echo $payment_address_2; ?>">
                  <td class="editable"><?php echo $text_address_2; ?></td>
                  <td class="clipboard"><span class="value"><?php echo $payment_address_2; ?></span></td>
                </tr>
                <?php } ?>
                <tr id="order-payment_city" data-table="order" data-id="payment_city" data-value="<?php echo $payment_city; ?>">
                  <td class="editable"><?php echo $text_city; ?></td>
                  <td class="clipboard"><span class="value"><?php echo $payment_city; ?></span></td>
                </tr>
                <tr id="order-payment_zone" data-table="order" data-id="payment_zone" data-value="<?php echo $payment_zone; ?>">
                  <td class="editable"><?php echo $text_zone; ?></td>
                  <td class="clipboard"><span class="value"><?php echo $payment_zone; ?></span></td>
                </tr>
                <tr id="order-payment_country" data-table="order" data-id="payment_country" data-value="<?php echo $payment_country; ?>">
                  <td class="editable"><?php echo $text_country; ?></td>
                  <td class="clipboard"><span class="value"><?php echo $payment_country; ?></span></td>
                </tr>
                <?php if ($payment_postcode) { ?>
                <tr id="order-payment_postcode" data-table="order" data-id="payment_postcode" data-value="<?php echo $payment_postcode; ?>">
                  <td class="editable"><?php echo $text_postcode; ?></td>
                  <td class="clipboard"><span class="value"><?php echo $payment_postcode; ?></span></td>
                </tr>
                <?php } ?>
                <?php foreach ($payment_custom_fields as $custom_field) { ?>
                <tr data-sort="<?php echo $custom_field['sort_order'] + 1; ?>">
                  <td><?php echo $custom_field['name']; ?>:</td>
                  <td><?php echo $custom_field['value']; ?></td>
                </tr>
                <?php } ?>
              </table>
            </div><!-- /.panel -->
          </div><!-- /.col-sm-6 -->
          <div class="col-sm-6">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-user"></i> Shipping Information</h3>
              </div>
              <?php if ($shipping_method) { ?>
              <table class="table table-bordered table-condensed">
                <tr id="order-shipping_firstname" data-table="order" data-id="shipping_firstname" data-value="<?php echo $shipping_firstname; ?>">
                  <td class="col-xs-4 editable"><?php echo $text_firstname; ?></td>
                  <td class="clipboard"><span class="value"><?php echo $shipping_firstname; ?></span></td>
                </tr>
                <tr id="order-shipping_lastname" data-table="order" data-id="shipping_lastname" data-value="<?php echo $shipping_lastname; ?>">
                  <td class="editable"><?php echo $text_lastname; ?></td>
                  <td class="clipboard"><span class="value"><?php echo $shipping_lastname; ?></span></td>
                </tr>
                <?php if ($shipping_company) { ?>
                <tr id="order-shipping_company" data-table="order" data-id="shipping_company" data-value="<?php echo $shipping_company; ?>">
                  <td class="editable"><?php echo $text_company; ?></td>
                  <td class="clipboard"><span class="value"><?php echo $shipping_company; ?></span></td>
                </tr>
                <?php } ?>
                <tr id="order-shipping_address_1" data-table="order" data-id="shipping_address_1" data-value="<?php echo $shipping_address_1; ?>">
                  <td class="editable"><?php echo $text_address_1; ?></td>
                  <td class="clipboard"><span class="value"><?php echo $shipping_address_1; ?></span></td>
                </tr>
                <?php if ($shipping_address_2) { ?>
                <tr id="order-shipping_address_2" data-table="order" data-id="shipping_address_2" data-value="<?php echo $shipping_address_2; ?>">
                  <td class="editable"><?php echo $text_address_2; ?></td>
                  <td class="clipboard"><span class="value"><?php echo $shipping_address_2; ?></span></td>
                </tr>
                <?php } ?>
                <tr id="order-shipping_city" data-table="order" data-id="shipping_city" data-value="<?php echo $shipping_city; ?>">
                  <td class="editable"><?php echo $text_city; ?></td>
                  <td class="clipboard"><span class="value"><?php echo $shipping_city; ?></span></td>
                </tr>
                <tr id="order-shipping_zone" data-table="order" data-id="shipping_zone" data-value="<?php echo $shipping_zone; ?>">
                  <td class="editable"><?php echo $text_zone; ?></td>
                  <td class="clipboard"><span class="value"><?php echo $shipping_zone; ?></span></td>
                </tr>
                <tr id="order-shipping_country" data-table="order" data-id="shipping_country" data-value="<?php echo $shipping_country; ?>">
                  <td class="editable"><?php echo $text_country; ?></td>
                  <td class="clipboard"><span class="value"><?php echo $shipping_country; ?></span></td>
                </tr>
                <?php if ($shipping_postcode) { ?>
                <tr id="order-shipping_postcode" data-table="order" data-id="shipping_postcode" data-value="<?php echo $shipping_postcode; ?>">
                  <td class="editable"><?php echo $text_postcode; ?></td>
                  <td class="clipboard"><span class="value"><?php echo $shipping_postcode; ?></span></td>
                </tr>
                <?php } ?>
                <?php foreach ($shipping_custom_fields as $custom_field) { ?>
                <tr data-sort="<?php echo $custom_field['sort_order'] + 1; ?>">
                  <td><?php echo $custom_field['name']; ?>:</td>
                  <td><?php echo $custom_field['value']; ?></td>
                </tr>
                <?php } ?>
              </table>
              <?php } ?>
            </div><!-- /.panel -->
          </div><!-- /.col-sm-6 -->
        </div>
        <div class="row">
          <div class="col-xs-12">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-shopping-cart"></i> <?php echo $heading_title; ?></h3>
              </div>
              <table class="table table-bordered" id="order-info">
                <thead>
                  <tr>
                    <td class="text-center"><?php echo $column_model; ?></td>
                    <td class="text-left"><?php echo $column_product; ?><a class="action-add btn btn-success btn-xs pull-right" data-toggle="modal" data-target="#product-add"><i class="fa fa-plus-circle"></i> Add Product</a></td>
                    <td class="text-center"><?php echo $column_price; ?></td>
                    <td class="text-center"><?php echo $column_quantity; ?></td>
                    <td class="text-center"><?php echo $column_total; ?></td>
                    <td style="width: 60px;"></td>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($products as $product) { ?>
                  <tr id="product-<?php echo $product['order_product_id']; ?>" data-table="product" data-id="<?php echo $product['order_product_id']; ?>" data-price="<?php echo $product['price_value']; ?>" data-quantity="<?php echo $product['quantity']; ?>" data-total="<?php echo $product['total_value']; ?>">
                    <td class="text-center model"><?php echo $product['model']; ?></td>
                    <td class="text-left name clipboard"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                      <?php foreach ($product['option'] as $option) { ?>
                      <br />
                      <?php if ($option['type'] != 'file') { ?>
                      - <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                      <?php } else { ?>
                      - <small><?php echo $option['name']; ?>: <a href="<?php echo $option['href']; ?>"><?php echo $option['value']; ?></a></small>
                      <?php } ?>
                      <?php } ?></td>
                    <td class="text-center price"><?php echo $product['price']; ?></td>
                    <td class="text-center quantity"><?php echo $product['quantity']; ?></td>
                    <td class="text-center total"><?php echo $product['total']; ?></td>
                    <td class="text-right action">
                      <div class="btn-group btn-group-flex">
                        <a role="button" data-toggle="popover" class="action-update btn btn-info btn-xs"><i class="fa fa-pencil"></i></a>
                        <a role="button" data-toggle="popover" class="action-delete btn btn-danger btn-xs"><i class="fa fa-times"></i></a>
                      </div>
                    </td>
                  </tr>
                  <?php } ?>
                  <?php foreach ($vouchers as $voucher) { ?>
                  <tr>
                    <td class="text-center"></td>
                    <td class="text-left"><a href="<?php echo $voucher['href']; ?>"><?php echo $voucher['description']; ?></a></td>
                    <td class="text-center"><?php echo $voucher['amount']; ?></td>
                    <td class="text-center">1</td>
                    <td class="text-center"><?php echo $voucher['amount']; ?></td>
                    <td></td>
                  </tr>
                  <?php } ?>
                  <?php foreach ($totals as $total) { ?>
                  <tr id="total-<?php echo $total['order_total_id']; ?>" data-table="total" data-id="<?php echo $total['order_total_id']; ?>" data-title="<?php echo $total['title']; ?>" data-value="<?php echo $total['value']; ?>">
                    <td colspan="4" class="text-right title"><?php echo $total['title']; ?></td>
                    <td class="text-center value"><?php echo $total['text']; ?></td>
                    <td class="text-right action">
                      <?php if ($total['code'] != 'sub_total' && $total['code'] != 'total') { ?>
                      <a role="button" data-toggle="popover" class="action-update btn btn-info btn-xs"><i class="fa fa-pencil"></i></a>
                      <?php } ?>
                    </td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div><!-- /.panel -->
          </div><!-- /.col-xs-12 -->
        </div><!-- /.row -->
      </div><!-- /.col-sm-8 -->
    </div><!-- /.row -->
  </div><!-- /.container-flui -->

  <!-- yym_custom: add product modal -->
  <div class="modal fade" id="product-add" tabindex="-1" role="dialog" aria-labelledby="product-add-label">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">x</button>
          <h4 class="modal-title" id="product-add-label">Add Product</h4>
        </div>
        <div class="modal-body">
          <div class="container-fluid">
            <form class="form-horizontal">
              <div class="form-group">
                <label for="name" class="control-label col-sm-3">Product</label>
                <div class="col-sm-9"><input id="name" type="text" class="form-control" placeholder="(Autocomplete)"></div>
              </div>
              <div class="form-group">
                <label for="quantity" class="control-label col-sm-3">Quantity</label>
                <div class="col-sm-2"><input name="quantity" id="quantity" type="number" class="form-control" value="1"></div>
              </div>
              <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
              <input type="hidden" name="product_id" id="product_id" value="">
            </form>
            <div class="action-alerts"></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="add-cancel btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="button" class="add-submit btn btn-success"><i class="fa fa-plus-circle"></i> Add Product</button>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript"><!--
$(document).delegate('#button-invoice', 'click', function() {
	$.ajax({
		url: 'index.php?route=sale/order/createinvoiceno&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		dataType: 'json',
		beforeSend: function() {
			$('#button-invoice').button('loading');
		},
		complete: function() {
			$('#button-invoice').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#tab-order').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}

			if (json['invoice_no']) {
				$('#button-invoice').replaceWith(json['invoice_no']);
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$(document).delegate('#button-reward-add', 'click', function() {
	$.ajax({
		url: 'index.php?route=sale/order/addreward&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			$('#button-reward-add').button('loading');
		},
		complete: function() {
			$('#button-reward-add').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}

			if (json['success']) {
                $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

				$('#button-reward-add').replaceWith('<button id="button-reward-remove" class="btn btn-danger btn-xs"><i class="fa fa-minus-circle"></i> <?php echo $button_reward_remove; ?></button>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$(document).delegate('#button-reward-remove', 'click', function() {
	$.ajax({
		url: 'index.php?route=sale/order/removereward&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			$('#button-reward-remove').button('loading');
		},
		complete: function() {
			$('#button-reward-remove').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}

			if (json['success']) {
                $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

				$('#button-reward-remove').replaceWith('<button id="button-reward-add" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i> <?php echo $button_reward_add; ?></button>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$(document).delegate('#button-commission-add', 'click', function() {
	$.ajax({
		url: 'index.php?route=sale/order/addcommission&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			$('#button-commission-add').button('loading');
		},
		complete: function() {
			$('#button-commission-add').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}

			if (json['success']) {
                $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

				$('#button-commission-add').replaceWith('<button id="button-commission-remove" class="btn btn-danger btn-xs"><i class="fa fa-minus-circle"></i> <?php echo $button_commission_remove; ?></button>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$(document).delegate('#button-commission-remove', 'click', function() {
	$.ajax({
		url: 'index.php?route=sale/order/removecommission&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			$('#button-commission-remove').button('loading');

		},
		complete: function() {
			$('#button-commission-remove').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}

			if (json['success']) {
                $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

				$('#button-commission-remove').replaceWith('<button id="button-commission-add" class="btn btn-success btn-xs"><i class="fa fa-minus-circle"></i> <?php echo $button_commission_add; ?></button>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#history').delegate('.pagination a', 'click', function(e) {
	e.preventDefault();

	$('#history').load(this.href);
});

$('#history').load('index.php?route=sale/order/history&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');

$('#button-history').on('click', function() {
  if(typeof verifyStatusChange == 'function'){
    if(verifyStatusChange() == false){
      return false;
    }else{
      addOrderInfo();
    }
  }else{
    addOrderInfo();
  }

	$.ajax({
		url: 'index.php?route=sale/order/api&token=<?php echo $token; ?>&api=api/order/history&order_id=<?php echo $order_id; ?>',
		type: 'post',
		dataType: 'json',
		data: 'order_status_id=' + encodeURIComponent($('select[name=\'order_status_id\']').val()) + '&notify=' + ($('input[name=\'notify\']').prop('checked') ? 1 : 0) + '&append=' + ($('input[name=\'append\']').prop('checked') ? 1 : 0) + '&comment=' + encodeURIComponent($('textarea[name=\'comment\']').val()),
		beforeSend: function() {
			$('#button-history').button('loading');
		},
		complete: function() {
			$('#button-history').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#history').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}

			if (json['success']) {
				$('#history').load('index.php?route=sale/order/history&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');

				$('#history').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				$('textarea[name=\'comment\']').val('');

				$('#order-status').html($('select[name=\'order_status_id\'] option:selected').text());
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

function changeStatus(){
  var status_id = $('select[name="order_status_id"]').val();

  $('#openbay-info').remove();

  $.ajax({
    url: 'index.php?route=extension/openbay/getorderinfo&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>&status_id='+status_id,
    dataType: 'html',
    success: function(html) {
      $('#history').after(html);
    }
  });
}

function addOrderInfo(){
  var status_id = $('select[name="order_status_id"]').val();

  $.ajax({
    url: 'index.php?route=extension/openbay/addorderinfo&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>&status_id='+status_id,
    type: 'post',
    dataType: 'html',
    data: $(".openbay-data").serialize()
  });
}

$(document).ready(function() {
  changeStatus();
});

$('select[name="order_status_id"]').change(function(){ changeStatus(); });
//--></script>
<script type="text/javascript"><!--
  // yym_custom: pass in token and order_id variables to order_ext
  var token = '<?php echo $token; ?>';
  var order_id = <?php echo $order_id; ?>;
//--></script>
<script src="view/javascript/order_ext.js"></script>
<script type="text/javascript"><!--
// Sort the custom fields
$('#tab-payment tr[data-sort]').detach().each(function() {
	if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#tab-payment tr').length) {
		$('#tab-payment tr').eq($(this).attr('data-sort')).before(this);
	}

	if ($(this).attr('data-sort') > $('#tab-payment tr').length) {
		$('#tab-payment tr:last').after(this);
	}

	if ($(this).attr('data-sort') < -$('#tab-payment tr').length) {
		$('#tab-payment tr:first').before(this);
	}
});

$('#tab-shipping tr[data-sort]').detach().each(function() {
	if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#tab-shipping tr').length) {
		$('#tab-shipping tr').eq($(this).attr('data-sort')).before(this);
	}

	if ($(this).attr('data-sort') > $('#tab-shipping tr').length) {
		$('#tab-shipping tr:last').after(this);
	}

	if ($(this).attr('data-sort') < -$('#tab-shipping tr').length) {
		$('#tab-shipping tr:first').before(this);
	}
});
//--></script>
<script type="text/javascript"><!--
// global var definition for later use
var current_order_ids = [];

// highlight method
jQuery.fn.qosu_highlight = function () {
  $(this).each(function () {
    var el = $(this);
    $("<div/>")
    .width(el.outerWidth())
    .height(el.outerHeight())
    .addClass('bg-info')
    .css({
      "position": "absolute",
      "left": el.offset().left,
      "top": el.offset().top,
      "opacity": "0.8",
      "z-index": "99999"
    }).appendTo('body').fadeOut(2000).queue(function () { $(this).remove(); });
  });
}

$(document).ready(function() {
  //draggable modal
  var qosu_modal = {isMouseDown: false, mouseOffset: {} };
  $('body').on('mousedown', '.modal-header', function(event) {
    qosu_modal.isMouseDown = true;
    var dialogOffset = $('#quick-status-dialog .modal-dialog').offset();
    qosu_modal.mouseOffset = {
      top: event.clientY - dialogOffset.top,
      left: event.clientX - dialogOffset.left
    };
    $('body').on('mousemove', '#quick-status-dialog', function(event) {
      if (!qosu_modal.isMouseDown) { return; }
      $('#quick-status-dialog .modal-dialog').offset({
        top: event.clientY - qosu_modal.mouseOffset.top,
        left: event.clientX - qosu_modal.mouseOffset.left
      });
    });
  });
  $('body').on('mouseup', '#quick-status-dialog', function(event) {
    qosu_modal.isMouseDown = false;
     $('body').off('mousemove', '#quick-status-dialog');
  });
  // end - draggable modal

  $('body').on('click', '#qosuSubmit', function() {
    quickStatusUpdate();
  });

  $('body').on('click', '#qosuSubmitClose', function() {
    quickStatusUpdate(1);
  });

  $('body').on('click', '#history .pagination a', function() {
    $('#history').load(this.href);
    return false;
  });

  $('body').on('hidden.bs.modal', '#quick-status-dialog', function () {
    current_order_ids = [];
  });

  // barcode handler
  <?php if($qosu_barcode) { ?>
  var barcode_id = '';

  $('#qosuBarcodeButton').on('click', function(event) {
    $(this).toggleClass('enabled');
    if ($(this).hasClass('enabled')) {
      $('body').on('keypress', function(e) {
        if (jQuery.inArray( e.which-48, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9] )  !== -1) {
          barcode_id += e.which-48;
        } else if (e.which == 13) {
          if(barcode_id != '') {
            e.preventDefault();
            current_order_ids.push(barcode_id);
            barcode_id = '';
            $('body').trigger('qosuPopup');
          }
        }
      });
    } else {
      $('body').off('keypress');
    }
  });
  <?php if($qosu_barcode_enabled) { ?>
  $('#qosuBarcodeButton').trigger('click');
  <?php }} ?>
  // end - barcode handler

  $('body').on('click', '.quick-update-multiple, .qosu-cell', function(e) {
    current_order_ids = [];

    if ($(this).attr('order_id')) {
      current_order_ids.push($(this).attr('order_id'));
    } else if ($("input[name='selected[]']:checked").length == 1) {
      current_order_ids.push($("input[name='selected[]']:checked").first().val());
    } else if (!$("input[name='selected[]']:checked").length && <?php echo (int) (substr(VERSION, 0, 1) != 2); ?>) {
      return alert('<?php echo $text_qosu_select_checkbox; ?>');
    } else {
      $("input[name='selected[]']:checked").each(function() {
        current_order_ids.push($(this).val());
      });
    }

    $('body').trigger('qosuPopup');
  });

  $('body').on('qosuPopup', function(e) {
    if (!current_order_ids) alert('No order selected');

    $('#quick-status-dialog .modalContent').html('<div style="text-align:center"><img src="<?php echo defined('_JEXEC') ? 'admin/' : ''; ?>view/quick_status_updater/img/loader.gif" alt=""/></div>');
    $('#quick-status-dialog').modal({});

    if (current_order_ids.length == 1) {
      $('#quick-status-dialog .modalContent').load('index.php?route=module/quick_status_updater/multiple_form&token=<?php echo $token; ?>', {'selected': current_order_ids}, function() {
        $('#history').load('index.php?route=sale/order/history&reverse&token=<?php echo $token; ?>&order_id='+current_order_ids[0]);
        orderStatusChange();
      });
    } else {
      $('#quick-status-dialog .modalContent').load('index.php?route=module/quick_status_updater/multiple_form&token=<?php echo $token; ?>', {'selected': current_order_ids}, function() {
        orderStatusChange();
      });
    }
  });
});

// submit form
function quickStatusUpdate(close) {
  close || (close = 0);
  if(typeof verifyStatusChange == 'function') {
    if(verifyStatusChange() == false) {
      return false;
    } else {
      addOrderInfo();
    }
  } else {
    addOrderInfo();
  }

  $.ajax({
    url: 'index.php?route=module/quick_status_updater/update_status&token=<?php echo $token; ?>',
    type: 'post',
    dataType: 'json',
    data: $('form#qosu_form').serialize(),
    beforeSend: function() {
      $('.success, .warning').remove();
      $('#button-history').attr('disabled', true);
      $('#history').html('<div style="text-align:center"><img src="<?php echo defined('_JEXEC') ? 'admin/' : ''; ?>view/quick_status_updater/img/loader.gif" alt=""/></div>');
    },
    complete: function() {
      $('#button-history').attr('disabled', false);
      $('.attention').remove();
    },
    error: function(req, error) {
      $('#history').html(error + '<br/>' + req.responseText);
    },
    success: function(json) {
      if (json.error) {
        $('#history').html(json.error);
        //alert(json.error);
      } else {
        //$('#history').html(html);
        //$('textarea[name=\'comment\']').val('');

        var order_id;
        $.each(json.order_id, function(i, v) {
          if(json.bg_mode == 'row') {
            $('td.qosu-cell[order_id="'+v+'"]').html($('select[name=\'order_status_id\'] option:selected').text());
            $('td.qosu-cell[order_id="'+v+'"]').parent().css('background', json.color);
            $('td.qosu-cell[order_id="'+v+'"]').parent().qosu_highlight(1000);
          } else if(json.bg_mode == 'cell') {
            $('td.qosu-cell[order_id="'+v+'"]').html($('select[name=\'order_status_id\'] option:selected').text());
            $('td.qosu-cell[order_id="'+v+'"]').css('background', json.color);
            $('td.qosu-cell[order_id="'+v+'"]').qosu_highlight(1000);
          } else {
            var btnClass = {
              'Pending': 'btn-default',
              'Approved': 'btn-default',
              'Processing': 'btn-warning',
              'On Hold': 'btn-warning',
              'Packing': 'btn-success',
              'Shipped': 'btn-primary',
              'Cancelled': 'btn-info',
              'Refunded': 'btn-info',
              'Void': 'btn-danger'
            };
            $('td.qosu-cell[order_id="'+v+'"]').html('<a class="btn btn-xs ' + btnClass[$('select[name=\'order_status_id\'] option:selected').text()] + '"><i class="fa fa-arrow-circle-right"></i> ' + $('select[name=\'order_status_id\'] option:selected').text() + ' </a>');
            $('td.qosu-cell[order_id="'+v+'"]').qosu_highlight(1000);
          }
          $('td.qosu-cell[order_id="'+v+'"]').qosu_highlight(1000);
          order_id = v;
        });

        if(close) {
          current_order_ids = [];
          $('#quick-status-dialog').modal('hide');
        } else {
          if (json.order_id.length > 1) {
            $('#history').html('');
          } else {
            $('#history').load('index.php?route=sale/order/history&reverse&token=<?php echo $token; ?>&order_id='+order_id);
          }
        }
      }
    }
  });
}

function orderStatusChange() {
  var status_id = $('select[name="order_status_id"]').val();

  // quick status updater
  $.ajax({
    url: 'index.php?route=module/quick_status_updater/getDefaultComment&token=<?php echo $token; ?>&status_id='+status_id,
    type: 'post',
    dataType: 'json',
    beforeSend: function() {},
    success: function(json) {
      $.each(json, function(i, v) {
        $('#quick-status-dialog textarea[name="comment['+ i +']"]').html(v);
      });
    },
    failure: function() {},
    error: function() {}
  });

  // openbay support, only if 1 order
  if (current_order_ids.length == 1) {
    $('#openbay-info').remove();

    $.ajax({
      url: 'index.php?route=extension/openbay/getorderinfo&token=<?php echo $token; ?>&order_id='+current_order_ids[0]+'&status_id='+status_id,
      dataType: 'html',
      success: function(html) {
        $('#history').after(html);
      }
    });
  }
}

function addOrderInfo() {
  var status_id = $('select[name="order_status_id"]').val();
  var old_status_id = $('#old_order_status_id').val();

  $('#old_order_status_id').val(status_id);

  // openbay handling
  if (current_order_ids.length == 1) {
    $.ajax({
      url: 'index.php?route=extension/openbay/addorderinfo&token=<?php echo $token; ?>&order_id='+$('#quick-status-dialog input[name=\'order_id\']').val()+'&status_id='+status_id,
      type: 'post',
      dataType: 'html',
      data: $(".openbayData").serialize()
    });
  }
}

$('body').on('change', 'select[name="order_status_id"]', function() {orderStatusChange();});
//--></script>
<style type="text/css">
<?php if ($qosu_bg_mode == 'row') { ?>
.qosuTable, .latest{color:#333;}
.list tbody td{background-color:transparent;}
<?php } ?>
.ui-dialog .ui-dialog-buttonpane{position: absolute; top: 30px; right:10px; background:transparent; border:0;}
.ui-dialog .ui-dialog-titlebar{margin-bottom: 5px;}
#qosuBarcodeButton{background:#ddd; border:1px solid #ccc; color:#333; outline:0;}
a#qosuBarcodeButton{margin-left:30px; margin-top:5px;}
button#qosuBarcodeButton{margin-top:-6px; padding: 6px 10px;}
button#qosuBarcodeButton i{font-size:14px;}
#qosuBarcodeButton.enabled, #qosuBarcodeButton:hover{background:#9FB9C4; border:1px solid #7CA0AF;}
#qosuBarcodeButton.enabled:hover{background:#9FB9C4; border:1px solid #7CA0AF;}
#qosuBarcodeButton:hover{background:#ccc; border:1px solid #bbb;}
#qosuBarcodeButton:active, #qosuBarcodeButton.enabled:active{background:#87B4C6; border:1px solid #77A7BA;}
</style></div>
<?php echo $footer; ?>
