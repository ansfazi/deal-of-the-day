<?php
/**
 * DOD Uninstall
 *
 * Uninstalling deletes options, and pages.
 *
 * @author 		Abuzer
 * @category 	Core
 * @version     1.0.0
 */
if( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

global $wpdb, $wp_roles;

// Pages
wp_trash_post( get_option( 'dod_page_id' ) );
$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'dod_%';");
