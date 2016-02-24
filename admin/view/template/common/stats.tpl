<!-- yym_custom: custom stats on admin menu panel -->
<div id="stats">
  <ul>
    <li>
      <div><?php echo $text_processing_status; ?> <span class="pull-right"><?php echo $order_processing_count; ?></span></div>
      <div class="progress">
        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?php echo $order_processing_percent; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $order_processing_percent; ?>%"> <span class="sr-only"><?php echo $order_processing_count; ?></span> </div>
      </div>
    </li>
    <li>
      <div><?php echo $text_packing_status; ?> <span class="pull-right"><?php echo $order_packing_count; ?></span></div>
      <div class="progress">
        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $order_packing_percent; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $order_packing_percent; ?>%"> <span class="sr-only"><?php echo $order_packing_count; ?></span> </div>
      </div>
    </li>
    <li>
      <div><?php echo $text_complete_status; ?> <span class="pull-right"><?php echo $order_complete_count; ?></span></div>
      <div class="progress">
        <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="<?php echo $order_complete_percent; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $order_complete_percent; ?>%"> <span class="sr-only"><?php echo $order_complete_count; ?></span> </div>
      </div>
    </li>
    <li>
      <div><?php echo $text_other_status; ?> <span class="pull-right"><?php echo $order_other_count; ?></span></div>
      <div class="progress">
        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="<?php echo $order_other_percent; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $order_other_percent; ?>%"> <span class="sr-only"><?php echo $order_other_count; ?></span> </div>
      </div>
    </li>
  </ul>
</div>
