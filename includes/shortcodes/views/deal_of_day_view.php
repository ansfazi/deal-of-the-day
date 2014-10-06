<?php
global $dealofday;
$args['post_type'] = 'product';
$args['meta_key'] = 'dod_is_in_dod';
$args['meta_value'] = 'on';
$args['orderby'] = 'menu_order';
$args['order'] = 'ASC';
$custom_css = array_shift(get_post_meta(get_the_ID(), 'dod_custom_css'));
?>
<?php if ($custom_css != "") {?>
<style type="text/css">
<?php echo $custom_css;?>
</style>
<?php }?>

<?php
global $dealofday;
add_filter('posts_results', array(&$dealofday, 'order_by_featured'), PHP_INT_MAX, 2);
$the_query = new WP_Query($args);
?>

<?php if ($the_query->have_posts()):?>
<link rel="stylesheet" type="text/css" href="http://xdsoft.net/scripts/jquery.flipcountdown.css" />
<script type="text/javascript" src="http://xdsoft.net/scripts/jquery.flipcountdown.js"></script>
<script type="text/javascript">
	jQuery(function(){
	  jQuery('.flipcountdownbox1').flipcountdown();
	})
</script>

<div class="deal-container">
<?php while ($the_query->have_posts()):$the_query->the_post();?>
	<div class="deal-wrapper">
	<?php if (get_post_meta(get_the_ID(), 'dod_featured_deal', true) == 'Yes') {?>
		        <img class="featured" src="<?php echo $dealofday::plugin_url();?>/assets/images/icon_featured1.png" alt="">
	<?php }?>
				<div class="deal-content">
					<div class="deal-detail">

						<div class="title">
							<a href="<?php the_permalink();?>" title="<?php the_title_attribute();?>" >
								<h2><?php the_title();?></h2>
							</a>
						</div>
						<div class="deal-buynow">
							<a href="#" class="myButton"><span class="buynow">Buy Now</span></a>
						</div>
	<?php if (get_post_meta(get_the_ID(), 'dod_deal_price', true)) {?>
						<div class="deal-dates">
							<h3>$<?php echo (get_post_meta(get_the_ID(), 'dod_deal_price', true));?></h3>
						</div>
	<?php }?>
	<div class="deal-dates">
					<div class="flipcountdownbox1"></div>
	<?php print_r(get_post_meta(get_the_ID(), 'dod_start_date', true));?> - <?php print_r(get_post_meta(get_the_ID(), 'dod_end_date', true));?><br>
					</div>
				</div>
				<div class="deal-image">
					<div class="img">
	<?php if (has_post_thumbnail()) {?>
					<a href="<?php the_permalink();?>" title="<?php the_title_attribute();?>" ><?php the_post_thumbnail('shop_single');?></a>
	<?php } else {
		echo apply_filters('woocommerce_single_product_image_html', sprintf('<img src="%s" alt="%s" />', wc_placeholder_img_src(), __('Placeholder', 'woocommerce')), $post->ID);
	}?>
					</div>
				</div>
			</div>
			<div class="deal-footer">
				<p><?php print_r(get_post_meta(get_the_ID(), 'dod_notes', true));?></p>
			</div>
		</div>

	<?php endwhile;?>
</div>
	<!-- enof the loop -->

	<!-- pagination here -->

<?php wp_reset_postdata();?>

<?php else:?>
	<p><?php _e('Sorry, no posts matched your criteria.');?></p>
<?php endif;?>