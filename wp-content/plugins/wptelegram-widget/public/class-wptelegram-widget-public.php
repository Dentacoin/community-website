<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://t.me/manzoorwanijk
 * @since      1.0.0
 *
 * @package    Wptelegram_Widget
 * @subpackage Wptelegram_Widget/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wptelegram_Widget
 * @subpackage Wptelegram_Widget/public
 * @author     Manzoor Wani 
 */
class Wptelegram_Widget_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The Telegram API
	 *
	 * @since  	1.0.0
	 * @access 	private
	 * @var WPTelegram_Bot_API $tg_api Telegram API Object
	 */
	private $tg_api;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wptelegram-widget-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		// Avoid caching during development
		$this->version = date( "ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . 'js/wptelegram-widget-public.js' ));
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wptelegram-widget-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Registers shortcode to display channel feed
	 *
	 * @since    1.0.0
	 */
	public function widget_shortcode( $atts ) {
		// settings page options
		$options = wptelegram_widget_get_option( 'all' );
		
		$defaults = array(
            'num_messages'	=> 5,
            'widget_with'	=> 100,
            'author_photo'	=> 'auto',
        );
        foreach ( $defaults as $key => $value ) {
        	if ( isset( $options[ $key ] ) ) {
        		$defaults[ $key ] = $options[ $key ];
        	}
        }
	    $args = shortcode_atts( $defaults, $atts );

		$username = wptelegram_widget_get_option( 'username' );

		// fetch messages
		$option = 'wptelegram_widget_messages';
		$messages = array_reverse( get_option( $option, array() ) );
		$num_messages = $args['num_messages'];
		if ( ! absint( $num_messages ) || $num_messages < 0 ) {
			$num_messages = 5;
		}
		$messages = array_slice( $messages, 0, $num_messages );
	    $widget_with = $args['widget_with'];
	    $author_photo = $args['author_photo'];

	    if ( ! absint( $widget_with ) || $widget_with < 0 || $widget_with > 100 ) {
			$widget_with = 100;
		}
		switch ( $author_photo ) {
			case 'always_show':
				$author_photo = 'true';
				break;
			case 'always_hide':
				$author_photo = 'false';
				break;
			default:
				$author_photo = null;
		}
		$widget_options = compact(
			'messages',
			'username',
			'widget_with',
			'author_photo'
		);
	    set_query_var( 'widget_options', $widget_options );

		ob_start();
        if ( $overridden_template = locate_template( 'wptelegram-widget/widget-view.php' ) ) {
		    load_template( $overridden_template );
		} else {
		    load_template( dirname( __FILE__ ) . '/partials/widget-view.php' );
		}
        $html = ob_get_contents();
        ob_get_clean();
        return $html;
	}

	/**
	 * Set up the basics to get/receive updates
	 *
	 * @since    1.0.0
	 */
	public function setup_updates() {
		// Avoid infinite loop
		if ( isset( $_GET['action'] ) && 'wptelegram_widget_long_polling' == $_GET['action'] ) {
			return;
		}

		// settings page options
		$bot_token = wptelegram_widget_get_option( 'bot_token' );
		$username = wptelegram_widget_get_option( 'username' );

		if ( ! $bot_token || ! $username ) {
			return;
		}

		$this->tg_api = new WPTelegram_Bot_API( $bot_token );

		$this->use_long_polling( $bot_token );
	}

	/**
	 * Pull updates from Telegram
	 *
	 * @param string $bot_token
	 * @since    1.0.0
	 */
	private function use_long_polling( $bot_token ) {
		$transient = 'wptelegram_widget_last_check_for_webhook';
		if ( ! get_site_transient( $transient ) ) {
			$webhook_info = $this->tg_api->getWebhookInfo();
			if ( ! is_wp_error( $webhook_info ) && 200 == $webhook_info->get_response_code() ) {
				$result = $webhook_info->get_result();
			}
			// to be used to short circuit the function
			set_site_transient( $transient, true, 600 );
		}

		// delete webhook if set
		if ( isset( $result['url'] ) && $result['url'] ) {
			$this->tg_api->deleteWebhook();
		}
		// return if already checked for updates in long_polling_interval
		$transient = 'wptelegram_widget_long_polling_interval';
		if ( get_site_transient( $transient ) ) {
			return;
		}
		/**
		 * Send a non-blocking request to admin-post.php
		 * to reduce the processing time of the page
		 * The update process will be completed in the background
		 */
	    $admin_post_url = admin_url( 'admin-post.php' );
	    $args = array(
	    	'action'	=> 'wptelegram_widget_long_polling',
	    	'bot_token'	=> $bot_token,
    	);
		$post_url = add_query_arg( $args, $admin_post_url );
	    $args = array(
	    	'timeout' => 0.1,
	    	'blocking' => false,
    	);
		wp_remote_post( $post_url, $args );
		// expiration for the transient in seconds
		// default to 5 minutes (300 seconds)
		$expiration = (int) apply_filters( 'wptelegram_widget_updates_interval', 300 );;
		set_site_transient( $transient, true, $expiration );
	}

}
