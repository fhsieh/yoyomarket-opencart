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
    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="pull-right">
          <a href="#" id="prev"><i class="fa fa-arrow-left fa-fw"></i></a>
          <a href="#" id="next"><i class="fa fa-arrow-right fa-fw"></i></a>
        </div>
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <span id="year"><?php echo $year; ?></span></h3>
      </div>
      <div class="panel-body">
        <div id="chart" class="ct-chart" style="width: 100%; height: 240px;"></div>
        <div class="table-responsive">
          <table id="table" class="table table-bordered table-condensed table-striped"><!-- yym -->
            <thead>
              <tr>
                <td class="text-left"><?php echo $column_month; ?></td>
                <td class="text-left"><?php echo $column_orders; ?></td>
                <td class="text-right"><?php echo $column_sub_total; ?></td>
                <td class="text-right"><?php echo $column_reward; ?></td>
                <td class="text-right"><?php echo $column_coupon; ?></td>
                <td class="text-right"><?php echo $column_voucher; ?></td>
                <td class="text-right"><?php echo $column_shipping; ?></td>
                <td class="text-right"><?php echo $column_total; ?></td>
                <td class="text-right"><?php echo $column_average; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($months as $key => $month) { ?>
              <tr id="month_<?php echo ($key + 1); ?>">
                <td class="month text-left"><strong><a href="#" target="_blank"><?php echo $month; ?> <?php echo $year; ?></a></strong></td>
                <td class="orders text-left"></td>
                <td class="sub_total text-right"></td>
                <td class="reward text-right"></td>
                <td class="coupon text-right"></td>
                <td class="voucher text-right"></td>
                <td class="shipping text-right"></td>
                <td class="total text-right"></td>
                <td class="average text-right"></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

<link rel="stylesheet" href="view/javascript/chartist/chartist.min.css">
<style>
  .ct-series-a .ct-bar { stroke: rgba(25, 118, 210, 0.5); stroke-width: 10%; }
</style>
<script src="view/javascript/chartist/chartist.min.js"></script>
<script type="text/javascript"><!--

function showdata(year) {
  $.ajax({
    type: 'get',
    url: 'index.php?route=report/sale_order/year&token=<?php echo $token; ?>&year=' + year,
    dataType: 'json',
    success: function(json) {
      if (typeof json['chart'] == 'undefined') { return false; }

      // update chart
      new Chartist.Bar('#chart', {
        labels: json.chart.xaxis,
        series: [json.chart.total]
      }, {
        axisY: {
          labelInterpolationFnc: function(value) {
            if (Math.round(value / 100000) % 10 != 0) {
              return false;
            }
            return (value / 1000000) + 'M';
          }
        }
      });

      // update table
      $.map(json.table, function(month, i) {
        $(month.id + ' .month a').attr('href', month.href.replace(/&amp;/g, "\&")).text(month.month);
        $(month.id + ' .orders').text(month.orders);
        $(month.id + ' .sub_total').text(month.sub_total);
        $(month.id + ' .reward').text(month.reward);
        $(month.id + ' .coupon').text(month.coupon);
        $(month.id + ' .voucher').text(month.voucher);
        $(month.id + ' .shipping').text(month.shipping);
        $(month.id + ' .total').text(month.total);
        $(month.id + ' .average').text(month.average);
      })

      // update title
      $('#year').text(year);

      // update buttons
      $('#prev').data('year', year - 1);
      $('#next').data('year', year + 1);

    },
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
}

$('#prev, #next').on('click', function(e) {
  e.preventDefault();
  showdata($(this).data('year'));
});

//$('#month .active a').trigger('click');
$(document).ready(function() {
  showdata(<?php echo $year; ?>);
});

//--></script>
<?php echo $footer; ?>