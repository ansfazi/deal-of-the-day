<?php
	global $dealofday;
	$args['post_type'] = 'product';
	$args['meta_key'] = 'dod_is_in_dod';
	$args['meta_value'] = 'on';
	$args['orderby'] = 'menu_order';
	$args['order'] = 'ASC';
	$custom_css = array_shift(get_post_meta(get_the_ID(), 'dod_custom_css'));
	// -- Function Name : getSavingtage
	// -- Params : $reg_price, $deal_price
	// -- Purpose : 
	function getSavingtage($reg_price, $deal_price) {
		if ($reg_price >= $deal_price) {
			echo 100 - round($deal_price / $reg_price * 100) . '%';
		}
	}
	?>
<?php  if ($custom_css != "") { ?>
	<style type="text/css">
	   <?php  echo $custom_css; ?>
	</style>
<?php  } ?>
<script type="text/javascript">
    jQuery(function() {
        jQuery('[data-expiry]').each(function() {
            console.log(jQuery(this).data('expiry'));
            var jQuerythis = jQuery(this);
            var finalDate = new Date(jQuery(this).data('expiry'));
            jQuery(jQuerythis).countdown({
                until: finalDate,
                layout: ' {dn} days {hn} hrs {mn} min {sn} sec'
            });
        });
    });
</script>
<?php
	global $dealofday;
	add_filter('posts_results', array(&$dealofday, 'order_by_featured'), PHP_INT_MAX, 2);
	$the_query = new WP_Query($args);
	?>
<?php  if ($the_query->have_posts()): ?>
<script src="<?php  echo $dealofday::plugin_url() ?>/assets/js/frontend/jquery.plugin.js"></script>
<script src="<?php  echo $dealofday::plugin_url() ?>/assets/js/frontend/jquery.countdown.js"></script>
<div class="deal-container">
   <?php 
	while ($the_query->have_posts()):
	$the_query->the_post();
	global $product;
	$_regular_price = array_shift(get_post_meta(get_the_ID(), '_regular_price'));
	?>
   <div class="deal-wrapper">
      <?php  if (get_post_meta(get_the_ID(), 'dod_featured_deal', true) == 'Yes') { ?>
      <img class="featured" src="<?php  echo $dealofday::plugin_url(); ?>/assets/images/icon_featured1.png" alt="">
      <?php  } ?>
      <div class="deal-content">
         <div class="title">
            <a href="<?php  the_permalink(); ?>" title="<?php  the_title_attribute(); ?>" >
               <h2><?php  the_title(); ?></h2>
            </a>
         </div>
         <div class="lower-part">
            <div class="deal-detail">
               <div class="deal-buynow">
                  <?php  if (get_post_meta(get_the_ID(), 'dod_deal_price', true)) { ?>
                  <div class="deal-price">
                     <?php  echo get_woocommerce_currency_symbol() . (get_post_meta(get_the_ID(), 'dod_deal_price', true)); ?>
                  </div>
                  <?php  } ?>
                  <div class="buynow-btn">BUY NOW<span class="dot"></span>
                  </div>
               </div>
               <div class="clear"></div>
               <?php  if ($_regular_price) { ?>
               <div class="price-savings">
                  <div class="regular-price-box">
                     <div class="reg-price">
                        <?php  echo get_woocommerce_currency_symbol() . $_regular_price; ?>
                     </div>
                     <div class="reg-text">
                        REGULAR PRICE
                     </div>
                  </div>
                  <div class="saving-tage-box">
                     <div class="savings">
                        <?php  getSavingtage($_regular_price, get_post_meta(get_the_ID(), 'dod_deal_price', true)) ?>
                     </div>
                     <div class="savings-text">
                        SAVINGS
                     </div>
                  </div>
               </div>
               <?php  } ?>
               <div class="clear"></div>
               <div class="timer-social-container">
                  <div class="deal-dates">
                     <?php
						if (DD_Shortcode_Deal::is_expired(get_post_meta(get_the_ID(), 'dod_end_date', true))) {
							echo '<h3 class="expired">Deal Expired</h3>';
						} else {
							?>
                     <p><strong>This Deal will end in:</strong></p>
                     <div class="dealExpireTimer" data-expiry="<?php  echo date('m/d/Y 23:59:59', strtotime(get_post_meta(get_the_ID(), 'dod_end_date', true))) ?>"></div>
                     <br>
                     <?php  }/// end else  ?>
                  </div>
                  <div class="social-icons">
                     <a title="send to Facebook"
                        href="http://www.facebook.com/sharer.php?s=100&p[title]=Check out the Amazing Deal&p[summary]=<?php  echo get_the_title(); ?>&p[url]=<?php  echo site_url() ?>/?page_id=<?php  echo get_option('dod_page_id'); ?>&product=<?php  echo get_the_ID(); ?>"
                        target="_blank">
                     <img src="<?php  echo $dealofday::plugin_url() ?>/assets/images/facebook.png" alt="">
                     </a>
                     <a title="send to Facebook"
                        href="https://twitter.com/share?url=<?php  echo site_url() . '/?page_id=' . get_option('dod_page_id'); ?>"
                        target="_blank">
                     <img src="<?php  echo $dealofday::plugin_url() ?>/assets/images/twitter.png" alt="">
                     </a>
                  </div>
               </div>
            </div>
            <div class="deal-image">
               <div class="img">
                  <?php  if (has_post_thumbnail()) { ?>
                  <a href="<?php  the_permalink(); ?>" title="<?php  the_title_attribute(); ?>" ><?php  the_post_thumbnail('shop_single'); ?></a>
                  <?php 
					} else {
						echo apply_filters('woocommerce_single_product_image_html', sprintf('<img src="%s" alt="%s" />', wc_placeholder_img_src(), __('Placeholder', 'woocommerce')), $post->ID);
					} ?>
               </div>
            </div>
            <div class="clear"></div>
            <div class="deal-footer">
               <p><?php  print_r(get_post_meta(get_the_ID(), 'dod_notes', true)); ?></p>
            </div>
         </div>
      </div>
   </div>
   <?php  endwhile; ?>
</div>
<?php  wp_reset_postdata(); ?>
<?php  else : ?>
<p><?php  _e('Sorry, no posts matched your criteria.'); ?></p>
<?php  endif; ?>