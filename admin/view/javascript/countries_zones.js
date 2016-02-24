
$(document).ready(function() {
	$('.update-name').blur(function() {
		str = $(this).attr('id');
		values = str.split("-");
		table = values[0];
		id = values[1];
		language = values[2];
					
		updateItemName(table, id, language);
	});

	$('.update-name').keydown(function(e) {
		if (e.keyCode == 13) {
			str = $(this).attr('id');
			values = str.split("-");
			table = values[0];
			id = values[1];
			language = values[2];
					
			updateItemName(table, id, language);
		}
	});
	
	$(".filter input[type='text']").keydown(function(e) {
		if (e.keyCode == 13) {
			str = $(this).closest('.filter').attr('id');
			values = str.split("-");
			table = values[1];
						
			filterItems(table);
		}
	});				
});
	
function updateItemName(table, id, language)
{
	name = $("input[name='name[" + id + "][" + language + "]'").val();
	
	$.ajax({
		url: 'index.php?route=setting/setting/updateitemname&token=' + getURLValues('token'),
		type: 'post',
		dataType: 'json',
		data: {'table': table, 'id': id, 'language': language, 'name': encodeURIComponent(name)},
		success: function(json) {
			if (json['error']) {
				alert(json['error']['name']);
			} else {
				$("#" + table + "-" + id + "-" + language).highlight();
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}

function filterItems(table)
{
	url = 'index.php?route=localisation/' + table + '&token=' + getURLValues('token');
	
	var filter_name = $("input[name='filter_name']").val();
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	
	var filter_country = $("input[name='filter_country']").val();
	
	if (filter_country) {
		url += '&filter_country=' + encodeURIComponent(filter_country);
	}
		
	var filter_iso_code_2 = $("input[name='filter_iso_code_2']").val();
	
	if (filter_iso_code_2) {
		url += '&filter_iso_code_2=' + encodeURIComponent(filter_iso_code_2);
	}
	
	var filter_iso_code_3 = $("input[name='filter_iso_code_3']").val();
	
	if (filter_iso_code_3) {
		url += '&filter_iso_code_3=' + encodeURIComponent(filter_iso_code_3);
	}
	
	var filter_code = $("input[name='filter_code']").val();
	
	if (filter_code) {
		url += '&filter_code=' + encodeURIComponent(filter_code);
	}
	
	location = url;
}

function getURLValues(a)
{
	return (a = location.search.match(RegExp("[?&]" + a + "=([^&]*)(&?)", "i"))) ? a[1] : a
}

$.fn.highlight = function(func) {
	highlightIn = function(options)	{
		var el = options.el;
		var visible = options.visible !== undefined ? options.visible : true;

		setTimeout(function() {
			if (visible) {
				el.css('background-color', 'rgba(' + [236, 242, 216] + ',' + options.iteration/10 + ')');

				if (options.iteration/10 < 1) {
					options.iteration += 2;
					highlightIn(options);
				}
			} else {
				el.css('background-color', 'rgba(' + [236, 242, 216] + ',' + options.iteration/10 + ')');

				if (options.iteration/10 > 0) {
					options.iteration -= 2;
					highlightIn(options);
				} else {
					el.removeAttr('style');
					if (typeof func == "function") func();
				}
			}
		}, 100);
	};

	highlightOut = function(options) {
		options.visible = false;
		highlightIn(options);
	};

	highlightIn({'iteration': 1, 'el': jQuery(this)});
	highlightOut({'iteration': 10, 'el': jQuery(this)});
};