<?php
/**
 * Business Kid functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package business-kid
 */

  add_action( 'wp_enqueue_scripts', 'business_kid_style' );
  function business_kid_style() {
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/frameworks/bootstrap/bootstrap.css' );

	wp_enqueue_style( 'business-click-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'business-kid-style',get_stylesheet_directory_uri() . '/style.css',array('business-click-style'));
}

// theme name
if ( ! function_exists ( 'business_click_theme_name' ) ) {
	function business_click_theme_name() {
		return esc_html__('Business Kid','business-kid');
	}
}

if(!function_exists('business_kid_setup') ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function business_kid_setup() {

		// Make theme available for translation.
		load_theme_textdomain( 'business-kid', get_stylesheet_directory() . '/languages' );
	}

endif;

add_action( 'after_setup_theme', 'business_kid_setup' );
