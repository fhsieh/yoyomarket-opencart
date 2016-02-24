<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-calendar"></i> <?php echo $heading_title; ?></h3>
  </div>
  <div class="table-responsive">
    <table class="table table-condensed">
      <tbody>
        <?php if ($activities) { ?>
        <?php foreach ($activities as $activity) { ?>
        <tr>
          <td><?php echo $activity['comment']; ?></td>
          <td class="text-right"><i class="fa fa-clock-o"></i> <?php echo $activity['date_added']; ?></td>
        </tr>
        <?php } ?>
        <?php } else { ?>
        <tr>
          <td class="text-center">
            <?php echo $text_no_results; ?>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>