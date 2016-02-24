<!-- POST -->
<div class="post-item">
	<h2><a href="<?php echo $blog['link'];?>" title="<?php echo $blog['title'];?>"><?php echo $blog['title'];?></a></h2>
	<div class="post-item-panel">
		<ul class="clearfix">
			<?php if( $config->get('cat_show_created') ) { ?>
			<li class="date"><p><i class="icon-calendar"></i><?php echo date("M",strtotime($blog['created']));?> <?php echo date("d",strtotime($blog['created']));?>, <?php echo date("Y",strtotime($blog['created']));?></p></li>
			<?php } ?>
			
			<?php if( $config->get('cat_show_author') ) { ?>
			<li><p><i class="icon-user"></i><?php echo $this->language->get("text_write_by");?> <?php echo $blog['author'];?></p></li>
			<?php } ?>
			
			<?php if( $config->get('cat_show_category') ) { ?>
			<li><p><i class="icon-folder-open"></i><?php echo $this->language->get("text_published_in");?> <a href="<?php echo $blog['category_link'];?>" title="<?php echo $blog['category_title'];?>"><?php echo $blog['category_title'];?></a></p></li>
			<?php } ?>
			
			<?php if( $config->get('cat_show_hits') ) { ?>
			<li><p><i class="icon-paper-clip"></i><span><?php echo $this->language->get("text_hits");?> <?php echo $blog['hits'];?></span></p></li>
			<?php } ?>
			
			<?php if( $config->get('cat_show_comment_counter') ) { ?>
			<li><p><i class="icon-comments"></i><span><?php echo $this->language->get("text_comment_count");?> <?php echo $blog['comment_count'];?></span></p></li>
			<?php } ?>
		</ul>
	</div>
	
	<div class="clearfix post-content">
		<?php if( $blog['thumb'] && $config->get('cat_show_image') )  { ?>
		<div class="post-img">
			<img src="<?php echo $blog['thumb'];?>" title="<?php echo $blog['title'];?>" />
		</div>
		<?php } ?>
		<?php if( $config->get('cat_show_description') ) {?>
		<div class="post-description"><?php echo $blog['description'];?></div>
		<?php } ?>
		<?php if( $config->get('cat_show_readmore') ) { ?>
		<a href="<?php echo $blog['link'];?>" class="post-more button"><?php echo $this->language->get('text_readmore');?></a>
		<?php } ?>
	</div>
</div>