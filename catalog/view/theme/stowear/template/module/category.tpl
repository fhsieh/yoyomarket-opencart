<div class="box box-category">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="strip-line"></div>
  <div class="box-content">
    <ul class="accordion" id="accordion-category">
      <?php $i = 0; foreach ($categories as $category) { $i++; ?>
      <li class="panel">
        <?php if ($category['category_id'] == $category_id) { ?>
        <a href="<?php echo $category['href']; ?>" class="active"><?php echo $category['name']; ?></a>
        <?php } else { ?>
        <a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
        <?php } ?>
        <?php if ($category['children']) { ?>
        <span class="head"><a style="float:right;padding-right:5px" class="accordion-toggle<?php if ($category['category_id'] != $category_id) { echo ' collapsed'; } ?>" data-toggle="collapse" data-parent="#accordion-category" href="#category<?php echo $i; ?>"><span class="plus">+</span><span class="minus">-</span></a></span>
        <?php if(!empty($category['children'])) { ?>
        <div id="category<?php echo $i; ?>" class="panel-collapse collapse <?php if ($category['category_id'] == $category_id) { echo 'in'; } ?>" style="clear:both">
        	<ul>
		       <?php foreach ($category['children'] as $child) { ?>
		        <li>
		         <?php if ($child['category_id'] == $child_id) { ?>
		         <a href="<?php echo $child['href']; ?>" class="active"><?php echo $child['name']; ?></a>
		         <?php } else { ?>
		         <a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a>
		         <?php } ?>
		        </li>
		       <?php } ?>
	        </ul>
        </div>
        <?php } ?>
        <?php } ?>
      </li>
      <?php } ?>
    </ul>
  </div>
</div>
