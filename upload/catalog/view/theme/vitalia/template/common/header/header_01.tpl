<!-- HEADER
	================================================== -->
<header>
	<div class="background-header"></div>
	<div class="slider-header">
		<!-- Top Bar -->
		<div id="top-bar" class="<?php if($this->theme_options->get( 'top_bar_layout' ) == 2) { echo 'fixed'; } else { echo 'full-width'; } ?>">
			<div class="background-top-bar"></div>
			<div class="background">
				<div class="shadow"></div>
				<div class="pattern">
					<div class="container">
						<div class="row">
							<!-- Top Bar Left -->
							<div class="col-sm-6">
								<!-- Welcome text -->
								<div class="welcome-text">
									<!-- Van Dao -->
								</div>
							</div>
							
							<!-- Top Bar Right -->
							<div class="col-sm-6" id="top-bar-right">
								<?php echo $currency.$language; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<!-- Top of pages -->
		<div id="top" class="<?php if($this->theme_options->get( 'header_layout' ) == 1) { echo 'full-width'; } else { echo 'fixed'; } ?>">
			<div class="background-top"></div>
			<div class="background">
				<div class="shadow"></div>
				<div class="pattern">
					<div class="container">
						<div class="row" style="text-align: center;">
							<!-- Header Left -->
							<div class="col-sm-4" id="header-left">
								<!-- Logo -->
								<a href="/">
									<img src="image/data/icon_chevy_off.png" style="width: 43%;">
									<div style="font-weight: bold; font-size: 18px;">
										<span>CHEVROLET VIỆT LONG</span>
									</div>
								</a>
							</div>
							
							<!-- Header Center -->
							<div class="col-sm-4" id="header-center">
								<?php if ($logo) { ?>
								<a href="/">
									<img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" />
								</a>
								<?php } ?>

								<!-- Search -->
								<div class="search_form" style="display: none">
									<div class="button-search"></div>
									<input type="text" class="input-block-level search-query" name="search" placeholder="<?php echo $text_search; ?>" id="search_query" value="<?php echo $search; ?>" />
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
										    .append( "<a><img src='" + item.thumb + "' alt=''>" + item.label + "<br><span class='description'>" + item.desc + "</span><br><span class='price'>" + item.price + "</span></a>" )
										    .appendTo( ul );
										};
									});
									</script>
								</div>
								
								<!-- Links -->
							</div>
							
							<!-- Header Right -->
							<div class="col-sm-4" id="header-right">
								<?php
									$customfooter = $this->theme_options->get( 'customfooter' );
									$language_id = $this->config->get( 'config_language_id' );
								?>
								<?php if($customfooter[$language_id]['contact_status'] == '1') { ?>
									<!-- Contact -->
										<ul class="contact-us clearfix">
											<?php if($customfooter[$language_id]['contact_phone'] != '' || $customfooter[$language_id]['contact_phone2'] != '') { ?>
											<!-- Phone -->
											<li style="margin-bottom: -15px;">
												<i class="icon-mobile-phone"></i>
												<p style="text-align: left;">
													<?php if($customfooter[$language_id]['contact_phone'] != '') { ?>
														<?php echo $customfooter[$language_id]['contact_phone']; ?><br>
													<?php } ?>
													<?php if($customfooter[$language_id]['contact_phone2'] != '') { ?>
														<?php echo $customfooter[$language_id]['contact_phone2']; ?>
													<?php } ?>
												</p>
											</li>
											<?php } ?>

											<?php if($customfooter[$language_id]['contact_email'] != '' || $customfooter[$language_id]['contact_email2'] != '') { ?>
											<li style="margin-bottom: -15px;">
												<i class="icon-envelope"></i>
												<p style="text-align: left;">
													<?php if($customfooter[$language_id]['contact_email'] != '') { ?>
														<span><?php echo $customfooter[$language_id]['contact_email']; ?></span><br>
													<?php } ?>
													<?php if($customfooter[$language_id]['contact_email2'] != '') { ?>
														<span><?php echo $customfooter[$language_id]['contact_email2']; ?></span>
													<?php } ?>
												</p>
											</li>
											<?php } ?>

											<?php if($customfooter[$language_id]['contact_skype'] != '' || $customfooter[$language_id]['contact_skype2'] != '') { ?>
											<!-- Phone -->
											<li style="margin-bottom: -15px;">
												<i class="icon-skype"></i>
												<p style="text-align: left;">
													<?php if($customfooter[$language_id]['contact_skype'] != '') { ?>
														<?php echo $customfooter[$language_id]['contact_skype']; ?><br>
													<?php } ?>
													<?php if($customfooter[$language_id]['contact_skype2'] != '') { ?>
														<?php echo $customfooter[$language_id]['contact_skype2']; ?>
													<?php } ?>
												</p>
											</li>
											<?php } ?>
										</ul>
								<?php } ?>
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
										<div><span></span><span></span><span></span></div>
										Menu
									</div>
								</div>
							</div>
						</div>
						
						<div class="megamenu-wrapper">
							<div class="megamenu-pattern">
								<div class="container">
									<ul class="megamenu">
										<li class="home"><a href="/"><i class="icon-home"></i></a></li>
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
										<li>
											<a href="/index.php?route=information/news"><span><strong>Tin Tức</strong></span></a>
										</li>
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
	<div id="slider" class="<?php if($this->theme_options->get( 'slideshow_layout' ) == 1) { echo 'full-width'; } else { echo 'fixed'; } ?>">
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