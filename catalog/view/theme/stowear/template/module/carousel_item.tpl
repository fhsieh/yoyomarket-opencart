<?php $class = 3; $id = rand(0, 5000)*rand(0, 5000); $all = 4; $row = 4; ?>
<div class="box">
  <!-- Carousel nav -->
  <a class="next" href="#myCarousel<?php echo $id; ?>" id="myCarousel<?php echo $id; ?>_next"><span></span></a>
  <a class="prev" href="#myCarousel<?php echo $id; ?>" id="myCarousel<?php echo $id; ?>_prev"><span></span></a>
	
  <div class="box-heading"><?php echo $block_heading; ?></div>
  <div class="strip-line"></div>
  <div class="box-content">
  	<div id="myCarousel<?php echo $id; ?>" class="carousel slide">
  		<div class="carousel-inner">
			<?php $i = 0; foreach($carousel_item as $item) { ?>
			<div class="item<?php if($i == 0) { echo ' active'; } ?>">
				<?php echo $item['content']; ?>
			</div>
			<?php $i++; } ?>
		</div>
		
		<!-- Indicators -->
		<ol class="carousel-indicators">
		  <?php for ($s = 0; $s < $i; $s++) { ?>
		   <li data-target="#myCarousel<?php echo $id; ?>" data-slide-to="<?php echo $s; ?>"<?php if($s == 0) { echo ' class="active"'; } ?>></li>
		  <?php } ?>
		</ol>
	</div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
  var owl<?php echo $id; ?> = $(".col-sm-12 > .box #myCarousel<?php echo $id; ?> .carousel-inner, .col-sm-9 > .box #myCarousel<?php echo $id; ?> .carousel-inner, .col-sm-6 > .box #myCarousel<?php echo $id; ?> .carousel-inner");
	
  $("#myCarousel<?php echo $id; ?>_next").click(function(){
      owl<?php echo $id; ?>.trigger('owl.next');
      return false;
    })
  $("#myCarousel<?php echo $id; ?>_prev").click(function(){
      owl<?php echo $id; ?>.trigger('owl.prev');
      return false;
  });
    
  owl<?php echo $id; ?>.owlCarousel({
  	  slideSpeed : 500,
      singleItem:true
   });
});
</script>