<?php
if($this->registry->has('theme_options') == false) {
	header("location: themeinstall/index.php");
	exit;
}

$theme_options = $this->registry->get('theme_options');
$config = $this->registry->get('config');
$model_url = $this->registry->get('url');

require_once( DIR_TEMPLATE.$config->get('config_template')."/lib/module.php" );
$modules = new Modules($this->registry);
?>
<!DOCTYPE html>
<!--[if IE 7]> <html lang="<?php echo $lang; ?>" class="ie7 <?php if($theme_options->get( 'responsive_design' ) == '0') { echo 'no-'; } ?>responsive"> <![endif]-->
<!--[if IE 8]> <html lang="<?php echo $lang; ?>" class="ie8 <?php if($theme_options->get( 'responsive_design' ) == '0') { echo 'no-'; } ?>responsive"> <![endif]-->
<!--[if IE 9]> <html lang="<?php echo $lang; ?>" class="ie9 <?php if($theme_options->get( 'responsive_design' ) == '0') { echo 'no-'; } ?>responsive"> <![endif]-->
<!--[if !IE]><!--> <html lang="<?php echo $lang; ?>" class="<?php if($theme_options->get( 'responsive_design' ) == '0') { echo 'no-'; } ?>responsive"> <!--<![endif]-->
<head>
	<title><?php echo $title; ?></title>
	<base href="<?php echo $base; ?>" />

	<!-- Meta -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<?php if($theme_options->get( 'responsive_design' ) != '0') { ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php } ?>
	<?php if ($description) { ?>
	<meta name="description" content="<?php echo $description; ?>" />
	<?php } ?>
	<?php if ($keywords) { ?>
	<meta name="keywords" content="<?php echo $keywords; ?>" />
	<?php } ?>

	<?php if ($icon) { ?>
	<link href="<?php echo $icon; ?>" rel="icon" />
	<?php } ?>
	<?php foreach ($links as $link) { ?>
	<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
	<?php } ?>

	<!-- Google Fonts -->
	<link href="//fonts.googleapis.com/css?family=Raleway:800,700,600,500,400,300,200" rel="stylesheet" type="text/css">
	<?php
	if( $theme_options->get( 'font_status' ) == '1' ) {
		$lista_fontow = array();
		if( $theme_options->get( 'body_font' ) != '' && $theme_options->get( 'body_font' ) != 'standard' && $theme_options->get( 'body_font' ) != 'Arial' && $theme_options->get( 'body_font' ) != 'Georgia' && $theme_options->get( 'body_font' ) != 'Times New Roman' ) {
			$font = $theme_options->get( 'body_font' );
			$lista_fontow[$font] = $font;
		}

		if( $theme_options->get( 'categories_bar' ) != '' && $theme_options->get( 'categories_bar' ) != 'standard' && $theme_options->get( 'categories_bar' ) != 'Arial' && $theme_options->get( 'categories_bar' ) != 'Georgia' && $theme_options->get( 'categories_bar' ) != 'Times New Roman' ) {
			$font = $theme_options->get( 'categories_bar' );
			if(!isset($lista_fontow[$font])) {
				$lista_fontow[$font] = $font;
			}
		}

		if( $theme_options->get( 'headlines' ) != '' && $theme_options->get( 'headlines' ) != 'standard' && $theme_options->get( 'headlines' ) != 'Arial' && $theme_options->get( 'headlines' ) != 'Georgia' && $theme_options->get( 'headlines' ) != 'Times New Roman' ) {
			$font = $theme_options->get( 'headlines' );
			if(!isset($lista_fontow[$font])) {
				$lista_fontow[$font] = $font;
			}
		}

		if( $theme_options->get( 'footer_headlines' ) != '' && $theme_options->get( 'footer_headlines' ) != 'standard'  && $theme_options->get( 'footer_headlines' ) != 'Arial' && $theme_options->get( 'footer_headlines' ) != 'Georgia' && $theme_options->get( 'footer_headlines' ) != 'Times New Roman' ) {
			$font = $theme_options->get( 'footer_headlines' );
			if(!isset($lista_fontow[$font])) {
				$lista_fontow[$font] = $font;
			}
		}

		if( $theme_options->get( 'page_name' ) != '' && $theme_options->get( 'page_name' ) != 'standard' && $theme_options->get( 'page_name' ) != 'Arial' && $theme_options->get( 'page_name' ) != 'Georgia' && $theme_options->get( 'page_name' ) != 'Times New Roman' ) {
			$font = $theme_options->get( 'page_name' );
			if(!isset($lista_fontow[$font])) {
				$lista_fontow[$font] = $font;
			}
		}

		if( $theme_options->get( 'button_font' ) != '' && $theme_options->get( 'button_font' ) != 'standard' && $theme_options->get( 'button_font' ) != 'Arial' && $theme_options->get( 'button_font' ) != 'Georgia' && $theme_options->get( 'button_font' ) != 'Times New Roman' ) {
			$font = $theme_options->get( 'button_font' );
			if(!isset($lista_fontow[$font])) {
				$lista_fontow[$font] = $font;
			}
		}

		if( $theme_options->get( 'custom_price' ) != '' && $theme_options->get( 'custom_price' ) != 'standard' && $theme_options->get( 'custom_price' ) != 'Arial' && $theme_options->get( 'custom_price' ) != 'Georgia' && $theme_options->get( 'custom_price' ) != 'Times New Roman' ) {
			$font = $theme_options->get( 'custom_price' );
			if(!isset($lista_fontow[$font])) {
				$lista_fontow[$font] = $font;
			}
		}

		foreach($lista_fontow as $font) {
			echo '<link href="//fonts.googleapis.com/css?family=' . $font . ':800,700,600,500,400,300" rel="stylesheet" type="text/css">';
			echo "\n";
		}
	}
	?>

	<?php $lista_plikow = array(
			'catalog/view/theme/'.$config->get( 'config_template' ).'/css/bootstrap.css',
			'catalog/view/theme/'.$config->get( 'config_template' ).'/css/stylesheet.css',
			'catalog/view/theme/'.$config->get( 'config_template' ).'/css/responsive.css',
			'catalog/view/theme/'.$config->get( 'config_template' ).'/css/blog.css',
			'catalog/view/theme/'.$config->get( 'config_template' ).'/css/owl.carousel.css',
			'catalog/view/theme/'.$config->get( 'config_template' ).'/css/menu.css',
			'catalog/view/javascript/font-awesome/css/font-awesome.min.css'
	);

	// Camera slider
	if($config->get( 'camera_slider_module' ) != '') $lista_plikow[] = 'catalog/view/theme/'.$config->get( 'config_template' ).'/css/camera_slider.css';

	// Category wall
	if($config->get( 'category_wall_module' ) != '') $lista_plikow[] = 'catalog/view/theme/'.$config->get( 'config_template' ).'/css/category_wall.css';

	// Filter product
	if($config->get( 'filter_product_module' ) != '') $lista_plikow[] = 'catalog/view/theme/'.$config->get( 'config_template' ).'/css/filter_product.css';

	// Revolution slider
	if($config->get( 'revolution_slider_module' ) != '') $lista_plikow[] = 'catalog/view/theme/'.$config->get( 'config_template' ).'/css/slider.css';

	// Carousel brands
	if($config->get( 'carousel_module' ) != '') $lista_plikow[] = 'catalog/view/theme/'.$config->get( 'config_template' ).'/css/carousel.css';

	// Wide width
	if($theme_options->get( 'page_width' ) == 1) $lista_plikow[] = 'catalog/view/theme/'.$config->get( 'config_template' ).'/css/wide-grid.css';

	// Normal width
	if($theme_options->get( 'page_width' ) == 3) $lista_plikow[] = 'catalog/view/theme/'.$config->get( 'config_template' ).'/css/standard-grid.css';

	// Nivo slider
	if($config->get( 'slideshow_module' ) != '') $lista_plikow[] = 'catalog/view/theme/' . $config->get( 'config_template' ) . '/css/nivo-slider.css';  ?>

	<?php echo $theme_options->compressorCodeCss( $config->get( 'config_template' ), $lista_plikow, $theme_options->get( 'compressor_code_status' ), HTTP_SERVER ); ?>

	<?php if($theme_options->get( 'background_status' ) == 1 || $theme_options->get( 'colors_status' ) == 1 || $theme_options->get( 'font_status' ) == 1) { ?>
	<style type="text/css">
	     <?php if($theme_options->get( 'colors_status' ) == '1') { ?>
	     	<?php if($theme_options->get( 'body_text_color' ) == '#737373' || $theme_options->get( 'body_fixed_content_background_color' ) == '#121212' || $theme_options->get( 'dropdown_background_color' ) == '#121212') { ?>
	     	.mini-cart-total,
	     	.box-category ul ul,
	     	.box-category ul ul:before,
	     	.ui-autocomplete li,
	     	div.pagination-results {
	     		background: url(catalog/view/theme/<?php echo $config->get( 'config_template' ); ?>/img/bg-menu2.png) top left repeat-x;
	     	}

	     	.standard-body .copyright .background,
	     	.copyright .background {
	     		background-image: url(catalog/view/theme/<?php echo $config->get( 'config_template' ); ?>/img/bg-menu2.png);
	     	}

	     	.center-column h1,
	     	.center-column h2,
	     	.center-column h3,
	     	.center-column h4,
	     	.center-column h5,
	     	.center-column h6,
	     	.box .box-heading,
	     	.refine_search,
	     	.category-list-text-only,
	     	.product-info .description,
	     	.product-info .price,
	     	.product-info .options,
	     	.product-info .cart,
	     	#quickview .description,
	     	#quickview .price,
	     	#quickview .options,
	     	#quickview .cart,
	     	.product-block .title-block,
	     	.custom-footer h4,
	     	.footer h4,
	     	.htabs,
	     	.center-column .tab-content,
	     	.filter-product .filter-tabs,
	     	ul.megamenu .sub-menu .content > .border,
	     	ul.megamenu li .sub-menu .content .static-menu a.main-menu {
	     		background: url(catalog/view/theme/<?php echo $config->get( 'config_template' ); ?>/img/bg-menu2.png) bottom left repeat-x;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'body_text_color' ) != '') { ?>
	     	body,
	     	.center-column .tab-content {
	     		color: <?php echo $theme_options->get( 'body_text_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'body_headlines_color' ) != '') { ?>
	     	h1,
	     	h2,
	     	h3,
	     	h4,
	     	h5,
	     	h6,
	     	.center-column h1,
	     	.center-column h2,
	     	.center-column h3,
	     	.center-column h4,
	     	.center-column h5,
	     	.center-column h6,
	     	.box .box-heading,
	     	.product-block .title-block {
	     		color: <?php echo $theme_options->get( 'body_headlines_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'body_links_color' ) != '') { ?>
	     	a,
	     	.box-category ul li .head a {
	     		color: <?php echo $theme_options->get( 'body_links_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'body_links_hover_color' ) != '') { ?>
	     	a:hover,
	     	div.testimonial p {
	     		color: <?php echo $theme_options->get( 'body_links_hover_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'price_text_color' ) != '') { ?>
	     	.table .price-new,
	     	.product-grid .product .price,
	     	.product-list .actions > div .price,
	     	.product-info .price .price-new,
	     	ul.megamenu li .product .price,
	     	.mini-cart-total td:last-child,
	     	.cart-total table tr td:last-child,
	     	.mini-cart-info td.total,
	     	#quickview .price .price-new,
	     	.product-list .price {
	     		color: <?php echo $theme_options->get( 'price_text_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'price_new_text_color' ) != '') { ?>
	     	.product-grid .product .price .price-new,
	     	.product-list .price .price-new,
	     	.table .price-new {
	     		color: <?php echo $theme_options->get( 'price_new_text_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'price_old_text_color' ) != '') { ?>
	     	.price-old {
	     		color: <?php echo $theme_options->get( 'price_old_text_color' ); ?> !important;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'body_background_color' ) != '') { ?>
	     	body {
	     		background: <?php echo $theme_options->get( 'body_background_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'body_fixed_content_background_color' ) != '') { ?>
	     	.main-fixed,
	     	.standard-body .fixed .background {
	     		background: <?php echo $theme_options->get( 'body_fixed_content_background_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'dropdown_text_color' ) != '') { ?>
	     	.dropdown-menu,
	     	.dropdown-menu a,
	     	.ui-autocomplete li a,
	     	.mini-cart-info .remove a,
	     	.modal-content,
	     	.modal-content .close {
	     		color: <?php echo $theme_options->get( 'dropdown_text_color' ); ?>;
	     	}

	     	.modal-content .close {
	     		text-shadow: none;
	     		opacity: 1;
	     	}

	     	.modal-header {
	     		background: url(catalog/view/theme/<?php echo $config->get( 'config_template' ); ?>/img/bg-menu.png) bottom left repeat-x;
	     		border: none;
	     	}

	     	.mini-cart-info .remove a {
	     		border-color: <?php echo $theme_options->get( 'dropdown_text_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'dropdown_border_color' ) != '') { ?>
	     	.dropdown-menu,
	     	.ui-autocomplete,
	     	#top #cart_block.open .cart-heading {
	     		border-color: <?php echo $theme_options->get( 'dropdown_border_color' ); ?>;
	     	}

	     	.bootstrap-datetimepicker-widget:after {
	     		border-bottom-color: <?php echo $theme_options->get( 'dropdown_border_color' ); ?> !important;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'dropdown_background_color' ) != '') { ?>
	     	.dropdown-menu,
	     	.ui-autocomplete,
	     	.modal-content,
	     	.modal-footer,
	     	.review-list .text {
	     		background: <?php echo $theme_options->get( 'dropdown_background_color' ); ?>;
	     	}

	     	.review-list .text:after {
	     		border-bottom-color: <?php echo $theme_options->get( 'dropdown_background_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'dropdown_item_hover_background_color' ) != '') { ?>
	     	.dropdown-menu > li > a:hover,
	     	.dropdown-menu > li > a:focus,
	     	.ui-autocomplete li a.ui-state-focus {
	     		background-color: <?php echo $theme_options->get( 'dropdown_item_hover_background_color' ); ?>;
	     	}

	     	@media (max-width: 960px) {
	     		.responsive ul.megamenu > li.active > a,
	     		.responsive ul.megamenu > li:hover > a,
	     		.responsive ul.megamenu > li.active .close-menu {
	     			background-color: <?php echo $theme_options->get( 'dropdown_item_hover_background_color' ); ?> !important;
	     		}
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'input_text_color' ) != '') { ?>
	     	textarea,
	     	input[type="text"],
	     	input[type="password"],
	     	input[type="datetime"],
	     	input[type="datetime-local"],
	     	input[type="date"],
	     	input[type="month"],
	     	input[type="time"],
	     	input[type="week"],
	     	input[type="number"],
	     	input[type="email"],
	     	input[type="url"],
	     	input[type="search"],
	     	input[type="tel"],
	     	input[type="color"],
	     	.uneditable-input,
	     	select,
	     	.product-info .cart .add-to-cart .quantity #q_up,
	     	.product-info .cart .add-to-cart .quantity #q_down {
	     		color: <?php echo $theme_options->get( 'input_text_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'input_border_color' ) != '') { ?>
	     	textarea,
	     	input[type="text"],
	     	input[type="password"],
	     	input[type="datetime"],
	     	input[type="datetime-local"],
	     	input[type="date"],
	     	input[type="month"],
	     	input[type="time"],
	     	input[type="week"],
	     	input[type="number"],
	     	input[type="email"],
	     	input[type="url"],
	     	input[type="search"],
	     	input[type="tel"],
	     	input[type="color"],
	     	.uneditable-input,
	     	select,
	     	.product-info .cart .add-to-cart .quantity #q_up,
	     	.product-info .cart .add-to-cart .quantity #q_down {
	     		border-color: <?php echo $theme_options->get( 'input_border_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'input_focus_border_color' ) != '') { ?>
	     	textarea:focus,
	     		input[type="text"]:focus,
	     		input[type="password"]:focus,
	     		input[type="datetime"]:focus,
	     		input[type="datetime-local"]:focus,
	     		input[type="date"]:focus,
	     		input[type="month"]:focus,
	     		input[type="time"]:focus,
	     		input[type="week"]:focus,
	     		input[type="number"]:focus,
	     		input[type="email"]:focus,
	     		input[type="url"]:focus,
	     		input[type="search"]:focus,
	     		input[type="tel"]:focus,
	     		input[type="color"]:focus,
	     		.uneditable-input:focus {
	     		border-color: <?php echo $theme_options->get( 'input_focus_border_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'input_background_color' ) != '') { ?>
	     	textarea,
	     	input[type="text"],
	     	input[type="password"],
	     	input[type="datetime"],
	     	input[type="datetime-local"],
	     	input[type="date"],
	     	input[type="month"],
	     	input[type="time"],
	     	input[type="week"],
	     	input[type="number"],
	     	input[type="email"],
	     	input[type="url"],
	     	input[type="search"],
	     	input[type="tel"],
	     	input[type="color"],
	     	.uneditable-input,
	     	select {
	     		background-color: <?php echo $theme_options->get( 'input_background_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'table_border_color' ) != '') { ?>
	     	table.attribute,
	     	table.list,
	     	.wishlist-product table,
	     	.wishlist-info table,
	     	.compare-info,
	     	.cart-info table,
	     	.checkout-product table,
	     	.table,
	     	table.attribute td,
	     	table.list td,
	     	.wishlist-product table td,
	     	.wishlist-info table td,
	     	.compare-info td,
	     	.cart-info table td,
	     	.checkout-product table td,
	     	.table td ,
	     	.manufacturer-list,
	     	.manufacturer-heading,
	     	.center-column .panel-body,
	     	.review-list .text {
	     		border-color: <?php echo $theme_options->get( 'table_border_color' ); ?>;
	     	}

	     	.review-list .text:before {
	     		border-bottom-color: <?php echo $theme_options->get( 'table_border_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'top_bar_text_color' ) != '') { ?>
	     	#top-bar .container,
	     	#top-bar .container > div > div > div > a,
	     	#top-bar .container > div > div > form > div > a,
	     	#top-bar .top-bar-links li a {
	     		color: <?php echo $theme_options->get( 'top_bar_text_color' ); ?>;
	     	}

	     	#top-bar .dropdown .caret {
	     		border-top-color: <?php echo $theme_options->get( 'top_bar_text_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'top_bar_border_color' ) != '') { ?>
	     	#top-bar .dropdown {
	     		border-color: <?php echo $theme_options->get( 'top_bar_border_color' ); ?> !important;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'top_bar_background_color' ) != '') { ?>
	     	#top-bar .background {
	     		background-color: <?php echo $theme_options->get( 'top_bar_background_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'top_links_color' ) != '') { ?>
	     	#top .header-links li a {
	     		color: <?php echo $theme_options->get( 'top_links_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'search_input_text_color' ) != '') { ?>
	     	#top .search_form input,
	     	.search_form .button-search,
	     	.search_form .button-search2 {
	     		color: <?php echo $theme_options->get( 'search_input_text_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'search_input_border_color' ) != '') { ?>
	     	#top .search_form input {
	     		border-color: <?php echo $theme_options->get( 'search_input_border_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'search_input_focus_border_color' ) != '') { ?>
	     	#top .search_form input:focus {
	     		border-color: <?php echo $theme_options->get( 'search_input_focus_border_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'search_input_background_color' ) != '') { ?>
	     	#top .search_form input {
	     		background-color: <?php echo $theme_options->get( 'search_input_background_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'price_in_cart_color' ) != '') { ?>
	     	#top #cart_block .cart-heading span {
	     		color: <?php echo $theme_options->get( 'price_in_cart_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'top_background_gradient_top' ) != '' && $theme_options->get( 'top_background_gradient_bottom' ) != '') { ?>
	     	#top .background {
	     		background: <?php echo $theme_options->get( 'top_background_gradient_bottom' ); ?>; /* Old browsers */
	     		background: -moz-linear-gradient(top, <?php echo $theme_options->get( 'top_background_gradient_bottom' ); ?> 0%, <?php echo $theme_options->get( 'top_background_gradient_top' ); ?> 0%, <?php echo $theme_options->get( 'top_background_gradient_bottom' ); ?> 99%); /* FF3.6+ */
	     		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,<?php echo $theme_options->get( 'top_background_gradient_bottom' ); ?>), color-stop(0%,<?php echo $theme_options->get( 'top_background_gradient_top' ); ?>), color-stop(99%,<?php echo $theme_options->get( 'top_background_gradient_bottom' ); ?>)); /* Chrome,Safari4+ */
	     		background: -webkit-linear-gradient(top, <?php echo $theme_options->get( 'top_background_gradient_bottom' ); ?> 0%,<?php echo $theme_options->get( 'top_background_gradient_top' ); ?> 0%,<?php echo $theme_options->get( 'top_background_gradient_bottom' ); ?> 99%); /* Chrome10+,Safari5.1+ */
	     		background: -o-linear-gradient(top, <?php echo $theme_options->get( 'top_background_gradient_bottom' ); ?> 0%,<?php echo $theme_options->get( 'top_background_gradient_top' ); ?> 0%,<?php echo $theme_options->get( 'top_background_gradient_bottom' ); ?> 99%); /* Opera 11.10+ */
	     		background: -ms-linear-gradient(top, <?php echo $theme_options->get( 'top_background_gradient_bottom' ); ?> 0%,<?php echo $theme_options->get( 'top_background_gradient_top' ); ?> 0%,<?php echo $theme_options->get( 'top_background_gradient_bottom' ); ?> 99%); /* IE10+ */
	     		background: linear-gradient(to bottom, <?php echo $theme_options->get( 'top_background_gradient_bottom' ); ?> 0%,<?php echo $theme_options->get( 'top_background_gradient_top' ); ?> 0%,<?php echo $theme_options->get( 'top_background_gradient_bottom' ); ?> 99%); /* W3C */
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'menu_main_links_color' ) != '') { ?>
	     	ul.megamenu > li > a,
	     	.megamenuToogle-wrapper .container .background-megamenu {
	     		color: <?php echo $theme_options->get( 'menu_main_links_color' ); ?>;
	     	}

	     	.megamenuToogle-wrapper .container > div span {
	     		background: <?php echo $theme_options->get( 'menu_main_links_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'menu_main_links_hover_color' ) != '') { ?>
	     	ul.megamenu > li > a:hover,
	     	ul.megamenu > li.active > a {
	     		color: <?php echo $theme_options->get( 'menu_main_links_hover_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'menu_main_links_hover_border_bottom' ) != '') { ?>
	     	ul.megamenu > li > a:before {
	     		background: <?php echo $theme_options->get( 'menu_main_links_hover_border_bottom' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'menu_icon_home_color' ) != '') { ?>
	     	ul.megamenu > li > a > .fa-home {
	     		color: <?php echo $theme_options->get( 'menu_icon_home_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'menu_background_gradient_top' ) != '' && $theme_options->get( 'menu_background_gradient_bottom' ) != '') { ?>
	     	.megamenu-wrapper,
	     	.megamenuToogle-wrapper {
	     		background: <?php echo $theme_options->get( 'menu_background_gradient_bottom' ); ?>; /* Old browsers */
	     		background: -moz-linear-gradient(top, <?php echo $theme_options->get( 'menu_background_gradient_bottom' ); ?> 0%, <?php echo $theme_options->get( 'menu_background_gradient_top' ); ?> 0%, <?php echo $theme_options->get( 'menu_background_gradient_bottom' ); ?> 99%); /* FF3.6+ */
	     		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,<?php echo $theme_options->get( 'menu_background_gradient_bottom' ); ?>), color-stop(0%,<?php echo $theme_options->get( 'menu_background_gradient_top' ); ?>), color-stop(99%,<?php echo $theme_options->get( 'menu_background_gradient_bottom' ); ?>)); /* Chrome,Safari4+ */
	     		background: -webkit-linear-gradient(top, <?php echo $theme_options->get( 'menu_background_gradient_bottom' ); ?> 0%,<?php echo $theme_options->get( 'menu_background_gradient_top' ); ?> 0%,<?php echo $theme_options->get( 'menu_background_gradient_bottom' ); ?> 99%); /* Chrome10+,Safari5.1+ */
	     		background: -o-linear-gradient(top, <?php echo $theme_options->get( 'menu_background_gradient_bottom' ); ?> 0%,<?php echo $theme_options->get( 'menu_background_gradient_top' ); ?> 0%,<?php echo $theme_options->get( 'menu_background_gradient_bottom' ); ?> 99%); /* Opera 11.10+ */
	     		background: -ms-linear-gradient(top, <?php echo $theme_options->get( 'menu_background_gradient_bottom' ); ?> 0%,<?php echo $theme_options->get( 'menu_background_gradient_top' ); ?> 0%,<?php echo $theme_options->get( 'menu_background_gradient_bottom' ); ?> 99%); /* IE10+ */
	     		background: linear-gradient(to bottom, <?php echo $theme_options->get( 'menu_background_gradient_bottom' ); ?> 0%,<?php echo $theme_options->get( 'menu_background_gradient_top' ); ?> 0%,<?php echo $theme_options->get( 'menu_background_gradient_bottom' ); ?> 99%); /* W3C */
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'menu_icon_plus_minus_color' ) != '') { ?>
	     	@media (max-width: 960px) {
	     		.responsive ul.megamenu > li.click:before,
	     		.responsive ul.megamenu > li.hover:before,
	     		.responsive ul.megamenu > li.active .close-menu:before {
	     			color: <?php echo $theme_options->get( 'menu_icon_plus_minus_color' ); ?>;
	     		}
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'submenu_text_color' ) != '') { ?>
	     	ul.megamenu li .sub-menu .content {
	     		color: <?php echo $theme_options->get( 'submenu_text_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'submenu_link_color' ) != '') { ?>
	     	ul.megamenu li .sub-menu .content a {
	     		color: <?php echo $theme_options->get( 'submenu_link_color' ); ?>;
	     	}

	     	@media (max-width: 960px) {
	     		.responsive ul.megamenu > li > a {
	     			color: <?php echo $theme_options->get( 'submenu_link_color' ); ?>;
	     		}
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'submenu_link_hover_color' ) != '') { ?>
	     	ul.megamenu li .sub-menu .content a:hover {
	     		color: <?php echo $theme_options->get( 'submenu_link_hover_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'submenu_border_color' ) != '') { ?>
	     	ul.megamenu li .sub-menu .content {
	     		border-color: <?php echo $theme_options->get( 'submenu_border_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'submenu_background_color' ) != '') { ?>
	     	ul.megamenu li .sub-menu .content {
	     		background-color: <?php echo $theme_options->get( 'submenu_background_color' ); ?>;
	     	}

	     	@media (max-width: 960px) {
	     		.responsive .megamenu-wrapper {
	     			background-color: <?php echo $theme_options->get( 'submenu_background_color' ); ?> !important;
	     		}
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'button_text_color' ) != '') { ?>
	     	.button,
	     	.btn {
	     		color: <?php echo $theme_options->get( 'button_text_color' ); ?> !important;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'button_background_color' ) != '') { ?>
	     	.button,
	     	.btn {
	     		background: <?php echo $theme_options->get( 'button_background_color' ); ?>;
	     		border-color: <?php echo $theme_options->get( 'button_background_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'button_hover_text_color' ) != '') { ?>
	     	.button:hover,
	     	.btn:hover {
	     		color: <?php echo $theme_options->get( 'button_hover_text_color' ); ?> !important;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'button_hover_background_color' ) != '') { ?>
	     	.button:hover,
	     	.btn:hover {
	     		background: <?php echo $theme_options->get( 'button_hover_background_color' ); ?>;
	     		border-color: <?php echo $theme_options->get( 'button_hover_background_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'second_button_text_color' ) != '') { ?>
	     	.buttons .left .button,
	     	.buttons .center .button,
	     	.btn-default,
	     	.input-group-btn .btn-primary {
	     		color: <?php echo $theme_options->get( 'second_button_text_color' ); ?> !important;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'second_button_border_color' ) != '') { ?>
	     	.buttons .left .button,
	     	.buttons .center .button,
	     	.btn-default,
	     	.input-group-btn .btn-primary{
	     		border-color: <?php echo $theme_options->get( 'second_button_border_color' ); ?> !important;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'second_button_background_color' ) != '') { ?>
	     	.buttons .left .button,
	     	.buttons .center .button,
	     	.btn-default,
	     	.input-group-btn .btn-primary{
	     		background-color: <?php echo $theme_options->get( 'second_button_background_color' ); ?> !important;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'second_button_hover_text_color' ) != '') { ?>
	     	.buttons .left .button:hover,
	     	.buttons .center .button:hover,
	     	.btn-default:hover,
	     	.input-group-btn .btn-primary:hover {
	     		color: <?php echo $theme_options->get( 'second_button_hover_text_color' ); ?> !important;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'second_button_hover_border_color' ) != '') { ?>
	     	.buttons .left .button:hover,
	     	.buttons .center .button:hover,
	     	.btn-default:hover,
	     	.input-group-btn .btn-primary:hover {
	     		border-color: <?php echo $theme_options->get( 'second_button_hover_border_color' ); ?> !important;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'second_button_hover_background_color' ) != '') { ?>
	     	.buttons .left .button:hover,
	     	.buttons .center .button:hover,
	     	.btn-default:hover,
	     	.input-group-btn .btn-primary:hover {
	     		background-color: <?php echo $theme_options->get( 'second_button_hover_background_color' ); ?> !important;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'carousel_button_background' ) != '') { ?>
	     	.tab-content .prev-button,
	     	.tab-content .next-button,
	     	.box > .prev,
	     	.box > .next,
	     	.carousel-brands .owl-prev,
	     	.carousel-brands .owl-next {
	     		background: <?php echo $theme_options->get( 'carousel_button_background' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'carousel_button_hover_background' ) != '') { ?>
	     	.tab-content .prev-button:hover,
	     	.tab-content .next-button:hover,
	     	.box > .prev:hover,
	     	.box > .next:hover,
	     	.carousel-brands .owl-prev:hover,
	     	.carousel-brands .owl-next:hover {
	     		background: <?php echo $theme_options->get( 'carousel_button_hover_background' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'carousel_bullet_background' ) != '') { ?>
	     	.carousel-indicators li {
	     		background: <?php echo $theme_options->get( 'carousel_bullet_background' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'carousel_bullet_active_background' ) != '') { ?>
	     	.carousel-indicators .active {
	     		background: <?php echo $theme_options->get( 'carousel_bullet_active_background' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'slider_button_background' ) != '') { ?>
	     	.camera_wrap .owl-controls .owl-buttons .owl-next:before,
	     	.camera_wrap .owl-controls .owl-buttons .owl-prev:before,
	     	.nivo-directionNav a,
	     	.fullwidthbanner-container .tp-leftarrow,
	     	.fullwidthbanner-container .tp-rightarrow {
	     		background: <?php echo $theme_options->get( 'slider_button_background' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'slider_bullet_background' ) != '') { ?>
	     	.camera_wrap .owl-controls .owl-pagination span,
	     	.tp-bullets .bullet {
	     		background: <?php echo $theme_options->get( 'slider_bullet_background' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'slider_bullet_active_background' ) != '') { ?>
	     	.tp-bullets .selected,
	     	.tp-bullets .bullet:hover,
	     	.camera_wrap .owl-controls .owl-pagination .active span {
	     		background: <?php echo $theme_options->get( 'slider_bullet_active_background' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'product_grid_button_text_color' ) != '') { ?>
	     	.product-grid .product .image .product-actions a {
	     		color: <?php echo $theme_options->get( 'product_grid_button_text_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'product_grid_button_background_color' ) != '') { ?>
	     	.product-grid .product .image .product-actions a {
	     		background: <?php echo $theme_options->get( 'product_grid_button_background_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'product_grid_button_hover_text_color' ) != '') { ?>
	     	.product-grid .product .image .product-actions a:hover {
	     		color: <?php echo $theme_options->get( 'product_grid_button_hover_text_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'product_grid_button_hover_background_color' ) != '') { ?>
	     	.product-grid .product .image .product-actions a:hover {
	     		background: <?php echo $theme_options->get( 'product_grid_button_hover_background_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'product_list_button_text_color' ) != '') { ?>
	     	.product-list .product-actions div a {
	     		color: <?php echo $theme_options->get( 'product_list_button_text_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'product_list_button_border_color' ) != '') { ?>
	     	.product-list .product-actions div a {
	     		border-color: <?php echo $theme_options->get( 'product_list_button_border_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'product_list_button_background_color' ) != '') { ?>
	     	.product-list .product-actions div a {
	     		background-color: <?php echo $theme_options->get( 'product_list_button_background_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'product_list_button_hover_text_color' ) != '') { ?>
	     	.product-list .product-actions div a:hover {
	     		color: <?php echo $theme_options->get( 'product_list_button_hover_text_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'product_list_button_hover_border_color' ) != '') { ?>
	     	.product-list .product-actions div a:hover {
	     		border-color: <?php echo $theme_options->get( 'product_list_button_hover_border_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'product_list_button_hover_background_color' ) != '') { ?>
	     	.product-list .product-actions div a:hover {
	     		background-color: <?php echo $theme_options->get( 'product_list_button_hover_background_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'sale_color_text' ) != '') { ?>
	     	.sale {
	     		color: <?php echo $theme_options->get( 'sale_color_text' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'sale_background_color' ) != '') { ?>
	     	.sale {
	     		background: <?php echo $theme_options->get( 'sale_background_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'rating_icon_background_color' ) != '') { ?>
	     	.rating i {
	     		color: <?php echo $theme_options->get( 'rating_icon_background_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'rating_icon_active_background_color' ) != '') { ?>
	     	.rating i.active {
	     		color: <?php echo $theme_options->get( 'rating_icon_active_background_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'custom_block_border_color' ) != '') { ?>
	     	.product-block {
	     		border-color: <?php echo $theme_options->get( 'custom_block_border_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'box_categories_border_color' ) != '') { ?>
	     	.box-category {
	     		border-color: <?php echo $theme_options->get( 'box_categories_border_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'box_categories_links_active_color' ) != '') { ?>
	     	.box-category ul li a.active {
	     		color: <?php echo $theme_options->get( 'box_categories_links_active_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'product_filter_icon_color' ) != '') { ?>
	     	.product-filter .options .button-group button {
	     		color: <?php echo $theme_options->get( 'product_filter_icon_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'product_filter_icon_hover_color' ) != '') { ?>
	     	.product-filter .options .button-group button:hover,
	     	.product-filter .options .button-group .active {
	     		color: <?php echo $theme_options->get( 'product_filter_icon_hover_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'tab_link_color' ) != '') { ?>
	     	.htabs a,
	     	.filter-product .filter-tabs ul > li > a {
	     		color: <?php echo $theme_options->get( 'tab_link_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'tab_link_active_color' ) != '') { ?>
	     	.htabs a.selected,
	     	.htabs a:hover,
	     	.filter-product .filter-tabs ul > li.active > a,
	     	.filter-product .filter-tabs ul > li > a:hover,
	     	.filter-product .filter-tabs ul > li.active > a:focus {
	     		color: <?php echo $theme_options->get( 'tab_link_active_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'tab_link_active_border_color' ) != '') { ?>
	     	.htabs a.selected {
	     		border-color: <?php echo $theme_options->get( 'tab_link_active_border_color' ); ?>;
	     	}

	     	.filter-product .filter-tabs ul > li:before {
	     		background: <?php echo $theme_options->get( 'tab_link_active_border_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'pagination_link_border_color' ) != '') { ?>
	     	div.pagination-results ul li {
	     		border-color: <?php echo $theme_options->get( 'pagination_link_border_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'pagination_link_active_border_color' ) != '') { ?>
	     	div.pagination-results ul li.active {
	     		border-color: <?php echo $theme_options->get( 'pagination_link_active_border_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'customfooter_text_color' ) != '') { ?>
	     	.custom-footer .pattern,
	     	ul.contact-us li {
	     		color: <?php echo $theme_options->get( 'customfooter_text_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'customfooter_headlines_color' ) != '') { ?>
	     	.custom-footer h4 {
	     		color: <?php echo $theme_options->get( 'customfooter_headlines_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'customfooter_icon_phone_background_color' ) != '') { ?>
	     	ul.contact-us li i.fa-phone {
	     		background: <?php echo $theme_options->get( 'customfooter_icon_phone_background_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'customfooter_icon_mail_background_color' ) != '') { ?>
	     	ul.contact-us li i.fa-envelope {
	     		background: <?php echo $theme_options->get( 'customfooter_icon_mail_background_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'customfooter_icon_skype_background_color' ) != '') { ?>
	     	ul.contact-us li i.fa-skype {
	     		background: <?php echo $theme_options->get( 'customfooter_icon_skype_background_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'customfooter_background_color' ) != '') { ?>
	     	.custom-footer .background,
	     	.standard-body .custom-footer .background {
	     		background: <?php echo $theme_options->get( 'customfooter_background_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'footer_text_color' ) != '') { ?>
	     	.footer .pattern,
	     	.footer .pattern a,
	     	.copyright .pattern,
	     	.copyright .pattern a {
	     		color: <?php echo $theme_options->get( 'footer_text_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'footer_headlines_color' ) != '') { ?>
	     	.footer h4 {
	     		color: <?php echo $theme_options->get( 'footer_headlines_color' ); ?>;
	     	}
	     	<?php } ?>

	     	<?php if($theme_options->get( 'footer_background_color' ) != '') { ?>
	     	.footer .background,
	     	.standard-body .footer .background,
	     	.copyright .background,
	     	.standard-body .copyright .background {
	     		background-color: <?php echo $theme_options->get( 'footer_background_color' ); ?>;
	     	}
	     	<?php } ?>
	     <?php } ?>

	     <?php if($theme_options->get( 'font_status' ) == 1) { ?>
	     body {
	     	font-size: <?php echo $theme_options->get( 'body_font_px' ); ?>px;
	     	font-weight: <?php echo $theme_options->get( 'body_font_weight' )*100; ?>;
	     	<?php if( $theme_options->get( 'body_font' ) != '' && $theme_options->get( 'body_font' ) != 'standard' ) { ?>
	     	font-family: <?php echo $theme_options->get( 'body_font' ); ?>;
	     	<?php } ?>
	     }

	     #top-bar .container,
	     #top .header-links li a,
	     .sale {
	     	font-size: <?php echo $theme_options->get( 'body_font_smaller_px' ); ?>px;
	     }

	     ul.megamenu > li > a strong {
	     	font-size: <?php echo $theme_options->get( 'categories_bar_px' ); ?>px;
	     	font-weight: <?php echo $theme_options->get( 'categories_bar_weight' )*100; ?>;
	     	<?php if( $theme_options->get( 'categories_bar_font' ) != '' && $theme_options->get( 'categories_bar_font' ) != 'standard' ) { ?>
	     	font-family: <?php echo $theme_options->get( 'categories_bar_font' ); ?>;
	     	<?php } ?>
	     }

	     .megamenuToogle-wrapper .container {
	     	font-weight: <?php echo $theme_options->get( 'categories_bar_weight' )*100; ?>;
	     	<?php if( $theme_options->get( 'categories_bar_font' ) != '' && $theme_options->get( 'categories_bar_font' ) != 'standard' ) { ?>
	     	font-family: <?php echo $theme_options->get( 'categories_bar_font' ); ?>;
	     	<?php } ?>
	     }

	     .box .box-heading,
	     .center-column h1,
	     .center-column h2,
	     .center-column h3,
	     .center-column h4,
	     .center-column h5,
	     .center-column h6,
	     .filter-product .filter-tabs,
	     .htabs a,
	     legend {
	     	font-size: <?php echo $theme_options->get( 'headlines_px' ); ?>px;
	     	font-weight: <?php echo $theme_options->get( 'headlines_weight' )*100; ?>;
	     	<?php if( $theme_options->get( 'headlines_font' ) != '' && $theme_options->get( 'headlines_font' ) != 'standard' ) { ?>
	     	font-family: <?php echo $theme_options->get( 'headlines_font' ); ?>;
	     	<?php } ?>
	     	text-transform: <?php if($theme_options->get( 'headlines_transform' ) == 1) { echo 'uppercase'; } else { echo 'none'; } ?>;
	     }

	     .footer h4,
	     .custom-footer h4 {
	     	font-size: <?php echo $theme_options->get( 'footer_headlines_px' ); ?>px;
	     	font-weight: <?php echo $theme_options->get( 'footer_headlines_weight' )*100; ?>;
	     	<?php if( $theme_options->get( 'footer_headlines_font' ) != '' && $theme_options->get( 'footer_headlines_font' ) != 'standard' ) { ?>
	     	font-family: <?php echo $theme_options->get( 'footer_headlines_font' ); ?>;
	     	<?php } ?>
	     	text-transform: <?php if($theme_options->get( 'footer_headlines_transform' ) == 1) { echo 'uppercase'; } else { echo 'none'; } ?>;
	     }

	     .breadcrumb .container h1 {
	     	font-size: <?php echo $theme_options->get( 'page_name_px' ); ?>px;
	     	font-weight: <?php echo $theme_options->get( 'page_name_weight' )*100; ?>;
	     	<?php if( $theme_options->get( 'page_name_font' ) != '' && $theme_options->get( 'page_name_font' ) != 'standard' ) { ?>
	     	font-family: <?php echo $theme_options->get( 'page_name_font' ); ?>;
	     	<?php } ?>
	     	text-transform: <?php if($theme_options->get( 'page_name_transform' ) == 1) { echo 'uppercase'; } else { echo 'none'; } ?>;
	     }

	     .button,
	     .btn {
	     	font-size: <?php echo $theme_options->get( 'button_font_px' ); ?>px;
	     	font-weight: <?php echo $theme_options->get( 'button_font_weight' )*100; ?> !important;
	     	text-transform: <?php if($theme_options->get( 'button_font_transform' ) == 1) { echo 'uppercase'; } else { echo 'none'; } ?>;
	     	<?php if( $theme_options->get( 'button_font' ) != '' && $theme_options->get( 'button_font' ) != 'standard' ) { ?>
	     	font-family: <?php echo $theme_options->get( 'button_font' ); ?>;
	     	<?php } ?>
	     }

	     <?php if( $theme_options->get( 'custom_price_font' ) != '' && $theme_options->get( 'custom_price_font' ) != 'standard' ) { ?>
	     .product-grid .product .price,
	     .hover-product .price,
	     .product-list .price,
	     .product-info .price .price-new,
	     ul.megamenu li .product .price,
	     #top #cart_block .cart-heading span,
	     .mini-cart-info td.total,
	     .mini-cart-total td:last-child {
	     	font-family: <?php echo $theme_options->get( 'custom_price_font' ); ?>;
	     }
	     <?php } ?>

	     .product-grid .product .price,
	     ul.megamenu li .product .price,
	     .product-list .price,
	     .mini-cart-info td.total {
	     	font-size: <?php echo $theme_options->get( 'custom_price_px_small' ); ?>px;
	     	font-weight: <?php echo $theme_options->get( 'custom_price_weight' )*100; ?>;
	     }

	     .mini-cart-total td:last-child,
	     .cart-total table tr td:last-child {
	     	font-weight: <?php echo $theme_options->get( 'custom_price_weight' )*100; ?>;
	     }

	     .product-info .price .price-new {
	     	font-size: <?php echo $theme_options->get( 'custom_price_px' ); ?>px;
	     	font-weight: <?php echo $theme_options->get( 'custom_price_weight' )*100; ?>;
	     }

	     #top #cart_block .cart-heading span {
	     	font-size: <?php echo $theme_options->get( 'custom_price_px_medium' ); ?>px;
	     	font-weight: <?php echo $theme_options->get( 'custom_price_weight' )*100; ?>;
	     }

	     .price-old {
	     	font-size: <?php echo $theme_options->get( 'custom_price_px_old_price' ); ?>px;
	     }
	     <?php } ?>

	     <?php if($theme_options->get( 'background_status' ) == 1) { ?>
     		<?php if($theme_options->get( 'body_background_background' ) == '1') { ?>
     		body { background-image:none !important; }
     		<?php } ?>
     		<?php if($theme_options->get( 'body_background_background' ) == '2') { ?>
     		body { background-image:url(image/<?php echo $theme_options->get( 'body_background' ); ?>);background-position:<?php echo $theme_options->get( 'body_background_position' ); ?>;background-repeat:<?php echo $theme_options->get( 'body_background_repeat' ); ?> !important;background-attachment:<?php echo $theme_options->get( 'body_background_attachment' ); ?> !important; }
     		<?php } ?>
     		<?php if($theme_options->get( 'body_background_background' ) == '3') { ?>
     		body { background-image:url(image/subtle_patterns/<?php echo $theme_options->get( 'body_background_subtle_patterns' ); ?>);background-position:<?php echo $theme_options->get( 'body_background_position' ); ?>;background-repeat:<?php echo $theme_options->get( 'body_background_repeat' ); ?> !important;background-attachment:<?php echo $theme_options->get( 'body_background_attachment' ); ?> !important; }
     		<?php } ?>

     		<?php if($theme_options->get( 'header_background_background' ) == '1') { ?>
     		#top .background { background-image:none !important; }
     		<?php } ?>
     		<?php if($theme_options->get( 'header_background_background' ) == '2') { ?>
     		#top .background { background-image:url(image/<?php echo $theme_options->get( 'header_background' ); ?>);background-position:<?php echo $theme_options->get( 'header_background_position' ); ?>;background-repeat:<?php echo $theme_options->get( 'header_background_repeat' ); ?> !important;background-attachment:<?php echo $theme_options->get( 'header_background_attachment' ); ?> !important; }
     		<?php } ?>
     		<?php if($theme_options->get( 'header_background_background' ) == '3') { ?>
     		#top .background { background-image:url(image/subtle_patterns/<?php echo $theme_options->get( 'header_background_subtle_patterns' ); ?>);background-position:<?php echo $theme_options->get( 'header_background_position' ); ?>;background-repeat:<?php echo $theme_options->get( 'header_background_repeat' ); ?> !important;background-attachment:<?php echo $theme_options->get( 'header_background_attachment' ); ?> !important; }
     		<?php } ?>
		<?php } ?>
	</style>
	<?php } ?>

	<?php if($theme_options->get( 'custom_code_css_status' ) == 1) { ?>
	<link rel="stylesheet" href="catalog/view/theme/<?php echo $config->get( 'config_template' ); ?>/skins/store_<?php echo $theme_options->get( 'store' ); ?>/<?php echo $theme_options->get( 'skin' ); ?>/css/custom_code.css">
	<?php } ?>

	<?php foreach ($styles as $style) { ?>
	<?php if($style['href'] != 'catalog/view/javascript/jquery/owl-carousel/owl.carousel.css') { ?>
	<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
	<?php } ?>
	<?php } ?>

	<?php if($theme_options->get( 'page_width' ) == 2 && $theme_options->get( 'max_width' ) > 900) { ?>
	<style type="text/css">
		.standard-body .full-width .container {
			max-width: <?php echo $theme_options->get( 'max_width' ); ?>px;
			<?php if($theme_options->get( 'responsive_design' ) == '0') { ?>
			width: <?php echo $theme_options->get( 'max_width' ); ?>px;
			<?php } ?>
		}

		.standard-body .fixed .background,
		.main-fixed {
			max-width: <?php echo $theme_options->get( 'max_width' )-40; ?>px;
			<?php if($theme_options->get( 'responsive_design' ) == '0') { ?>
			width: <?php echo $theme_options->get( 'max_width' )-40; ?>px;
			<?php } ?>
		}
	</style>
	<?php } ?>

    <?php $lista_plikow = array();

    $lista_plikow[] = 'catalog/view/theme/'.$config->get( 'config_template' ).'/js/jquery.min.js';
    $lista_plikow[] = 'catalog/view/theme/'.$config->get( 'config_template' ).'/js/jquery-migrate-1.2.1.min.js';
    $lista_plikow[] = 'catalog/view/theme/'.$config->get( 'config_template' ).'/js/jquery.easing.1.3.js';
    $lista_plikow[] = 'catalog/view/theme/'.$config->get( 'config_template' ).'/js/bootstrap.min.js';
    $lista_plikow[] = 'catalog/view/theme/'.$config->get( 'config_template' ).'/js/twitter-bootstrap-hover-dropdown.js';
    $lista_plikow[] = 'catalog/view/theme/'.$config->get( 'config_template' ).'/js/common.js';
    $lista_plikow[] = 'catalog/view/theme/'.$config->get( 'config_template' ).'/js/owl.carousel.min.js';
    $lista_plikow[] = 'catalog/view/theme/'.$config->get( 'config_template' ).'/js/jquery.cookie.js';

    // Revolution slider
    if($config->get( 'revolution_slider_module' ) != '') {
		$lista_plikow[] = 'catalog/view/theme/' . $config->get( 'config_template' ) . '/js/jquery.themepunch.plugins.min.js';
    	$lista_plikow[] = 'catalog/view/theme/' . $config->get( 'config_template' ) . '/js/jquery.themepunch.revolution.min.js';
    }

    // Nivo slider
    if($config->get( 'slideshow_module' ) != '') $lista_plikow[] = 'catalog/view/theme/' . $config->get( 'config_template' ) . '/js/jquery.nivo.slider.pack.js';

    // Fixed header
    if($theme_options->get( 'fixed_header' ) == 1) $lista_plikow[] = 'catalog/view/theme/' . $config->get( 'config_template' ) . '/js/jquery.sticky.js';

    // Fixed menu
    if($theme_options->get( 'fixed_menu' ) == 1) $lista_plikow[] = 'catalog/view/theme/' . $config->get( 'config_template' ) . '/js/jquery.sticky2.js';

    // Carousel brands
    if($config->get( 'carousel_module' ) != '') $lista_plikow[] = 'catalog/view/theme/'.$config->get( 'config_template' ).'/js/jquery.jcarousel.min.js';

    // Banner module
    if($config->get( 'banner_module' ) != '') $lista_plikow[] = 'catalog/view/theme/'.$config->get( 'config_template' ).'/js/jquery.cycle2.min.js';

    ?>

    <?php echo $theme_options->compressorCodeJs( $config->get( 'config_template' ), $lista_plikow, $theme_options->get( 'compressor_code_status' ), HTTP_SERVER ); ?>

    <?php if($theme_options->get( 'quick_search_autosuggest' ) != '0') { ?>
    <script type="text/javascript" src="catalog/view/theme/<?php echo $config->get( 'config_template' ); ?>/js/jquery-ui-1.10.4.custom.min.js"></script>
    <?php } ?>

	<script type="text/javascript">
	var transition = 'fade';
	var animation_time = 200;
	var responsive_design = '<?php if($theme_options->get( 'responsive_design' ) == '0') { echo 'no'; } else { echo 'yes'; } ?>';
	</script>
	<?php foreach ($scripts as $script) { ?>
	<?php if($script != 'catalog/view/javascript/jquery/owl-carousel/owl.carousel.min.js') { ?>
	<script type="text/javascript" src="<?php echo $script; ?>"></script>
	<?php } ?>
	<?php } ?>
	<?php if($theme_options->get( 'custom_code_javascript_status' ) == 1) { ?>
	<script type="text/javascript" src="catalog/view/theme/<?php echo $config->get( 'config_template' ); ?>/skins/store_<?php echo $theme_options->get( 'store' ); ?>/<?php echo $theme_options->get( 'skin' ); ?>/js/custom_code.js"></script>
	<?php } ?>

	<?php echo $google_analytics; ?>
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<script src="catalog/view/theme/<?php echo $config->get( 'config_template' ); ?>/js/respond.min.js"></script>
	<![endif]-->
</head>
<body>

<?php if($theme_options->get( 'widget_facebook_status' ) == 1) { ?>
<div class="facebook_<?php if($theme_options->get( 'widget_facebook_position' ) == 1) { echo 'left'; } else { echo 'right'; } ?> hidden-xs hidden-sm">
	<div class="facebook-icon"></div>
	<div class="facebook-content">
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>

		<div class="fb-like-box fb_iframe_widget" profile_id="<?php echo $theme_options->get( 'widget_facebook_id' ); ?>" data-colorscheme="light" data-height="370" data-connections="16" fb-xfbml-state="rendered"></div>
	</div>

	<script type="text/javascript">
	$(function() {
		$(".facebook_right").hover(function() {
			$(".facebook_right").stop(true, false).animate({right: "0"}, 800, 'easeOutQuint');
		}, function() {
			$(".facebook_right").stop(true, false).animate({right: "-250"}, 800, 'easeInQuint');
		}, 1000);

		$(".facebook_left").hover(function() {
			$(".facebook_left").stop(true, false).animate({left: "0"}, 800, 'easeOutQuint');
		}, function() {
			$(".facebook_left").stop(true, false).animate({left: "-250"}, 800, 'easeInQuint');
		}, 1000);
	});
	</script>
</div>
<?php } ?>

<?php if($theme_options->get( 'widget_twitter_status' ) == 1) { ?>
<div class="twitter_<?php if($theme_options->get( 'widget_twitter_position' ) == 1) { echo 'left'; } else { echo 'right'; } ?> hidden-xs hidden-sm">
	<div class="twitter-icon"></div>
	<div class="twitter-content">
		<a class="twitter-timeline"  href="https://twitter.com/@<?php echo $theme_options->get( 'widget_twitter_user_name' ); ?>" data-chrome="noborders" data-tweet-limit="<?php echo $theme_options->get( 'widget_twitter_limit' ); ?>"  data-widget-id="<?php echo $theme_options->get( 'widget_twitter_id' ); ?>" data-theme="light" data-related="twitterapi,twitter" data-aria-polite="assertive">Tweets by @<?php echo $theme_options->get( 'widget_twitter_user_name' ); ?></a>
	</div>

	<script type="text/javascript">
	!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
	$(function() {
		$(".twitter_right").hover(function() {
			$(".twitter_right").stop(true, false).animate({right: "0"}, 800, 'easeOutQuint');
		}, function() {
			$(".twitter_right").stop(true, false).animate({right: "-250"}, 800, 'easeInQuint');
		}, 1000);

		$(".twitter_left").hover(function() {
			$(".twitter_left").stop(true, false).animate({left: "0"}, 800, 'easeOutQuint');
		}, function() {
			$(".twitter_left").stop(true, false).animate({left: "-250"}, 800, 'easeInQuint');
		}, 1000);
	});
	</script>
</div>
<?php } ?>

<?php if($theme_options->get( 'widget_custom_status' ) == 1) { ?>
<div class="custom_<?php if($theme_options->get( 'widget_custom_position' ) == 1) { echo 'left'; } else { echo 'right'; } ?> hidden-xs hidden-sm">
	<div class="custom-icon"></div>
	<div class="custom-content">
		<?php $lang_id = $config->get( 'config_language_id' ); ?>
		<?php $custom_content = $theme_options->get( 'widget_custom_content' ); ?>
		<?php if(isset($custom_content[$lang_id])) echo html_entity_decode($custom_content[$lang_id]); ?>
	</div>

	<script type="text/javascript">
	$(function() {
		$(".custom_right").hover(function() {
			$(".custom_right").stop(true, false).animate({right: "0"}, 800, 'easeOutQuint');
		}, function() {
			$(".custom_right").stop(true, false).animate({right: "-250"}, 800, 'easeInQuint');
		}, 1000);

		$(".custom_left").hover(function() {
			$(".custom_left").stop(true, false).animate({left: "0"}, 800, 'easeOutQuint');
		}, function() {
			$(".custom_left").stop(true, false).animate({left: "-250"}, 800, 'easeInQuint');
		}, 1000);
	});
	</script>

</div>
<?php } ?>

<div id="notification" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php if($theme_options->get( 'confirmation_text', $config->get( 'config_language_id' ) ) != '') { echo $theme_options->get( 'confirmation_text', $config->get( 'config_language_id' ) ); } else { echo 'Confirmation'; } ?></h4>
            </div>
            <div class="modal-body">
                <p></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php if($theme_options->get( 'continue_shopping_text', $config->get( 'config_language_id' ) ) != '') { echo $theme_options->get( 'continue_shopping_text', $config->get( 'config_language_id' ) ); } else { echo 'Continue shopping'; } ?></button>
                <a href="<?php echo $checkout; ?>" class="btn btn-primary"><?php if($theme_options->get( 'checkout_text', $config->get( 'config_language_id' ) ) != '') { echo $theme_options->get( 'checkout_text', $config->get( 'config_language_id' ) ); } else { echo 'Checkout'; } ?></a>
            </div>
        </div>
    </div>
</div>

<div id="quickview" class="modal fade bs-example-modal-lg">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Product</h4>
            </div>
            <div class="modal-body">
                <p></p>
            </div>
        </div>
    </div>
</div>

<?php if($theme_options->get( 'quick_view' ) == 1) { ?>
<!--<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/magnific/magnific-popup.css" media="screen" />-->
<script type="text/javascript" src="catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js"></script>
<script type="text/javascript">
	$('body').on('click', '.quickview a', function () {
		$('#quickview .modal-header .modal-title').html($(this).attr('title'));
		$('#quickview .modal-body').load($(this).attr('rel') + ' #quickview_product' ,function(result){
		    $('#quickview').modal('show');
		    $('#quickview .popup-gallery').magnificPopup({
		    	delegate: 'a',
		    	type: 'image',
		    	tLoading: 'Loading image #%curr%...',
		    	mainClass: 'mfp-img-mobile',
		    	gallery: {
		    		enabled: true,
		    		navigateByImgClick: true,
		    		preload: [0,1] // Will preload 0 - before current, and 1 after the current image
		    	},
		    	image: {
		    		tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
		    		titleSrc: function(item) {
		    			return item.el.attr('title');
		    		}
		    	}
		    });
		});
		return false;
	});

	$('#quickview').on('click', '#button-cart', function () {
		$('#quickview').modal('hide');
		cart.add($(this).attr("rel"));
	});
</script>
<?php } ?>

<?php if($theme_options->get( 'fixed_header' ) == 1) { ?>
<script type="text/javascript">
  $(window).load(function(){
    $("#top").sticky({ topSpacing: 0 });
  });
</script>
<?php } ?>

<?php if($theme_options->get( 'fixed_menu' ) == 1) { ?>
<script type="text/javascript">
  $(window).load(function(){
    $("#top .container-megamenu").sticky({ topSpacing: 0 });
  });
</script>
<?php } ?>

<div class="<?php if($theme_options->get( 'main_layout' ) == 2) { echo 'fixed-body'; } else { echo 'standard-body'; } ?>">
	<div id="main" class="<?php if($theme_options->get( 'main_layout' ) == 2) { echo 'main-fixed'; } ?>">
		<div class="hover-product"></div>
		<?php
		if($theme_options->get( 'header_type' ) == 2) {
			include('catalog/view/theme/'.$config->get('config_template').'/template/common/header/header_02.tpl');
		} elseif($theme_options->get( 'header_type' ) == 3) {
			include('catalog/view/theme/'.$config->get('config_template').'/template/common/header/header_03.tpl');
		} else {
			include('catalog/view/theme/'.$config->get('config_template').'/template/common/header/header_01.tpl');
		}
		?>