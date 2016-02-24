<!-- HEADER
	================================================== -->
<header>
	<div class="background-header"></div>
	<div class="slider-header">
		<!-- Top Bar -->
		<div id="top-bar" class="<?php if($theme_options->get( 'top_bar_layout' ) == 2) { echo 'fixed'; } else { echo 'full-width'; } ?>">
			<div class="background-top-bar"></div>
			<div class="background">
				<div class="shadow"></div>
				<div class="pattern">
					<div class="container">
						<div class="row">
							<!-- Top Bar Left -->
							<div class="col-sm-3 hidden-xs">
								<!-- Welcome text -->
								<div class="welcome-text">
									<?php if($theme_options->get( 'welcome_text', $config->get( 'config_language_id' ) ) != '') { echo html_entity_decode($theme_options->get( 'welcome_text', $config->get( 'config_language_id' ) )); } else { echo 'Call us: +48 500-312-312'; } ?>
								</div>
							</div>
							
							<!-- Top Bar Right -->
							<div class="col-sm-9" id="top-bar-right">
								<?php echo $currency.$language; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<!-- Top of pages -->
		<div id="top" class="<?php if($theme_options->get( 'header_layout' ) == 1) { echo 'full-width'; } else { echo 'fixed'; } ?>">
			<div class="background-top"></div>
			<div class="background">
				<div class="shadow"></div>
				<div class="pattern">
					<div class="container">
						<div class="row">
							<!-- Header Left -->
							<div class="col-sm-4" id="header-left">
								<!-- Search -->
								<div class="search_form">
									<div class="button-search"></div>
									<input type="text" class="input-block-level search-query" name="search" placeholder="" id="search_query" value="" />
									<?php if($theme_options->get( 'quick_search_autosuggest' ) != '0') { ?>
									<div id="autocomplete-results" class="autocomplete-results"></div>
									
									<script type="text/javascript">
									$(document).ready(function() {
										$('#search_query').autocomplete({
											delay: 0,
											appendTo: "#autocomplete-results",
											source: function(request, response) {		
												$.ajax({
													url: 'index.php?route=search/autocomplete&filter_name=' +  encodeURIComponent(request.term),
													dataType: 'json',
													success: function(json) {
														response($.map(json, function(item) {
															return {
																label: item.name,
																value: item.product_id,
																href: item.href,
																thumb: item.thumb,
																desc: item.desc,
																price: item.price
															}
														}));
													}
												});
											},
											select: function(event, ui) {
												document.location.href = ui.item.href;
												
												return false;
											},
											focus: function(event, ui) {
										      	return false;
										   	},
										   	minLength: 2
										})
										.data( "ui-autocomplete" )._renderItem = function( ul, item ) {
										  return $( "<li>" )
										    .append( "<a>" + item.label + "</a>" )
										    .appendTo( ul );
										};
									});
									</script>
									<?php } ?>
								</div>
							</div>
							
							<!-- Header Center -->
							<div class="col-sm-4 text-center" id="header-center">
								<?php if ($logo) { ?>
								<!-- Logo -->
								<div class="logo"><a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" /></a></div>
								<?php } ?>								
							</div>
							
							<!-- Header Right -->
							<div class="col-sm-4" id="header-right">
								<?php echo $cart; ?>
							</div>
						</div>
					</div>
					
					<?php 
					$menu = $modules->getModules('menu');
					if( count($menu) ) {
						foreach ($menu as $module) {
							echo $module;
						}
					} elseif($categories) {
					?>
					<div class="container-megamenu container horizontal">
						<div id="megaMenuToggle">
							<div class="megamenuToogle-wrapper">
								<div class="megamenuToogle-pattern">
									<div class="container">
										<div class="background-megamenu">
											<div><span></span><span></span><span></span></div>
											Navigation
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="megamenu-wrapper">
							<div class="megamenu-pattern">
								<div class="container">
									<ul class="megamenu">
										<li class="home"><a href="<?php echo $home; ?>"><i class="fa fa-home"></i></a></li>
										<?php foreach ($categories as $category) { ?>
										<?php if ($category['children']) { ?>
										<li class="with-sub-menu hover"><p class="close-menu"></p>
											<a href="<?php echo $category['href'];?>"><span><strong><?php echo $category['name']; ?></strong></span></a>
										<?php } else { ?>
										<li>
											<a href="<?php echo $category['href']; ?>"><span><strong><?php echo $category['name']; ?></strong></span></a>
										<?php } ?>
											<?php if ($category['children']) { ?>
											<?php 
												$width = '100%';
												$row_fluid = 3;
												if($category['column'] == 1) { $width = '220px'; $row_fluid = 12; }
												if($category['column'] == 2) { $width = '500px'; $row_fluid = 6; }
												if($category['column'] == 3) { $width = '700px'; $row_fluid = 4; }
											?>
											<div class="sub-menu" style="width: <?php echo $width; ?>">
												<div class="content">
													<div class="row hover-menu">
														<?php for ($i = 0; $i < count($category['children']);) { ?>
														<div class="col-sm-<?php echo $row_fluid; ?>">
															<div class="menu">
																<ul>
																  <?php $j = $i + ceil(count($category['children']) / $category['column']); ?>
																  <?php for (; $i < $j; $i++) { ?>
																  <?php if (isset($category['children'][$i])) { ?>
																  <li><a href="<?php echo $category['children'][$i]['href']; ?>" onclick="window.location = '<?php echo $category['children'][$i]['href']; ?>';"><?php echo $category['children'][$i]['name']; ?></a></li>
																  <?php } ?>
																  <?php } ?>
																</ul>
															</div>
														</div>
														<?php } ?>
													</div>
												</div>
											</div>
											<?php } ?>
										</li>
										<?php } ?>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
	
	<?php $slideshow = $modules->getModules('slideshow'); ?>
	<?php  if(count($slideshow)) { ?>
	<!-- Slider -->
	<div id="slider" class="<?php if($theme_options->get( 'slideshow_layout' ) == 1) { echo 'full-width'; } else { echo 'fixed'; } ?>">
		<div class="background-slider"></div>
		<div class="background">
			<div class="shadow"></div>
			<div class="pattern">
				<?php foreach($slideshow as $module) { ?>
				<?php echo $module; ?>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php } ?>
</header>