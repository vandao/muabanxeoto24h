 	<!--jCarousel library-->	

	<script type="text/javascript" src="catalog/view/theme/sportstore/js/jquery.jcarousel.min.js"></script>
	
	<script type="text/javascript">

	function mycarousel_initCallback(carousel)
	{
	    // Disable autoscrolling if the user clicks the prev or next button.
	    carousel.buttonNext.bind('click', function() {
	        carousel.startAuto(0);
	    });

	    carousel.buttonPrev.bind('click', function() {
	        carousel.startAuto(0);
	    });

	    // Pause autoscrolling if the user moves with the cursor over the clip.
	    carousel.clip.hover(function() {
	        carousel.stopAuto();
	    }, function() {
	        carousel.startAuto();
	    });
	};

	jQuery(document).ready(function() {
	    jQuery('#newest-products').jcarousel({
	        auto: 15,
	        wrap: 'last',
	        initCallback: mycarousel_initCallback
	    });
	});

	</script>

	<!-- Box -> Latest -->

		<div class="box-color-2 box-shadow">

			<!-- Title -->
			
			<h3 class="box-color-2-title"><span><?php echo $heading_title; ?></span></h3>	

			<!-- Text -->

			<div class="box-color-2-text">
					
		    <ul id="newest-products" class="jcarousel-skin-tango">
			 
		      <?php foreach ($products as $product) { ?>
				<!-- Item -->	
		      <li>
		        <?php if ($product['thumb']) { ?>
				  <!-- Img --><div class="img"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" width="150px" height="100px" alt="" /></a></div>	
		        <?php } ?>
				  <h2><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h2>
		        <?php if ($product['price']) { ?>
		        <p class="price">
		          <?php if (!$product['special']) { ?>
		          <?php echo "<span style='display:block;position:relative;margin:-4px 0px 0px 0px;padding-bottom:1px'>".$product['price'].'</span>'; ?>
		          <?php } else { ?>
		          <span class="price-old"><?php echo $product['price']; ?></span><br /><?php echo $product['special']; ?>
		          <?php } ?>
		        </p>
		        <?php } ?>
		        <a onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button"><span><?php echo $button_cart; ?></span></a>
		      </li>
				<!-- End item -->
		      <?php } ?>
				
		    </ul>
				
			</div>

			<!-- End Text -->

		</div>
		
		<!-- End Box -> Latest -->
