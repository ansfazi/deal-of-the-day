<?php
/**
 * Setup menus in WP admin.
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
 * class list deals
 */
	class DD_List_Deals {

		/**
		 * Hook in tabs.
		 */
		public function __construct() {
			// Add menus

		}
		/**
		 * Renders the deals in Admin panel,
		 * @return void
		 */
		public static function output() {
			$msg = '';
			if (!empty($_POST) && isset($_POST['save-settings'])) {
				$msg = self::save();
			}
			if (!empty($_POST) && isset($_POST['create_deal'])) {
				$msg = self::create_deal();
			}
			$page_id = get_option('dod_page_id');
			$args['page_id'] = $page_id;
			$query = new WP_Query($args);
			$page = $query->posts[0];
			$custom_css = array_shift(get_post_meta($page_id, 'dod_custom_css'));
			$templates = get_page_templates();

			include 'views/html-deals-page.php';
			wp_enqueue_script('jquery-ui-sortable');// including sortable js library
		}
	}

endif;
