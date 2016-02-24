<table class="table table-bordered table-condensed"><!-- yym -->
  <thead>
    <tr>
      <td class="text-center"><?php echo $column_date; ?></td>
      <td class="text-center"><?php echo $column_status; ?></td>
      <td class="text-center"><?php echo $column_notify; ?></td>
      <td class="text-left"><?php echo $column_comment; ?></td>
    </tr>
  </thead>
  <tbody>
    <?php if ($histories) { ?>
    <?php foreach ($histories as $history) { ?>
    <tr>
      <td class="text-center"><?php echo $history['date_added']; ?></td>
      <td class="text-center"><?php echo $history['status']; ?></td>
      <td class="text-center"><?php echo $history['notify']; ?></td>
      <td class="text-left"><?php echo $history['comment']; ?></td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
<div class="row">
  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>
