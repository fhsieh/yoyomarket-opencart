<?php echo $header;
$theme_options = $this->registry->get('theme_options');
$config = $this->registry->get('config');
include('catalog/view/theme/'.$config->get('config_template').'/template/new_elements/wrapper_top.tpl'); ?>

  <?php if ($products) { ?>
  <!-- Filter -->
  <div class="product-filter clearfix">
  	<div class="options">
  		<?php /* yym ?><div class="product-compare"><a href="<?php echo $compare; ?>" id="compare-total"><?php echo $text_compare; ?></a></div><?php */ ?>

  		<div class="button-group display" data-toggle="buttons-radio">
  			<button id="grid" <?php if($theme_options->get('default_list_grid') == '1') { echo 'class="active"'; } ?> rel="tooltip" title="Grid" onclick="display('grid');"><i class="fa fa-th-large"></i></button>
  			<button id="list" <?php if($theme_options->get('default_list_grid') != '1') { echo 'class="active"'; } ?> rel="tooltip" title="List" onclick="display('list');"><i class="fa fa-th-list"></i></button>
  		</div>
  	</div>

  	<div class="list-options">
  		<div class="sort">
  			<?php echo $text_sort; ?>
  			<select onchange="location = this.value;">
  			  <?php foreach ($sorts as $sorts) { ?>
  			  <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
  			  <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
  			  <?php } else { ?>
  			  <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
  			  <?php } ?>
  			  <?php } ?>
  			</select>
  		</div>

  		<div class="limit">
  			<?php echo $text_limit; ?>
  			<select onchange="location = this.value;">
  			  <?php foreach ($limits as $limits) { ?>
  			  <?php if ($limits['value'] == $limit) { ?>
  			  <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
  			  <?php } else { ?>
  			  <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
  			  <?php } ?>
  			  <?php } ?>
  			</select>
  		</div>
  	</div>
  </div>

  <!-- Products list -->
  <div class="product-list"<?php if($theme_options->get('default_list_grid') == '1') { echo ' style="display:none;"'; } ?>>
  	<?php foreach ($products as $product) { ?>
  	<!-- Product -->
  	<div>
  		<div class="row">
  			<div class="col-sm-3">
  				<div class="image <?php if($theme_options->get( 'product_image_effect' ) == '1') { echo 'image-swap-effect'; } ?>">
  	  				<?php if($product['special'] && $theme_options->get( 'display_text_sale' ) != '0') { ?>
  	  					<?php $text_sale = 'Sale';
  	  					if($theme_options->get( 'sale_text', $config->get( 'config_language_id' ) ) != '') {
  	  						$text_sale = $theme_options->get( 'sale_text', $config->get( 'config_language_id' ) );
  	  					} ?>
  	  					<?php if($theme_options->get( 'type_sale' ) == '1') { ?>
  	  					<?php $product_detail = $theme_options->getDataProduct( $product['product_id'] );
  	  					$roznica_ceny = $product_detail['price']-$product_detail['special'];
  	  					$procent = ($roznica_ceny*100)/$product_detail['price']; ?>
  	  					<div class="sale">-<?php echo round($procent); ?>%</div>
  	  					<?php } else { ?>
  	  					<div class="sale"><?php echo $text_sale; ?></div>
  	  					<?php } ?>
  	  				<?php } ?>

  	  				<?php if($product['thumb']) { ?>
  	  				<a href="<?php echo $product['href']; ?>">
  	  					<?php if($theme_options->get( 'product_image_effect' ) == '1') {
  	  						$nthumb = str_replace(' ', "%20", ($product['thumb']));
  	  						$image_size = getimagesize($nthumb);
  	  						$image_swap = $theme_options->productImageSwap($product['product_id'], $image_size[0], $image_size[1]);
  	  						if($image_swap != '') echo '<img src="' . $image_swap . '" alt="' . $product['name'] . '" class="swap-image" />';
  	  					} ?>
  	  					<img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>"  <?php if($theme_options->get( 'product_image_effect' ) == '2') { echo 'class="zoom-image-effect"'; } ?> />
  	  				</a>
  	  				<?php } else { ?>
  	  				<a href="<?php echo $product['href']; ?>"><img src="image/no_image.jpg" alt="<?php echo $product['name']; ?>" /></a>
  	  				<?php } ?>
  				</div>
  			</div>

  			<div class="col-sm-9">
  				<div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
  				<?php if ($product['rating']) { ?>
  				<div class="rating-reviews clearfix">
  					<div class="rating"><i class="fa fa-star<?php if($product['rating'] >= 1) { echo ' active'; } ?>"></i><i class="fa fa-star<?php if($product['rating'] >= 2) { echo ' active'; } ?>"></i><i class="fa fa-star<?php if($product['rating'] >= 3) { echo ' active'; } ?>"></i><i class="fa fa-star<?php if($product['rating'] >= 4) { echo ' active'; } ?>"></i><i class="fa fa-star<?php if($product['rating'] >= 5) { echo ' active'; } ?>"></i></div>
  				</div>
  				<?php } ?>
  				<div class="description"><?php echo $product['description']; ?></div>

  				<div class="price">
  					<?php if (!$product['special']) { ?>
  					<?php echo $product['price']; ?>
  					<?php } else { ?>
  					<span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
  					<?php } ?>
  				</div>

  				<div class="product-actions">
  	  				<?php if($theme_options->get( 'display_add_to_cart' ) != '0') { ?>
  	  				<div class="add-to-cart"><a onclick="cart.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-shopping-cart"></i> <?php echo $button_cart; ?></a></div>
  	  				<?php } ?>

  	  				<?php if($theme_options->get( 'display_add_to_wishlist' ) != '0') { ?>
  	  				<div><a onclick="wishlist.add('<?php echo $product['product_id']; ?>');" data-toggle="tooltip" data-original-title="<?php if($theme_options->get( 'add_to_wishlist_text', $config->get( 'config_language_id' ) ) != '') { echo $theme_options->get( 'add_to_wishlist_text', $config->get( 'config_language_id' ) ); } else { echo 'Add to wishlist'; } ?>"><i class="fa fa-heart"></i></a></div>
  	  				<?php } ?>

  	  				<?php if($theme_options->get( 'display_add_to_compare' ) != '0') { ?>
  	  				<div><a onclick="compare.add('<?php echo $product['product_id']; ?>');" data-toggle="tooltip" data-original-title="<?php if($theme_options->get( 'add_to_compare_text', $config->get( 'config_language_id' ) ) != '') { echo $theme_options->get( 'add_to_compare_text', $config->get( 'config_language_id' ) ); } else { echo 'Add to compare'; } ?>"><i class="fa fa-exchange"></i></a></div>
  	  				<?php } ?>

  	  				<?php if($theme_options->get( 'quick_view' ) == 1) { ?>
  	  				<div class="quickview" data-toggle="tooltip" data-original-title="<?php if($theme_options->get( 'quickview_text', $config->get( 'config_language_id' ) ) != '') { echo html_entity_decode($theme_options->get( 'quickview_text', $config->get( 'config_language_id' ) )); } else { echo 'Quickview'; } ?>">
  	  					<a rel="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>"><i class="fa fa-search"></i></a>
  	  				</div>
  	  				<?php } ?>
  				</div>

  			</div>
  		</div>
  	</div>
  	<?php } ?>
  </div>

  <!-- Products grid -->
  <?php
  $class = 3;
  $row = 4;

  if($theme_options->get( 'product_per_pow' ) == 6) $class = 2;
  if($theme_options->get( 'product_per_pow' ) == 5) $class = 25;
  if($theme_options->get( 'product_per_pow' ) == 3) $class = 4;

  if($theme_options->get( 'product_per_pow' ) > 1) $row = $theme_options->get( 'product_per_pow' ); ?>

  <div class="product-grid"<?php if(!($theme_options->get('default_list_grid') == '1')) { echo ' style="display:none;"'; } ?>>
  	<div class="row">
	  	<?php $row_fluid = 0; foreach ($products as $product) { $row_fluid++; ?>
		  	<?php $r=$row_fluid-floor($row_fluid/$row)*$row; if($row_fluid>$row && $r == 1) { echo '</div><div class="row">'; } ?>
		  	<div class="col-sm-<?php echo $class; ?> col-xs-6">
		  	    <?php include('catalog/view/theme/'.$config->get('config_template').'/template/new_elements/product.tpl'); ?>
		  	</div>
	    <?php } ?>
    </div>
  </div>

  <div class="row pagination-results">
    <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
  </div>
  <?php } else { ?>
  <p><?php echo $text_empty; ?></p>
  <div class="buttons">
    <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
  </div>
  <?php } ?>

<script type="text/javascript"><!--
function display(view) {

	if (view == 'list') {
		$('.product-grid').css("display", "none");
		$('.product-list').css("display", "block");

		$('.display').html('<button id="grid" rel="tooltip" title="Grid" onclick="display(\'grid\');"><i class="fa fa-th-large"></i></button> <button class="active" id="list" rel="tooltip" title="List" onclick="display(\'list\');"><i class="fa fa-th-list"></i></button>');

		$.cookie('display', 'list');
	} else {

		$('.product-grid').css("display", "block");
		$('.product-list').css("display", "none");

		$('.display').html('<button class="active" id="grid" rel="tooltip" title="Grid" onclick="display(\'grid\');"><i class="fa fa-th-large"></i></button> <button id="list" rel="tooltip" title="List" onclick="display(\'list\');"><i class="fa fa-th-list"></i></button>');

		$.cookie('display', 'grid');
	}
}

view = $.cookie('display');

if (view) {
	display(view);
}
//--></script>

<?php include('catalog/view/theme/'.$config->get('config_template').'/template/new_elements/wrapper_bottom.tpl'); ?>
<?php echo $footer; ?>