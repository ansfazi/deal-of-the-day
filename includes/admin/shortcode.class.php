<?php
/*
 * @author 		Abuzer
 * @category 	Admin
 * @package 	deal-of-the-day/Admin
 * @version     1.0
*/
class DD_Shortcodes {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {

	}
	/**
	 * Init shortcodes
	 */
	public static function init() {
		// Define shortcodes
		$shortcodes = array(
			'deal_of_day'                    => __CLASS__ . '::deal_of_day',
		);
		foreach ( $shortcodes as $shortcode => $function ) {
			add_shortcode( apply_filters( "{$shortcode}_shortcode_tag", $shortcode ), $function );
		}

	}

	/**
	 * Shortcode Wrapper
	 *
	 * @param mixed $function
	 * @param array $atts (default: array())
	 * @return string
	 */
	public static function dd_shortcode_wrapper(
		$function,
		$atts    = array(),
		$wrapper = array(
			'class'  => 'dod',
			'before' => null,
			'after'  => null
		)
	) {
		ob_start();

		$before 	= empty( $wrapper['before'] ) ? '<div class="' . esc_attr( $wrapper['class'] ) . '">' : $wrapper['before'];
		$after 		= empty( $wrapper['after'] ) ? '</div>' : $wrapper['after'];

		echo $before;
		call_user_func( $function, $atts );
		echo $after;

		return ob_get_clean();
	}
	/**
	 * Deal  shortcode.
	 *
	 * @access public
	 * @param mixed $atts
	 * @return string
	 */
	public static function deal_of_day( $atts ) {
		return self::dd_shortcode_wrapper( array( 'DD_Shortcode_Deal', 'output' ), $atts );
	}

}