<?php
/**
 * Plugin Name: Deal of the Day
 * Plugin URI: http://www.deal-of-the-day.com/woocommerce/
 * Description: Plugin to add facility in woocommerce to create deals
 * Version: 1.0.0
 * Author: infix
 * Author URI: http://github.com/abuzer
 *
 * @package DealOfTheDay
 * @category Core
 * @author Abuzer
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'DealOfDay' ) ) :


final class DealOfDay{


	protected static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	public function __construct(){
		$this->define_constants();
		$this->includes();
		add_action( 'init', array( 'DD_Shortcodes', 'init' ) );
	}

    /**
	 * Define DD Include files
	 */
	public function includes(){
		include_once( 'includes/admin/install.class.php' );
		include( 'includes/admin/views_renderer.class.php' ); // to render admin views
		include( 'includes/admin/admin-menu.class.php' );
		include( 'includes/admin/admin-functions.php' );
		include( 'includes/admin/shortcode.class.php' );
		include ('includes/shortcodes/dd_shortcode_deal.class.php');
		include ('includes/dod_ajax.class.php');
		include ('includes/dod_custom_fields.class.php');
	}

	/**
	 * Define DD Constants
	 */
	private function define_constants() {
		define( 'DD_PLUGIN_FILE', __FILE__ );
	}

	/**
	 * Get the plugin url.
	 *
	 * @return string
	 */
	public function plugin_url() {
		return untrailingslashit( plugins_url( '/', __FILE__ ) );
	}
	public function default_img_src() {
		return $this->plugin_url().'/assets/images/dd-placeholder.gif';
	}

	/**
	 * Get the plugin path.
	 *
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Get the template path.
	 *
	 * @return string
	 */
    public function order_by_featured ( $posts, $query ) {
        // run once, removing filter
        remove_filter( current_filter(), __FUNCTION__, PHP_INT_MAX, 2 );
        $nonfeatured = array();
        $featured = array();
        foreach ( $posts as $post ) {
          if ( get_post_meta( $post->ID, 'dod_featured_deal', TRUE ) == 'Yes' ) {
            $featured[] = $post;
          } else {
            $nonfeatured[] = $post;
          }
        }
        $order = strtoupper( $query->get('order') ) === 'ASC' ? 'DESC' : 'ASC';
        // if order is ASC put featured at top, otherwise put featured at bottm
        $posts = ( $order === 'ASC' )
          ? array_merge( $nonfeatured, $featured )
          : array_merge( $featured, $nonfeatured );
      return $posts;
    }

}
/**
 * Returns the main instance of DOD to prevent the need to use globals.
 *
 */
function DOD() {
	return DealOfDay::instance();
}
// Global for backwards compatibility.
$GLOBALS['dealofday'] = DOD();


endif;