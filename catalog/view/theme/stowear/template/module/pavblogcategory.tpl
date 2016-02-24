<div class="box ">
	<div class="box-heading"><?php echo $heading_title; ?></div>
	<div class="strip-line"></div>
	<div class="box-content" id="categorymenu">

		 <?php echo $tree;?>
		 
	</div>
 </div>
<script>
$(document).ready(function(){
		// applying the settings
		$("#categorymenu li.active span.head").addClass("selected");
			$('#categorymenu ul').Accordion({
				active: 'span.selected',
				header: 'span.head',
				alwaysOpen: false,
				animated: true,
				showSpeed: 400,
				hideSpeed: 800,
				event: 'click'
			});
});

</script>