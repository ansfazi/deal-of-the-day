<?php
/**
*
* @author      Abuzer
* @category    Admin
* @package     deal-of-the-day/Admin
* @version     1.0
*/
class DOD_Ajax
{

	public static function init() {
		$ajax_events = array(
            'dod_update_deal_status',
			'update_deals_order',
            'dod_featured_deal'
		);
		foreach ( $ajax_events as $ajax_event) {
			add_action( 'wp_ajax_'.$ajax_event, array( __CLASS__, $ajax_event ) );
		}
	}
    public static function dod_update_deal_status() {
        global $wpdb; // this is how you get access to the database
        //print_r( $_POST );
        $deal_id = intval( $_POST['deal_id'] );
        $status = array_shift ( get_post_meta($deal_id, 'dod_active_deal' ) ) ;
        echo  update_post_meta($deal_id, 'dod_active_deal', DOD_Ajax::reverse_status( $status ) , $status);
        //echo json_encode( $response);
        die(); // this is required to terminate immediately and return a proper response
    }
    public static function update_deals_order() {
            global $wpdb; // this is how you get access to the database
            global $wpdb;
            $response = 0;
            parse_str($_POST['order'], $data);

            if (is_array($data))
            foreach($data as $key => $values )
                {
                    if ( $key == 'item' )
                        {
                            foreach( $values as $position => $id )
                                {
                                    $data = array('menu_order' => $position, 'post_parent' => 0);
                                    $data = apply_filters('post-types-order_save-ajax-order', $data, $key, $id);
                                    $updated = $wpdb->update( $wpdb->posts, $data, array('ID' => $id) );
                                    if( $updated )
                                        $response = 1;
                                }
                        }
                    else
                        {
                            foreach( $values as $position => $id )
                                {
                                    $data = array('menu_order' => $position, 'post_parent' => str_replace('item_', '', $key));
                                    $data = apply_filters('post-types-order_save-ajax-order', $data, $key, $id);
                                    $wpdb->update( $wpdb->posts, $data, array('ID' => $id) );
                                    if( $updated )
                                        $response = 1;
                                }
                        }
                }
            echo   $response ;
            die(); // this is required to terminate immediately and return a proper response
        }
    public static function dod_featured_deal() {
        global $wpdb; // this is how you get access to the database
        //print_r( $_POST );
        $deal_id = intval( $_POST['deal_id'] );
        $status = array_shift ( get_post_meta($deal_id, 'dod_featured_deal' ) ) ;
        echo  update_post_meta($deal_id, 'dod_featured_deal', DOD_Ajax::reverse_status( $status ) , $status);
        //echo json_encode( $response);
        die(); // this is required to terminate immediately and return a proper response
    }
    public function reverse_status( $status ){
    	return $status == 'Yes' ? 'No' : 'Yes';
    }
}
DOD_Ajax::init();