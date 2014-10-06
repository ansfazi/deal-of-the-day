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
 */
	class DD_Admin_Menus {

		/**
		 * Hook in tabs.
		 */
		public function __construct() {
			// Add menus
			add_action('admin_menu', array($this, 'admin_menu'), 9);
		}

		/**
		 * Add menu items
		 *
		 * @return void
		 */
		public function admin_menu() {
			global $menu;
			$main_page = add_menu_page('Deal of the Day', 'Deal of the Day', 'manage_options', 'deal-of-the-day', array($this, 'settings_page'), null, '45.5');
		}
		/**
		 * menu call back function
		 *
		 * @return void
		 **/
		public function settings_page() {
			if (isset($_GET['tab'])) {
				if ($_GET['tab'] == 'reorder-deals') {
					DD_Admin_Views::reorder();
				} else if ($_GET['tab'] == 'general-settings') {
				DD_Admin_Views::general_settings();
			} else if ($_GET['tab'] == 'how-to-use') {
				DD_Admin_Views::how_to_use();
			} else {
				DD_Admin_Views::list_deals();
			}
		} else {
			DD_Admin_Views::list_deals();
		}

	}
}

endif;

return new DD_Admin_Menus();
