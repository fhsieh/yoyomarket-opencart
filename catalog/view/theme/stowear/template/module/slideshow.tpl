<div class="container">
  <div class="camera_wrap" id="camera_wrap_<?php echo $module+20; ?>">
    <?php foreach ($banners as $banner) { ?>
    <?php if ($banner['link']) { ?><a href="<?php echo $banner['link']; ?>"><?php } ?>
      <img src="<?php echo $banner['image']; ?>" srcset="<?php echo $banner['srcset']; ?>" sizes="(max-width: 767px) 100vw, 978px" alt="<?php echo $banner['title']; ?>" />
    <?php if ($banner['link']) { ?></a><?php } ?>
    <?php } ?>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
  var camera_slider = $("#camera_wrap_<?php echo $module+20; ?>");

  camera_slider.owlCarousel({
    slideSpeed : 300,
    lazyLoad : true,
    singleItem: true,
    autoPlay: 7000,
    stopOnHover: true,
    navigation: true
  });
});
</script>
