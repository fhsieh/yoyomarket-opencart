// http://deelay.me/1000/
var debug = true,
    preamble = 'order_ext.js: ';



/*---------------------------------------------------------------------------*/

/**
 * settings for update form fields
 */
var fields = {
  'product': {
    'price': {
      'type': 'text',
      'style': 'width: 90px;'
    },
    'quantity': {
      'type': 'number',
      'style': 'width: 60px;'
    }
  },
  'total': {
    'title': {
      'type': 'text',
      'style': 'width: 90px;'
    },
    'value': {
      'type': 'text',
      'style': 'width: 60px;'
    }
  },
  'order': {
    'value': {
      'type': 'text',
      'style': 'width: 150px;'
    }
  }
};




/*---------------------------------------------------------------------------*/

/**
 * localize integer as currency
 */
Number.prototype.toCurrency = function() {
  return '¥' + this.toLocaleString('ja-JP');
}

/**
 * serialize form into json object
 * http://jsfiddle.net/davidhong/gp9bh/
 */
$.fn.serializeObject = function() {
  var o = Object.create(null);

  $.each(
    $.map(this.serializeArray(), function(element) {
      element.name = $.camelCase(element.name);
      return element;
    }),
    function(i, element) {
      if (/^option\[(\d+)\]$/g.test(element.name)) {
        var key = element.name.replace(/^option\[(\d+)\]$/g, '$1');

        if (typeof o.option == 'undefined' || o.option === null) {
          o.option = {};
        }

        if (typeof o.option[key] != 'undefined' && o.option[key] !== null) {
          o.option[key] = o.option[key].push ? o.option[key].push(element.value) : [o.option[key], element.value];
        } else {
          o.option[key] = element.value;
        }
      } else {
        if (typeof o[element.name] != 'undefined' && o[element.name] !== null) {
          o[element.name] = o[element.name].push ? o[element.name].push(element.value) : [o[element.name], element.value];
        } else {
          o[element.name] = element.value;
        }
      }
    }
  );

  return o;
};

/**
 * highlight effect on action
 */
$.fn.highlight = function() {
  $(this).each(function() {
    var el = $(this);
    $('<div/>')
      .width(el.outerWidth())
      .height(el.outerHeight())
      .addClass('bg-info')
      .css({
        'position': 'absolute',
        'top': el.offset().top,
        'left': el.offset().left,
        'opacity': '0.8',
        'z-index': '99999'
      })
      .appendTo('body')
      .fadeOut(500)
      .queue(function () {
        $(this).remove();
      });
  });
}

/**
 * copy to clipboard
 * http://stackoverflow.com/questions/400212/how-do-i-copy-to-the-clipboard-in-javascript
 */
$.fn.copyToClipboard = function() {
  var text = $.map($(this).children(':not(.action-copy)'), function(e) {
    var t = $(e).text().replace(/\s{2,}|\t|\n|\r/gm, ' ').replace(/^\s+|\s+$/gm, '');
    return t !== '' ? t : null;
  }).join(', ');

  var textArea = document.createElement("textarea");

  textArea.value = text;
  document.body.appendChild(textArea);
  textArea.select();

  try {
    if (document.execCommand('copy')) {
      if (debug) console.log(preamble + 'text copied: ' + textArea.value);
      $(this).highlight();
    }
  } catch (err) {
    if (debug) console.log(preamble + 'error copying text');
  }

  document.body.removeChild(textArea);
}

/**
 * initialize copy to clipboard
 */
$.fn.enableCopy = function() {
  if (!$(this).children('.action-copy').length) {
    $(this).prepend(
      $('<span></span>')
        .addClass('action-copy text-muted pull-right')
        .css({'cursor': 'pointer'})
        .append(
          $('<i></i>')
            .addClass('fa fa-pencil-square-o')
        )
    );
  }
}



/*---------------------------------------------------------------------------*/

/**
 * update table with ajax results
 */
function updateTable(o_id, result) {
  for (var index in result) {
    var id = result[index].id,
        row = o_id == 0 ? '#' + result[index].table + '-' + id : '#order-' + o_id + '-' + id;

    if (debug) console.log(preamble + 'update table ' + row);
    if (debug) console.log(result[index]);

    if (result[index].action == 'add') {
      // add product
      var html = [];

      html.push('<tr id="' + row.substr(1) + '" data-table="' + result[index].table + '" data-id="' + result[index].id + '" data-price="' + result[index].columns.price + '" data-quantity="' + result[index].columns.quantity + '" data-total="' + result[index].columns.total + '">');
      html.push('<td class="text-center model">' + result[index].columns.model + '</td>');
      html.push('<td class="text-left name clipboard">');
      html.push('<a href="' + result[index].href + '">' + result[index].columns.name + '</a>');
      if ($.isArray(result[index].option)) {
        for (var key in result[index].option) {
          // if (result[index].option[key].type != 'file') {
          html.push('<br>- <small>' + result[index].option[key].name + ': ' + result[index].option[key].value + '</small>');
          // } else {
          //   html.push('<br>- <small>' + result[index].option[key].name + ': <a href="' + result[index].option[key].href + '">' + result[index].option[key].value + '</a></small>');
          // }
        }
      }
      html.push('</td>');
      html.push('<td class="text-center price">' + result[index].columns.price.toCurrency() + '</td>');
      html.push('<td class="text-center quantity">' + result[index].columns.quantity + '</td>');
      html.push('<td class="text-center total">' + result[index].columns.total.toCurrency() + '</td>');
      html.push('<td class="text-right">');
      html.push('<div class="btn-group btn-group-flex">');
      html.push('<a role="button" data-toggle="popover" class="action-update btn btn-info btn-xs"><i class="fa fa-pencil"></i></a>');
      html.push('<a role="button" data-toggle="popover" class="action-delete btn btn-danger btn-xs"><i class="fa fa-times"></i></a>');
      html.push('</div>');
      html.push('</td>');
      html.push('</tr>');

      $('#order-info tbody tr[data-table="total"]').first().before($(html.join(' ')));

      $(row).children('td.clipboard').enableCopy();
      $(row).highlight();
    } else if (result[index].action == 'delete') {
      // delete product
      $(row).remove();
    } else if (result[index].action == 'update') {
      // update product or total
      $.each(result[index].columns, function(column, value) {
        if ($(row).data(column) != value) {
          $(row).data(column, value);
          var col = o_id == 0 ? ' .' + column : '';
          if ($(row).data('id') == 'shipping_date') {
            var html = [];
            if (value != '') {
              var dow = moment(value, 'YYYY/MM/DD').format('dddd');
              html.push('<a role="button" data-toggle="popover" class="action-update btn btn-xs btn-' + dow.toLowerCase() + '">' + dow + '</a>');
              if (col != '') {
                html.push('<span>' + value + '</span>'); // align left on info
              } else {
                html.push('<span class="pull-right">' + value + '</span>'); // align right on list
              }
            } else {
              html.push('<a class="action-update btn btn-xs btn-default">Set Date</a>'); // reset to set date
            }
            $(row + col).html($(html.join(' '))).highlight();
          } else {
            $(row + col).text((result[index].table != 'order' && $.inArray(column, ['price', 'total', 'value']) > -1) ? value.toCurrency() : value).highlight();
          }
        }
      });
    }
  }
}

/*---------------------------------------------------------------------------*/

/**
 * on ajax error, display errors
 */
function showAlerts(alerts) {
  var html = [];
  $.each(alerts, function(key, value) {
    if (value != '') {
      html.push('<div class="alert alert-' + key + '" role="alert" style="margin-top: 5px; margin-bottom: 0;">' + value + '</div>');
    }
  });
  $('.action-alerts').html(html.join(' '));
}



/*---------------------------------------------------------------------------*/

/**
 * generate delete form
 */
function htmlDelete(o_id, table, id) {
  if (debug) console.log(preamble + 'delete form: order ' + o_id + ', ' + table + '-' + id);

  var html = [];

  html.push('<form class="form-inline" style="white-space: nowrap;">');
  html.push('<input name="order_id" value="' + o_id + '" type="hidden">');
  html.push('<input name="table" value="' + table + '" type="hidden">');
  html.push('<input name="id" value="' + id + '" type="hidden">');
  html.push('<button type="submit" class="delete-submit btn btn-danger btn-sm"><i class="fa fa-check"></i> Delete</button>');
  html.push('<button type="button" class="delete-cancel btn btn-default btn-sm"><i class="fa fa-times"></i> Cancel</button>');
  html.push('</form>');
  html.push('<div class="action-alerts"></div>');

  return html.join(' ');
}

/**
 * generate update form
 */
function htmlUpdate(o_id, table, id, columns) {
  if (debug) {
    console.log(preamble + 'update form: order ' + o_id + ', ' + table + '-' + id);
    console.log('default values:');
    console.log(columns);
  }

  var html = [];

  html.push('<form class="form-inline" style="white-space: nowrap;">');
  html.push('<input name="order_id" value="' + o_id + '" type="hidden">');
  html.push('<input name="table" value="' + table + '" type="hidden">');
  html.push('<input name="id" value="' + id + '" type="hidden">');
  $.each(columns, function(column, value) {
    html.push('<div class="form-group" style="margin-left: 0; margin-right: 0;">');
    if (id == 'shipping_date') {
      html.push('<input name="' + column + '" type="' + fields[table][column].type + '" value="' + value + '" class="form-control input-sm date" data-date-format="YYYY/MM/DD" style="' + fields[table][column].style + '">');
    } else {
      html.push('<input name="' + column + '" type="' + fields[table][column].type + '" value="' + value + '" class="form-control input-sm" style="' + fields[table][column].style + '">');
    }
    html.push('</div>');
  });
  html.push('<button type="submit" class="btn btn-primary btn-sm update-submit"><i class="fa fa-check"></i></button>');
  html.push('</form>');
  html.push('<div class="action-alerts"></div>');

  return html.join(' ');
}



/*---------------------------------------------------------------------------*/
$(document).ready(function() {

  /**
   * initialize clipboard
   */
  $('.clipboard').each(function() {
    $(this).enableCopy();
  });

  /**
   * handle copy to clipboard
   */
  $('body').on('click', '.action-copy', function() {
    $(this).closest('.clipboard').copyToClipboard();
  });



/*---------------------------------------------------------------------------*/

  /**
   * handle add form product autocomplete
   */
  $('#product-add #name').autocomplete({
    'source': function(request, response) {
      $.ajax({
        url: 'index.php?route=catalog/product/autocomplete&token=' + token + '&filter_name=' +  encodeURIComponent(request),
        dataType: 'json',
        success: function(json) {
          response($.map(json, function(item) {
            return {
              label: item.name,
              value: item.product_id,
              option: item.option
            }
          }));
        }
      });
    },
    'select': function(item) {
      if (debug) console.log(preamble + 'autocomplete item selected:');
      if (debug) console.log(item);

      if ($('#product-add .option').length) {
        $('#product-add .option').remove();
      }

      $('#product-add #name').val(item.label);
      $('#product-add input[name="product_id"]').val(item.value);

      if (item.option.length) {
        if (debug) console.log(preamble + 'product has options');
        var html = [];

        html.push('<div class="option">');
        for (var o in item.option) {
          html.push('<div class="form-group">');
          html.push('<label class="control-label col-sm-3">' + item.option[o].name + '</label>');
          html.push('<div class="col-sm-6">');

          switch (item.option[o].type) {
            case 'select':
              html.push('<select name="option[' + item.option[o].product_option_id + ']" class="form-control">');
              for (var v in item.option[o].product_option_value) {
                html.push('<option value="' + item.option[o].product_option_value[v].product_option_value_id + '">' + item.option[o].product_option_value[v].name + '</option>')
              }
              html.push('</select>');
              break;
            case 'radio':
            case 'checkbox':
              for (var v in item.option[o].product_option_value) {
                html.push('<div class="' + item.option[o].type + '-inline">');
                html.push('<label>');
                html.push('<input type="' + item.option[o].type + '" name="option[' + item.option[o].product_option_id + ']" value="' + item.option[o].product_option_value[v].product_option_value_id + '">');
                html.push(item.option[o].product_option_value[v].name);
                html.push('</label>');
                html.push('</div>');
              }
              break;
            case 'text':
            case 'textarea':
              html.push('<input type="' + item.option[o].type + '" name="option[' + item.option[o].product_option_id + ']" class="form-control">');
              break;
          }

          html.push('</div>');
          html.push('</div>');
        }
        html.push('</div>');

        $('#product-add form').append($(html.join(' ')));
      }
    }
  });

  /**
   * handle add submit
   */
  $('body').on('click', 'button.add-submit', function(e) {
    e.preventDefault();

    var data = $('#product-add form').serializeObject();

    if (debug) {
      console.log(preamble + 'add submit: order ' + data.order_id + ', product id ' + data.id);
      console.log('ajax submit payload:');
      console.log(data);
    }

    $.ajax({
      url: 'index.php?route=sale/order_ext/add&token=' + token,
      type: 'post',
      data: data,
      dataType: 'json',
      beforeSend: function() {
        if (debug) console.log(preamble + 'ajax.beforeSend');
        // validation
        // if invalid input
//        showAlerts({ warning: 'Invalid input' });

        $('button.add-submit i.fa').addClass('fa-spinner fa-spin').removeClass('fa-check');
      },
      success: function(json) {
        if (debug) {
          console.log(preamble + 'ajax.success');
          console.log('ajax return data:');
          console.log(json);
        }
        if (json.success) {
          updateTable(order_id ? 0 : data.order_id, json.result);
          $('#product-add').modal('hide');
          $('#name, #product_id', '#product-add').val('');
          $('#quantity', '#product-add').val(1);
        } else {
          showAlerts(json.alerts);
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        if (debug) alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    }).always(function() {
      $('button.add-submit i.fa').removeClass('fa-spinner fa-spin').addClass('fa-check');
    });
  });

  /**
   * show add form
   * behavior handled by jquery modal
   */
  $('#product-add').on('shown.bs.modal', function() {
    $('#product-add').find('input:text:visible:first').focus().select();
  })



/*---------------------------------------------------------------------------*/

  /**
   * show delete form
   */
  $(document).on('click', '.action-delete', function(e) {
    e.preventDefault();

    if (!$(this).hasClass('open')) {
      var $t    = $(this).closest('td').data('id') ? $(this).closest('td') : $(this).closest('tr'),
          o_id  = typeof order_id !== 'undefined' ? order_id : $t.data('order-id'),
          table = $t.data('table'),
          id    = $t.data('id');

      $(this).popover({
        content: $(htmlDelete(o_id, table, id)),
        html: true,
        placement: 'top',
        trigger: 'manual'
      });

      $(this).on('show.bs.popover', function() {
        if (debug) console.log(preamble + 'delete show: order ' + o_id + ', ' + table + '-' + id);
        $(this).addClass('open');
      }).on('hidden.bs.popover', function() {
        if (debug) console.log(preamble + 'delete hide: order ' + o_id + ', ' + table + '-' + id);
        $(this).removeClass('open');
        $(this).unbind('hidden.bs.popover');
        $(this).popover('destroy');
      });

      $(this).popover('show');
    } else {
      $(this).popover('hide');
    }
  });

  /**
   * handle delete submit, cancel
   */
  $('body').on('click', 'button.delete-submit', function(e) {
    e.preventDefault();
    console.log('ajax delete action');

    var data = $(this).closest('form').serializeObject();

    if (debug) {
      console.log(preamble + 'delete submit: order ' + data.order_id + ', ' + data.table + ' id ' + data.id);
      console.log('ajax submit payload:');
      console.log(data);
    }

    $.ajax({
      url: 'index.php?route=sale/order_ext/delete&token=' + token,
      type: 'post',
      data: data,
      dataType: 'json',
      beforeSend: function() {
        if (debug) console.log(preamble + 'ajax.beforeSend');
        // validation

        $('button.delete-submit i.fa').addClass('fa-spinner fa-spin').removeClass('fa-check');
      },
      success: function(json) {
        if (debug) {
          console.log(preamble + 'ajax.success');
          console.log('ajax return data:');
          console.log(json);
        }
        if (json.success) {
          updateTable(typeof order_id !== 'undefined' ? 0 : data.order_id, json.result);
          $('.action-update.open').popover('hide');
        } else {
          showAlerts(json.alerts);
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        if (debug) alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    }).always(function() {
      $('button.delete-submit i.fa').removeClass('fa-spinner fa-spin').addClass('fa-check');
    });
  }).on('click', 'button.delete-cancel', function(e) {
    e.preventDefault();

    $('[data-toggle="popover"].open').popover('hide'); // assumes only 1 popover is open at a time
  })



/*---------------------------------------------------------------------------*/

  /**
   * attach edit buttons
   */
  $('.editable').each(function() {
    $(this).prepend($('<a role="button" data-toggle="popover" class="action-update btn btn-info btn-xs pull-right"><i class="fa fa-pencil"></i></a>'));
  });

  /**
   * show update form
   */
  $(document).on('click', '.action-update', function(e) {
    e.preventDefault();

    if (!$(this).hasClass('open')) {
      var $t      = $(this).closest('td').data('id') ? $(this).closest('td') : $(this).closest('tr'),
          o_id    = typeof order_id !== 'undefined' ? order_id : $t.data('order-id'),
          table   = $t.data('table'),
          id      = $t.data('id'),
          columns = {};

      if (table == 'product') {
        columns.price     = $t.data('price');
        columns.quantity  = $t.data('quantity');
      } else if (table == 'total') {
        columns.title     = $t.data('title');
        columns.value     = $t.data('value');
      } else if (table == 'order') {
        columns.value     = $t.data('value');
      }

      $(this).popover({
        content: $(htmlUpdate(o_id, table, id, columns)),
        html: true,
        placement: 'top',
        trigger: 'manual'
      });

      $(this).on('show.bs.popover', function() {
        if (debug) console.log(preamble + 'update show: order ' + o_id + ', ' + table + '-' + id);
        $(this).addClass('open');
      }).on('shown.bs.popover', function() {
        if (id == 'shipping_date') {
          $('.popover .date').datetimepicker({
            pickTime: false
          });
        }
      }).on('hidden.bs.popover', function() {
        if (debug) console.log(preamble + 'update hide: order ' + o_id + ', ' + table + '-' + id);
        $(this).removeClass('open');
        $(this).unbind('hidden.bs.popover'); // prevent recursive calls to hidden.bs.popover on destroy
        $(this).popover('destroy');
      });

      $(this).popover('show');
      $('.popover').find('input:text:visible:first').focus().select();
    } else {
      $(this).popover('hide');
    }
  });

  /**
   * handle update submit
   */
  $('body').on('click', 'button.update-submit', function(e) {
    e.preventDefault();

    var data = $(this).closest('form').serializeObject();

    if (debug) {
      console.log(preamble + 'update submit: order ' + data.order_id + ', ' + data.table + '-' + data.id);
      console.log('ajax submit payload:');
      console.log(data);
    }

    $.ajax({
      url: 'index.php?route=sale/order_ext/update&token=' + token,
      type: 'post',
      data: data,
      dataType: 'json',
      beforeSend: function() {
        if (debug) console.log(preamble + 'ajax.beforeSend');
        // validate

        $('button.update-submit i.fa').addClass('fa-spinner fa-spin').removeClass('fa-check');
      },
      success: function(json) {
        if (debug) {
          console.log(preamble + 'ajax.success');
          console.log('ajax return data:');
          console.log(json);
        }
        if (json.success) {
          updateTable(typeof order_id !== 'undefined' ? 0 : data.order_id, json.result);
          $('.action-update.open').popover('hide');
        } else {
          showAlerts(json.alerts);
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        if (debug) alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    }).always(function() {
      $('button.update-submit i.fa').removeClass('fa-spinner fa-spin').addClass('fa-check');
    });
  });




/*---------------------------------------------------------------------------*/

  /**
   * handle close popover
   * http://stackoverflow.com/questions/11703093/how-to-dismiss-a-twitter-bootstrap-popover-by-clicking-outside
   */
  $('body').on('click', function(e) {
    $('[data-toggle="popover"].open').each(function () {
      if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
        $(this).popover('hide');
      }
    });
  });
});
