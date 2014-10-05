<?php

/*
 * To add Deal related custom fields tp product post type
 * @author      Abuzer
 * @category    Admin
 * @package     deal-of-the-day/Admin
 * @version     1.0
*/
class DOD_Custom_Fields
{
    public static $prefix = 'dod_';
    public static $custom_meta_fields, $hidden_fields;
	public static function init() {
        self::$custom_meta_fields = array(
            array(
                'label'=> 'Select as Deal',
                'desc'  => 'add this product as deal page',
                'id'    =>  self::$prefix.'is_in_dod',
                'type'  => 'checkbox'
            ),
            array(
                'label'=> 'Deal Price',
                'desc'  => 'Deal Price',
                'id'    => self::$prefix.'deal_price',
                'type'  => 'text'
            ),
            array(
                'label'=> 'Start Date',
                'desc'  => 'Deal start date',
                'id'    => self::$prefix.'start_date',
                'type'  => 'date'
            ),
            array(
                'label'=> 'End Date',
                'desc'  => 'Deal End date',
                'id'    => self::$prefix.'end_date',
                'type'  => 'date'
            ),
            array(
                'label'=> 'Notes',
                'desc'  => 'Special Notes.',
                'id'    => self::$prefix.'notes',
                'type'  => 'textarea'
            ),
        );
        self::$hidden_fields = array(
            array(
                'id'    => self::$prefix.'featured_deal',
                'type'  => 'hidden',
                'value' => 'No'
            ),
            array(
                'id'    => self::$prefix.'active_deal',
                'type'  => 'hidden',
                'value' => 'Yes'
            ),
        );

        //DOD_Custom_Fields::add_custom_meta_box();
        if(is_admin() && strpos($_SERVER['PHP_SELF'], 'post.php') ) {
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_style( 'validate-css',  DealOfDay::plugin_url().('/assets/css/cmxform.css' ) );
            wp_enqueue_script('jquery-validate-min', DealOfDay::plugin_url().'/assets/js/admin/jquery.validate.min.js' );
            wp_enqueue_script('post-validate', DealOfDay::plugin_url().'/assets/js/admin/post_validate.js' );
            add_action('admin_head', array( __CLASS__,'add_custom_scripts' ) );
        }
        add_action('add_meta_boxes', array( __CLASS__, 'add_custom_meta_box') );
        add_action('save_post', array( __CLASS__,'save_custom_meta' ) );
	}

    // Save the Data
    public static function save_custom_meta($post_id) {
        // verify nonce
        if (!wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__))) 
            return $post_id;
        // check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return $post_id;
        // check permissions
        if ('page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id))
                return $post_id;
            } elseif (!current_user_can('edit_post', $post_id)) {
                return $post_id;
        }

        foreach (self::$custom_meta_fields as $field) {
            if( $_POST[$field['id']] == '' ){
                // validate
            }else{
                $old = get_post_meta($post_id, $field['id'], true);
                $new = $_POST[$field['id']];
                if ($new && $new != $old) {
                    update_post_meta($post_id, $field['id'], $new);
                } elseif ('' == $new && $old) {
                    delete_post_meta($post_id, $field['id'], $old);
                }
            }
        }
        foreach (self::$hidden_fields as $field) {
            if( $_POST[$field['id']] == '' ){
                // validate
            }else{
                $old = get_post_meta($post_id, $field['id'], true);
                $new = $_POST[$field['id']];
                if ($new && $new != $old) {
                    update_post_meta($post_id, $field['id'], $new);
                } elseif ('' == $new && $old) {
                    delete_post_meta($post_id, $field['id'], $old);
                }
            }
        }
    }
    public static  function add_custom_scripts() {
        //global $custom_meta_fields, $post;
        $output = '<script type="text/javascript">
                    jQuery(function() {';
        foreach (self::$custom_meta_fields as $field) { // loop through the fields looking for certain types
            if($field['type'] == 'date')
                $output .= 'jQuery(".datepicker").datepicker(  {onSelect: function (date) { jQuery(this).removeClass("error"); jQuery(this).next().hide();  } } );';
        }
        $output .= '});
            </script>';
        echo $output;
    }
    public static function add_custom_meta_box() {
        add_meta_box(
            'custom_meta_box', // $id
            'Deal of Day', // $title
            array( __CLASS__, 'show_custom_meta_box'), // $callback
            'product', // $page
            'normal', // $context
            'high'); // $priority
    }
    public static function show_custom_meta_box() {
        global $post;
        // Use nonce for verification
        echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';
            // Begin the field table and loop
            echo '<table class="form-table">';
            foreach (self::$custom_meta_fields as $field) {
                // get value of this field if it exists for this post
                $meta = get_post_meta($post->ID, $field['id'], true);
                // begin a table row withadd_custom_meta_boxadd_cuadd_custom_meta_boxstom_meta_box
                echo '<tr>
                        <th><label for="'.$field['id'].'">'.$field['label'].'</label></th>
                        <td>';
                        switch($field['type']) {
                            case 'text':
                            echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
                                <br /><span class="description">'.$field['desc'].'</span>';
                        break;
                        case 'textarea':
                            echo '<textarea name="'.$field['id'].'" id="'.$field['id'].'" cols="60" rows="4">'.$meta.'</textarea>
                                <br /><span class="description">'.$field['desc'].'</span>';
                        break;
                        case 'checkbox':
                            echo '<input type="checkbox" name="'.$field['id'].'" id="'.$field['id'].'" ',$meta ? ' checked="checked"' : '','/>
                                <label for="'.$field['id'].'">'.$field['desc'].'</label>';
                        break;
                        case 'date':
                            echo '<input type="text" class="datepicker" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
                                    <br /><span class="description">'.$field['desc'].'</span>';
                        break;
                        } //end switch
                echo '</td></tr>';
            } // end foreach
            echo '</table>'; // end table
            foreach (self::$hidden_fields as $key => $field) {
                $meta = get_post_meta($post->ID, $field['id'], true);
                if( $meta )
                    echo '<input type="hidden" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" />';
                else
                    echo '<input type="hidden" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$field['value'].'"  />';
            }
        }

}
DOD_Custom_Fields::init();

//add_action('add_meta_boxes', 'add_custom_meta_box');
