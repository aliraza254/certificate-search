<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://https://webtechsofts.co.uk/
 * @since      1.0.0
 *
 * @package    Certificate_Search
 * @subpackage Certificate_Search/includes
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
 * @package    Certificate_Search
 * @subpackage Certificate_Search/includes
 * @author     Web Tech Softs <info@webtechsofts.com>
 */
class Certificate_Search {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Certificate_Search_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

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
		if ( defined( 'CERTIFICATE_SEARCH_VERSION' ) ) {
			$this->version = CERTIFICATE_SEARCH_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'certificate-search';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_shortcodes();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Certificate_Search_Loader. Orchestrates the hooks of the plugin.
	 * - Certificate_Search_i18n. Defines internationalization functionality.
	 * - Certificate_Search_Admin. Defines all hooks for the admin area.
	 * - Certificate_Search_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-certificate-search-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-certificate-search-i18n.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-certificate-search-short-code.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-certificate-search-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-certificate-search-public.php';

		$this->loader = new Certificate_Search_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Certificate_Search_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Certificate_Search_i18n();

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

		$plugin_admin = new Certificate_Search_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_admin, 'create_custom_post_type' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_custom_fields' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_custom_metabox' );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_menu_function' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'admin_init_function' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Certificate_Search_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
//
	}

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    private function define_shortcodes() {

        $shortcode_admin = new Certificate_Search_ShortCode( $this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action( 'wp_enqueue_scripts', $shortcode_admin, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $shortcode_admin, 'enqueue_scripts' );
        $this->loader->add_action( 'wp_ajax_certificate_search_result', $shortcode_admin, 'certificate_search_result' );
        $this->loader->add_action( 'wp_ajax_nopriv_certificate_search_result', $shortcode_admin, 'certificate_search_result' );

        $this->loader->add_action( 'wp_ajax_certificate_search_subscription_result', $shortcode_admin, 'certificate_search_subscription_result' );
        $this->loader->add_action( 'wp_ajax_nopriv_certificate_search_subscription_result', $shortcode_admin, 'certificate_search_subscription_result' );

        $this->loader->add_filter( 'woocommerce_registration_redirect', $shortcode_admin, 'redirect_to_checkout_after_registration' );

        add_shortcode( 'certificate_search', array($shortcode_admin, 'certificate_search_function') );
        add_shortcode( 'certificate_search_table', array($shortcode_admin, 'certificate_search_table_function') );

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
	 * @return    Certificate_Search_Loader    Orchestrates the hooks of the plugin.
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
