<div class="box ">
	<div class="box-heading"><?php echo $heading_title; ?></div>
	<div class="strip-line"></div>
	<div class="box-content box-blog-latest">
		<?php if( !empty($blogs) ) { ?>
		<div class="blog-latest row">
			<?php 
			$columns = 12;
			if($cols == 2) $columns = 6;
			if($cols == 3) $columns = 4;
			if($cols == 4) $columns = 3;
			if($cols == 5) $columns = 25;
			if($cols == 6) $columns = 2;
			foreach( $blogs as $key => $blog ) { $key = $key + 1;?>
			<div class="col-sm-<?php echo $columns; ?>">
				<?php if( $blog['thumb']  )  { ?>
				<img src="<?php echo $blog['thumb'];?>" title="<?php echo $blog['title'];?>" />
				<?php } ?>
				
				<h4><a href="<?php echo $blog['link'];?>" title="<?php echo $blog['title'];?>"><?php echo $blog['title'];?></a></h4>
							
				<div class="description">
					<?php echo utf8_substr( $blog['description'],0, 200 );?>
				</div>
			</div>
			<?php if( ( $key%$cols==0 || $key == count($blogs)) ){  ?>
				</div><div class="row">
			<?php } ?>
			<?php } ?>
		</div>
		<?php } ?>
	</div>
 </div>
