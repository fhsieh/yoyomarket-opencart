// add for browse begin
var browseQ = [];
// add for browse end
var product_add_option = [];
var saved_zones = zones;

function openFancybox(selector, type, content, onCloseFn, reOpenFn) {
	var minWidth = 260, maxWidth = 1100;
	if (type == 'narrower') { minWidth = 220; maxWidth = 220; }
	else if (type == 'narrow') { maxWidth = 320; }
	else if (type == 'normal') { maxWidth = 450; }
	else if (type == 'wide') { maxWidth = 760; }
	else if (type == 'wider') { maxWidth = 1100; }
	
	var data = {
		padding : 0,
		margin  : 10,
		width	: '95%',
		height    : 'auto',
		minWidth  : minWidth,
		maxWidth  : maxWidth,
		autoSize	: false,
		fitToView	: true,
		modal: true,
		openEffect: 'none',
		closeEffect: 'none',
		afterShow : function() {
			$('.fancybox-skin').append('<a title="Close" class="fancybox-item fancybox-close" onclick="' + (reOpenFn ? reOpenFn : '$.fancybox.close') + '();"></a>');
		},
		beforeShow: function(){
			$(window).on({
				'resize.fancybox' : function(){
					$.fancybox.update();
				}
			});
		},
		afterClose: function(){
			$(window).off('resize.fancybox');
		}
	};
	if (selector) {
		data['href'] = selector;
	} else {
		data['content'] = content;
	}
	if (onCloseFn) {
		data['afterClose'] = onCloseFn;
	}
	
	$.fancybox(data);
};

function closeFancybox() {
	$.fancybox.close();
};

function resizeFancybox() {
	$.fancybox.update();
};

function getOrderList(data) {
	var url = 'index.php?route=module/pos/getOrderList&token=' + token;
	$.ajax({
		url: url,
		type: 'post',
		dataType: 'json',
		data: data,
		beforeSend: function() {
			if ($('#order_list_dialog').is(':visible')) {
				var width = $('#button_filter').width();
				$('#button_filter').closest('td').append('<div style="width:' + width + 'px;"><i class="fa fa-spinner fa-spin"></i></div>');
				$('#button_filter').hide();
			} else {
				openWaitDialog(text_fetching_orders);
			}
		},
		complete: function() {
			if ($('#order_list_dialog').is(':visible')) {
				$('#button_filter').closest('td').find('div').remove();
				$('#button_filter').show();
			}
			closeWaitDialog();
		},
		success: function(json) {
			if (!$('#order_list_dialog').is(':visible')) {
				openFancybox('#order_list_dialog', 'wider');
			}
			renderOrderList(json);
		}
	});
};

function renderOrderList(json) {
	// render the order list
	var html = '';
	if (json['orders'] && json['orders'].length > 0) {
		var orderRow = 1;
		for (var i in json['orders']) {
			var rowClass = 'odd';
			if (orderRow % 2 == 0) { rowClass = 'even'; }
			
			var bgcolor = '';
			html += '<tr class="' + rowClass + '">';
			html += '<td class="one checkbox-item"><label class="radio_check"><input type="checkbox" name="order_selected[]" value="' + json['orders'][i]['order_id'] + '" /> <span class="skip_content">Select</span></label></td>';
			html += '<td class="two"' + bgcolor + '><span class="skip_content label">' + column_order_id + ':</span><span class="txt">' + json['orders'][i]['order_id'] + '</span></td>';
			html += '<td class="four"' + bgcolor + '><span class="skip_content label">' + column_customer + ':</span>' + json['orders'][i]['customer'] + '</td>';
			html += '<td class="five"' + bgcolor + '><span class="skip_content label">' + column_status + ':</span>' + json['orders'][i]['status'] + '</td>';
			html += '<td class="six"' + bgcolor + '><span class="skip_content label">' + column_order_total + ':</span>' + json['orders'][i]['total'] + '</td>';
			html += '<td class="seven"' + bgcolor + '><span class="skip_content label">' + column_date_added + ':</span>' + json['orders'][i]['date_added'] + '</td>';
			html += '<td class="eight"' + bgcolor + '><span class="skip_content label">' + column_date_modified + ':</span>' + json['orders'][i]['date_modified'] + '</td>';
			html += '<td class="nine"' + bgcolor + '><a onclick="selectOrder(this, ' + json['orders'][i]['order_id'] + ');" class="table-btn"><span class="icon select"></span> ' + text_select + '</a></td>';
			html += '</tr>';
			
			orderRow++;
		}
	} else {
		html += '<tr><td align="center" colspan="8">' + text_no_results + '</td></tr>';
	}
	$('#order_list_orders').html(html);
	$('#order_list_pagination').html(json['pagination']);
	resizeFancybox();
};

function selectOrderPage(page) {
	filter(page);
};

function filter(page) {
	var data = {};
	var filter_order_id = $('input[name=\'filter_order_id\']').val();
	if (filter_order_id) {
		data['filter_order_id'] = filter_order_id;
	}
	var filter_order_status_id = $('select[name=\'filter_order_status_id\']').val();
	if (filter_order_status_id != '*') {
		data['filter_order_status_id'] = filter_order_status_id;
	}
	var filter_customer = $('input[name=\'filter_customer\']').val();
	if (filter_customer) {
		data['filter_customer'] = filter_customer;
	}
	var filter_total = $('input[name=\'filter_total\']').val();
	if (filter_total != '') {
		data['filter_total'] = filter_total;
	}	
	var filter_date_added = $('input[name=\'filter_date_added\']').val();
	if (filter_date_added) {
		data['filter_date_added'] = filter_date_added;
	}
	var filter_date_modified = $('input[name=\'filter_date_modified\']').val();
	if (filter_date_modified) {
		data['filter_date_modified'] = filter_date_modified;
	}
	if (page) {
		data['page'] = page;
	}
	
	getOrderList(data);
};

function selectOrder(anchor, select_order_id) {
	// refresh the page using the current order_id
	order_id = select_order_id;
	var td = 0;
	var tdhtml = 0;
	if (anchor) {
		td = $(anchor).closest('td');
		tdhtml = td.html();
	}
	
	var url = 'index.php?route=module/pos/main&token=' + token + '&order_id=' + order_id + '&ajax=1';
	$.ajax({
		url: url,
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			if (td) {
				td.html('<div><i class="fa fa-spinner fa-spin"></i> ' + text_load_order + '</div>');
			}
		},
		complete: function() {
			if (td) {
				td.find('div').remove();
				td.html(tdhtml);
			}
		},
		success: function(json) {
			removeMessage();
			// update the values of all page element and javascript variables
			for (var name in json) {
				if ($("input[name='" + name + "']").length) {
					if ($("input[name='" + name + "']").is(':radio')) {
						$("input[name='" + name + "'][value='" + json[name] + "']").prop('checked', 1);
					} else {
						// do not set the radio value as it has multiple values
						$("input[name='" + name + "']").val(json[name]);
					}
					
					if ($("input[name='" + name + "']").is(':checkbox')) {
						if (parseInt(json[name])) {
							$("input[name='" + name + "']").prop('checked', 1);
						} else {
							$("input[name='" + name + "']").prop('checked', 0);
						}
					}
				} else if ($("select[name='" + name + "']").length) {
					$("select[name='" + name + "']").val(json[name]);
					$("select[name='" + name + "']").trigger('change');
				} else if ($("span[id='" + name + "']").length) {
					$("span[id='" + name + "']").text(json[name]);
				}
				window[name] = json[name];
			}
			
			refreshPageForOrder(json);
			
			closeFancybox();
		}
	});
};

function refreshPageForOrder(json) {
	$('#work_mode_dropdown').val('sale');
	$('.pay-btn span').text(text_pay);
	
	// general info of order
	$('#order_id_text').text(json['order_id_text']);
	// show the shipping method
	$('#shipping_method').closest('li').show();
	// show the order comment
	$('#order_comment').closest('li').show();
	// show the shortcuts
	$('.left-container .shortcuts').show();
	$('#customer').text(json['customer']);
	$('#order_id_text').closest('a').attr('onclick', 'getOrderList();');
	$('#order_status_name').closest('a').attr('onclick', 'changeOrderStatus();');
	// hide the non-catalog button
	$('.non-catalog').show();

	// also hide some of the order processing buttons
	$('#order_only_buttons').show();
	
	// display order for return info
	$('#add_product_control').show();
	$('#return_control').hide();
	$('#browse_category_div').show();
	
	for (var i in order_statuses) {
		if (order_statuses[i]['order_status_id'] == order_status_id) {
			$('#order_status_name').text(order_statuses[i]['name']);
			break;
		}
	}
	updateProducts(json['products']);
	
	// update the payments
	if (json['order_payments']) {
		updatePayments(json['order_payments']);
	} else {
		updatePayments({});
	}
	// update change
	$('#payment_change').find('span').text(json['payment_change_text']);
	$('#dialog_change_text').text(json['payment_change_text']);
	
	// update total
	updateTotal(json['totals']);
	
	showMessage('success', text_order_ready);
};

function updateProducts(orderProducts, forReturn) {
	$('#product').empty();
	
	if (orderProducts) {
		var product_row = 0;
		html = '';
		
		for ( var i in orderProducts) {
			var product = orderProducts[i];
			
			// get the encoded key, for now it is used by "return without order" only
			var addData = {'product_id':product['product_id']};
			
			var options = [];
			for (var j in product['option']) {
				var option = product['option'][j];
				var option_id = parseInt(option['product_option_id']);
				if (option['type'] == 'checkbox') {
					var product_option_value_id = parseInt(option['product_option_value_id']);
					// checkbox value is an array
					for (var k = 0; k < options.length; k++) {
						if (options[k]['option_id'] == option_id) {
							// found the array element, insert the value in the right position
							for (var l = 0; l < options[k]['value'].length; l++) {
								if (product_option_value_id < options[k]['value'][l]) {
									break;
								}
							}
							// need insert before position l
							options[k]['value'].splice(l, 0, product_option_value_id);
							break;
						}
					}
					if (k == options.length) {
						// no such option_id inserted yet
						options.push({'option_id':option_id, 'value':[product_option_value_id]});
					}
				} else if (option['type'] == 'select' || option['type'] == 'radio' || option['type'] == 'image') {
					options.push({'option_id':option_id, 'value':option['product_option_value_id']});
				} else {
					options.push({'option_id':option_id, 'value':option['value']});
				}
			}
			if (options.length > 0) {
				// sort the array by option_id
				for (var j = 0; j < options.length-1; j++) {
					for (var k = j+1; k < options.length; k++) {
						if (options[k]['option_id'] < options[j]['option_id']) {
							var tmp = options[k];
							options[k] = options[j];
							options[j] = tmp;
						}
					}
				}
				addData['options'] = options;
			}
			encodedKey = base64_encode(JSON.stringify(addData));
			
			html += '<tr id="product-row' + product_row + '" class="' + ((product_row % 2 == 0) ? 'even' : 'odd') + '">';

			if (product['order_id']) {
			html += '	<input type="hidden" name="order_product[' + product_row + '][order_id]" value="' + product['order_id'] + '" />';
			}
			html += '	<input type="hidden" name="order_product[' + product_row + '][encodedKey]" value="' + encodedKey + '" />';
			html += '	<input type="hidden" name="order_product[' + product_row + '][order_product_id]" value="' + product['order_product_id'] + '" />';
			html += '	<input type="hidden" name="order_product[' + product_row + '][product_id]" value="' + product['product_id'] + '" />';
			html += '	<input type="hidden" name="order_product[' + product_row + '][quantity]" value="' + product['quantity'] + '" />';
			html += '	<input type="hidden" name="order_product[' + product_row + '][tax_class_id]" value="' + product['tax_class_id'] + '" />';
			html += '	<input type="hidden" name="order_product[' + product_row + '][shipping]" value="' + product['shipping'] + '" />';
			html += '	<input type="hidden" name="order_product[' + product_row + '][subtract]" value="' + product['subtract'] + '" />';
			html += '	<td align="center" valign="middle" class="one"><span class="cart-round-img-outr" onclick="changeQuantity(this);"><img src="' + product['image'] + '" class="cart-round-img" alt=""><a class="cart-round-qty" id="quantity_anchor_' + product_row + '">' + product['quantity'] + '</a></span></td>';
			html += '	<td align="left" valign="middle" class="two">';
			html += '		<span class="product-name" onclick="' + (forReturn ? 'showReturnDetails(this)' : 'showProductDetails(' + product['product_id'] + ')') + '">'
			html += '			<span class="raw-name" id="order_product[' + product_row + '][order_product_display_name]">' + product['name'] + '</span>';
			for (var j in product['option']) {
				var option = product['option'][j];
			html += '			<br />';
			html += '			&nbsp;<small> - ' + option['name'] + ': ' + option['value'] + '</small>';
			html += '			<input type="hidden" name="order_product[' + product_row + '][order_option][' + option['product_option_id'] + '][product_option_id]" value="' + option['product_option_id'] + '" />';
				if (option['type'] == 'checkbox') {
			html += '			<input type="hidden" name="order_product[' + product_row + '][order_option][' + option['product_option_id'] + '][product_option_value_id][' + option['product_option_value_id'] + ']" value="' + option['value'] + '" />';
				} else {
			html += '			<input type="hidden" name="order_product[' + product_row + '][order_option][' + option['product_option_id'] + '][product_option_value_id]" value="' + option['product_option_value_id'] + '" />';
				}
			html += '			<input type="hidden" name="order_product[' + product_row + '][order_option][' + option['product_option_id'] + '][value]" value="' + option['value'] + '" />';
			html += '			<input type="hidden" name="order_product[' + product_row + '][order_option][' + option['product_option_id'] + '][type]" value="' + option['type'] + '" />';
			}
			html += '		</span>';
			html += '		<span class="highlight"><a id="price_anchor_' + product_row + '">@ ' + product['price_text'] + '</a></span>';
			html += '	</td>';
			html += '	<input type="hidden" name="order_product[' + product_row + '][price]" value="' + product['price'] + '" />';
			if (product['tax']) {
			html += '	<input type="hidden" name="order_product[' + product_row + '][tax]" value="' + product['tax'] + '" />';
			}
			html += '	<td align="center" valign="middle" class="three"><a class="cart-link">';
			html += '		<span class="product-price" id="total_text_only-' + product_row + '">' + product['total_text'] + '</span>';
			html += '		<input type="hidden" name="order_product[' + product_row + '][product_total_text]" value="' + product['total_text'] + '" />';
			html += '	</a></td>';
			html += '	<td align="center" valign="middle" class="four"><a class="delete" onclick="' + (forReturn ? 'deleteReturnProduct' : 'deleteOrderProduct') + '(this)"></a></td>';
			html += '</tr>';
			
			product_row++;
		}
		
		$('#product').html(html);
	}
};

function updatePayments(order_payments) {
	// remove the existing payments
	$('#payment_list tr:gt(0)').remove();
	
	var html = '';
	var trClass = 'even';
	for ( var i in order_payments) {
		trClass = (trClass == 'even') ? 'odd' : 'even';
		var order_payment = order_payments[i];
		
		html += '<tr id="order_payment_' + order_payment['order_payment_id'] + '" class="' + trClass + '">';
		html += '	<td><span class="skip_content label">' + column_payment_type + ':</span>' + order_payment['payment_type'] + '</td>';
		html += '	<td><span class="skip_content label">' + column_payment_amount + ':</span>' + formatMoney(order_payment['tendered_amount']) + '</td>';
		html += '	<td><span class="skip_content label"><span id="payment_note_text">' + column_payment_note + '</span>:</span>' + order_payment['payment_note'] + '</td>';
		html += '	<td class="action"><a class="table-btn table-btn-delete-2" onclick="deletePayment(this, \'' + order_payment['order_payment_id'] + '\');"><span class="icon"></span>' + button_delete + '</a></td>';
		html += '</tr>';
	}
	
	$('#payment_list').append(html);
};

function deleteOrder(anchor) {

	if ($('#order_list_orders input[type=\'checkbox\']:checked').length == 0) {
		// nothing is selected
		openAlert(text_no_order_selected);
	} else {
		var can_continue = true;
		$('#order_list_orders input[type=\'checkbox\']:checked').each(function() {
			if (parseInt($(this).val()) == parseInt(order_id)) {
				openAlert(text_can_not_delete_current_order);
				can_continue = false;
			}
		});
		if (!can_continue) return false;
		openConfirm(text_confirm_delete_order, function(anchor) {
			var data = '#order_list_orders input[type=\'checkbox\']:checked';
			var url = 'index.php?route=module/pos/deleteOrder&token=' + token;
			$.ajax({
				url: url,
				type: 'post',
				data: $(data),
				dataType: 'json',
				beforeSend: function() {
					$(anchor).closest('td').append('<div><i class="fa fa-spinner fa-spin"></i> ' + text_wait + '</div>');
					$(anchor).hide();
				},
				complete: function() {
					$(anchor).closest('td').find('div').remove();
					$(anchor).show();
				},
				success: function(json) {
					renderOrderList(json);
				}
			});
		});
	}
};
function changeOrderStatus() {
	$('#order_status_dialog ul').empty();
	
	var html = '';
	$('#order_status_dialog h3').text(text_change_order_status);
	for (var i in order_statuses) {
		if (order_status_id == order_statuses[i]['order_status_id']) {
			html += '<li><a onclick="saveOrderStatus(\'' + order_statuses[i]['order_status_id'] + '\');" class="table-btn-order-status selected"><span class="icon"></span>' + order_statuses[i]['name'] + '</a></li>';
		} else {
			html += '<li><a onclick="saveOrderStatus(\'' + order_statuses[i]['order_status_id'] + '\');" class="table-btn-order-status">' + order_statuses[i]['name'] + '</a></li>';
		}
	}
	
	$('#order_status_dialog ul').append(html);
	openFancybox('#order_status_dialog', 'wide');
};

function saveOrderStatus(new_order_status_id) {
	var data = {'order_id': order_id, 'order_status_id': new_order_status_id};
	$.ajax({
		url: 'index.php?route=module/pos/saveOrderStatus&token=' + token,
		type: 'post',
		data: data,
		dataType: 'json',
		beforeSend: function() {
			openWaitDialog(text_saving_order_status);
		},
		complete: function() {
			closeWaitDialog();
		},
		success: function(json) {
			if (json['success']) {
				removeMessage();
				if (json['error']) {
					showMessage('error', json['error']);
				} else {
					showMessage('success', json['success']);
				}
					
				// refresh the current variable and the page value
				order_status_id = new_order_status_id;
				for (var i in order_statuses) {
					if (order_statuses[i]['order_status_id'] == new_order_status_id) {
						$('#order_status_name').text(order_statuses[i]['name']);
						order_status_name = order_statuses[i]['name'];
						break;
					}
				}

				if (order_status_id == parseInt(complete_status_id) || order_status_id == parseInt(parking_status_id) || order_status_id == parseInt(void_status_id)) {
					refreshPage('order');
				}
			}
			closeFancybox();
		},
		error: function(xhr, ajaxOptions, thrownError) {
			openAlert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
};

function refreshTotal() {
	// get total from backend and refresh total area
	data = {};
	data['order_id'] = order_id;
	data['customer_id'] = customer_id;
	data['customer_group_id'] = customer_group_id;
	data['shipping_country_id'] = shipping_country_id;
	data['shipping_zone_id'] = shipping_zone_id;
	data['payment_country_id'] = payment_country_id;
	data['payment_zone_id'] = payment_zone_id;
	data['currency_code'] = currency_code;
	data['currency_value'] = currency_value;
	// as the refreshTotal is only called by saveShippingDetails after its ajax call, this call will not need any cache
	$.ajax({
		url: 'index.php?route=module/pos/update_total&token=' + token,
		type: 'post',
		data: data,
		dataType: 'json',
		localCache: false,
		success: function(json) {
			// if the order does have products added, update the total
			if (json['order_total']) {
				updateTotal(json['order_total']);
			}
		}
	});
};

function changeOrderCustomer() {
	var data = {'customer_id': customer_id, 'customer_group_id' : customer_group_id};
	if (parseInt(customer_id)) {
		data['customer_firstname'] = customer_firstname;
		data['customer_lastname'] = customer_lastname;
		data['customer_email'] = customer_email;
		data['customer_telephone'] = customer_telephone;
		data['customer_fax'] = customer_fax;
		data['customer_password'] = '';
		data['customer_confirm'] = '';
		data['customer_newsletter'] = customer_newsletter;
		data['customer_status'] = customer_status;
		data['customer_address_id'] = (typeof customer_address_id == 'undefined') ? 0 : customer_address_id;
		
		data['customer_addresses'] = (typeof customer_addresses == 'undefined') ? [] : customer_addresses;
	} else {
		data['customer_firstname'] = firstname;
		data['customer_lastname'] = lastname;
		data['customer_email'] = email;
		data['customer_telephone'] = telephone;
		data['customer_fax'] = fax;
	}

	populateCustomerDialog(data);
	
	openFancybox('#customer_dialog', 'wide');
	resizeFancybox();
};

function saveCustomer() {
	var data = '#order_customer input[type=\'text\'], #order_customer input[type=\'hidden\'], #order_customer input[type=\'password\'], #order_customer input[type=\'radio\']:checked, #order_customer input[type=\'checkbox\']:checked, #order_customer select, #order_customer textarea';
	var url = 'index.php?route=module/pos/saveCustomer&token=' + token + '&order_id=' + order_id;
	$.ajax({
		url: url,
		type: 'post',
		data: $(data),
		dataType: 'json',
		beforeSend: function() {
			openWaitDialog(text_saving_customer);
		},
		complete: function() {
			closeWaitDialog();
		},
		success: function(json) {
			populateCustomerData(json);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			openAlert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
};

function populateCustomerData(json) {
	if (json['success']) {
		removeMessage();
		showMessage('success', json['success']);
		var name = $('#order_customer input[name=\'customer_firstname\']').val() + ' ' + $('#order_customer input[name=\'customer_lastname\']').val();
		$('#customer').text(name);
		$('#address_warning').remove();
		if (json['hasAddress'] && json['hasAddress'] == '1') {
			$('#customer').before('<img id="address_warning" src="view/image/pos/warning.png" alt="' + text_customer_no_address + '" title="' + text_customer_no_address + '" />');
			$('#customer').css('width', ($('#customer').width() - 32) + 'px');
		}

		// add for edit order address begin
		$('select[name=shipping_address]').empty();
		$('select[name=shipping_address]').append('<option value="0" selected="selected">' + text_none + '</option>');
		$('select[name=payment_address]').empty();
		$('select[name=payment_address]').append('<option value="0" selected="selected">' + text_none + '</option>');
		for (i in json['customer_addresses']) {
			$('select[name=shipping_address]').append('<option value="' + json['customer_addresses'][i]['address_id'] + '">' + json['customer_addresses'][i]['firstname'] + ' ' + json['customer_addresses'][i]['lastname'] + ', ' + json['customer_addresses'][i]['address_1'] + ', ' + json['customer_addresses'][i]['city'] + ', ' + json['customer_addresses'][i]['country'] + '</option>');
			$('select[name=payment_address]').append('<option value="' + json['customer_addresses'][i]['address_id'] + '">' + json['customer_addresses'][i]['firstname'] + ' ' + json['customer_addresses'][i]['lastname'] + ', ' + json['customer_addresses'][i]['address_1'] + ', ' + json['customer_addresses'][i]['city'] + ', ' + json['customer_addresses'][i]['country'] + '</option>');
		}
		// add for edit order address end
		
		// before update the variable, store the zones info for the previous saved addresses
		if (typeof customer_addresses != 'undefined') {
			for (var i in customer_addresses) {
				var address = customer_addresses[i];
				if (address['zones']) {
					saved_zones[address['country_id']] = address['zones'];
				}
			}
		}
		// update the javascript variables
		if (parseInt(json['customer_id']) == 0) {
			for (var name in json) {
				window[name] = json[name];
				$('input[name=' + name + ']').val(json[name]);
			}
		} else {
			for (var name in json) {
				var vName = name;
				if (name != 'customer_id' && name != 'customer_group_id' && name.substring(0, 9) == 'customer_') {
					vName = name.substring(9);
				}
				window[vName] = json[name];
				window[name] = json[name];
				$('input[name=' + name + ']').val(json[name]);
			}
			if (typeof json['hasAddress'] != 'undefined' && parseInt(json['hasAddress']) == 0) {
				// no address
				customer_addresses = {};
			}
		}
		
		closeFancybox();
	}
};

function resetCustomer() {
	// Get the default customer info
	$.ajax({
		url: 'index.php?route=module/pos/get_default_customer_ajax&token=' + token,
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			$('#order_customer').hide();
			$('#customer_action_info').show();
		},
		success: function(json) {
			// update order_customer section
			populateCustomerDialog(json);
			$('input[name=customer_id]').val(json['customer_id']);
			for (var name in json) {
				window[name] = json[name];
			}
			
			$('#order_customer').show();
			$('#customer_action_info').hide();
			resizeFancybox();
		}
	});
};

function populateCustomerDialog(data) {
	// remove address tabs
	$('a[id^=customer_address_]').each(function() {
		$(this).closest('li').remove();
	});
	$('div[id^=tab_customer_address_]').remove();
		
	if (parseInt(data['customer_id'])) {
		// is real customer
		// show extra info
		$('#customer_extra_info').show();
		// populate customer general info
		$('input[name=customer_firstname]').val(data['customer_firstname']);
		$('input[name=customer_lastname]').val(data['customer_lastname']);
		$('input[name=customer_email]').val(data['customer_email']);
		$('input[name=customer_telephone]').val(data['customer_telephone']);
		$('input[name=customer_fax]').val(data['customer_fax']);
		$('input[name=customer_password]').val(data['customer_password']);
		$('input[name=customer_confirm]').val(data['customer_confirm']);
		$('select[name=customer_newsletter]').val(data['customer_newsletter']);
		$('select[name=customer_newsletter]').trigger('change');
		$('select[name=customer_group_id]').val(data['customer_group_id']);
		$('select[name=customer_group_id]').trigger('change');
		$('select[name=customer_status]').val(data['customer_status']);
		$('select[name=customer_status]').trigger('change');
		// add address tabs
		if (data['customer_addresses']) {
			var address_row = 1;
			for (var i in data['customer_addresses']) {
				// add an address tab
				addCustomerAddressTab(address_row, data['customer_addresses'][i], data['customer_address_id']);
				
				address_row++;
			}
		}
		// show add address tab
		$('#customer_new_address').show();
	} else {
		// hide extra info
		$('#customer_extra_info').hide();
		// populate general info
		$('input[name=customer_firstname]').val(data['customer_firstname']);
		$('input[name=customer_lastname]').val(data['customer_lastname']);
		$('input[name=customer_email]').val(data['customer_email']);
		$('input[name=customer_telephone]').val(data['customer_telephone']);
		$('input[name=customer_fax]').val(data['customer_fax']);
		// hide add address tab
		$('#customer_new_address').hide();
	}
	$('#customer_general').trigger('click');
};

function addCustomerAddressTab(address_row, address, customer_address_id) {
	var addressTabHtml = '<li><a href="#tab_customer_address_' + address_row + '" id="customer_address_' + address_row + '" data-toggle="tab">';
	addressTabHtml += '<span onclick="$(\'#customer_general\').trigger(\'click\');$(this).closest(\'li\').remove(); $(\'#tab_customer_address_' + address_row + '\').remove();" class="icon">';
	addressTabHtml += '</span> ' + tab_address + ' ' + address_row + '</a></li>';
	$('#customer_new_address').closest('li').before(addressTabHtml);
	
	// find an address id for the new address
	var new_address_id = -1;
	$('div[id^=tab_customer_address_]').each(function() {
		var address_id = $(this).find("input[name$='[address_id]']").val();
		if (parseInt(address_id) <= new_address_id) {
			new_address_id = parseInt(address_id) - 1;
		}
	});
	var addressHtml = '<div id="tab_customer_address_' + address_row + '" class="tab-pane">';
	addressHtml += '<input type="hidden" name="customer_addresses[' + address_row + '][address_id]" value="' + (address ? address['address_id'] : new_address_id) + '" />';
	addressHtml += '<ul class="form_list">';
	addressHtml += '	<li>';
	addressHtml += '		<label>' + entry_firstname + ' <em>*</em></label>';
	addressHtml += '		<div class="inputbox"><input type="text" name="customer_addresses[' + address_row + '][firstname]" value="' + (address ? address['firstname'] : '') + '" /></div>';
	addressHtml += '	</li>';
	addressHtml += '	<li>';
	addressHtml += '		<label>' + entry_lastname + ' <em>*</em></label>';
	addressHtml += '		<div class="inputbox"><input type="text" name="customer_addresses[' + address_row + '][lastname]" value="' + (address ? address['lastname'] : '') + '" /></div>';
	addressHtml += '	</li>';
	addressHtml += '	<li>';
	addressHtml += '		<label>' + entry_company + '</label>';
	addressHtml += '		<div class="inputbox"><input type="text" name="customer_addresses[' + address_row + '][company]" value="' + (address ? address['company'] : '') + '" /></div>';
	addressHtml += '	</li>';
	addressHtml += '	<li>';
	addressHtml += '		<label>' + entry_address_1 + ' <em>*</em></label>';
	addressHtml += '		<div class="inputbox"><input type="text" name="customer_addresses[' + address_row + '][address_1]" value="' + (address ? address['address_1'] : '') + '" /></div>';
	addressHtml += '	</li>';
	addressHtml += '	<li>';
	addressHtml += '		<label>' + entry_address_2 + '</label>';
	addressHtml += '		<div class="inputbox"><input type="text" name="customer_addresses[' + address_row + '][address_2]" value="' + (address ? address['address_2'] : '') + '" /></div>';
	addressHtml += '	</li>';
	addressHtml += '	<li>';
	addressHtml += '		<label>' + entry_city + ' <em>*</em></label>';
	addressHtml += '		<div class="inputbox"><input type="text" name="customer_addresses[' + address_row + '][city]" value="' + (address ? address['city'] : '') + '" /></div>';
	addressHtml += '	</li>';
	addressHtml += '	<li>';
	addressHtml += '		<label>' + entry_postcode + ' <em>*</em></label>';
	addressHtml += '		<div class="inputbox"><input type="text" name="customer_addresses[' + address_row + '][postcode]" value="' + (address ? address['postcode'] : '') + '" /></div>';
	addressHtml += '	</li>';
	addressHtml += '	<li>';
	addressHtml += '		<label>' + entry_country + ' <em>*</em></label>';
	addressHtml += '		<div class="inputbox"><select name="customer_addresses[' + address_row + '][country_id]" onchange="country(this, ' + address_row + ', ' + (address ? address['zone_id'] : shipping_zone_id) + ');">';
	addressHtml += '			<option value="">' + text_select + '</option>';
	for (var j in customer_countries) {
		var customer_country = customer_countries[j];
		if (customer_country['country_id'] == (address ? address['country_id'] : shipping_country_id)) {
	addressHtml += '			<option value="' + customer_country['country_id'] + '" selected="selected">' + customer_country['name'] + '</option>';
		} else {
	addressHtml += '			<option value="' + customer_country['country_id'] + '">' + customer_country['name'] + '</option>';
		}
	}
	addressHtml += '		</select></div>';
	addressHtml += '	</li>';
	addressHtml += '	<li>';
	addressHtml += '		<label>' + entry_zone + ' <em>*</em></label>';
	addressHtml += '		<div class="inputbox">';
	addressHtml += '			<select name="customer_addresses[' + address_row + '][zone_id]">';
	addressHtml += '				<option value="">' + text_select + '</option>';
	var zones = customer_shipping_zones;
	if (address) {
		zones = saved_zones[address['country_id']] ? saved_zones[address['country_id']] : address['zones'];
	}
	for (var j in zones) {
		var zone = zones[j];
		if (zone['zone_id'] == (address ? address['zone_id'] : shipping_zone_id)) {
	addressHtml += '				<option value="' + zone['zone_id'] + '" selected="selected">' + zone['name'] + '</option>';
		} else {
	addressHtml += '				<option value="' + zone['zone_id'] + '" >' + zone['name'] + '</option>';
		}
	}
	addressHtml += '			</select>';
	addressHtml += '		</div>';
	addressHtml += '	</li>';
	addressHtml += '	<li>';
	addressHtml += '		<label>&nbsp;</label>';
	addressHtml += '		<div class="inputbox"><label class="radio_check">';
	if (address && address['address_id'] == customer_address_id) {
	addressHtml += '			<input type="checkbox" name="customer_addresses[' + address_row + '][default]" value="' + address_row + '" checked="checked" />' + entry_default;
	} else {
	addressHtml += '			<input type="checkbox" name="customer_addresses[' + address_row + '][default]" value="' + address_row + '" />' + entry_default;
	}
	addressHtml += '		</label></div>';
	addressHtml += '	</li>';
	addressHtml += '</ul>';
	addressHtml += '</div>';
	
	$('#tab_customer_new_address').before(addressHtml);
};

function newCustomer() {
	// remove address tabs
	$('a[id^=customer_address_]').each(function() {
		$(this).closest('li').remove();
	});
	$('div[id^=tab_customer_address_]').remove();
	
	// hide extra info
	$('#customer_extra_info').show();
	// populate general info
	$('input[name=customer_firstname]').val('');
	$('input[name=customer_lastname]').val('');
	$('input[name=customer_email]').val('');
	$('input[name=customer_telephone]').val('');
	$('input[name=customer_fax]').val('');
	$('input[name=customer_password]').val('');
	$('input[name=customer_confirm]').val('');
	$('select[name=customer_newsletter]').val(0);
	$('select[name=customer_newsletter]').trigger('change');
	$('select[name=customer_group_id]').val($('select[name=customer_group_id] option:first').val());
	$('select[name=customer_group_id]').trigger('change');
	$('select[name=customer_status]').val(0);
	$('select[name=customer_status]').trigger('change');
	// show add address tab
	$('#customer_new_address').show();
	$('#customer_general').trigger('click');
	
	$('input[name=customer_id]').val('-1');
	resizeFancybox();
};

$(".pos-nav-tabs").on("click", "a", function (e) {
     e.preventDefault();
     if ($(this).attr('id') != 'customer_new_address') {
         $(this).tab('show');
     }
})

$('#customer_new_address').click(function(e) {
	e.preventDefault();
	var len = $(this).closest('ul').children().length;
	var address_row = 1;
	if (len > 2) {
		var tabName = $(this).closest('ul').find('li').eq(len-2).find('a').attr('id');
		var index = tabName.lastIndexOf('_');
		address_row = parseInt(tabName.substring(index+1)) + 1;
	}
	addCustomerAddressTab(address_row);

	$('#customer_address_' + address_row).click();
});

function getCustomerList(data) {
	var url = 'index.php?route=module/pos/getCustomerList&token=' + token;
	$.ajax({
		url: url,
		type: 'post',
		dataType: 'json',
		data: data ? data : {},
		beforeSend: function() {
			openWaitDialog();
		},
		complete: function() {
			closeWaitDialog();
		},
		success: function(json) {
			renderCustomerList(json);
			closeFancybox();
			openFancybox('#customer_list_dialog', 'wide');
		}
	});
};

function renderCustomerList(json) {
	// render the customer list
	var html = '';
	if (json['customers'] && json['customers'].length > 0) {
		var trClass = 'even';
		for (var i in json['customers']) {
			if (trClass == 'even') { trClass = 'odd' } else { trClass = 'even'; };
			html += '<tr class="' + trClass + '">';
			html += '<td class="two"><span class="skip_content label">' + column_customer_id + '</span>' + json['customers'][i]['customer_id'] + '</td>';
			html += '<td class="four"><label class="skip_content">' + column_customer_name + '</label>' + json['customers'][i]['name'] + '</td>';
			html += '<td class="five"><label class="skip_content">' + column_email + '</label>' + json['customers'][i]['email'] + '</td>';
			html += '<td class="six"><label class="skip_content">' + column_telephone + '</label>' + json['customers'][i]['telephone'] + '</td>';
			html += '<td class="seven"><label class="skip_content">' + column_date_added + '</label>' + json['customers'][i]['date_added'] + '</td>';
			html += '<td class="nine"><a onclick="selectCustomer(this, ' + json['customers'][i]['customer_id'] + ');" class="table-btn fbox_trigger_2"><span class="icon select"></span> ' + text_select + '</a></td>';
			html += '</tr>';
		}
	} else {
		html += '<tr><td align="center" colspan="6">' + text_no_results + '</td></tr>';
	}
	$('#customer_list_customers').html(html);
	$('#customer_list_pagination').html(json['pagination']);
};

function selectCustomerPage(page) {
	filterCustomer(page);
};

$('#customer_list_dialog input').keypress(function(e) {
	if (e.keyCode == $.ui.keyCode.ENTER) {
		filterCustomer();
	}
});

function filterCustomer(page) {
	var data = {};
	var filter_customer_id = $('input[name=\'filter_customer_id\']').val();
	if (filter_customer_id) {
		data['filter_customer_id'] = filter_customer_id;
	}
	var filter_customer_name = $('input[name=\'filter_customer_name\']').val();
	if (filter_customer_name) {
		data['filter_name'] = filter_customer_name;
	}
	var filter_customer_email = $('input[name=\'filter_customer_email\']').val();
	if (filter_customer_email != '') {
		data['filter_email'] = filter_customer_email;
	}	
	var filter_customer_telephone = $('input[name=\'filter_customer_telephone\']').val();
	if (filter_customer_telephone != '') {
		data['filter_telephone'] = filter_customer_telephone;
	}	
	var filter_customer_date = $('input[name=\'filter_customer_date\']').val();
	if (filter_customer_date) {
		data['filter_date_added'] = filter_customer_date;
	}
	if (page) {
		data['page'] = page;
	}
	
	getCustomerList(data);
};

function selectCustomer(anchor, customer_id) {
	// select the customer and populate the customer dialog
	var td = 0;
	var tdhtml = 0;
	if (anchor) {
		td = $(anchor).closest('td');
		tdhtml = td.html();
	}
	
	var url = 'index.php?route=module/pos/getCustomerAjax&token=' + token + '&customer_id=' + customer_id;
	$.ajax({
		url: url,
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			if (td) {
				td.html('<div><i class="fa fa-spinner fa-spin"></i> ' + text_load_order + '</div>');
			}
		},
		complete: function() {
			if (td) {
				td.find('div').remove();
				td.html(tdhtml);
			}
		},
		success: function(json) {
			// save the zones info that will be referred later
			if (json['customer_addresses']) {
				for (var i in json['customer_addresses']) {
					var address = json['customer_addresses'][i];
					if (address['zones']) {
						saved_zones[address['country_id']] = address['zones'];
					}
				}
			}
			// populate customer dialog
			populateCustomerDialog(json);
			
			$('input[name=customer_id]').val(customer_id);
			
			closeFancybox();
			openFancybox('#customer_dialog', 'wide');
		}
	});
};

function showProductDetails(product_id) {
	$.ajax({
		url: 'index.php?route=module/pos/getProductDetails&token=' + token + '&product_id='+product_id,
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			openWaitDialog(text_fetching_product_details);
		},
		complete: function() {
			closeWaitDialog();
		},
		success: function(json) {
			// display string attributes
			var dispay_attrs_string = ['name', 'sku', 'upc', 'model', 'cost', 'description', 'price', 'quantity', 'thumb', 'manufacturer', 'location', 'minimum'];
			for (i = 0; i < dispay_attrs_string.length; i++) {
				var value = json[dispay_attrs_string[i]] ? json[dispay_attrs_string[i]] : '';
				if ('thumb' == dispay_attrs_string[i]) {
					$('#product_details_thumb').attr('src', json['thumb']);
					$('#product_details_thumb').attr('alt', json['name']);
				} else {
					$('#product_details_' + dispay_attrs_string[i]).html($('<textarea />').html(value).text());
				}
			}
			
			// dispaly array attributes
			var html = ''
			if (json['product_options'] && json['product_options'].length > 0) {
				var trClass = 'even';
				for (var i = 0; i < json['product_options'].length; i++) {
					trClass = (trClass == 'even') ? 'odd' : 'even';
					html += '<tr class="' + trClass + '">';
					var option_value = '';
					var product_option = json['product_options'][i];
					if (product_option['type'] == 'text' ||
						product_option['type'] == 'textarea' ||
						product_option['type'] == 'file' ||
						product_option['type'] == 'date' ||
						product_option['type'] == 'datetime' ||
						product_option['type'] == 'time') {
						option_value = product_option['option_value'];
					} else if (product_option['type'] == 'select' || 
						product_option['type'] == 'radio' || 
						product_option['type'] == 'checkbox' || 
						product_option['type'] == 'image') {
						var product_option_id = parseInt(product_option['option_id']);
						if (json['option_values'][product_option_id]) {
							var option_value_value = json['option_values'][product_option_id];
							for (var k in option_value_value) {
								for (var j in product_option['product_option_value']) {
									var product_option_value = product_option['product_option_value'][j];
									if (product_option_value['option_value_id'] == option_value_value[k]['option_value_id']) {
										option_value += option_value_value[k]['name'] + '<br/>';
									}
								}
							}
						}
					}
					
					html += '<td>' + product_option['name'] + '</td><td>' + option_value + '</td><td>' + (product_option['required'] ? text_yes : text_no) + '</td></tr>';
				}
			}
			$('#product_details_options').html(html);
			openFancybox('#product_details_dialog', 'wide');
		}
	});
};

function deleteOrderProduct(anchor) {
	var index = $('#product tr').index($(anchor).closest('tr'));
	console.log('index: ' + index);
	var data = getSelectedPostData(index);
	// before remove, set the style for the rows
	for (var i = index+1; i < $('#product tr').length; i++) {
		if ($('#product tr:eq(' + i + ')').hasClass('odd')) {
			$('#product tr:eq(' + i + ')').removeClass('odd').addClass('even');
		} else {
			$('#product tr:eq(' + i + ')').removeClass('even').addClass('odd');
		}
	}
	$(anchor).closest('tr').remove();
	data['action'] = 'delete';
	checkAndSaveOrder(data);
};

function getSelectedPostData(index) {
	var data = {};
	$('#product tr:eq(' + index + ') input').each(function() {
		var attr_name = $(this).attr('name');
		var index_bracket = attr_name.indexOf(']');
		if (index_bracket > 0) {
			attr_name = attr_name.substring(index_bracket+2);
			var index_bracket = attr_name.indexOf(']');
			attr_name = attr_name.substring(0, index_bracket) + attr_name.substring(index_bracket+1);
		}
		
		data[attr_name] = $(this).val();
	});
	return data;
};

function showTotals() {
	openFancybox('#totals_details_dialog', 'narrow');
};

function updateTotal(updated_totals) {
	// update total from the discount value
	html = '';
	$("#total").empty();
	
	var total_text = formatMoney(0);
	
	var addCR = '';
	
	for (var i = 0; i < updated_totals.length; i++ ) {
		var total = updated_totals[i];
		
		var trClass = (i % 2 == 0) ? 'odd' : 'even';
		if (total['code'] == 'total') {
			total_text = formatMoney(total['value']);
			window['total'] = total['value'];
			trClass += ' total';
		}
		html += '<tr id="total-row' + i + '" class="' + trClass + '">';
		html += '  <td>' + total['title'] + ':</td>';
		html += '  <td>' + (total['text'] ? total['text'] : formatMoney(total['value'])) + addCR + '</td>';
		html += '</tr>';
	}
	
	$('#total').html(html);
	
	$('#payment_total span').text(total_text + addCR);
	
	// also update the js variable
	totals = updated_totals;

	// recalculate the due amount
	calcDueAmount();
};

function completeOrder() {
	saveOrderStatus(complete_status_id);
};

function makePayment() {
	// select cash payment if it's there, otherwise select the first payment type
	var hasCash = false;
	$('#payment_type option').each(function() {
		if ($(this).val() == 'cash') {
			hasCash = true;
			$('#payment_type').val('cash');
			$('#payment_type').trigger('change');
		}
	});
	if (!hasCash) {
		$('#payment_type').val($('#payment_type option:first').val());
		$('#payment_type').trigger('change');
	}
	openFancybox('#order_payments_dialog', 'wide');
};

function addPayment() {
	var amount = $('#tendered_amount').val();
	var dueAmount = $('#payment_due_amount').text();
	dueAmount = posParseFloat(dueAmount);
	if (dueAmount <= 0) {
		// nothing can be added
		return false;
	} else {
		// clear the cash display row
		$('#cash_display_tr').remove();
		cashList = [];
		// check if zero is in the text
		if (parseFloat(amount) == 0 && $('#payment_type').val() != 'purchase_order') {
			$('#tendered_amount').css('border', 'solid 2px #FF0000');
			$('#tendered_amount').attr('alt', text_payment_zero_amount);
			$('#tendered_amount').attr('title', text_payment_zero_amount);
			return false;
		} else {
			$('#tendered_amount').css('border', '');
			$('#tendered_amount').attr('alt', '');
			$('#tendered_amount').attr('title', '');
		}
	}
	
	processAddPayment(amount, '');
};

function processAddPayment(amount, noteAppend) {
	var note = $('#payment_note').val();
	if (noteAppend != '') {
		note += ' ' + noteAppend;
	}
	
	var type = $('#payment_type option:selected').text();
	var payment_type = $('#payment_type').val();
	
	var url = 'index.php?route=module/pos/addOrderPayment&token=' + token;
	var data = {'user_id':user_id, 'payment_type':type, 'payment_note':note, 'payment_code':payment_type };
	data['order_id'] = order_id;
	var dueAmount = calcDueAmount();
	if (parseFloat(amount) > dueAmount) {
		data['tendered_amount'] = dueAmount;
		data['change'] = (parseFloat(amount) - dueAmount);
	} else {
		data['tendered_amount'] = amount;
	}

	$.ajax({
		url: url,
		type: 'post',
		data: data,
		dataType: 'json',
		beforeSend: function() {
			openWaitDialog();
		},
		complete: function() {
			closeWaitDialog();
		},
		success: function(json) {
			if (json['error']) {
				openAlert(json['error']);
			}
			else {
				// translate the amount to money format
				// get rid of non digital first
				amount = parseFloat(amount);
				amount = formatMoney(amount);
				
				var curPaymentLen = $('#payment_list tr').length - 1;
				var trClass = (curPaymentLen % 2 == 0) ? 'odd' : 'even';
				var tr_element = '<tr id="order_payment_' + json['order_payment_id'] +'" class="' + trClass + '">';
				tr_element += '<td><span class="skip_content label">' + column_payment_type + ':</span>' + type + '</td>';
				tr_element += '<td><span class="skip_content label">' + column_payment_amount + ':</span>' + amount + '</td>';
				tr_element += '<td><span class="skip_content label"><span id="payment_note_text">' + column_payment_note + '</span>:</span>' + note + '</td>';
				tr_element += '<td class="action"><a class="table-btn table-btn-delete-2" onclick="deletePayment(this, \''+json['order_payment_id']+'\');"><span class="icon"></span>' + button_delete + '</a></td>';
				$(tr_element).insertAfter('#button_add_payment_tr');
				// clear the current inputs
				var totalDue = calcDueAmount();
				
				$('#tendered_amount').val(toFixed(totalDue, 2));
				$('#payment_type').val('cash');
				$('#payment_type').trigger('change');
				$('#payment_note').val('');
				
				resizeFancybox();
			}
		}
	});
}

function deletePayment(anchor, paymentId) {
	openConfirm(text_del_payment_confirm, function() {
		$.ajax({
			url: 'index.php?route=module/pos/deleteOrderPayment&token=' + token + '&order_payment_id='+paymentId,
			dataType: 'json',
			beforeSend: function() {
				openWaitDialog();
			},
			complete: function() {
				closeWaitDialog();
			},
			success: function(json) {
				if (json['error']) {
					openAlert(json['error']);
				}
				$('#order_payment_'+paymentId).remove();
				calcDueAmount();
				// reset click flag
				firstPaymentCashClick = true;
			}
		});
	});
};

function postPayment(post_status_id) {
	closeFancybox();
	saveOrderStatus(post_status_id);
};

var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();

$(document).on('keyup', 'input[name=filter_product]', function() {
	delay(function() {
		// search product when input product name in search field
		var filter_name = $('input[name=filter_product]').val();
		if (filter_name != '') {
			var url = 'index.php?route=module/pos/autocomplete_product&token=' + token;
			var data = {'filter_name':filter_name, 'customer_group_id':customer_group_id, 'filter_scopes':searchScopes};
			$.ajax({
				url: url,
				type: 'post',
				data: data,
				dataType: 'json',
				success: function(json) {
					if (json && json.length == 1) {
						// a single product
						$('input[name=current_product_id]').val(json[0]['product_id']);
						$('input[name=current_product_name]').val(json[0]['name']);
						$('input[name=current_product_hasOption]').val(json[0]['hasOptions']);
						$('input[name=current_product_price]').val(json[0]['price']);
						$('input[name=current_product_tax]').val(json[0]['tax']);
						$('input[name=current_product_points]').val(json[0]['points']);
						$('input[name=current_product_image]').val(json[0]['image']);
					}
					populateBrowseTable(json, true);
				}
			});
		}
	}, 500);
});

function populateBrowseTable(items, highlight, forReturn) {
	// save in memory for further use
	window['browse_items'] = items;

	$('#browse_list a').remove();
	var filter_name = $('input[name=filter_product]').val();
	
	var html = '';
	if (items) {
		for (var index in items) {
			if (items[index]['type'] == 'C') {
				html += '<a onclick="showCategoryItems(\'' + items[index]['category_id'] + '\')" class="product-box product-folder">';
				html += '	<span class="product-box-img">';
				html += '		<span class="product-box-frame-wrap">';
				html += '			<span class="product-box-frame">';
				html += '				<img src="' + items[index]['image'] + '"  alt="">';
				html += '			</span>';
				html += '			<span class="product-count">' + items[index]['total_items'] + '</span>';
				html += '		</span>'             	
				html += '	</span>';
				html += '	<span class="product-box-prod">';
				html += '		<span class="product-box-prod-title">' + items[index]['name'] + '</span>';
				html += '	</span>';
				html += '</a>';
			} else {
				html += '<a onclick="selectProduct(' + items[index]['product_id'] + ')" class="product-box product-item">';
				html += '	<span class="product-box-img">';
				html += '		<span class="product-box-frame-wrap">';
				html += '			<span class="product-box-frame">';
				html += '				<img src="' + items[index]['image'] + '"  alt="">';
				html += '			<span class="product-count">' + items[index]['stock'] + '</span>';
				html += '			</span>';
				html += '		</span>'             	
				html += '	</span>';
				html += '	<span class="product-box-prod">';
				html += '		<span class="product-box-prod-title">';
				html += (highlight ? highlightStr(items[index]['name'], filter_name) : items[index]['name']) + '<br />';
				// highlight all matched fields
				if (highlight) {
					for (var i in searchScopes) {
						if (searchScopes[i] == 'model' && items[index]['model'] && items[index]['model'].toLowerCase().indexOf(filter_name.toLowerCase()) >= 0) {
							html += text_search_model_short + highlightStr(items[index]['model'], filter_name) + '<br />';
						}
						if (searchScopes[i] == 'manufacturer' && items[index]['manufacturer'] && items[index]['manufacturer'].toLowerCase().indexOf(filter_name.toLowerCase()) >= 0) {
							html += text_search_manufacturer_short + highlightStr(items[index]['manufacturer'], filter_name) + '<br />';
						}
						if (searchScopes[i] == 'upc' && items[index]['upc'] && items[index]['upc'].toLowerCase().indexOf(filter_name.toLowerCase()) >= 0) {
							html += 'UPC: ' + highlightStr(items[index]['upc'], filter_name) + '<br />';
						}
						if (searchScopes[i] == 'sku' && items[index]['sku'] && items[index]['sku'].toLowerCase().indexOf(filter_name.toLowerCase()) >= 0) {
							html += 'SKU: ' + highlightStr(items[index]['sku'], filter_name) + '<br />';
						}
						if (searchScopes[i] == 'ean' && items[index]['ean'] && items[index]['ean'].toLowerCase().indexOf(filter_name.toLowerCase()) >= 0) {
							html += 'EAN: ' + highlightStr(items[index]['ean'], filter_name) + '<br />';
						}
						if (searchScopes[i] == 'mpn' && items[index]['mpn'] && items[index]['mpn'].toLowerCase().indexOf(filter_name.toLowerCase()) >= 0) {
							html += 'MPN: ' + highlightStr(items[index]['mpn'], filter_name) + '<br />';
						}
					}
				}
				html += '		</span>';
				html += '		<span class="product-box-prod-price">' + items[index]['price_text'] + '</span>';
				html += '	</span>';
				html += '</a>';
			}
		}
	}
	$('#browse_list').append(html);
};

function highlightStr(str, search) {
	// only highlight the first occurrence as it gives us the information info
	var highlighted = str;
	
	var index = str.toLowerCase().indexOf(search.toLowerCase());
	if (index >= 0) {
		highlighted = str.substring(0, index) + '<span style="background-color:red;color:yellow;">' + str.substring(index, index+search.length) + '</span>' + str.substring(index+search.length);
	}
	
	return highlighted;
};

function showCategoryItems(category_id) {
	var data = {'category_id':category_id, 'currency_code':currency_code, 'currency_value':currency_value, 'customer_group_id':customer_group_id};
	$.ajax({
		url: 'index.php?route=module/pos/getCategoryItemsAjax&token=' + token,
		type: 'post',
		dataType: 'json',
		data: data,
		beforeSend: function() {
			removeMessage();
			showMessage('notification', '');
		},
		complete: function() {
			removeMessage();
			showMessage('success', text_order_ready);
		},
		success: function(json) {
			// reset the product_id field for shortcut logic
			$('input[name=current_product_id]').val('0');
			
			$('#browse_category').empty();
			var ulhtml = '<li><a onclick="showCategoryItems(\'' + text_top_category_id + '\')" class="home-icon last"></a></li>';
			if (json['path']) {
				if (json['path'].length == 0) {
					$('input[name=current_category_id]').val(0);
					$('input[name=current_category_name]').val('');
					$('input[name=current_category_image]').val('');
				} else {
					ulhtml = '<li><a onclick="showCategoryItems(\'' + text_top_category_id + '\')" class="home-icon"></a></li>';
					for (var i = 0; i < json['path'].length; i++) {
						if (i == json['path'].length - 1) {
							$('input[name=current_category_id]').val(category_id);
							$('input[name=current_category_name]').val(json['path'][i]['name']);
							$('input[name=current_category_image]').val(json['path'][i]['image']);
							ulhtml += '<li><a onclick="showCategoryItems(\'' + json['path'][i]['id'] + '\')">' + json['path'][i]['name'] + '</a></li>';
						} else {
							ulhtml += '<li><a onclick="showCategoryItems(\'' + json['path'][i]['id'] + '\')" class="last">' + json['path'][i]['name'] + '</a></li>';
						}
					}
				}
			}
			$('#browse_category').html(ulhtml);
			if (json['browse_items']) {
				// clean up the display table
				populateBrowseTable(json['browse_items']);
			}
		}
	});
};

function selectProduct(product_id) {
	// check if previous option was not selected and another product is selected
	if ($('#option div').length > 0) {
		deQueue();
		$('#option').empty();
		product_add_option = [];
	}
	if (browseQ.length > 0) {
		enQueue(product_id);
	} else {
		enQueue('processing...');
		processSelectProduct(product_id);
	}
};

function getProduct(product_id) {
	var product;
	if (browse_items && browse_items.length > 0) {
		for (var i = 0; i < browse_items.length; i++) {
			if (browse_items[i]['type'] == 'P' && parseInt(browse_items[i]['product_id']) == product_id) {
				product =  browse_items[i];
				break;
			}
		}
	}
	return product;
};

function processSelectProduct(product_id) {
	var product = getProduct(product_id);
	if (product) {
		var hasOption = parseInt(product['hasOptions']);
		var product_name = product['name'];
		var price = (parseInt(config['config_tax']) == 1) ? parseFloat(product['price']) + parseFloat(product['tax']) : parseFloat(product['price']);
		var points = parseInt(product['points']);
		var has_sn = parseInt(product['has_sn']);
		var product_image = product['image'];
		var reward_points = product['reward_points'];
		var subtract = product['subtract'];
		
		// add the given product with the product_id
		$('#product_new input[name=quantity]').val('1');
		$('#product_new input[name=product_id]').val(product_id);
		$('#product_new input[name=product]').val(product_name);
		$('#product_new input[name=product_price]').val(price);
		$('#product_new input[name=product_points]').val(points);
		$('#product_new input[name=product_image]').val(product_image);
		$('#product_new input[name=product_reward_points]').val(reward_points);
		$('#product_new input[name=subtract]').val(subtract);

		if (hasOption) {
			$.ajax({
				url: 'index.php?route=module/pos/getProductOptions&token=' + token + '&product_id=' + product_id,
				type: 'post',
				dataType: 'json',
				data: {},
				beforeSend: function() {
					openWaitDialog();
				},
				complete: function() {
					closeWaitDialog();
				},
				success: function(json) {
					if (json && json['option_data']) {
						handleOptionReturn(product_name, product_id, json['option_data'], true);
					}
				}
			});
		} else {
			// no option
			chooseProduct();
		}
	}
};

function enQueue(product_id) {
	var data = {'product_id':product_id};
	browseQ.push(data);
};

function deQueue() {
	if (browseQ.length > 0) {
		var data = browseQ.shift();
		if (data['product_id'] == 'processing...') {
			data = browseQ.shift();
		}
		if (data) {
			processSelectProduct(data['product_id']);
		}
	}
};

var searchScopes = ['name'];

function searchSettings() {
	for (var i in searchScopes) {
		$('input[name=search_scope_' + searchScopes[i] + ']').prop('checked', true);
	}
	openFancybox('#search_settings_dialog', 'narrower');
};

function setSearchScope() {
	searchScopes = [];
	$('#search_settings_dialog input[type=checkbox]:checked').each(function() {
		var name = $(this).attr('name').substring(13);
		searchScopes.push(name);
	});
	
	if (searchScopes.length == 0) {
		searchScopes.push('name');
	}
	
	// save the searchScopes to the local storage
	localStorage.setItem('pos_searchScopes', JSON.stringify(searchScopes));
	
	closeFancybox();
};

function changeOrderComment() {
	$('textarea[name=order_comment]').val(comment);
	openFancybox('#order_comment_dialog', 'narrow');
};

function saveOrderComment() {
	var data = {'order_id': order_id, 'comment':$('textarea[name=order_comment]').val()};
	$.ajax({
		url: 'index.php?route=module/pos/saveOrderComment&token=' + token,
		type: 'post',
		data: data,
		dataType: 'json',
		beforeSend: function() {
			removeMessage();
			showMessage('notification', '');
		},
		success: function(json) {
			if (json['success']) {
				removeMessage();
				showMessage('success', json['success']);
			}
			comment = $('textarea[name=order_comment]').val();
			closeFancybox();
		}
	});
};

$('.clear_input').on('click', function() {
	$('.enter_amount input').val(0);
	$('.enter_amount span').remove();
});

$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
	$('.datetime').datetimepicker({
		dateFormat: 'yy-mm-dd',
		timeFormat: 'h:m'
	});
	$('.time').datetimepicker({	pickDate: false	});
	
	if (full_screen_mode == "0") {
		$('#header').show();
		$('.breadcrumb').show();
		$('#footer').show();
		$('#column-left').show();
	} else {
		$('#header').hide();
		$('.breadcrumb').hide();
		$('#footer').hide();
		$('#column-left').hide();
	}
	
	showMessage('success', text_order_ready);
	CheckSizeZoom();
	
	// get the search scopes from the local storage
	searchScopes = localStorage.getItem('pos_searchScopes');
	if (searchScopes) {
		searchScopes = JSON.parse(searchScopes);
	} else {
		searchScopes = ['name'];
	}
	
	if ($('.menu-toggle').length) {
		$('.menu-toggle').click(function () {
			$(this).toggleClass('clicked');
			return false;
		});	
	}
	
	if ($('.hide-show-nav').length) {
		var size=[];		
		$(".hide-show-nav").click(function(){
			$('.left-container').width() >= 230 ? size=[44] : size=[230];
			$('.left-container').animate({
				width: size[0]
			},0);	
			var lpad=[];	
			currentPadding = parseInt($('.main-container').css('padding-left'));			
			if(currentPadding >= 230) {
				lpad=[44];
				$('.left-container h2').css({'display':'none'});
			} else {
				lpad=[230];
				$('.left-container h2').css({'display':'block'});
			}
			$('.main-container').animate({
				paddingLeft: lpad[0]
			},0);
			return false;
		});
	}
	
	//--------- Right Container Height ---------	
	if ($('.right-container').length) {
		jQuery(window).resize(rightHeight);	
		if (!window.addEventListener) {
			window.attachEvent("orientationchange", rightHeight, false);
		}
		else {
			window.addEventListener("orientationchange", rightHeight, false);
		}
		rightHeight();
	}
	//--------- Product Container Height ---------	
	if ($('.right-container').length) {
		jQuery(window).resize(prodHeight);	
		if (!window.addEventListener) {
			window.attachEvent("orientationchange", prodHeight, false);
		}
		else {
			window.addEventListener("orientationchange", prodHeight, false);
		}
		prodHeight();
	}
	$('#work_mode_dropdown').selectmenu();
	
	//--------- Cart Container Height ---------	
	if ($('.right-container').length) {
		jQuery(window).resize(cartHeight);	
		if (!window.addEventListener) {
			window.attachEvent("orientationchange", cartHeight, false);
		}
		else {
			window.addEventListener("orientationchange", cartHeight, false);
		}
		cartHeight();
	}
	$('td.filter a').click(function () {
  		$("td.filter").siblings().slideToggle(400);
  		$('td.filter a').toggleClass('active');
  		return false;
 	});
	
	updateClock();
	setInterval(updateClock, 1000);
});

// new UI functions
function rightHeight(){
	$('.main-container').css('height', $(window).innerHeight()-$(".header").height());
};
function prodHeight(){
	$('.product-box-outer').css('height', $(window).innerHeight()-($(".header").height()+$(".prdct-header").height()+43));
};
function cartHeight(){
	$('.cart-outer-scroller').css('height', $(window).innerHeight()-($(".header").height()+$(".cart-title-bg").height()+$(".cart-footer").height()+63));
};

function showMessage(className, text) {
	$('.message').removeClass('success error notification').addClass(className);
	if (className == 'notification') {
		$('.message p').text(text_wait);
	} else {
		$('.message p').html(text);
	}
};

function removeMessage() {
	$('#order_message').empty();
};

$(document).on('focus', 'input[name=\'filter_customer\']', function(){
	$(this).autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=sale/customer/autocomplete&token=' + token + '&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				cacheCallback: function(json) {
					// dot not save the result for now as the info returned from opencart customer model is not complete
				},
				cachePreDone: function(cacheKey, callback) {
					backendGetCustomers(request.term, callback);
				},
				success: function(json) {
					response($.map(json, function(item) {
						return {
							category: item.customer_group,
							label: item.name,
							value: item.customer_id
						}
					}));
				}
			});
		},
		select: function(event, ui) {
			$('input[name=\'filter_customer\']').val(ui.item.label);
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
});

function handleOptionReturn(product_name, product_id, product_option, popup) {
	$('input[name=\'product\']').val(product_name);
	$('input[name=\'product_id\']').val(product_id);
	
	if (product_option) {
		product_add_option = product_option;
		
		html = '';

		for (var i = 0; i < product_option.length; i++) {
			var option = product_option[i];
			
			html += '<li id="option-' + option['product_option_id'] + '">';
			
			html += '<label>' + option['name'] + '';
			if (parseInt(option['required'])) {
				html += ' <em>*</em>';
			}
			html += '</label><div class="inputbox">';

			if (option['type'] == 'select' || option['type'] == 'radio' || option['type'] == 'image') {
				html += '<select name="option[' + option['product_option_id'] + '][product_option_value_id]" onchange="$(\'input[name=\\\'option[' + option['product_option_id'] + '][value]\\\']\').val($(this).find(\'option:selected\').text());$(\'input[name=\\\'option[' + option['product_option_id'] + '][price_prefix]\\\']\').val($(this).find(\'option:selected\').attr(\'price_prefix\'));$(\'input[name=\\\'option[' + option['product_option_id'] + '][price]\\\']\').val(posParseFloat($(this).find(\'option:selected\').attr(\'price\')));">';
				html += '<option value="">' + text_none + '</option>';
			
				for (j = 0; j < option['option_value'].length; j++) {
					option_value = option['option_value'][j];
					
					html += '<option value="' + option_value['product_option_value_id'] + '"';
					
					if (option_value['price']) {
						html += ' price_prefix="' + option_value['price_prefix'] + '"';
						html += ' price="' + option_value['price'] + '"';
						html += '>' + option_value['name'] + ' (' + option_value['price_prefix'] + option_value['price'] + ')';
					} else {
						html += ' price_prefix="+" price="0">' + option_value['name'];
					}
					
					html += '</option>';
				}
				html += '<input type="hidden" name="option[' + option['product_option_id'] + '][value]" value="" />';
				html += '<input type="hidden" name="option[' + option['product_option_id'] + '][price_prefix]" value="+" />';
				html += '<input type="hidden" name="option[' + option['product_option_id'] + '][price]" value="0" />';
					
				html += '</select>';
			} else if (option['type'] == 'checkbox') {
				for (j = 0; j < option['option_value'].length; j++) {
					option_value = option['option_value'][j];
					
					html += '<input type="checkbox" name="option_checkbox"';
					if (option_value['price']) {
						html += ' price_prefix="' + option_value['price_prefix'] + '" price="' + option_value['price'] + '"';
					} else {
						html += ' price_prefix="+" price="0"';
					}
					html += ' value="' + option_value['name'] + '" id="option-value-' + option_value['product_option_value_id'] + '" onchange="if ($(this).is(\':checked\')) {$(\'input[name=\\\'option['+option['product_option_id']+'][value]\\\']\').val($(\'input[name=\\\'option['+option['product_option_id']+'][value]\\\']\').val()+'+option_value['product_option_value_id']+'+\'|\');$(\'input[name=\\\'option['+option['product_option_id']+'][product_option_value_id]['+option_value['product_option_value_id']+'][value]\\\']\').val($(this).val());$(\'input[name=\\\'option['+option['product_option_id']+'][product_option_value_id]['+option_value['product_option_value_id']+'][price_prefix]\\\']\').val($(this).attr(\'price_prefix\'));$(\'input[name=\\\'option['+option['product_option_id']+'][product_option_value_id]['+option_value['product_option_value_id']+'][price]\\\']\').val(posParseFloat($(this).attr(\'price\')));} else {$(\'input[name=\\\'option['+option['product_option_id']+'][value]\\\']\').val($(\'input[name=\\\'option['+option['product_option_id']+'][value]\\\']\').val().replace('+option_value['product_option_value_id']+'+\'|\', \'\'));$(\'input[name=\\\'option['+option['product_option_id']+'][product_option_value_id]['+option_value['product_option_value_id']+'][value]\\\']\').val(\'\');$(\'input[name=\\\'option['+option['product_option_id']+'][product_option_value_id]['+option_value['product_option_value_id']+'][price_prefix]\\\']\').val(\'+\');$(\'input[name=\\\'option['+option['product_option_id']+'][product_option_value_id]['+option_value['product_option_value_id']+'][price]\\\']\').val(\'0\');} "/>';
					html += ' ' + option_value['name'];
					
					if (option_value['price']) {
						html += ' (' + option_value['price_prefix'] + option_value['price'] + ')';
					}
					html += '<br/>';

					html += '<input type="hidden" name="option[' + option['product_option_id'] + '][product_option_value_id]['+option_value['product_option_value_id']+'][price_prefix]" value="+" />';
					html += '<input type="hidden" name="option[' + option['product_option_id'] + '][product_option_value_id]['+option_value['product_option_value_id']+'][price]" value="0" />';
					html += '<input type="hidden" name="option[' + option['product_option_id'] + '][product_option_value_id]['+option_value['product_option_value_id']+'][value]" value="" />';
				}
				
				html += '<input type="hidden" name="option[' + option['product_option_id'] + '][value]" value="" />';
			} else if (option['type'] == 'text') {
				html += '<input type="hidden" name="option[' + option['product_option_id'] + '][product_option_value_id]" value="0" />';
				html += '<input type="text" name="option[' + option['product_option_id'] + '][value]" value="' + option['option_value'] + '" />';
			} else if (option['type'] == 'textarea') {
				html += '<input type="hidden" name="option[' + option['product_option_id'] + '][product_option_value_id]" value="0" />';
				html += '<textarea name="option[' + option['product_option_id'] + '][value]" cols="40" rows="5">' + option['option_value'] + '</textarea>';
			} else if (option['type'] == 'date') {
				html += '<input type="hidden" name="option[' + option['product_option_id'] + '][product_option_value_id]" value="0" />';
				html += '<input type="text" name="option[' + option['product_option_id'] + '][value]" value="' + option['option_value'] + '" class="date" />';
			} else if (option['type'] == 'datetime') {
				html += '<input type="hidden" name="option[' + option['product_option_id'] + '][product_option_value_id]" value="0" />';
				html += '<input type="text" name="option[' + option['product_option_id'] + '][value]" value="' + option['option_value'] + '" class="datetime" />';
			} else if (option['type'] == 'time') {
				html += '<input type="hidden" name="option[' + option['product_option_id'] + '][product_option_value_id]" value="0" />';
				html += '<input type="text" name="option[' + option['product_option_id'] + '][value]" value="' + option['option_value'] + '" class="time" />';
			}
			html += '<input type="hidden" name="option[' + option['product_option_id'] + '][product_option_id]" value="' + option['product_option_id'] + '" />';
			html += '<input type="hidden" name="option[' + option['product_option_id'] + '][name]" value="' + option['name'] + '" />';
			html += '<input type="hidden" name="option[' + option['product_option_id'] + '][type]" value="' + option['type'] + '" />';
		}
		
		html += '</div></li>';
		$('#option').append(html);
		
		if (popup) {
			popupOption($('#option').html());
		}

		// not going to support file upload (the part has been removed)
		
		$('.date').datepicker({dateFormat: 'yy-mm-dd'});
		$('.datetime').datetimepicker({
			dateFormat: 'yy-mm-dd',
			timeFormat: 'h:m'
		});
		$('.time').datetimepicker({	pickDate: false	});
	} else {
		$('#option').empty();
		product_add_option = [];
	}
};

function popupOption(html) {
	var popup_dialog = '<div id="popup_option_dialog" class="fbox_cont popup_option"><h3>' + entry_option.substring(0, entry_option.length) + '</h3><div class="table-container form-box"><ul class="form_list">' + html + '</ul></div>';
	popup_dialog += '<div class="fbox_btn_wrap"><a onclick="addProductWithOption();" class="table-btn-common">' + button_add_product + '</a></div></div>';
	openFancybox(false, 'narrow', popup_dialog, clearOptionMessage);
};

function clearOptionMessage() {
	deQueue();
	removeMessage();
	showMessage('success', text_order_ready);
}

function addProductWithOption() {
	var formData = {};
	var productFormData = '#product_new input[type=\'text\'], #product_new input[type=\'hidden\'], #product_new input[type=\'radio\']:checked, #product_new input[type=\'checkbox\']:checked, #product_new select, #product_new textarea';
	$(productFormData).each(function() {
		formData[$(this).attr('name')] = $(this).val();
	});
	var add_option = {};
	for (var i in formData) {
		if (i.substring(0, 7) == 'option[') {
			add_option[i] = formData[i];
		}
	}
	var option_error = [];
	for (var i in product_add_option) {
		if (product_add_option[i]['required']) {
			var option_attr_name = 'option[' + product_add_option[i]['product_option_id'] + '][value]';
			if (!add_option[option_attr_name]) {
				option_error[product_add_option[i]['product_option_id']] = product_add_option[i]['name'];
			}
		}
	}
	
	$('.has-error').each(function() {
		$(this).find('.text-danger').remove();
		$(this).removeClass('has-error');
	});
	if (!$.isEmptyObject(option_error)) {
		var errorSize = 0;
		for (var i in option_error) {
			$('#popup_option_dialog #option-' + i).addClass('has-error');
			$('#popup_option_dialog #option-' + i).find('div').append('<div class="text-danger">' + error_required.replace('%s', option_error[i]) + '</div>');
			errorSize ++;
		}
		resizeFancybox();
	} else {
		$('#button_product').trigger('click');
		closeFancybox();
	}
};

$(document).on('change', '#popup_option_dialog input, #popup_option_dialog textarea, #popup_option_dialog select', function() {
	// change on the dialog will be reflected to the fields on the page
	var type = $(this).prop('tagName').toLowerCase();
	var name = $(this).attr('name');
	var value = $(this).val();
	$("#option " + type + "[name='" + name + "']").val(value);
	if (type == 'select') {
		$("#option " + type + "[name='" + name + "']").trigger('change');
	}
});

function refreshPage() {
	selectOrder(0, 0);
};

$(document).on('click', '#button_product', function() {
	chooseProduct();
});

function chooseProduct() {
	var product_id = parseInt($('input[name=product_id]').val());
	if (isNaN(product_id) || product_id == 0) {
		openAlert(text_not_valid_product);
		return;
	}
	var formData = {};
	var productFormData = '#product_new input[type=\'text\'], #product_new input[type=\'hidden\'], #product_new input[type=\'radio\']:checked, #product_new input[type=\'checkbox\']:checked, #product_new select, #product_new textarea';
	$(productFormData).each(function() {
		formData[$(this).attr('name')] = $(this).val();
	});
	var add_option = {};
	for (var i in formData) {
		if (i.substring(0, 7) == 'option[') {
			add_option[i] = formData[i];
		}
	}
	// check if the required option has value set
	var option_error = [];
	for (var i in product_add_option) {
		var option_attr_name = 'option[' + product_add_option[i]['product_option_id'] + '][value]';
		if (!add_option[option_attr_name]) {
			if (parseInt(product_add_option[i]['required'])) {
				option_error[product_add_option[i]['product_option_id']] = product_add_option[i]['name'];
			}
			// remove options without value
			var option_remove_prefix = 'option[' + product_add_option[i]['product_option_id'] + ']';
			for (var remove_index in add_option) {
				if (remove_index.substring(0, option_remove_prefix.length) == option_remove_prefix) {
					delete add_option[remove_index];
				}
			}
		}
	}
	
	$('.has-error').each(function() {
		$(this).find('.text-danger').remove();
		$(this).removeClass('has-error');
	});
	if (option_error.length > 0) {
		for (var i in option_error) {
			$('#option-' + i).addClass('has-error');
			$('#option-' + i).find('div').append('<div class="text-danger">' + error_required.replace('%s', option_error[i]) + '</div>');
		}
		return false;
	}

	var add_quantity = parseInt($('input[name=quantity]').val());
	var add_price = parseFloat($('input[name=product_price]').val());
	var subtract = parseInt($('input[name=subtract]').val());
	var name = $('input[name=product]').val();
	var image = $('input[name=product_image]').val();
	var data = {'action':'insert', 'product_id':product_id, 'option':add_option, 'quantity':add_quantity, 'price':add_price, 'subtract':subtract, 'name':name, 'image':image, 'weight':1};
	addProduct(data);
}

function addProduct(add_data) {
	// update the order product list, using the stored products object
	var data = add_data;
	var product_id = data['product_id'];
	var tax_class_id = 0;
	var shipping = 0;
	var ref_product = getProduct(product_id);
	if (ref_product) {
		tax_class_id = ref_product['tax_class_id'];
		shipping = ref_product['shipping'];
	}
	var add_option = data['option'];
	var product_exists = false;
	var index = 0;
	// use weight string to compare product existing
	var weight = 1;
	
	var orderFormData = {};
	$('#product input').each(function() {
		orderFormData[$(this).attr('name')] = $(this).val();
	});
	var order_products = {};
	for (var i in orderFormData) {
		if (i.substring(0,14) == 'order_product[') {
			order_products[i] = orderFormData[i];
		}
	}

	var index_list = [];
	for (var i in order_products) {
		var i_suffix = '[product_id]';
		if (i.length > i_suffix.length && i.substring(i.length-i_suffix.length) == i_suffix && order_products[i] == product_id) {
			var index_bracket_begin = i.indexOf('[');
			var index_bracket_end = i.indexOf(']');
			index = i.substring(index_bracket_begin+1, index_bracket_end);
			product_exists = true;
			index_list.push(index);
		}
	}

	if (product_exists && product_add_option.length > 0) {
		// compare if option is the same
		var order_product_option_min_size = 1;
		
		for (var index_i in index_list) {
			var order_product_option_size = 0;
			for (var i in order_products) {
				var i_prefix = 'order_product['+index_list[index_i]+'][order_option]';
				if (i.substring(0, i_prefix.length) == i_prefix) {
					order_product_option_size++;
				}
			}
			if (order_product_option_size < order_product_option_min_size) {
				order_product_option_min_size = order_product_option_size;
				index = index_list[index_i];
			}
		}
		
		var add_option_size = 0;
		for (var i in add_option) { add_option_size++; }
		
		if (add_option_size == 0) {
			if (order_product_option_min_size > 0) {
				product_exists = false;
			}
		} else {
			product_exists = false;
			for (var index_i in index_list) {
				index = index_list[index_i];
				
				// match add_option with order_products[index]
				var add_option_matched_count = 0;
				var add_option_number = 0;
				for (var i in product_add_option) {
					var add_option_key = 'option[' + product_add_option[i]['product_option_id'] + '][name]';
					var type = product_add_option[i]['type'];
					if (add_option[add_option_key]) {
						if (type == 'select' || type == 'radio' || type == 'image') {
							add_option_number++;
							var add_product_option_value_id = add_option['option[' + product_add_option[i]['product_option_id'] + '][product_option_value_id]'];
							var ext_product_option_value_id = order_products['order_product['+index+'][order_option][' + product_add_option[i]['product_option_id'] + '][product_option_value_id]'];
							if (add_product_option_value_id == ext_product_option_value_id) {
								add_option_matched_count++;
							}
						} else if (type == 'checkbox') {
							if (product_add_option[i]['option_value']) {
								for (var j in product_add_option[i]['option_value']) {
									var product_option_value_id = product_add_option[i]['option_value'][j]['product_option_value_id'];
									var add_option_value_key = 'option[' + product_add_option[i]['product_option_id'] + '][product_option_value_id][' + product_option_value_id + '][value]';
									if (add_option[add_option_value_key]) {
										add_option_number++;
										if (order_products['order_product['+index+'][order_option][' + product_add_option[i]['product_option_id'] + '][product_option_value_id][' + product_option_value_id + ']']) {
											add_option_matched_count++;
										}
									}
								}
							}
						} else {
							add_option_number++;
							var add_option_value = add_option['option[' + product_add_option[i]['product_option_id'] + '][value]'];
							var ext_option_value = order_products['order_product['+index+'][order_option][' + product_add_option[i]['product_option_id'] + '][value]'];
							if (add_option_value == ext_option_value) {
								add_option_matched_count++;
							}
						}
					}
				}
				// match order_products[index] with add_option
				var order_product_matched_count = 0;
				var order_product_option_number = 0;
				for (var i in product_add_option) {
					var order_product_option_key = 'order_product['+index+'][order_option][' + product_add_option[i]['product_option_id'] + '][product_option_id]';
					var type = product_add_option[i]['type'];
					if (order_products[order_product_option_key]) {
						if (type == 'select' || type == 'radio' || type == 'image') {
							order_product_option_number++;
							var add_product_option_value_id = add_option['option[' + product_add_option[i]['product_option_id'] + '][product_option_value_id]'];
							var ext_product_option_value_id = order_products['order_product['+index+'][order_option][' + product_add_option[i]['product_option_id'] + '][product_option_value_id]'];
							if (add_product_option_value_id == ext_product_option_value_id) {
								order_product_matched_count++;
							}
						} else if (type == 'checkbox') {
							if (product_add_option[i]['option_value']) {
								for (var j in product_add_option[i]['option_value']) {
									var product_option_value_id = product_add_option[i]['option_value'][j]['product_option_value_id'];
									var order_product_option_value_key = 'order_product['+index+'][order_option][' + product_add_option[i]['product_option_id'] + '][product_option_value_id][' + product_option_value_id + ']';
									if (order_products[order_product_option_value_key]) {
										order_product_option_number++;
										if (add_option['option[' + product_add_option[i]['product_option_id'] + '][product_option_value_id][' + product_option_value_id + '][value]']) {
											order_product_matched_count++;
										}
									}
								}
							}
						} else {
							order_product_option_number++;
							var add_option_value = add_option['option[' + product_add_option[i]['product_option_id'] + '][value]'];
							var ext_option_value = order_products['order_product['+index+'][order_option][' + product_add_option[i]['product_option_id'] + '][value]'];
							if (add_option_value == ext_option_value) {
								order_product_matched_count++;
							}
						}
					}
				}
				if (add_option_matched_count == add_option_number && order_product_matched_count == order_product_option_number) {
					product_exists = true;
					break;
				}
			}
		}
	}
	
	var add_quantity = data['quantity'];
	var add_price = data['price'];
	var add_price_text = formatMoney(add_price);
	var add_total_text = formatMoney(add_quantity * add_price * weight);
	
	if (product_exists) {
		// update the quantity and total for the existing row
		var ex_quantity = parseInt($('#product tr:eq('+index+') input[name$=\'[quantity]\']').val());
		var ex_price = posParseFloat($('#price_anchor_'+index).text().substring(2));
		
		$('#quantity_anchor_'+index).text(ex_quantity + add_quantity);
		$('#product tr:eq('+index+') input[name$=\'[quantity]\']').val(ex_quantity + add_quantity);
		
		var text_total = formatMoney((ex_quantity + add_quantity) * ex_price * weight);
		$('#total_text_only-'+index).text(text_total);
		$('#total_text_only-'+index).closest('td').find('input').val(text_total);
		
		data['action'] = 'modify_quantity';
		data['index'] = index;
		data['quantity_before'] = ex_quantity;
		data['quantity_after'] = ex_quantity + add_quantity;
		data['order_product_id'] = $('#product tr:eq('+index+') input[name$=\'[order_product_id]\']').val();
	} else {
		// add a new row
		var new_row_num = $('#product tr').length;
		new_row_id = 'product-row' +  new_row_num;
		html = '<tr id="' + new_row_id + '" class="' + ((new_row_num % 2 == 0) ? 'even' : 'odd') + '">';
		html += '<input type="hidden" name="order_product[' + new_row_num + '][order_product_id]" value="" />';
		html += '<input type="hidden" name="order_product[' + new_row_num + '][product_id]" value="' + product_id + '" /></td>';
		html += '<input type="hidden" name="order_product[' + new_row_num + '][quantity]" value="' + add_quantity + '" />';
		html += '<input type="hidden" name="order_product[' + new_row_num + '][tax_class_id]" value="' + tax_class_id + '" />';
		html += '<input type="hidden" name="order_product[' + new_row_num + '][shipping]" value="' + shipping + '" />';
		html += '<input type="hidden" name="order_product[' + new_row_num + '][subtract]" value="' + add_data['subtract'] + '" />';
		html += '<td align="center" valign="middle" class="one"><span class="cart-round-img-outr" onclick="changeQuantity(this);"><img src="' + data['image'] + '" class="cart-round-img" alt=""><a class="cart-round-qty" id="quantity_anchor_' + new_row_num + '">' + add_quantity + '</a></span></td>';
		html += '<td align="left" valign="middle" class="two">';
		html += '	<span class="product-name">';
		html += '		<span class="raw-name" onclick="showProductDetails(' + product_id + ')" id="order_product[' + new_row_num + '][order_product_display_name]">' + data['name'] + '</span>';
		for (var j in product_add_option) {
			var add_option_key = 'option[' + product_add_option[j]['product_option_id'] + '][name]';
			if (add_option[add_option_key]) {
				var name = product_add_option[j]['name'];
				var type = product_add_option[j]['type'];
				var product_option_value_id = parseInt(add_option['option[' + product_add_option[j]['product_option_id'] + '][product_option_value_id]']);
				var value = '';
				var product_option_value_ids = [];
				if (type == 'select' || type == 'radio' || type == 'image') {
					for (var k in product_add_option[j]['option_value']) {
						if (product_option_value_id == product_add_option[j]['option_value'][k]['product_option_value_id']) {
							value = product_add_option[j]['option_value'][k]['name'];
							break;
						}
					}
				} else if (type == 'checkbox') {
					for (var k in product_add_option[j]['option_value']) {
						var l_key = 'option[' + product_add_option[j]['product_option_id'] + '][product_option_value_id][' + product_add_option[j]['option_value'][k]['product_option_value_id'] + '][value]';
						if (add_option[l_key]) {
							product_option_value_ids[product_add_option[j]['option_value'][k]['product_option_value_id']] = add_option[l_key];
							value += add_option[l_key] + ', ';
						}
					}
				} else {
					value = add_option['option[' + product_add_option[j]['product_option_id'] + '][value]'];
				}
				
				if (value != '') {
					if (type == 'checkbox') {
						for (var l in product_option_value_ids) {
							html +=		' <input type="hidden" name="order_product[' + new_row_num + '][order_option][' + product_add_option[j]['product_option_id'] + '][product_option_value_id]['+l+']" value="' + product_option_value_ids[l] + '" />';
						}
						value = value.substring(0, value.length-2);
					} else {
						html +=		' <input type="hidden" name="order_product[' + new_row_num + '][order_option][' + product_add_option[j]['product_option_id'] + '][product_option_value_id]" value="' + product_option_value_id + '" />';
					}
					html +=		'<br />&nbsp;<small> - ' + name + ': ' + value + '</small>';
					html +=		' <input type="hidden" name="order_product[' + new_row_num + '][order_option][' + product_add_option[j]['product_option_id'] + '][product_option_id]" value="' + product_add_option[j]['product_option_id'] + '" />';
					html +=		' <input type="hidden" name="order_product[' + new_row_num + '][order_option][' + product_add_option[j]['product_option_id'] + '][type]" value="' + type + '" />';
					html +=		' <input type="hidden" name="order_product[' + new_row_num + '][order_option][' + product_add_option[j]['product_option_id'] + '][value]" value="' + value + '" />';
				}
			}
		}
		html +=		' <input type="hidden" name="order_product[' + new_row_num + '][weight_price]" value="1" />';
		html +=		' <input type="hidden" name="order_product[' + new_row_num + '][weight]" value="' + weight + '" />';
		html += '	</span>';
		html += '	<span class="highlight"><a id="price_anchor_' + new_row_num + '">@ ' + add_price_text + '</a></span>';
		html += '</td>';
		html += '<input type="hidden" name="order_product[' + new_row_num + '][price]" value="' + add_price + '" />';
		html += ' <input type="hidden" name="order_product[' + new_row_num + '][product_discount_type]" value="0" />';
		html += ' <input type="hidden" name="order_product[' + new_row_num + '][product_discount_value]" value="0" />';
		html += '<td align="center" valign="middle" class="three"><a class="cart-link"><span class="product-price" id="total_text_only-' + new_row_num + '">' + add_total_text + '</span></a>';
		html += '<input type="hidden" name="order_product[' + new_row_num + '][product_total_text]" value="' + add_total_text + '" /></td>';
		html += '<td align="center" valign="middle" class="four"><a class="delete" onclick="deleteOrderProduct(this)"></a></td>';
		html += '</tr>';
		
		$('#product').append(html);
	}
	
	var scrollIndex = data['index'] ? data['index'] : $('#product tr').length-1;
	// scroll to the updating / adding row
	var divHeight = $('#product').closest('div').height();
	var scrollTop = 0;
	for (var i = 0; i < scrollIndex; i++) {
		scrollTop += $('#product tr:eq('+i+')').height();
	}
	var scrollBottom = scrollTop + $('#product tr:eq('+scrollIndex+')').height();
	var curPosition = $('#product').closest('div').scrollTop();
	if (curPosition > scrollTop || curPosition + divHeight < scrollBottom) {
		$('#product').closest('div').scrollTop(scrollTop);
	}

	$('input[name=\'product\']').val('');
	$('input[name=\'product_id\']').val('');
	$('input[name=\'product_image\']').val('');
	$('#option').empty();
	$('input[name=\'quantity\']').val('1');
	product_add_option = [];
		
	// send asynchronous request to get total and save order
	for (var i in add_option) {
		data[i] = add_option[i];
	}
	data['option'] = [];
	data['weight'] = weight;
	checkAndSaveOrder(data);
};

function checkAndSaveOrder(data) {
	// for all actions, data will contain order_id and customer_group_id
	// action can be insert, insert_quick, delete, modify_quantity, modify_price
	// for insert, data contains the product id and options (product_option_id, production_option_value_id, value and type), quantity, product sn, product sn id
	// for insert_quick, data contains the product name, model, price, is_tax_included, quantity
	// for delete, data contains the order_product_id, product_id, quantity and options
	// for modify_quantity, data contains the order_product_id, before quantity and after quantity, product_id and options
	// for modify_price, data contains the order_product_id, before price and after price
	data['order_id'] = order_id;
	data['customer_id'] = customer_id;
	data['customer_group_id'] = customer_group_id;
	data['shipping_country_id'] = shipping_country_id;
	data['shipping_zone_id'] = shipping_zone_id;
	data['payment_country_id'] = payment_country_id;
	data['payment_zone_id'] = payment_zone_id;
	data['currency_code'] = currency_code;
	data['currency_value'] = currency_value;
	// update the browse table to change the product quantity
	if (data['subtract'] && parseInt(data['subtract']) > 0 && (data['action'] == 'insert' || data['action'] == 'modify_quantity' || data['action'] == 'delete')) {
		var qtyChange = 0;
		if (data['action'] == 'insert') {
			qtyChange = data['quantity'];
		} else if (data['action'] == 'modify_quantity') {
			qtyChange = data['quantity_after'] - data['quantity_before'];
		} else if (data['action'] == 'delete') {
			qtyChange = 0 - data['quantity'];
		}
		
		$('#browse_list a').each(function() {
			var onclick = 'selectProduct(' + data['product_id'] + ')';
			if ($(this).attr('onclick') == onclick) {
				var curStock = parseInt($(this).find('.product-count').text());
				$(this).find('.product-count').text(curStock-qtyChange);
			}
		});
	}
	$.ajax({
		url: 'index.php?route=module/pos/check_and_save_order&token=' + token,
		type: 'post',
		data: data,
		dataType: 'json',	
		beforeSend: function() {
			// removeMessage();
			// showMessage('notification', '');
		},
		success: function(json) {
			// Check for errors
			if (json['error']) {
				removeMessage();

				// Products
				if (json['error']['stock']) {
					showMessage('error', json['error']['stock']);
				}
			}
			deQueue();
			
			if (json['success']) {
				if (json['order_total']) {
					updateTotal(json['order_total']);
				} else {
					html  = '</tr>';
					html += '  <td colspan="5" class="center">' + text_no_results + '</td>';
					html += '</tr>';	

					$('#total').html(html);					
				}
				calcDueAmount();

				if (data['action'] == 'insert') {
					// get the generated order_product_id and assign it back to the order product on the page
					var new_row_index = $('#product tr').length-1;
					$('input[name=\'order_product[' + new_row_index + '][order_product_id]\']').val(json['order_product_id']);
					if (json['text_price'] && json['text_total']) {
						$('#price_anchor_' + new_row_index).text('@ ' + json['text_price']);
						$('input[name=\'order_product[' + new_row_index + '][product_total_text]\']').val(json['text_total']);
						$('#total_text_only-' + new_row_index).text(json['text_total']);
					}
				}
				removeMessage();
				showMessage('success', json['success']);
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			openAlert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});	
}

function toggleFullScreen() {
	$('#header').toggle();
	$('.breadcrumb').toggle();
	$('#footer').toggle();
	$('#column-left').toggle();
	full_screen_mode = 1 - full_screen_mode;
	if (full_screen_mode) {
		$('.menu-toggle').css('top', '5px');
	} else {
		$('.menu-toggle').css('top', '50px');
	}
};

var first_click_numeric_key = true;

$(document).on('keydown', 'input[name=changed_quantity]', function(event) {
	amountInputOnly(event);
});

function changeQuantity(div) {
	var quantity = $(div).find('a').text();
	var index = $('#product tr').index($(div).closest('tr'));
	$('input[name=org_quantity]').val(quantity);
	$('input[name=quantity_index]').val(index);
	$('input[name=changed_quantity]').val(quantity);
	first_click_numeric_key = true;
	openFancybox('#quantity_dialog', 'narrower');
};

function handleInplaceQuantity() {
	var newQuantity = parseInt($('input[name=changed_quantity]').val());
	var orgQuantity = parseInt($('input[name=org_quantity]').val());
	if (newQuantity == 0 || isNaN(newQuantity)) {
		openAlert(text_quantity_invalid);
		return;
	} else if (newQuantity != orgQuantity) {
		// change the total text
		var index = $('input[name=quantity_index]').val();
		processInplaceQuantity(index, orgQuantity, newQuantity);
	}
};

function processInplaceQuantity(index, orgQuantity, newQuantity) {
	$('#quantity_anchor_' + index).text(newQuantity);
	var td_total = $('#price_anchor_' + index).closest('tr').find('td:nth-last-child(2)');
	var ex_price = posParseFloat($('#price_anchor_'+index).text().substring(2));
	var weight = 1;
	if (parseInt($('input[name="order_product[' + index + '][weight_price]"]').val()) == 1) {
		weight = parseFloat($('input[name="order_product[' + index + '][weight]"]').val());
	}
	var text_total = formatMoney(newQuantity * ex_price * weight);
	
	if (td_total.find('span').length > 0) {
		td_total.find('span').text(text_total);
	} else {
		td_total.text(text_total);
	}
	td_total.find('input').val(text_total);

	var data = getSelectedPostData(index);
	data['action'] = 'modify_quantity';
	data['index'] = index;
	data['quantity_before'] = orgQuantity;
	data['quantity_after'] = newQuantity;
	$('#product tr:eq('+index+') input[name$=\'[quantity]\']').val(newQuantity);

	checkAndSaveOrder(data);
	closeFancybox();
};

$('.keypad_wrap .btn').click(function() {
	var parent = $(this).closest('div').attr('id');
	var key = $(this).text();
	
	var input = $('input[name=changed_quantity]');
	var currValue = input.val();

	if (key == 'Clear' || key == 'C') {
		input.val('0');
	} else if (key == 'OK') {
		handleInplaceQuantity();
	} else if (currValue == '0' || currValue == '0.00' || currValue == '') {
		currValue = '0';
		if (key == '.') {
			input.val(currValue+key);
		} else if (key.length == 1 && key != '0') {
			input.val(key);
		}
		if (first_click_numeric_key) {
			first_click_numeric_key = false;
		}
	} else {
		if (first_click_numeric_key) {
			first_click_numeric_key = false;
			if (key == '.') {
				input.val('0.');
			} else {
				input.val(key);
			}
		} else {
			if (key == '.') {
				if (currValue.indexOf('.') < 0) {
					input.val(currValue+key);
				}
			} else if (key.length == 1) {
				if (currValue.indexOf('.') > 0) {
					if (currValue.substring(currValue.indexOf('.')+1).length < 2) {
						input.val(currValue+key);
					}
				} else {
					input.val(currValue+key);
				}
			}
		}
	}
});

$(document).on('keydown', '#tendered_amount', function(event) {
	amountInputOnly(event);
});

function amountInputOnly(event) {
	// Allow: backspace, delete, tab, escape, and enter
	if ( event.keyCode == 46 || event.keyCode == 110 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || event.keyCode == 190 ||
		 // Allow: Ctrl+A
		(event.keyCode == 65 && event.ctrlKey === true) ||
		 // Allow: home, end, left, right
		(event.keyCode >= 35 && event.keyCode <= 39)) {
		// let it happen, don't do anything
		return;
	} else {
		// Ensure that it is a number and stop the keypress
		if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
			event.preventDefault(); 
		}
	}
};

function calcDueAmount() {
	// count the total quantity
	var totalQuantity = 0;
	for (i = 0; i < $('#product tr').length; i++) {
		totalQuantity += parseInt($('#product tr:eq('+i+') input[name$="[quantity]"]').val());
	}
	$('#items_in_cart').text(totalQuantity);
	
	var totalText = $('#payment_total').find('span').text();
	totalAmount = posParseFloat(totalText);

	var totalPaid = 0;
	$('#payment_list tr:gt(0)').each(function() {
		// ignore the first line
		var rowAmount = $(this).find('td').eq(1).text();
		rowAmount = posParseFloat(rowAmount);
		totalPaid += rowAmount;
	});
	totalDue = totalAmount - totalPaid;
	
	if (totalDue < 0) {
		$('#payment_change').find('span').text(formatMoney(0-totalDue));
		// update dialog change amount
		$('#dialog_change_text').text(formatMoney(0-totalDue));
		
		$('#payment_due_amount').text(formatMoney(0));
		// update dialog due amount
		$('#dialog_due_amount_text').text(formatMoney(0));
		$('#tendered_amount').val('0');
	} else {
		$('#payment_due_amount').text(formatMoney(totalDue));
		// update dialog due amount
		$('#dialog_due_amount_text').text(formatMoney(totalDue));
		
		$('#payment_change').find('span').text(formatMoney(0));
		// update dialog change amount
		$('#dialog_change_text').text(formatMoney(0));
		$('#tendered_amount').val(posParseFloat(formatMoney(totalDue)));
	}

	if (totalDue < 0.01) {
		// change color to green
		$('#payment_due_amount').css("color", "green");
	} else {
		// change color to red
		$('#payment_due_amount').css("color", "#bc4c3c");
	}
	return totalDue;
};

function country(element, index, zone_id) {
  if (element.value != '') {
		var country_id = element.value;
		if (saved_zones[country_id]) {
			html = '<option value="">' + text_select + '</option>';
			
			if (saved_zones[country_id].length > 0) {
				for (i = 0; i < saved_zones[country_id].length; i++) {
					html += '<option value="' + saved_zones[country_id][i]['zone_id'] + '"';
					
					if (saved_zones[country_id][i]['zone_id'] == zone_id) {
						html += ' selected="selected"';
					}
	
					html += '>' + saved_zones[country_id][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0">' + text_none + '</option>';
			}
			
			$('select[name=\'customer_addresses[' + index + '][zone_id]\']').html(html);
		} else {
			$.ajax({
				url: 'index.php?route=sale/customer/country&token=' + token + '&country_id=' + country_id,
				dataType: 'json',
				beforeSend: function() {
					$('select[name=\'customer_addresses[' + index + '][country_id]\']').after('<span class="wait"><i class="fa fa-spinner fa-spin"></i></span>');
				},
				complete: function() {
					$('.wait').remove();
				},
				success: function(json) {
					saved_zones[country_id] = json['zone'];
					html = '<option value="">' + text_select + '</option>';
					
					if (json['zone'] != '') {
						for (i = 0; i < json['zone'].length; i++) {
							html += '<option value="' + json['zone'][i]['zone_id'] + '"';
							
							if (json['zone'][i]['zone_id'] == zone_id) {
								html += ' selected="selected"';
							}
			
							html += '>' + json['zone'][i]['name'] + '</option>';
						}
					} else {
						html += '<option value="0">' + text_none + '</option>';
					}
					
					$('select[name=\'customer_addresses[' + index + '][zone_id]\']').html(html);
				},
				error: function(xhr, ajaxOptions, thrownError) {
					openAlert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}
};

function updateClock() {
	var currentTime = new Date ( );

	var currentHours = currentTime.getHours();
	var currentMinutes = currentTime.getMinutes();
	currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
	var timeOfDay = ( currentHours < 12 ) ? "am" : "pm";
	currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;
	currentHours = ( currentHours == 0 ) ? 12 : currentHours;
	var currentDate = currentTime.getDate();
	currentDate = ( currentDate < 10 ? "0" : "" ) + currentDate;
	var currentMonth = currentTime.getMonth();
	var month_name = text_monthes[currentMonth];
	var currentYear = currentTime.getFullYear();
	var currentDay = currentTime.getDay();
	var week_day_name = text_weeks[currentDay];
	
	$('#header_year').text(currentYear);
	$('#header_month').text(month_name);
	$('#header_date').text(currentDate);
	$('#header_week').text(week_day_name);
	$('#header_hour').text(currentHours);
	$('#header_minute').text(currentMinutes);
	$('#header_apm').text(timeOfDay);
};

var resizeTimer;
$(window).resize(function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(CheckSizeZoom, 100);
});

function CheckSizeZoom() {
	if ($(window).width() > 1024) {
		/*
		var zoomLev = $(window).width() / 1080;
		if (720 * zoomLev > $(window).height() && $(window).height() / 720 > 1) {
			zoomLev = $(window).height() / 720;
		}
		
		if ($(window).width() > 1024 && $(window).height() > 680) {
			if (typeof (document.body.style.zoom) != "undefined" && !$.browser.msie) {
				$(document.body).css('zoom', zoomLev);
			}
		}
		*/
		
		$('#divWrap').css('margin', '0 auto');
	} else {
		$(document.body).css('zoom', '');
		$('#divWrap').css('margin', '');
	}
};

function window_print_url(msg, url, data, fn, para) {
	// get the page from url and print it
	if (data['change']) {
		// get the change if there is any
		var change = $('#payment_change').find('span').text();
		change = posParseFloat(change);
		if (change < 0.01) {
			data['change'] = formatMoney(0);
		} else {
			data['change'] = formatMoney(change);
		}
	}
		
	$.ajax({
		url: url,
		type: 'post',
		data: data,
		dataType: 'json',
		beforeSend: function() {
			openWaitDialog(msg);
		},
		converters: {
			'text json': true
		},
		complete: function() {
			closeWaitDialog();
		},
		success: function(html) {
			// replace media="screen" to media="print" to make sure we have the same style for printing
			html = html.replace('media="screen"', 'media="print"');
			// send html to iframe for printing
			$('#print_iframe').contents().find('html').html(html);

			setTimeout(function() {
				// append the print script
				if (navigator.appName == 'Microsoft Internet Explorer') {
					$("#print_iframe").get(0).contentWindow.document.execCommand('print', false, null);
				} else {
					$("#print_iframe").get(0).contentWindow.print();
				}
				// call the function to continue
				if (fn) {
					fn(para);
				}
			}, 1000);
		}
	});
};

function printReceipt(ex_id) {
	var print_order_id = ex_id ? ex_id : order_id;
	var url = 'index.php?route=module/pos/invoice&token=' + token + '&order_id=' + print_order_id;
	window_print_url(print_invoice_message, url, {'order_id':print_order_id}, afterPrintReceipt, null);
};
function afterPrintReceipt() {
	closeWaitDialog();
}

$(document).on('change', '#payment_type', function() {
});

$('.menu-toggle').on('click', function() {
	toggleFullScreen();
});