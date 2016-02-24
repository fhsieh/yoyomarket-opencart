<?php
// Heading
$_['heading_title']             = '<span class="label label-success">Quick Order Status Updater</span>';
//$_['heading_title']           = '<img src="'. (defined('_JEXEC') ? 'admin/' : '') . 'view/quick_status_updater/img/update.png" style="vertical-align:top;padding-right:4px"/>Quick Order Status Updater';

$_['module_title']              = 'Quick Order Status <span>Updater</span>';

// Text
$_['text_module']               = 'Modules';
$_['text_success']              = 'Success: You have modified module Quick Order Status Updater!';

// Tabs
$_['text_tab_0']                = 'Main options';
$_['entry_bg_mode']             = 'Color display mode:<span class="help">In order list, choose how to display the status colors</span>';
$_['entry_bg_mode_text']        = 'Text only';
$_['entry_bg_mode_cell']        = 'Cell';
$_['entry_bg_mode_row']         = 'Full row';
$_['entry_notify']              = 'Notify checked:<span class="help">Choose if notify checkbox is checked or not</span>';
$_['entry_barcode']             = 'Barcode handler:<span class="help">Automatically pop up the updater form on barcod scanning, also works by manually entering order number and typing return on keyboard</span>';
$_['entry_barcode_enabled']     = 'Barcode status:<span class="help">Is the barcode handler enabled or disabled by default ?</span>';
$_['entry_extra_info']          = 'Extra info:<span class="help">Add some additional information about the order in update popup</span>';
$_['entry_extra_info_help']     = 'Available tags (full list in docs):<br/>';

$_['text_tab_1']                = 'Tracking System';
$_['entry_shipping_title']      = 'Title:';
$_['entry_shipping_url']        = 'Url:';
$_['tab_add_shipping']          = 'Add shipping';
$_['text_info_tracking']        = '';

$_['text_tab_2']                = 'Order Status';
$_['entry_message']             = 'Default message:<br/><br/><span class="help">This message will be automatically filled when you select the corresponding status<br/>Use the tags to automatically replace with specific values</span>';
$_['entry_next_status']         = 'Next status:<span class="help">Auto select this status when opening quick updater</span>';
$_['entry_status_color']        = 'Color:<span class="help"></span>';

$_['text_tab_3']                = 'Custom inputs';
$_['tab_add_input']             = 'Add input';
$_['entry_input_title']         = 'Title:';
$_['entry_input_tag']           = 'Tag:';

$_['text_tab_about']            = 'About';

// Buttons
$_['button_save']               = 'Save';
$_['button_save_close']         = 'Save & Close';
$_['button_cancel']             = 'Cancel';

// order list page
$_['text_qosu_unknown']         = 'Unknown Order IDs:';
$_['text_qosu_add_history']     = 'Update status';
$_['text_qosu_order_id']        = 'Order';
$_['text_qosu_dialog_title']    = 'Update Order Status';
$_['text_qosu_tracking_number'] = 'Tracking Number';
$_['text_qosu_add_tracking']    = 'Add Tracking Number'; // yym_custom
$_['text_qosu_select_checkbox'] = 'Please select at least one checkbox';
$_['text_qosu_tracking_note']   = 'Note'; // yym_custom
$_['text_qosu_order_status']    = 'Order status:';
$_['text_qosu_notify']          = 'Notify';
$_['text_qosu_comment']         = 'Comment:';
$_['text_qosu_barcode']         = 'Activate/desactivate barcode handler';

// Error
$_['error_permission']          = 'Warning: You do not have permission to modify this module!';

// Info
$_['info_title_default']        = 'Help';
$_['info_msg_default']          = 'Help section for this topic not found';

$_['info_title_color_mode']     = 'Color display mode';
$_['info_msg_color_mode']       = '<div class="infoblock indent-left"><div class="context">1</div><p><b>Text only:</b> Only text gets colored</p><p><img class="img-thumbnail" src="view/quick_status_updater/info/bgcolor_text.png" alt="" /></p></div>
<div class="infoblock indent-left"><div class="context">2</div><p><b>Cell:</b> The cell background of the status is colored</p><p><img class="img-thumbnail" src="view/quick_status_updater/info/bgcolor_cell.png" alt="" /></p></div>
<div class="infoblock indent-left"><div class="context">3</div><p><b>Full row:</b> The whole row is colored</p><p><img class="img-thumbnail" src="view/quick_status_updater/info/bgcolor_row.png" alt="" /></p></div>
';

$_['info_title_extra_info']     = 'Extra info';
$_['info_msg_extra_info']       = '<p>Add some extra information about order in the quick update popup. This information is for you only, to have a quick view on user name or some other fields.<br/>For example if you set "Customer: <b class="tag">{customer}</b>" it will appears like this in the popup:</p><p class="text-center"><img class="img-thumbnail" src="view/quick_status_updater/info/extra_info.png" alt="" /></p><hr /><h4 class="text-center">Available tags</h4>';

$_['info_title_tracking_url']   = 'Tracking system';
$_['info_msg_tracking_url']     = '<p>Insert here the tracking url of your shipping carrier, ir works with all of them. Then you will be able to use the tag system to automatically insert the url into the update message.</p>
<h5>2 ways to work with tracking url</h5>
<div class="infoblock indent-left"><div class="context">1</div><p>Insert the url alone, expecting that tracking number will be at the end of the url.</p><p><img class="img-thumbnail" src="view/quick_status_updater/info/tracking1.png" alt="" /></p><p>Then insert in update message the tags <b class="tag">{tracking_url}{tracking_no}</b> to get it replaced with full formated url.</p></div>
<div class="infoblock indent-left"><div class="context">2</div><p>Insert the url directly with the tag <b class="tag">{tracking_no}</b> inside, the tag can be at any place in the url.</p><p><img class="img-thumbnail" src="view/quick_status_updater/info/tracking2.png" alt="" /></p><p>Then insert in update message only the tag <b class="tag">{tracking_url}</b> to get it replaced with full formated url, <b class="tag">{tracking_no}</b> is no more necessary since it is already included into <b class="tag">{tracking_url}</b>.</p></div>
';

$_['info_title_tags']           = 'Available tags';
$_['info_msg_tags_spec']        = '
<div class="infotags">
<h5>Specific tags</h5>
<p>
<span><b class="tag">{tracking_url}</b> Tracking url</span>
<span><b class="tag">{tracking_no}</b> Tracking number</span>
<span><b class="tag">{tracking_title}</b> Tracking method title</span>
<span><b class="tag">{if_tracking}...{/if_tracking}</b> Display if tracking</span>
</p>
</div>';
$_['info_msg_tags']             = '
<div class="infotags">
<h5>Customer</h5>
<p>
<span><b class="tag">{customer_id}</b> Customer ID</span>
<span><b class="tag">{customer}</b> Full name</span>
<span><b class="tag">{firstname}</b> First name</span>
<span><b class="tag">{lastname}</b> Last name</span>
<span><b class="tag">{telephone}</b> Phone number</span>
<span><b class="tag">{email}</b> Email address</span>
</p>
<h5>Order</h5>
<p>
<span><b class="tag">{order_id}</b> Order ID</span>
<span><b class="tag">{invoice_no}</b> Invoice number</span>
<span><b class="tag">{invoice_prefix}</b> Invoice prefix</span>
<span><b class="tag">{comment}</b> Comment</span>
<span><b class="tag">{total}</b> Total amount</span>
<span><b class="tag">{reward}</b> Reward</span>
<span><b class="tag">{commission}</b> Commission</span>
<span><b class="tag">{language_code}</b> Language code</span>
<span><b class="tag">{currency_code}</b> Currency code</span>
<span><b class="tag">{currency_value}</b> Currency value</span>
<span><b class="tag">{amazon_order_id}</b> Amazon order ID</span>
</p>
<h5>Payment</h5>
<p>
<span><b class="tag">{payment_firstname}</b> First name</span>
<span><b class="tag">{payment_lastname}</b> Last name</span>
<span><b class="tag">{payment_company}</b> Company</span>
<span><b class="tag">{payment_address_1}</b> Address 1</span>
<span><b class="tag">{payment_address_2}</b> Address 2</span>
<span><b class="tag">{payment_postcode}</b> Postcode</span>
<span><b class="tag">{payment_city}</b> City</span>
<span><b class="tag">{payment_zone}</b> Zone</span>
<span><b class="tag">{payment_country}</b> Country</span>
<span><b class="tag">{payment_method}</b> Method</span>
</p>
<h5>Shipping</h5>
<p>
<span><b class="tag">{shipping_firstname}</b> First name</span>
<span><b class="tag">{shipping_lastname}</b> Last name</span>
<span><b class="tag">{shipping_company}</b> Company</span>
<span><b class="tag">{shipping_address_1}</b> Address 1</span>
<span><b class="tag">{shipping_address_2}</b> Address 2</span>
<span><b class="tag">{shipping_postcode}</b> Postcode</span>
<span><b class="tag">{shipping_city}</b> City</span>
<span><b class="tag">{shipping_zone}</b> Zone</span>
<span><b class="tag">{shipping_country}</b> Country</span>
<span><b class="tag">{shipping_method}</b> Method</span>
</p>
<h5>Misc</h5>
<p>
<span><b class="tag">{store_name}</b> Store name</span>
<span><b class="tag">{store_url}</b> Store URL</span>
<span><b class="tag">{store_email}</b> Store email</span>
<span><b class="tag">{store_phone}</b> Store phone</span>
<span><b class="tag">{ip}</b> User IP</span>
<span><b class="tag">{user_agent}</b> User agent</span>
</p>
</div>';
?>