<?php

/**
 * The file that defines the core plugin class
 *
 *
 * @link       https://t.me/manzoorwanijk
 * @since      1.0.0
 *
 * @package    Wptelegram_Widget
 * @subpackage Wptelegram_Widget/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wptelegram_Widget
 * @subpackage Wptelegram_Widget/includes
 * @author     Manzoor Wani 
 */
class Wptelegram_Widget {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wptelegram_Widget_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * Title of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $title    Title of the plugin
	 */
	protected $title;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->title =  __( 'WP Telegram Widget', 'wptelegram-widget' );
		if ( defined( 'WPTELEGRAM_WIDGET_VER' ) ) {
			$this->version = WPTELEGRAM_WIDGET_VER;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wptelegram-widget';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wptelegram_Widget_Loader. Orchestrates the hooks of the plugin.
	 * - Wptelegram_Widget_i18n. Defines internationalization functionality.
	 * - Wptelegram_Widget_Admin. Defines all hooks for the admin area.
	 * - Wptelegram_Widget_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once WPTELEGRAM_WIDGET_DIR . '/includes/class-wptelegram-widget-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once WPTELEGRAM_WIDGET_DIR . '/includes/class-wptelegram-widget-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once WPTELEGRAM_WIDGET_DIR . '/admin/class-wptelegram-widget-admin.php';

		/**
		 * The class responsible for displaying messages list
		 */
		require_once WPTELEGRAM_WIDGET_DIR . '/admin/class-wptelegram-widget-messages-list.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once WPTELEGRAM_WIDGET_DIR . '/public/class-wptelegram-widget-public.php';

		/**
		 * Helper functions
		 */
		require_once WPTELEGRAM_WIDGET_DIR . '/includes/helper-functions.php';

		/**
		 * Our widget class
		 */
		require_once WPTELEGRAM_WIDGET_DIR . '/public/widgets/class-wptelegram-widget-widget.php';

		/**
		 * The class responsible for loading WPTelegram_Bot_API library
		 */
		require_once WPTELEGRAM_WIDGET_DIR . '/includes/wptelegram-bot-api/class-wptelegram-bot-api-loader.php';

		$this->loader = new Wptelegram_Widget_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wptelegram_Widget_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wptelegram_Widget_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wptelegram_Widget_Admin( $this->get_plugin_title(), $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_notices', $plugin_admin, 'admin_notice_for_cmb2' );

		$this->loader->add_action( 'cmb2_admin_init', $plugin_admin, 'create_options_page' );

		$this->loader->add_action( 'widgets_init', $plugin_admin, 'register_widgets' );

		// to be used for long polling
		$this->loader->add_action( 'admin_post_nopriv_wptelegram_widget_long_polling', $plugin_admin, 'handle_long_polling' );
		$this->loader->add_action( 'admin_post_wptelegram_widget_long_polling', $plugin_admin, 'handle_long_polling' );

		$this->loader->add_action( 'current_screen', $plugin_admin, 'set_screen_options' );

		$this->loader->add_filter( 'set-screen-option', $plugin_admin, 'save_screen_option', 10, 3 );

		$this->loader->add_action( 'wp_ajax_wptelegram_widget_pull_messages', $plugin_admin, 'pull_messages' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wptelegram_Widget_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'init', $plugin_public, 'setup_updates', 11 );

		$this->loader->add_shortcode( 'wptelegram-widget', $plugin_public, 'widget_shortcode' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The title of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The title of the plugin.
	 */
	public function get_plugin_title() {
		return $this->title;
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wptelegram_Widget_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
