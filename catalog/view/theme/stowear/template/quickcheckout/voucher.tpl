<?php if ($coupon_module) { ?>
<div id="coupon-heading"><i class="fa fa-ticket"></i> <?php echo $entry_coupon; ?></div>
<div id="coupon-content">
  <div class="input-group">
	<input type="text" name="coupon" value="" class="form-control" />
	<span class="input-group-btn">
	  <button type="button" id="button-coupon" class="btn btn-primary"><?php echo $text_use_coupon; ?></button>
	</span>
  </div>
</div>
<?php } ?>
<?php if ($voucher_module) { ?>
<div id="voucher-heading"><i class="fa fa-gift"></i> <?php echo $entry_voucher; ?></div>
<div id="voucher-content">
  <div class="input-group">
	<input type="text" name="voucher" value="" class="form-control" />
	<span class="input-group-btn">
	  <button type="button" id="button-voucher" class="btn btn-primary"><?php echo $text_use_voucher; ?></button>
	</span>
  </div>
</div>
<?php } ?>
<?php if ($reward_module && $reward) { ?>
<div id="reward-heading"><i class="fa fa-star"></i> <?php echo $entry_reward; ?></div>
<div id="reward-content">
  <div class="input-group">
	<input type="text" name="reward" value="" class="form-control" />
	<span class="input-group-btn">
	  <button type="button" id="button-reward" class="btn btn-primary"><?php echo $text_use_reward; ?></button>
	</span>
  </div>
</div>
<?php } ?>

<script type="text/javascript"><!--
$('#coupon-heading').on('click', function() {
    if($('#coupon-content').is(':visible')){
      $('#coupon-content').slideUp('slow');
    } else {
      $('#coupon-content').slideDown('slow');
    };
});

$('#voucher-heading').on('click', function() {
    if($('#voucher-content').is(':visible')){
      $('#voucher-content').slideUp('slow');
    } else {
      $('#voucher-content').slideDown('slow');
    };
});

$('#reward-heading').on('click', function() {
    if($('#reward-content').is(':visible')){
      $('#reward-content').slideUp('slow');
    } else {
      $('#reward-content').slideDown('slow');
    };
});
//--></script>