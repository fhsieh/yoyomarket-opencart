function formatMoney(number) {
	number = number || 0;
	var places = 2;
	number = toFixed(number, places);
	var negative = number < 0 ? "-" : "";
	i = parseInt(toFixed(number = Math.abs(+number || 0), places), 10) + "";
	j = (j = i.length) > 3 ? j % 3 : 0;
	return symbol_left + negative + (j ? i.substr(0, j) + text_thousand_point : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + text_thousand_point) + (places ? text_decimal_point + toFixed(Math.abs(number - i), places).slice(2) : "") + symbol_right;
};

function toFixed(num, fixed) {
	return (Math.round(parseFloat(num) * Math.pow(10, fixed)) / Math.pow(10, fixed)).toFixed(fixed);
};

function formatDate(date) {
	var hours = date.getHours();
	var minutes = date.getMinutes();
	var seconds = date.getMinutes();
	hours = ( hours < 10 ? "0" : "" ) + hours;
	minutes = ( minutes < 10 ? "0" : "" ) + minutes;
	seconds = ( seconds < 10 ? "0" : "" ) + seconds;

	var month = date.getMonth()+1;
	var day = date.getDate();
	month = ( month < 10 ? "0" : "" ) + month;
	day = ( day < 10 ? "0" : "" ) + day;
	
	return  date.getFullYear() + '-' + month + '-' + day + ' ' + hours + ':' + minutes + ':' + seconds;
};

function parseDate(dateString) {
	// assuming the date format is yyyy-MM-dd HH:mm:ss which is the string format from mysql database
	var parts = dateString.split(' ');
	var dateParts = parts[0].split('-');
	if (parts[1]) {
		var timeParts = parts[1].split(':');
		var date = new Date(dateParts[0], dateParts[1]-1, dateParts[2], timeParts[0], timeParts[1], timeParts[2]);
		return date.getTime();
	} else {
		var date = new Date(dateParts[0], dateParts[1]-1, dateParts[2]);
		return date.getTime();
	}
};

function getFormData(formElements) {
	var data = {};
	for (var index in formElements) {
		var top = data;
		var path = index;
		var val = formElements[index];
		var prev = '';
		while ((path.replace(/^(\[?\w+\]?)(.*)$/, function(_m, _part, _rest) {
			prev = path;
			_part = _part.replace(/[^A-Za-z_0-9]/g, '');
			if (!top[_part]) {
				if (/\w+/.test(_rest)) { 
					top[_part] = {}; 
					top = top[_part];
				} else {
					top[_part] = val; 
				}
			} else if (!/\w+/.test(_rest)) {
				top[_part] = val;
			} else {
				top = top[_part];
			}
			path = _rest;
		})) && (prev !== path));
	}
	
	return data;
};

function base64_encode(data) {
  var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
  var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
    ac = 0,
    enc = '',
    tmp_arr = [];

  if (!data) {
    return data;
  }

  do { // pack three octets into four hexets
    o1 = data.charCodeAt(i++);
    o2 = data.charCodeAt(i++);
    o3 = data.charCodeAt(i++);

    bits = o1 << 16 | o2 << 8 | o3;

    h1 = bits >> 18 & 0x3f;
    h2 = bits >> 12 & 0x3f;
    h3 = bits >> 6 & 0x3f;
    h4 = bits & 0x3f;

    // use hexets to index into b64, and append result to encoded string
    tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
  } while (i < data.length);

  enc = tmp_arr.join('');

  var r = data.length % 3;

  return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);
};

function base64_decode(data) {
  var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
  var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
    ac = 0,
    dec = '',
    tmp_arr = [];

  if (!data) {
    return data;
  }

  data += '';

  do { // unpack four hexets into three octets using index points in b64
    h1 = b64.indexOf(data.charAt(i++));
    h2 = b64.indexOf(data.charAt(i++));
    h3 = b64.indexOf(data.charAt(i++));
    h4 = b64.indexOf(data.charAt(i++));

    bits = h1 << 18 | h2 << 12 | h3 << 6 | h4;

    o1 = bits >> 16 & 0xff;
    o2 = bits >> 8 & 0xff;
    o3 = bits & 0xff;

    if (h3 == 64) {
      tmp_arr[ac++] = String.fromCharCode(o1);
    } else if (h4 == 64) {
      tmp_arr[ac++] = String.fromCharCode(o1, o2);
    } else {
      tmp_arr[ac++] = String.fromCharCode(o1, o2, o3);
    }
  } while (i < data.length);

  dec = tmp_arr.join('');

  return dec.replace(/\0+$/, '');
};

function posParseFloat(floatstring) {
	// to take care of different culture with the formatted currency string
	// convert to general thousand point (,) and decimal point (.)
	var fString = ''+floatstring;
	if (text_thousand_point != ',' || text_decimal_point != '.') {
		fString = fString.replace(text_thousand_point, '#tp#');
		fString = fString.replace(text_decimal_point, '.');
		fString = fString.replace('#tp#', ',');
	}
	
	return parseFloat(fString.replace(/[^0-9-.]/g, ''));
};

function occurrences(string, subString, allowOverlapping){

    string+=""; subString+="";
    if(subString.length<=0) return string.length+1;

    var n=0, pos=0;
    var step=(allowOverlapping)?(1):(subString.length);

    while(true){
        pos=string.indexOf(subString,pos);
        if(pos>=0){ n++; pos+=step; } else break;
    }
    return(n);
};

function openWaitDialog(msg) {
	if (msg) {
		$('#pos_wait_msg span').text(msg);
	} else {
		$('#pos_wait_msg span').text(text_wait);
	}
	$('#pos_wait_msg').show();
};

function closeWaitDialog() {
	$('#pos_wait_msg').hide();
};

function openAlert(msg) {
	$('#alert_dialog p').text(msg);
	$('#alert_dialog #alert_cancel').hide();
	$('#alert_dialog #alert_ok').unbind('click');
	$('#alert_dialog #alert_ok').click(function() {$("#alert_dialog").hide();});
	$('#alert_dialog #alert_ok').show();
	$('#alert_dialog').show();
};

function openConfirm(msg, fn) {
	$('#alert_dialog p').text(msg);
	$('#alert_dialog #alert_cancel').show();
	$('#alert_dialog #alert_ok').unbind('click');
	$('#alert_dialog #alert_ok').click(fn);
	$('#alert_dialog #alert_ok').show();
	$('#alert_dialog').show();
};
