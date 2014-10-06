<?php
/**
 *
 * @author 		Abuzer
 * @category 	Admin
 * @package 	deal-of-the-day/Admin
 * @version     1.0
 */

if (!defined('ABSPATH')) {
	exit;// Exit if accessed directly
}

if (!class_exists('DD_Admin_Menus')):

/**
 * Render admin views
 */
	class DD_Admin_Views {

		/**
		 * Hook in tabs.
		 */
		public function __construct() {
			// Add menus
		}
		/**
		 * get deals to show on deals listing and reorder page
		 * @return object
		 */
		public static function getDeals() {
			global $dealofday;
			$args = array();
			$args['post_type'] = 'product';
			$args['meta_key'] = 'dod_is_in_dod';
			$args['meta_value'] = 'on';
			$args['orderby'] = "menu_order";
			$args['order'] = 'ASC';
			add_filter('posts_results', array(&$dealofday, 'order_by_featured'), PHP_INT_MAX, 2);
			return new WP_Query($args);
		}
		/**
		 * save action to update the fields on settings page
		 * @return int
		 */
		public static function save() {
			$page_title = $_POST['page_title'];
			$meta['dod_custom_css'] = $_POST['custom_css'];
			$meta['_wp_page_template'] = $_POST['page_template'];
			return dd_update_page(get_option('dod_page_id'), $page_title, '', $meta);
		}
		/**
		 * Make the tab active on current page
		 * @return string
		 */
		public static function get_active($tab) {
			if (isset($_GET['tab'])) {
				echo $_GET['tab'] == $tab ? 'nav-tab-active' : '';
			} elseif ($tab == 'create-deal') {
			echo 'nav-tab-active';
		}
	}
	/**
	 * Render general setting page
	 * @return void
	 */
	public static function general_settings() {
		$msg = '';
		if (!empty($_POST) && isset($_POST['save-settings'])) {
			$msg = self::save();
		}
		$page_id = get_option('dod_page_id');
		$args['page_id'] = $page_id;
		$query = new WP_Query($args);
		$page = $query->posts[0];
		$custom_css = array_shift(get_post_meta($page_id, 'dod_custom_css'));
		$selected_template = array_shift(get_post_meta($page_id, '_wp_page_template'));
		$templates = get_page_templates();
		include 'views/_geneal_settings.php';
	}
	/**
	 * Renders the deals reorder
	 * @return void
	 */
	public static function reorder() {
		$msg = '';

		global $dealofday;
		$args = array();
		$args['post_type'] = 'product';
		$args['meta_key'] = 'dod_is_in_dod';
		$args['meta_value'] = 'on';
		$args['orderby'] = "menu_order";
		$args['order'] = 'ASC';
		add_filter('posts_results', array(&$dealofday, 'order_by_featured'), PHP_INT_MAX, 2);
		$query = new WP_Query($args);

		include 'views/_reorder_deals.php';
		wp_enqueue_script('jquery-ui-sortable');
	}
	/**
	 * Renders the deals list in Admin panel,
	 * @return void
	 */
	public static function list_deals() {
		$msg = '';
		$query = self::getDeals();
		include 'views/_list_deals.php';
	}
	/**
	 * Renders the How to use page,
	 * @return void
	 */
	public static function how_to_use() {
		$msg = '';
		include 'views/_how_to_use.php';
	}
}

endif;
