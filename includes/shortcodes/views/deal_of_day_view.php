<?php
global $dealofday;
	$args['post_type'] = 'product';
    $args['meta_key'] = 'dod_is_in_dod';
    $args['meta_value'] = 'on';
    $args['orderby'] = 'menu_order';
    $args['order'] = 'ASC';
    $custom_css = array_shift(  get_post_meta( get_the_ID(), 'dod_custom_css' ) );
  ?>
<?php if( $custom_css != ""){ ?>
<style type="text/css">
    <?php echo $custom_css; ?>
</style>
<?php   } ?>

 <?php
    global $dealofday;
    add_filter( 'posts_results', array(&$dealofday, 'order_by_featured'), PHP_INT_MAX, 2 );
    $the_query = new WP_Query( $args ); 
?>

<?php if ( $the_query->have_posts() ) : ?>
	<link rel="stylesheet" type="text/css" href="http://xdsoft.net/scripts/jquery.flipcountdown.css" />
	<script type="text/javascript" src="http://xdsoft.net/scripts/jquery.flipcountdown.js"></script>
	<script type="text/javascript">
		jQuery(function(){
		  jQuery('.flipcountdownbox1').flipcountdown();
		})
	</script>
	<style type="text/css">
table,
table td {
    border: 0;
}
table td {
    vertical-align: top;
}
table td {
    border-bottom: 1px solid #ccc;
    padding-bottom: 10px;
}
.deal-wrapper {
    padding: 5px;
    border: 1px solid #eee;
    position: relative;
    margin-bottom: 10px;
}
.deal-wrapper .title {
    font-size: 14px;
}
.deal-wrapper .title a {
    text-decoration: none;
}
.deal-content {
    z-index: 10;
    min-height: 300px;
}
.deal-detail {
    width: 60%;
    float: left;
    ;
}
.deal-image {
    width: 40%;
    float: left;
}
.deal-image .img {
    /* border: 1px solid #ccc; */
    padding: 5px;
    margin-top: 30px;
    min-height: 300px;
}
.deal-footer p {
    font-size: 12px;
    clear: both;
}
.deal-dates {
    width: 90%;
    margin: 0 auto;
    padding: 10px;
    text-align: center;
}
.deal-buynow {
    width: 100%;
    text-align: center;
    padding: 10px;
    min-height: 25px;
}
.deal-buynow .price {
    float: left;
}
.deal-buynow .buynow {
    float: right;
}
.myButton {
    background-color: #44c767;
    -moz-border-radius: 28px;
    -webkit-border-radius: 28px;
    border-radius: 28px;
    border: 1px solid #18ab29;
    display: inline-block;
    cursor: pointer;
    color: #fff !important;
    font-family: arial;
    font-size: 23px;
    padding: 11px 90px;
    text-decoration: none;
    text-shadow: 0px 1px 0px #2f6627;
}
.myButton:hover {
    background-color: #5cbf2a;
    color: #ffffff;
    text-decoration: none;
}
.featured {
    z-index: 5;
    position: absolute;
    top: 0;
    right: 0;
    width: 70px;
    border: 0 !important;
    padding: 0 !important;
    background: transparent !important;
}
</style>

<div class="deal-container">
	<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

		<div class="deal-wrapper">
			<?php if( get_post_meta( get_the_ID() , 'dod_featured_deal', true) == 'Yes'  ){ ?>
				<img class="featured" src="<?php echo $dealofday::plugin_url(); ?>/assets/images/icon_featured1.png" alt="">
			<?php } ?>
		<div class="deal-content">
			<div class="deal-detail">

				<div class="title">
					<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
						<h2><?php the_title(); ?></h2>
					</a>
				</div>
				<div class="deal-buynow">
					<a href="#" class="myButton"><span class="buynow">Buy Now</span></a>
				</div>
				<?php if( get_post_meta( get_the_ID() , 'dod_deal_price', true) ){ ?>
				<div class="deal-dates">
					<h3>$<?php  echo (  get_post_meta( get_the_ID() , 'dod_deal_price', true) ); ?></h3>
				</div>
				<?php }  ?>
				<div class="deal-dates">
					<div class="flipcountdownbox1"></div>
					 <?php  print_r(  get_post_meta( get_the_ID() , 'dod_start_date', true) ); ?> - <?php  print_r(  get_post_meta( get_the_ID() , 'dod_end_date', true) ); ?><br>	
				</div>
			</div>
			<div class="deal-image">
				<div class="img">
				<?php if ( has_post_thumbnail() ) { ?>
					<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
						<?php  the_post_thumbnail( 'shop_single' ); ?>
					</a>
				<?php }else{ 
					echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), __( 'Placeholder', 'woocommerce' ) ), $post->ID );
					} ?>
				</div>
			</div>
		</div>
		<div class="deal-footer">
			<p><?php  print_r(  get_post_meta( get_the_ID() , 'dod_notes', true) ); ?></p>
		</div>
	</div>

	<?php endwhile; ?>
</div>
	<!-- enof the loop -->

	<!-- pagination here -->
    
	<?php wp_reset_postdata(); ?>

<?php else : ?>
	<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
<?php endif; ?>