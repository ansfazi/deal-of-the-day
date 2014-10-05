<?php
/*
 * @author 		Abuzer
 * @category 	Admin
 * @package 	deal-of-the-day/Admin
 * @version     1.0
 */
class DD_Install {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		// Run this on activation.
		register_activation_hook( DD_PLUGIN_FILE, array( $this, 'install' ) );
		// Hooks
		add_action( 'admin_init', array( $this, 'install_actions' ) );
	}

	/**
	 * Install actions such as installing pages when a button is clicked.
	 */
	public function install_actions() {
		// Install - Add pages button
		if ( ! empty( $_GET['install_dd_pages'] )  || !get_option( 'dod_installed' ) ) {
			self::create_pages();
			$this->create_tables();
			update_option('dod_installed', true );
		}
	}
	/**
	 * Install DD
	 */
	public function install() {
		// Check if pages are needed
		if ( dd_get_page_id( 'shop' ) < 1 ) {
			update_option( '_wc_needs_pages', 1 );
		}

	}
	/**
	 * Create pages that the plugin relies on, storing page id's in variables.
	 *
	 * @access public
	 * @return void
	 */
	public static function create_pages() {
		$pages = apply_filters( 'woocommerce_create_pages', array(
			'deal-of-the-day' => array(
				'name'    => _x( 'deal-of-the-day', 'Page slug', 'dealoftheday' ),
				'title'   => _x( 'Deal of the Day', 'Page title', 'dealoftheday' ),
				'content' => '[deal_of_day]'
			)
		) );
		foreach ( $pages as $key => $page ) {
			dd_create_page( esc_sql( $page['name'] ), 'dod_page_id', $page['title'], $page['content'], '' );
		}
	}
	private function create_tables() {
		global $wpdb;
		//  Tables
		$collate = '';
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		if ( $wpdb->has_cap( 'collation' ) ) {
			if ( ! empty($wpdb->charset ) ) {
				$collate .= "DEFAULT CHARACTER SET $wpdb->charset";
			}
			if ( ! empty($wpdb->collate ) ) {
				$collate .= " COLLATE $wpdb->collate";
			}
		}

		$dod_tables = "
	CREATE TABLE {$wpdb->prefix}dod_deals (
	  id bigint(20) NOT NULL auto_increment,
	  price varchar(200) NOT NULL,
	  expire_on longtext NULL,
	  notes varchar(600) NOT NULL,
	  PRIMARY KEY  (id),
	  KEY attribute_name (id)
	) $collate;
	";
		dbDelta( $dod_tables );
	}
}
return new DD_Install();