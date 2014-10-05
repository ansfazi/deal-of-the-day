<?php 
function dd_create_page( $slug, $option = '', $page_title = '', $page_content = '', $post_parent = 0 ) {
	global $wpdb;

	$option_value = get_option( $option );

	if ( $option_value > 0 && get_post( $option_value ) )
		return -1;

	$page_found = null;

	if ( strlen( $page_content ) > 0 ) {
		// Search for an existing page with the specified page content (typically a shortcode)
		$page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM " . $wpdb->posts . " WHERE post_type='page' AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );
	} else {
		// Search for an existing page with the specified page slug
		$page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM " . $wpdb->posts . " WHERE post_type='page' AND post_name = %s LIMIT 1;", $slug ) );
	}

	$page_found = apply_filters( 'dod_create_page_id', $page_found, $slug, $page_content ); ////

	if ( $page_found ) {
		if ( ! $option_value ) {
			update_option( $option, $page_found );
		}

		return $page_found;
	}

	$page_data = array(
		'post_status'       => 'publish',
		'post_type'         => 'page',
		'post_author'       => 1,
		'post_name'         => $slug,
		'post_title'        => $page_title,
		'post_content'      => $page_content,
		'post_parent'       => $post_parent,
		'comment_status'    => 'closed'
	);
	$page_id = wp_insert_post( $page_data );

	if ( $option ) {
		update_option( $option, $page_id );
	}

	return $page_id;
}

function dd_update_page( $id, $page_title = '', $page_content = '', $meta, $post_parent = 0 ) {
	global $wpdb;
	$page_data = array(
		'ID'           => $id,
		'post_title'        => $page_title,
	);
	$page_id = wp_update_post( $page_data );
	foreach ($meta as $meta_key => $meta_value) {
		update_post_meta( $id,  $meta_key, $meta_value );
	}
	if( $page_id )
		return "Settings updated";
	else
		return "Error";
}

