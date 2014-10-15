<?php

/**
 * Post to Social
 *
 * @package   Post_to_Social
 * @author    Steve Taylor
 * @license   GPL-2.0+
 */

/**
 * Plugin class
 *
 * @package Post_to_Social
 * @author  Steve Taylor
 */
class Post_to_Social {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   0.1
	 *
	 * @var     string
	 */
	protected $version = '0.1';

	/**
	 * Unique identifier for your plugin.
	 *
	 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
	 * match the Text Domain file header in the main plugin file.
	 *
	 * @since    0.1
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'post-to-social';

	/**
	 * Instance of this class.
	 *
	 * @since    0.1
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    0.1
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * The plugin's settings.
	 *
	 * @since    0.1
	 *
	 * @var      array
	 */
	protected $settings = null;

	/**
	 * Ready to use Twitter?
	 *
	 * @since    0.1
	 *
	 * @var      array
	 */
	protected $twitter_ready = false;

	/**
	 * Ready to use Facebook?
	 *
	 * @since    0.1
	 *
	 * @var      array
	 */
	protected $facebook_ready = false;

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     0.1
	 */
	private function __construct() {

		// Global init
		add_action( 'init', array( $this, 'init' ) );

		// Admin init
		add_action( 'admin_init', array( $this, 'admin_init' ) );

		// Add the settings page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'process_plugin_admin_settings' ) );

		// Load admin style sheet and JavaScript.
		//add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		//add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Load public-facing style sheet and JavaScript.
		//add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		//add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Custom fields
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 10, 2 );

		// Other hooks

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     0.1
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    0.1
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		// This is a trick used to get around the difficulty of adding hooks and calling non-static methods here
		// The actual activation stuff is done in admin_init
		// @link http://codex.wordpress.org/Function_Reference/register_activation_hook#Process_Flow
		add_option( __CLASS__ . '_activating', 1 );

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    0.1
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

	}

	/**
	 * Initialize
	 *
	 * @since    0.1
	 */
	public function init() {

		// Set the settings
		$this->settings = $this->get_settings();

		// Load plugin text domain
		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

		// Set ready flags
		$this->twitter_ready = ! (
			empty( $this->settings['twitter_consumer_key'] ) ||
			empty( $this->settings['twitter_consumer_secret'] ) ||
			empty( $this->settings['twitter_access_token'] ) ||
			empty( $this->settings['twitter_access_token_secret'] )
		);

		// Load Codebird library for Twitter?
		if ( $this->twitter_ready ) {
			require_once( plugin_dir_path( __FILE__ ) . 'inc/codebird.php' );
		}

	}

	/**
	 * Initialize admin
	 *
	 * @since	0.1
	 * @return	void
	 */
	public function admin_init() {

	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     0.1
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {
		$screen = get_current_screen();

		wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'css/admin.css', __FILE__ ), array(), $this->version );

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     0.1
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {
		$screen = get_current_screen();

		$script = defined( 'WP_LOCAL_DEV' ) && WP_LOCAL_DEV ? plugins_url( 'js/admin.js', __FILE__ ) : plugins_url( 'js/admin.min.js', __FILE__ );
		wp_enqueue_script( $this->plugin_slug . '-admin-script', $script, array( 'jquery' ), $this->version );

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    0.1
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'css/public.css', __FILE__ ), array(), $this->version );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    0.1
	 */
	public function enqueue_scripts() {
		$script = defined( 'WP_LOCAL_DEV' ) && WP_LOCAL_DEV ? plugins_url( 'js/public.js', __FILE__ ) : plugins_url( 'js/public.min.js', __FILE__ );
		wp_enqueue_script( $this->plugin_slug . '-plugin-script', $script, array( 'jquery' ), $this->version );
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    0.1
	 */
	public function add_plugin_admin_menu() {

		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'Post to Social', $this->plugin_slug ),
			__( 'Post to Social', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    0.1
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}

	/**
	 * Get the plugin's settings
	 *
	 * @since    0.1
	 */
	public function get_settings() {

		$settings = get_option( $this->plugin_slug . '_settings' );

		// Defaults
		if ( ! $settings ) {
			$settings = array();
		}
		$settings = array_merge(
			array(
				'twitter_consumer_key'			=> '',
				'twitter_consumer_secret'		=> '',
				'twitter_access_token'			=> '',
				'twitter_access_token_secret'	=> '',
				'twitter_post_types'			=> array( 'post' )
			),
			$settings
		);

		return $settings;
	}

	/**
	 * Set the plugin's settings
	 *
	 * @since    0.1
	 */
	public function set_settings( $settings ) {
		return update_option( $this->plugin_slug . '_settings', $settings );
	}

	/**
	 * Process the settings page for this plugin.
	 *
	 * @since    0.1
	 */
	public function process_plugin_admin_settings() {

		// Submitted?
		if ( isset( $_POST[ $this->plugin_slug . '_settings_admin_nonce' ] ) && check_admin_referer( $this->plugin_slug . '_settings', $this->plugin_slug . '_settings_admin_nonce' ) ) {

			// Gather into array
			$settings = array(
				'twitter_consumer_key'			=> preg_replace( '/[^A-Za-z0-9\-]*/', '', $_POST['twitter_consumer_key'] ),
				'twitter_consumer_secret'		=> preg_replace( '/[^A-Za-z0-9\-]*/', '', $_POST['twitter_consumer_secret'] ),
				'twitter_access_token'			=> preg_replace( '/[^A-Za-z0-9\-]*/', '', $_POST['twitter_access_token'] ),
				'twitter_access_token_secret'	=> preg_replace( '/[^A-Za-z0-9\-]*/', '', $_POST['twitter_access_token_secret'] ),
				'twitter_post_types'			=> $_POST['twitter_post_types']
			);

			// Save as option
			$this->set_settings( $settings );

			// Redirect
			wp_redirect( admin_url( 'options-general.php?page=' . $this->plugin_slug . '&done=1' ) );

		}

	}

	/**
	 * Get post types which might be posted
	 *
	 * @since	0.1
	 * @return array
	 */
	public function get_potential_post_types() {
		return array_merge(
			array( 'post' => 'post', 'page' => 'page' ),
			get_post_types( array( 'public' => true, '_builtin' => false ) )
		);
	}

	/**
	 * Add meta boxes
	 *
	 * @since	0.1
	 * @param	$context
	 * @param	$object
	 * @return	void
	 */
	public function add_meta_boxes( $context, $object ) {

		// Check for context based on object properties in case the are 'link' or 'comment' core custom post types
		if ( is_object( $object ) && ! ( isset( $object->comment_ID ) || isset( $object->link_id ) ) ) {
			$request_type = 'post';
			$scope = $context;
			if ( $context == 'attachment' ) {
				$request_type = 'attachment';
				$scope = $object->post_mime_type;
			}
			slt_cf_init_fields( $request_type, $scope, $object->ID );
			if ( count( $slt_custom_fields['boxes'] ) )
				slt_cf_add_meta_boxes( $context );

			// Post meta output for admins
			if ( current_user_can( 'update_core' ) )
				add_meta_box( 'slt_cf_postmeta_output', __( 'All post meta' ), 'slt_cf_postmeta_output', null, 'advanced', 'low' );
		}
	}

}