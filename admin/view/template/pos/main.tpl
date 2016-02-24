<?php echo $header; ?><?php echo $column_left; ?>
<link rel="stylesheet" type="text/css" href="view/stylesheet/pos/pos.css" />
<link rel="stylesheet" type="text/css" href="view/stylesheet/pos/jquery-ui.css" />
<div id="oc_content" class="container-fluid" style="padding: 0;">
	<div id="divWrap" class="wrapper">
		<div class="header">
			<div class="logo"><img src="view/image/pos/logo.png"  alt=""></div>
			<div class="sub-title">
				<h2><?php echo $user; ?> @ T001</h2>
				<span class="pos-date">
					<span id="header_week">Wed</span>,
					<span id="header_date">01</span>
					<span id="header_month">May</span>
					<span id="header_year">2013</span>&nbsp;
					<span id="header_hour">12</span>:
					<span id="header_minute">05</span>
					<span id="header_apm">pm</span>
				</span>
			</div>
			<div class="select_wrap">
				<select name="work_mode_dropdown" id="work_mode_dropdown" class="select_type">
				  <option value="sale" selected="selected"><?php echo $text_workmode_sale; ?></option>
				</select>
			</div>
			<div class="message success">
				<div class="icon"></div>
				<p></p>
			</div>
			<a class="menu-toggle"></a>
		</div>
		<div class="main-container">
			<!--left-container-start-->
			<div class="left-container">
				<div class="left-nav">
					<ul>
						<li><a href="#" class="hide-show-nav"><span class="bg"><span class="icon nav-toggle"></span></span></a></li>
					</ul>
				</div>
				<div class="left-nav">
					<!-- <h2><?php echo $text_order_options; ?></h2> -->
					<ul>
						<li><a onclick="getOrderList();"><span class="bg"><span class="icon order-id"></span></span><span class="txt" id="order_id_text"><?php echo $order_id_text; ?></span></a></li>
						<li><a onclick="changeOrderStatus();"><span class="bg"><span class="icon order-status"></span></span><span class="txt" id="order_status_name"><?php echo !empty($order_status_name) ? $order_status_name : ''; ?></span></a></li>
						<li><a onclick="changeOrderCustomer();"><span class="bg"><span class="icon customer"></span></span><span class="txt" id="customer"><?php echo $customer; ?></span></a></li>
						<li><a onclick="changeOrderComment();"><span class="bg"><span class="icon comment"></span></span><span class="txt" id="order_comment"><?php echo $column_payment_note; ?></span></a></li>
					</ul>
				</div>
			</div>

			<!--right-container-start-->
			<div class="right-container">
				<!--product-container-start-->
				<div class="prdct-container">
					<div class="prdct-header">
						<!-- Use below heading for return for order -->
						<div class="prdct-header-top" id="add_product_control">
							<div class="input-box">
								<input type="search" autocomplete="off" id="search" name="filter_product"  placeholder="<?php echo $text_search_placeholder; ?>">
							</div>
						</div>
						<div class="breadcrumb-bg" id="browse_category_div">
							<input type="hidden" name="current_category_id" value="0" />
							<input type="hidden" name="current_category_name" value="<?php echo $text_top_category_id; ?>" />
							<input type="hidden" name="current_category_image" value="" />
							<ul id="browse_category">
								<li><a class="home-icon last" onclick="showCategoryItems('<?php echo $text_top_category_id; ?>')"></a></li>
							</ul>
						</div>
					</div>
					<div class="product-box-outer" id="browse_list">
						<input type="hidden" name="current_product_id" value="0" />
						<input type="hidden" name="current_product_name" value="" />
						<input type="hidden" name="current_product_hasOption" value="" />
						<input type="hidden" name="current_product_price" value="" />
						<input type="hidden" name="current_product_tax" value="" />
						<input type="hidden" name="current_product_points" value="" />
						<input type="hidden" name="current_product_image" value="" />
						<?php if (!empty($browse_items)) { foreach ($browse_items as $browse_item) { ?>
							<?php if ($browse_item['type'] == 'C') { ?>
								<a onclick="showCategoryItems('<?php echo $browse_item['category_id']; ?>')" class="product-box product-folder">
									<span class="product-box-img">
										<span class="product-box-frame-wrap">
											<span class="product-box-frame">
												<img src="<?php echo $browse_item['image']; ?>"  alt="">
											</span>
											<span class="product-count"><?php echo $browse_item['total_items']; ?></span>
										</span>
									</span>
									<span class="product-box-prod">
										<span class="product-box-prod-title"><?php echo $browse_item['name']; ?></span>
									</span>
								</a>
							<?php } else { ?>
								<a onclick="selectProduct(<?php echo $browse_item['product_id']; ?>)" class="product-box product-item">
									<span class="product-box-img">
										<span class="product-box-frame-wrap">
											<span class="product-box-frame">
												<img src="<?php echo $browse_item['image']; ?>"  alt="">
											</span>
											<span class="product-count"><?php echo $browse_item['total_items']; ?></span>
										</span>
									</span>
									<span class="product-box-prod">
										<span class="product-box-prod-title"><?php echo $browse_item['name']; ?></span>
										<span class="product-box-prod-price"><?php echo $browse_item['price_text']; ?></span>
									</span>
								</a>
							<?php } ?>
						<?php } } ?>
					</div>
				</div>
				<div class="prdct-container cart-container">
					<div class="cart-title-bg">
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td class="one" align="center" valign="middle"><span class="name"><?php echo $text_items_in_cart; ?></span> <span class="product-count" id="items_in_cart"><?php echo $items_in_cart; ?></span></td>
								<td class="two two-sub" align="left" valign="middle">&nbsp;</td>
								<td class="three" align="center" valign="middle"><?php echo $text_subtotal; ?></td>
								<td class="four" align="center" valign="middle">&nbsp;</td>
							</tr>
						</table>
					</div>
					<div class="cart-outer-scroller">
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<tbody id="product">
							</tobdy>
						</table>
					</div>
					<div class="cart-footer">
						<div class="pay-area-bg">
							<div class="cart-totals">
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tr class="grand-total">
										<td align="right" valign="middle"><?php echo $text_grandtotal; ?> :</td>
										<?php $total_text = ''; if (!empty($totals)) { foreach ($totals as $total) { if ($total['code'] == 'total') { $total_text = $total['text']; break; } } } ?>
										<td align="left" valign="middle" id="payment_total"><span class="highlight" onclick="showTotals();"><?php echo $total_text; ?></span></td>
									</tr>
									<tr>
										<td align="right" valign="middle"><?php echo $text_amount_due; ?> :</td>
										<td align="left" valign="middle"><span class="amount" id="payment_due_amount"><?php echo $payment_due_amount_text; ?></span></td>
									</tr>
									<tr>
										<td align="right" valign="middle"><?php echo $text_change; ?> :</td>
										<td align="left" valign="middle" id="payment_change"><span class="amount"><?php echo $payment_change_text; ?></span></td>
									</tr>
								</table>
							</div>
							<a class="pay-btn" onclick="makePayment();"><span><?php echo $text_pay; ?></span></a>
						</div>
						<div class="buttons-wrap">
							<a class="btn void" onclick="saveOrderStatus('<?php echo $void_status_id; ?>');"><span class="icon"></span><?php echo $button_void_order; ?></a>
							<a class="btn park" onclick="saveOrderStatus('<?php echo $parking_status_id; ?>');"><span class="icon"></span><?php echo $button_park_order; ?></a>
							<a class="btn complete" onclick="completeOrder();"><span class="icon"></span><?php echo $button_complete_order; ?></a>
							<a class="btn print" onclick="printReceipt();"><span class="icon"></span><?php echo $text_print; ?></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- print message iframe -->
	<iframe id="print_iframe" src="about:blank" style="display:none; width: 0; height: 0;"></iframe>

	<div style="display: none;"> <!-- start the hidden wrapper div to hide all dialogs when load -->

		<div id="order_list_dialog" class="fbox_cont order-list">
			<h3><?php echo $text_order_list; ?></h3>
        	<div class="table-header">
        		<a onclick="deleteOrder(this);" class="table-btn-delete"><span class="icon"></span><?php echo $button_delete; ?></a>
       		</div>
			<div class="table-container">
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="table_orderlist">
        			<thead>
  						<tr>
                            <td class="one checkbox-head"><label class="radio_check"><input type="checkbox" onclick="$('input[name*=order_selected]').prop('checked', $(this).is(':checked'));" /> <span class="skip_content">Select All</span></label></td>
                            <td class="two"><?php echo $column_order_id; ?></td>
                            <td class="four"><?php echo $column_customer; ?></td>
                            <td class="five"><?php echo $column_status; ?></td>
                            <td class="six"><?php echo $column_order_total; ?></td>
                            <td class="seven"><?php echo $column_date_added; ?></td>
                            <td class="eight"><?php echo $column_date_modified; ?></td>
                            <td class="nine"><?php echo $column_action; ?></td>
                 		</tr>
                  	</thead>
                    <tbody>
  						<tr class="first-row">
                            <td class="one filter"><a class="skip_content filter_tab"><?php echo $button_filter; ?><span class="icon"></span></a></td>
                            <td class="two"><label class="skip_content"><?php echo $column_order_id; ?></label><input name="filter_order_id" type="text" value=""></td>
                            <td class="four"><label class="skip_content"><?php echo $column_customer; ?></label><input name="filter_customer" type="text" class="auto_complete"></td>
                            <td class="five">
                            	<label class="skip_content"><?php echo $column_status; ?></label>
								<select name="filter_order_status_id">
									<option value="*"></option>
									<option value="0"><?php echo $text_missing; ?></option>
									<?php foreach ($order_statuses as $order_status_top) { ?>
									<option value="<?php echo $order_status_top['order_status_id']; ?>"><?php echo $order_status_top['name']; ?></option>
									<?php } ?>
								</select>
                            </td>
                            <td class="six"><label class="skip_content"><?php echo $column_order_total; ?></label><input name="filter_total" type="text"></td>
                            <td class="seven"><label class="skip_content"><?php echo $column_date_added; ?></label><input name="filter_date_added" type="text" class="date"></td>
                            <td class="eight"><label class="skip_content"><?php echo $column_date_modified; ?></label><input name="filter_date_modified" type="text" class="date"></td>
                            <td class="nine"><label class="skip_content">&nbsp;</label><a id="button_filter" onclick="filter();" class="table-btn table-btn-filter"><span class="icon filter"></span> <?php echo $button_filter; ?></a></td>
                   		</tr>
					</tbody>
					<tbody id="order_list_orders"></tbody>
				</table>
			</div>
			<div id="order_list_pagination" class="table-pagination"></div>
		</div>

		<!-- for order status dialog popups, prepare the div for dialogs -->
		<div id="order_status_dialog" class="fbox_cont order-status">
			<h3><?php echo $text_change_order_status; ?></h3>
			<div class="table-container"><ul class="order_status_list"></ul></div>
		</div>

		<!-- for customer dialog popup, prepare the div -->
		<div id="customer_dialog" class="fbox_cont change_add_details">
			<h3><?php echo $text_change_customer; ?></h3>
            <div class="fbox_btn_wrap margin_b_6">
				<a onclick="resetCustomer();" class="table-btn-common customer reset"><span class="icon"></span><?php echo $text_reset_customer; ?></a>
                <a onclick="newCustomer();" class="table-btn-common customer new"><span class="icon"></span><?php echo $text_add_customer; ?></a>
                <a onclick="getCustomerList();" class="table-btn-common customer existing fbox_trigger_2"><span class="icon"></span><?php echo $text_select_customer; ?></a>
            </div>
			<div class="table-container margin_b_6" id="order_customer">
 				<input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>" />
				<?php $address_row = 1; ?>
				<div class="nav_tab_wrap">
                    <ul class="nav nav-tabs">
                        <li class=""><a href="#tab_customer_general" id="customer_general" data-toggle="tab"><?php echo $tab_customer_general; ?></a></li>
						<?php if (!empty($customer_addresses)) { foreach ($customer_addresses as $customer_address) { ?>
                        <li><a href="tab_customer_address_<?php echo $address_row; ?>" id="customer_address_<?php echo $address_row; ?>"><span onclick="$('#customer_general').trigger('click');$(this).closest('li').remove(); $('#tab_customer_address_<?php echo $address_row; ?>').remove();" class="icon"></span> <?php echo $tab_address . ' ' . $address_row; ?></a></li>
						<?php $address_row++; ?>
						<?php } } ?>
                        <li><a href="#tab_customer_new_address" id="customer_new_address" class="new_address" <?php if (empty($customer_id)) {?> style="display: none;"<?php }?>><span class="icon"></span><?php echo $tab_customer_new_address; ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane" id="tab_customer_general">
                            <ul class="form_list">
								<li>
									<label><?php echo $entry_firstname; ?> <em>*</em></label>
									<div class="inputbox"><input name="customer_firstname" type="text" value="<?php echo empty($customer_id) ? $firstname : $customer_firstname; ?>"></div>
								</li>
								<li>
									<label><?php echo $entry_lastname; ?> <em>*</em></label>
									<div class="inputbox"><input name="customer_lastname" type="text" value="<?php echo empty($customer_id) ? $lastname : $customer_lastname; ?>"></div>
								</li>
								<li>
									<label><?php echo $entry_email; ?> <em>*</em></label>
									<div class="inputbox"><input name="customer_email" type="text" value="<?php echo empty($customer_id) ? $email : $customer_email; ?>"></div>
								</li>
								<li>
									<label><?php echo $entry_telephone; ?> <em>*</em></label>
									<div class="inputbox"><input name="customer_telephone" type="text" value="<?php echo empty($customer_id) ? $telephone : $customer_telephone; ?>"></div>
								</li>
								<li>
									<label><?php echo $entry_fax; ?></label>
									<div class="inputbox"><input name="customer_fax" type="text" value="<?php echo empty($customer_id) ? $fax : $customer_fax; ?>"></div>
								</li>
								<span id="customer_extra_info" <?php if (empty($customer_id)) {?> style="display: none;"<?php }?>>
									<li>
										<label><?php echo $entry_password; ?></label>
										<div class="inputbox"><input name="customer_password" type="password"></div>
									</li>
									<li>
										<label><?php echo $entry_confirm; ?>:</label>
										<div class="inputbox"><input name="customer_confirm" type="password"></div>
									</li>
									<li>
										<label><?php echo $entry_newsletter; ?></label>
										<div class="inputbox">
											<select name="customer_newsletter">
												<?php if (!empty($customer_newsletter)) { ?>
												<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
												<option value="0"><?php echo $text_disabled; ?></option>
												<?php } else { ?>
												<option value="1"><?php echo $text_enabled; ?></option>
												<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
												<?php } ?>
											</select>
										</div>
									</li>
									<li>
										<label><?php echo $entry_customer_group; ?></label>
										<div class="inputbox">
											<select name="customer_group_id">
												<?php foreach ($customer_groups as $customer_group) { ?>
													<?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
													<option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
													<?php } else { ?>
													<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
													<?php } ?>
												<?php } ?>
											</select>
										</div>
									</li>
									<li>
										<label><?php echo $entry_status; ?></label>
										<div class="inputbox">
											<select name="customer_status">
												<?php if (!empty($customer_status)) { ?>
												<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
												<option value="0"><?php echo $text_disabled; ?></option>
												<?php } else { ?>
												<option value="1"><?php echo $text_enabled; ?></option>
												<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
												<?php } ?>
											</select>
										</div>
									</li>
								</span>
                            </ul>
                        </div><!--tab-pane-->
					<?php $address_row = 1; ?>
					<?php if (!empty($customer_addresses)) { foreach ($customer_addresses as $customer_address) { ?>
                        <div class="tab-pane" id="tab_customer_address_<?php echo $address_row; ?>">
							<input type="hidden" name="customer_addresses[<?php echo $address_row; ?>][address_id]" value="<?php echo $customer_address['address_id']; ?>" />
                            <ul class="form_list">
                                <li>
                                    <label><?php echo $entry_firstname; ?> <em>*</em></label>
                                    <div class="inputbox"><input name="customer_addresses[<?php echo $address_row; ?>][firstname]" type="text" value="<?php echo $customer_address['firstname']; ?>"></div>
                                </li>
                                <li>
                                    <label><?php echo $entry_lastname; ?> <em>*</em></label>
                                    <div class="inputbox"><input name="customer_addresses[<?php echo $address_row; ?>][lastname]" type="text" value="<?php echo $customer_address['lastname']; ?>"></div>
                                </li>
                                <li>
                                    <label><?php echo $entry_company; ?></label>
                                    <div class="inputbox"><input name="customer_addresses[<?php echo $address_row; ?>][company]" type="text" value="<?php echo $customer_address['company']; ?>"></div>
                                </li>
                                <li>
                                    <label><?php echo $entry_address_1; ?> <em>*</em></label>
                                    <div class="inputbox"><input name="customer_addresses[<?php echo $address_row; ?>][address_1]" type="text" value="<?php echo $customer_address['address_1']; ?>"></div>
                                </li>
                                <li>
                                    <label><?php echo $entry_address_2; ?></label>
                                    <div class="inputbox"><input name="customer_addresses[<?php echo $address_row; ?>][address_2]" type="text" value="<?php echo $customer_address['address_2']; ?>"></div>
                                </li>
                                <li>
                                    <label><?php echo $entry_city; ?> <em>*</em></label>
                                    <div class="inputbox"><input name="customer_addresses[<?php echo $address_row; ?>][city]" type="text" value="<?php echo $customer_address['city']; ?>"></div>
                                </li>
                                <li>
                                    <label><?php echo $entry_postcode; ?> <em>*</em></label>
                                    <div class="inputbox"><input name="customer_addresses[<?php echo $address_row; ?>][postcode]" type="text" value="<?php echo $customer_address['postcode']; ?>"></div>
                                </li>
                                <li>
                                    <label><?php echo $entry_country; ?> <em>*</em></label>
                                    <div class="inputbox">
										<select name="customer_addresses[<?php echo $address_row; ?>][country_id]" onchange="country(this, '<?php echo $address_row; ?>', '<?php echo $customer_address['zone_id']; ?>');">
											<option value=""><?php echo $text_select; ?></option>
											<?php foreach ($customer_countries as $customer_country) { ?>
												<?php if ($customer_country['country_id'] == $customer_address['country_id']) { ?>
												<option value="<?php echo $customer_country['country_id']; ?>" selected="selected"><?php echo $customer_country['name']; ?></option>
												<?php } else { ?>
												<option value="<?php echo $customer_country['country_id']; ?>"><?php echo $customer_country['name']; ?></option>
												<?php } ?>
											<?php } ?>
										</select>
                                    </div>
                                </li>
                                <li>
                                    <label><?php echo $entry_zone; ?> <em>*</em></label>
                                    <div class="inputbox">
										<select name="customer_addresses[<?php echo $address_row; ?>][zone_id]">
											<option value=""><?php echo $text_select; ?></option>
											<?php foreach ($customer_address['zones'] as $zone) {
													if ($zone['zone_id'] == $customer_address['zone_id']) { ?>
														<option value="<?php echo $zone['zone_id']; ?>" selected="selected"><?php echo $zone['name']; ?></option>
											<?php } else { ?>
														<option value="<?php echo $zone['zone_id']; ?>"><?php echo $zone['name']; ?></option>
											<?php } } ?>
										</select>
                                    </div>
                                </li>
                                <li>
                                    <label>&nbsp;</label>
                                    <div class="inputbox">
										<?php if (($customer_address['address_id'] == $customer_address_id) || !$customer_addresses) { ?>
										<label class="radio_check"><input type="radio" class="" value="<?php echo $address_row; ?>" name="customer_addresses[<?php echo $address_row; ?>][default]"  checked="checked"><?php echo $entry_default; ?></label>
										<?php } else { ?>
										<label class="radio_check"><input type="radio" class="" value="<?php echo $address_row; ?>" name="customer_addresses[<?php echo $address_row; ?>][default]"><?php echo $entry_default; ?></label>
										<?php } ?>
									</div>
                                </li>
                            </ul>
                        </div><!--tab-pane-->
					<?php $address_row++; ?>
					<?php } } ?>
                        <div class="tab-pane" id="tab_customer_new_address"></div><!--tab-pane-->
                    </div><!--tab-content-->
                </div><!--nav_tab_wrap-->
				<div id="customer_action_info" style="padding: 20px; display:none;">
					<i class="fa fa-spinner fa-spin"></i>&nbsp;<?php echo $text_fetching_customers; ?>
				</div>
			</div>
            <div class="fbox_btn_wrap">
				<a onclick="saveCustomer();" class="table-btn-common"><?php echo $button_save; ?></a>
            </div>
    	</div><!--fbox_cont-->

		<!-- for customer list dialog popup, prepare the div -->
		<div id="customer_list_dialog" class="fbox_cont existing_customer">
			<h3><?php echo $text_customer_list; ?></h3>
        	<div class="table-container">
            	<a class="skip_content filter_tab"><?php echo $button_filter; ?> <span class="icon"></span></a>
       			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="table_orderlist table_customerlist">
        			<thead>
  						<tr>
                            <td class="two"><?php echo $column_customer_id; ?></td>
                            <td class="four"><?php echo $column_customer_name; ?></td>
                            <td class="five"><?php echo $column_email; ?></td>
                            <td class="six"><?php echo $column_telephone; ?></td>
                            <td class="seven"><?php echo $column_date_added; ?></td>
                            <td class="nine"><?php echo $column_action; ?></td>
                 		</tr>
                  	</thead>
                    <tbody>
  						<tr class="first-row">
                            <td class="two"><label class="skip_content"><?php echo $column_customer_id; ?></label> <input name="filter_customer_id" type="text"></td>
                            <td class="four"><label class="skip_content"><?php echo $column_customer_name; ?></label> <input name="filter_customer_name" type="text"></td>
                            <td class="five"><label class="skip_content"><?php echo $column_email; ?></label> <input name="filter_customer_email" type="text"></td>
                            <td class="six"><label class="skip_content"><?php echo $column_telephone; ?></label> <input name="filter_customer_telephone" type="text"></td>
                            <td class="seven"><label class="skip_content"><?php echo $column_date_added; ?></label> <input name="filter_customer_date" type="text" class="date"></td>
                            <td class="nine"><label class="skip_content">&nbsp;</label> <a id="button_customer_filter" onclick="filterCustomer();" class="table-btn table-btn-filter"><span class="icon filter"></span> <?php echo $button_filter; ?></a></td>
                   		</tr>
					</tbody>
					<tbody id="customer_list_customers">
						<tr><td align="center" colspan="6"><i class="fa fa-spinner fa-spin"></i> <?php echo $text_fetching_customers; ?></td></tr>
					</tbody>
				</table>
        	</div><!--table-container-->
        	<div id="customer_list_pagination" class="table-pagination"></div>
		</div>

		<div id="product_details_dialog" class="fbox_cont prod_details">
			<h3><?php echo $text_product_details; ?></h3>
    		<div class="table-container" id="product_details_info">
            	<div class="prod_wrap">
                	<div class="prod_cont">
                    	<div class="prod_l prod_img"><img id="product_details_thumb" src="" width="300" height="300" alt=""></div>
                        <div class="prod_r">
                        	<h5 id="product_details_name"></h5>
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr class="odd">
                                    <td><?php echo $column_details_price; ?></td>
                                    <td class="txt_c">:</td>
                                    <td><span id="product_details_price" class="price"></span></td>
                                </tr>
                                <tr class="even">
                                    <td><?php echo $column_details_model; ?></td>
                                    <td class="txt_c">:</td>
                                    <td id="product_details_model"></td>
                                </tr>
                                <tr class="odd">
                                    <td><?php echo $column_details_quantity; ?></td>
                                    <td class="txt_c">:</td>
                                    <td id="product_details_quantity"></td>
                                </tr>
                                <tr class="even">
                                    <td><?php echo $column_details_manufacturer; ?></td>
                                    <td class="txt_c">:</td>
                                    <td id="product_details_manufacturer"></td>
                                </tr>
                                <tr class="odd">
                                    <td><?php echo $column_details_sku; ?></td>
                                    <td class="txt_c">:</td>
                                    <td id="product_details_sku"></td>
                                </tr>
                                <tr class="even">
                                    <td><?php echo $column_details_upc; ?></td>
                                    <td class="txt_c">:</td>
                                    <td id="product_details_upc"></td>
                                </tr>
                                <tr class="odd">
                                    <td><?php echo $column_details_location; ?></td>
                                    <td class="txt_c">:</td>
                                    <td id="product_details_location"></td>
                                </tr>
                                <tr class="even">
                                    <td><?php echo $column_details_minimum; ?></td>
                                    <td class="txt_c">:</td>
                                    <td id="product_details_minimum"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="prod_cont">
                    	<div class="prod_l" id="product_details_description"></div>
                        <div class="prod_r prod_options">
                        	<h6><?php echo $column_product_options; ?></h6>
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            	<thead>
                                    <tr>
                                        <td><?php echo $column_attr_name; ?></td>
                                        <td><?php echo $column_attr_value; ?></td>
                                        <td align="center"><?php echo $column_details_requried; ?></td>
                                    </tr>
                                </thead>
                                <tbody id="product_details_options"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
        	</div><!--table-container-->
		</div>

		<!-- prepare for totals section -->
		<div id="totals_details_dialog" class="fbox_cont totals">
			<h3><?php echo $text_show_totals; ?></h3>
			<div class="table-container form-box margin_0">
         		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table_totals">
					<tbody id="total">
					<?php
					$total_row = 0;
					foreach ($totals as $total_order) {
						$trClass = ($total_row % 2 == 0) ? 'odd' : 'even';
						if ($total_order['code'] == 'total')  {
							$trClass .= ' total';
						}
					?>
						<tr class="<?php echo $trClass; ?>">
							<td><?php echo $total_order['title']; ?> :</td>
							<td><?php echo $total_order['text']; ?></td>
						</tr>
						<?php $total_row++; ?>
					<?php }?>
					</tbody>
                </table>
			</div>
		</div>

		<!-- prepare for payment dialog -->
		<div id="order_payments_dialog" class="fbox_cont payment">
			<h3><?php echo $text_make_payment; ?></h3>
			<div class="table-container" id="payment_action_div">
            	<div class="payment_l">
                	<div class="payment_head">
                    	<div class="amount">
                        	<?php echo $text_amount_due; ?>: <span id="dialog_due_amount_text" class="cash"><?php echo $payment_due_amount_text; ?></span>
                        </div>
                        <div class="amount">
                        	<?php echo $text_change; ?>: <span id="dialog_change_text" class="cash"><?php echo $payment_change_text; ?></span>
                        </div>
                    </div>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="payment_list">
                        <thead>
                            <tr>
                                <td><?php echo $column_payment_type; ?></td>
                                <td class="amount_td"><?php echo $column_payment_amount; ?></td>
                                <td><span id="payment_note_text"><?php echo $column_payment_note; ?></span></td>
                                <td class="action"><?php echo $column_payment_action; ?></td>
                            </tr>
                        </thead>
                        <tbody id="payment_list">
                        	<tr class="first-row" id="button_add_payment_tr">
                            	<td>
                                	<label class="skip_content"><?php echo $column_payment_type; ?></label>
                                	<select name="payment_type" id="payment_type" class="payment_type">
										<?php if (!empty($payment_types)) { foreach($payment_types as $payment_type => $payment_name) { ?>
										<?php if ($payment_type == 'cash') { ?>
										<option value="<?php echo $payment_type ?>" selected="selected"><?php echo $payment_name ?></option>
										<?php } else { ?>
										<option value="<?php echo $payment_type ?>"><?php echo $payment_name ?></option>
										<?php }}} ?>
                                	</select>
                                </td>
								<?php
									$float_amount_due = $payment_due_amount;
									$firstChar = substr($payment_due_amount, 0, 1);
									if (strcmp($firstChar, '0') < 0 || strcmp($firstChar, '9') > 0) {
										$float_amount_due = substr($payment_due_amount, 1);
									}
								?>
                                <td>
                                	<label class="skip_content"><?php echo $column_payment_amount; ?></label>
                                	<div class="inputbox enter_amount enter_amount_2">
                                    	<input type="text" name="tendered_amount" id="tendered_amount" value="<?php echo round(floatval($float_amount_due), 2); ?>">
                                        <a class="clear clear_input" onclick="$(this).closest('div').find('input').val(0);"></a>
                                    </div>
                                </td>
                                <td>
                                	<label class="skip_content"><span id="payment_note_text"><?php echo $column_payment_note; ?></label>
                                	<input type="text" name="payment_note" id="payment_note" value="">
                                </td>
                                <td class="action">
                                	<label class="skip_content">&nbsp;</label>
                                	<a id="button_add_payment" class="table-btn table-btn-add" onclick="addPayment();"><span class="icon"></span> <?php echo $button_add_payment; ?></a>
                                </td>
                            </tr>
							<?php if (!empty($order_payments)) { $trClass = 'even'; foreach ($order_payments as $order_payment) { $trClass = ($trClass == 'even') ? 'odd' : 'even';?>
                            <tr class="<?php echo $trClass; ?>">
                                <td><span class="skip_content label"><?php echo $column_payment_type; ?>:</span><?php echo $order_payment['payment_type']; ?></td>
                                <td><span class="skip_content label"><?php echo $column_payment_amount; ?>:</span><?php echo $order_payment['tendered_amount']; ?></td>
                                <td><span class="skip_content label"><span id="payment_note_text"><?php echo $column_payment_note; ?></span>:</span><?php echo $order_payment['payment_note']; ?></td>
                                <td class="action"><a class="table-btn table-btn-delete-2" onclick="deletePayment(this, '<?php echo $order_payment['order_payment_id']; ?>');"><span class="icon"></span><?php echo $button_delete; ?></a></td>
                            </tr>
							<?php }} ?>
                        </tbody>
                    </table>
                </div><!--payment_l-->
			</div>
            <div class="fbox_btn_wrap">
				<span id="post_payment_action_div">
					<?php if (!empty($order_payment_post_status)) { foreach ($order_payment_post_status as $post_order_status_id => $post_order_status_name) { ?>
					<a id="button_order_action_<?php echo $post_order_status_id; ?>" class="table-btn-common" onclick="postPayment(<?php echo $post_order_status_id; ?>);"><?php echo $post_order_status_name; ?></a>
					<?php } } ?>
				</span>
            </div>
		</div>

		<!-- hidden form for adding products -->
		<div id="product_new" style="display: none;">
			<input type="hidden" name="product_id" value="" />
			<input type="hidden" name="product" value="" />
			<input type="hidden" name="product_price" value="" />
			<input type="hidden" name="subtract" value="1" />
			<input type="hidden" name="manufacturer_id" value="0" />
			<input type="hidden" name="sku" value="" />
			<input type="hidden" name="upc" value="" />
			<input type="hidden" name="mpn" value="" />
			<input type="hidden" name="model" value="" />
			<input type="hidden" name="quantity" value="1" />
			<input type="hidden" name="product_sn_id" value="" />
			<input type="hidden" name="product_image" value="" />
			<div id="option"></div>
			<a id="button_product">add</a>
		</div>

		<!-- prepare for search settings section -->
		<div id="search_settings_dialog" class="fbox_cont search_scope">
			<h3><?php echo $text_search_scope; ?></h3>
			<div class="table-container form-box">
         		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table_search_scope">
					<?php $trClass = 'even'; ?>
					<?php $search_scopes = array('name'=>$text_search_product_name, 'model'=>$text_search_model_name, 'manufacturer'=>$text_search_manufacturer, 'sku'=>'SKU', 'upc'=>'UPC', 'mpn'=>'MPN', 'ean'=>'EAN'); ?>
					<?php foreach ($search_scopes as $key => $search_scope) { ?>
					<?php $trClass = ($trClass == 'even') ? 'odd' : 'even'; ?>
                  	<tr class="<?php echo $trClass; ?>">
                    	<td><label><input name="search_scope_<?php echo $key; ?>" type="checkbox" value=""> <?php echo $search_scope; ?></label></td>
                  	</tr>
					<?php }?>
                </table>
			</div>
            <div class="fbox_btn_wrap">
				<a onclick="setSearchScope();" class="table-btn-common"><?php echo $button_set_scope; ?></a>
            </div>
		</div>
		<!-- prepare quantity dialog -->
		<div id="quantity_dialog" class="fbox_cont quantity">
			<h3><?php echo $text_change_quantity; ?></h3>
			<div class="table-container">
				<?php $pad_keys = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '.', 'C'); ?>
				<input type="hidden" name="org_quantity" value="" />
				<input type="hidden" name="quantity_index" value="" />
         		<div class="keypad_wrap" id="quantity_pad">
                	<input name="changed_quantity" type="text" class="display">
					<?php foreach ($pad_keys as $pad_key) {?>
                    <a class="btn"><?php echo $pad_key; ?></a>
					<?php }?>
                    <a class="btn ok">OK</a>
                </div><!--keypad_wrap-->
			</div>
		</div>

		<!-- prepare dialog for order comment -->
		<div id="order_comment_dialog" class="fbox_cont comment">
			<h3><?php echo $text_order_comment; ?></h3>
			<div class="table-container form-box">
         		<ul class="form_list">
                	<li>
                        <div class="inputbox"><textarea name="order_comment"><?php echo $comment; ?></textarea></div>
                    </li>
                 </ul>
			</div>
            <div class="fbox_btn_wrap">
				<a onclick="saveOrderComment();" class="table-btn-common"><?php echo $button_save; ?></a>
            </div>
		</div>

		</div> <!-- end for the hidden wrapper for all dialogs -->

		<div class="alert_cont" id="alert_dialog">
			<p></p>
			<div class="fbox_btn_wrap">
				<a id="alert_cancel" class="table-btn table-btn-grey alert_hide" onclick="$('.alert_cont').hide(); $('.alert_cont .alert_hide').show();"><?php echo $button_cancel; ?></a>
				<a id="alert_ok" class="table-btn table-btn-delete-2 alert_hide" onclick="$('.alert_cont').hide();"><?php echo $button_ok; ?></a>
			</div>
		</div>
		<div class="alert_cont" id="pos_wait_msg">
			<p><i class="fa fa-spinner fa-spin"></i> <span></span></p>
		</div>
	</div>
</div>

<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="view/javascript/pos/pos_vars.js"></script>
<script type="text/javascript">
	var token = '<?php echo $token; ?>';
</script>
<script type="text/javascript" src="view/javascript/pos/pos_backend.js"></script>
<script type="text/javascript" src="view/javascript/pos/jquery.fancybox.pack.js"></script>
<script type="text/javascript" src="view/javascript/pos/pos.js"></script>

<?php echo $footer; ?>