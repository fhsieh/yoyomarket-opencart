//==============================================================================
// Stripe Payment Gateway v210.2
// 
// Author: Clear Thinking, LLC
// E-mail: johnathan@getclearthinking.com
// Website: http://www.getclearthinking.com
// 
// All code within this file is copyright Clear Thinking, LLC.
// You may not copy or reuse code within this file without written permission.
//==============================================================================

function getQueryVariable(variable) {
	var vars = window.location.search.substring(1).split('&');
	for (i = 0; i < vars.length; i++) {
		var pair = vars[i].split('=');
		if (pair[0] == variable) return pair[1];
	}
	return false;
}

function capture(element, charge_id) {
	element.after('<span id="please-wait" style="font-size: 11px"> Please wait...</span>');
	$.get('index.php?route=payment/stripe/capture&charge_id=' + charge_id + '&token=' + getQueryVariable('token'),
		function(error) {
			$('#please-wait').remove();
			if (error) {
				alert(error);
			}
			if (!error || error.indexOf('has already been captured') != -1) {
				element.prev().html('Yes');
				element.remove();
			}
		}
	);
}

function refund(element, charge_amount, charge_id, update_history) {
	amount = prompt('Enter the amount to refund:', charge_amount);
	if (amount != null && amount > 0) {
		element.after('<span id="please-wait" style="font-size: 11px"> Please wait...</span>');
		$.get('index.php?route=payment/stripe/refund&charge_id=' + charge_id + '&amount=' + amount + '&token=' + getQueryVariable('token'),
			function(error) {
				if (error) {
					alert(error);
					$('#please-wait').remove();
				} else {
					alert('Success!');
					if (update_history) {
						setTimeout(function(){
							$('#history').load('index.php?route=sale/order/history&token=' + getQueryVariable('token') + '&order_id=' + getQueryVariable('order_id'));
							$('#please-wait').remove();
						}, 2000);
					} else {
						$('#please-wait').remove();
					}
				}
			}
		);
	}
}