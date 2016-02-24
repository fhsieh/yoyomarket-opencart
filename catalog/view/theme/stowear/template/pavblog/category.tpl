<?php echo $header; 
$theme_options = $this->registry->get('theme_options');
$config = $this->registry->get('config'); 
include('catalog/view/theme/'.$config->get('config_template').'/template/new_elements/wrapper_top.tpl'); ?>

  <div class="pav-category">
		<?php if( !empty($children) ) { ?>
		<div class="blog-children clearfix">
			<h3><?php echo $this->language->get('text_children');?></h3>
			<div class="row">
				
				<?php 
				$cols = (int)$config->get('children_columns');
				$columns = 12;
				if($cols == 2) { $columns = 6; }
				if($cols == 3) { $columns = 4; }
				if($cols == 4) { $columns = 3; }
				if($cols == 5) { $columns = 25; }
				if($cols == 6) { $columns = 2; }
				foreach( $children as $key => $sub )  { $key = $key + 1;?>
					<div class="col-sm-<?php echo $columns; ?>">
						<div class="children-inner">
							<h4>
							<a href="<?php echo $sub['link']; ?>" title="<?php echo $sub['title']; ?>"><?php echo $sub['title']; ?> (<?php echo $sub['count_blogs']; ?>)</a> 
							
							</h4>
							<?php if( $sub['thumb'] ) { ?>
								<img src="<?php echo $sub['thumb'];?>"/>
							<?php } ?>
							<div class="sub-description">
							<?php echo $sub['description']; ?>
							</div>
						</div>
					</div>
					<?php if( ( $key%$cols==0 ) ){ ?>
						</div><div class="row">
					<?php } ?>
				<?php } ?>
			</div>
		</div>
		<?php } ?>
		<div class="pav-blogs">
			<?php
			$key = 0;
			$cols = $config->get('cat_columns_leading_blog');
			$columns = 12;
			if($cols == 2) { $columns = 6; }
			if($cols == 3) { $columns = 4; }
			if($cols == 4) { $columns = 3; }
			if($cols == 5) { $columns = 25; }
			if($cols == 6) { $columns = 2; }
			if( count($leading_blogs) ) { ?>
				<div class="leading-blogs row">
					<?php foreach( $leading_blogs as $key => $blog ) { $key = $key + 1;?>
					<div class="col-sm-<?php echo $columns;?>">
					<?php require( '_item.tpl' ); ?>
					</div>
					<?php if( ( $key%$cols==0 ) ){ ?>
						</div><div class="leading-blogs row">
					<?php } ?>
					<?php } ?>
				</div>
			<?php } ?>

			<?php
				$cols = $config->get('cat_columns_secondary_blogs');
				$key = 0;
				$columns = 12;
				if($cols == 2) { $columns = 6; }
				if($cols == 3) { $columns = 4; }
				if($cols == 4) { $columns = 3; }
				if($cols == 5) { $columns = 25; }
				if($cols == 6) { $columns = 2; }
				if ( count($secondary_blogs) ) { ?>
				<div class="row">
					
					<?php foreach( $secondary_blogs as $key => $blog ) {  $key = $key+1; ?>
					<div class="col-sm-<?php echo $columns;?>">
					<?php require( '_item.tpl' ); ?>
					</div>
					<?php if( ( $key%$cols==0 ) ){ ?>
						</div><div class="row">
					<?php } ?>
					<?php } ?>
					
				</div>
			<?php } ?>
		</div>
  </div>
  
  <?php if( $total ) { ?>	
  <div class="pav-pagination pagination"><?php echo $pagination;?></div>
  <?php } ?>

<?php include('catalog/view/theme/'.$config->get('config_template').'/template/new_elements/wrapper_bottom.tpl'); ?>
<?php echo $footer; ?>