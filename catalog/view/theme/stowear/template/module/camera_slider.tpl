<div class="<?php if($slider['settings']['layout_type'] == 1) { echo 'container'; } else { echo 'fullwidth'; } ?>">
	<div class="camera_wrap" id="camera_wrap_<?php echo $slider['id']; ?>">
		<?php foreach($slider['content'] as $sliders) {
			if($sliders[$language_id]['status'] == 1) { 
				$link = false;
				$image = $sliders[$language_id]['slider'];
				
				if($sliders[$language_id]['link'] != '') {
					$link = $sliders[$language_id]['link'];
				} ?>
				<div><?php if($link) { echo '<a href="' . $link . '">'; } ?><img src="image/<?php echo $image; ?>" alt="Slider"><?php if($link) { echo '</a>'; } ?></div>
		<?php	}
		} ?>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
  var camera_slider = $("#camera_wrap_<?php echo $slider['id']; ?>");
    
  camera_slider.owlCarousel({
  	  slideSpeed : 300,
      singleItem: true,
      transitionStyle: "fadeUp",
      autoPlay: 7000,
      stopOnHover: true,
      navigation: true
   });
});
</script>