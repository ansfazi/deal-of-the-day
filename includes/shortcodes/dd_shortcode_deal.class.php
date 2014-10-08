<?php
/*
 * @author 		Abuzer
 * @category 	Admin
 * @package 	deal-of-the-day/Admin
 * @version     1.0
 */
class DD_Shortcode_Deal {

	/**
	 * Output the shortcode.
	 *
	 * @access public
	 * @param array $atts
	 * @return void
	 */
	public static function output($atts) {
		global $wp, $dealofday;
		wp_enqueue_style('deals-listing', $dealofday::plugin_url() . '/assets/css/deals_listing.css');
		include 'views/deal_of_day_view.php';
	}
	/**
	 * return int
	 */
	public static function is_expired($date) {
		return strtotime($date) <= time();
	}

}
