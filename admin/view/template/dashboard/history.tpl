<div class="panel panel-default">
  <div class="panel-heading">
    <div class="pull-right">
      <a href="#" id="refresh"><i class="fa fa-refresh fa-fw"></i></a>
      <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-calendar fa-fw"></i> <i class="caret"></i></a>
      <ul id="month" class="dropdown-menu dropdown-menu-right">
        <?php foreach ($months as $key => $month) { ?>
        <?php if (date('n') == $key + 1) { ?>
        <li class="active"><a href="<?php echo ($key + 1); ?>"><?php echo $month; ?></a></li>
        <?php } else { ?>
        <li><a href="<?php echo ($key + 1); ?>"><?php echo $month; ?></a></li>
        <?php } ?>
        <?php } ?>
      </ul>
    </div>
    <h3 class="panel-title"><i class="fa fa-line-chart"></i> <?php echo $heading_title; ?></h3>
  </div>
  <div class="panel-body">
    <div id="chart1" class="ct-chart" style="width: 100%; height: 320px;"></div>
    <div id="chart2" class="ct-chart" style="width: 100%; height: 160px;"></div>
  </div>
</div>

<link rel="stylesheet" href="view/javascript/chartist/chartist.min.css">
<style>
  .ct-series-a .ct-bar { stroke: rgba(25, 118, 210, 0.5); stroke-width: 4%; }
  .ct-series-a .ct-line { stroke: #1976d2; stroke-width: 6px; } /* md@blue700 */
  .ct-series-b .ct-line { stroke: #2196f3; stroke-width: 2px; } /* md@blue500 */
  .ct-series-c .ct-line { stroke: #64b5f6; stroke-width: 2px; } /* md@blue300 */
  .ct-series-d .ct-line { stroke: #90caf9; stroke-width: 1px; } /* md@blue200 */
  .ct-series-e .ct-line { stroke: #bbdefb; stroke-width: 1px; } /* md@blue100 */
</style>
<script src="view/javascript/chartist/chartist.min.js"></script>
<script type="text/javascript"><!--
$('#month a').on('click', function(e) {
  e.preventDefault();
  $(this).parent().parent().find('li').removeClass('active');
  $(this).parent().addClass('active');

  $.ajax({
    type: 'get',
    url: 'index.php?route=dashboard/history/chart&token=<?php echo $token; ?>&month=' + $(this).attr('href'),
    dataType: 'json',
    success: function(json) {
      if (typeof json['total'] == 'undefined') { return false; }

      var labels = json.xaxis;

      new Chartist.Line('#chart1', {
        labels: labels,
        series: json.progress
      }, {
        lineSmooth: false,
        showPoint: false,
        axisY: {
          labelInterpolationFnc: function(value) {
            if (Math.round(value / 100000) % 10 != 0) {
              return false;
            }
            return (value / 1000000) + 'M';
          }
        }
      });

      new Chartist.Bar('#chart2', {
        labels: labels,
        series: [json.total]
      }, {
        axisY: {
          labelInterpolationFnc: function(value) {
            return (value / 1000) + 'k';
          }
        }
      });
    },
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});

$('#refresh').on('click', function(e) {
  e.preventDefault();

  $.ajax({
    type: 'get',
    url: 'index.php?route=dashboard/history/refresh&token=<?php echo $token; ?>&month=' + $('#month .active a').attr('href'),
    dataType: 'json',
    success: function(json) {
      $('#month .active a').trigger('click');
    },
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});

$('#month .active a').trigger('click');

//--></script>
