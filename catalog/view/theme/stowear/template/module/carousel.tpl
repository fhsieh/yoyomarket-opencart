<?php if($this->registry->has('theme_options') == true) { 
$theme_options = $this->registry->get('theme_options');
$config = $this->registry->get('config'); ?>
<div class="box">
  <div class="box-heading"><?php if($theme_options->get( 'ourbrands_text', $config->get( 'config_language_id' ) ) != '') { echo $theme_options->get( 'ourbrands_text', $config->get( 'config_language_id' ) ); } else { echo 'Our brands'; } ?></div>

	<div class="box-content">
		<div id="carousel<?php echo $module; ?>" class="owl-carousel carousel-brands">
		  <?php foreach ($banners as $banner) { ?>
		  <div class="item text-center">
		    <?php if ($banner['link']) { ?>
		    <a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive" /></a>
		    <?php } else { ?>
		    <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive" />
		    <?php } ?>
		  </div>
		  <?php } ?>
		</div>
		<script type="text/javascript"><!--
		$('#carousel<?php echo $module; ?>').owlCarousel({
			items: 6,
			autoPlay: 3000,
			navigation: true,
			navigationText: false,
			pagination: true
		});
		--></script>
	</div>
 </div>
 <?php } ?>