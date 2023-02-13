<?php

class Rekki13_Google_Auth {

	protected $loader;

	protected $rekki13_google_auth;

	protected $version;

	public function __construct() {
		if ( defined( 'REKKI13_GOOGLE_AUTH_VERSION' ) ) {
			$this->version = REKKI13_GOOGLE_AUTH_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->rekki13_google_auth = 'rekki13-google-auth';

		$this->loadDependencies();
		$this->setLocale();
		$this->defineAdminHooks();
		$this->definePublicHooks();

	}

	private function loadDependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-rekki13-google-auth-loader.php';
		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-rekki13-google-auth-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-rekki13-google-auth-admin.php';


		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-rekki13-google-auth-public.php';

		/**
		 * Include base32 HASH
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'base32.php';

		$this->loader = new Rekki13_Google_Auth_Loader();
	}
	private function setLocale() {
		$plugin_i18n = new Rekki13_Google_Auth_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'loadPluginTextdomain' );
	}

	private function defineAdminHooks() {
		$plugin_admin = new Rekki13_Google_Auth_Admin( $this->getRekki13GoogleAuth(), $this->getVersion() );

		/**
		 * Styles and scripts
		 */
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueueStyles' );
		$this->loader->add_action( 'login_enqueue_scripts', $plugin_admin, 'enqueueStyles' );

		/**
		 * User registration
		 */
		$this->loader->add_action( 'init', $plugin_admin, 'createAccount' );

		/**
		 * User info update
		 */
		$this->loader->add_action( 'init', $plugin_admin, 'userInfoUpdate' );

		/**
		 * Custom tables
		 */
		$this->loader->add_filter( 'manage_users_columns',$plugin_admin, 'modifyUserTable' );
		$this->loader->add_filter('manage_users_custom_column',$plugin_admin,'modifyUserTableRow', 10, 3 );

		/**
		 * Add plugin login page state
		 */
		$this->loader->add_filter( 'display_post_states', $plugin_admin,'pagePostState', 10, 2 );

		/**
		 * Create menu display conditions
		 */
		$this->loader->add_action( 'wp_nav_menu_item_custom_fields', $plugin_admin,'menuCustomFields', 10, 2 );
		$this->loader->add_action( 'get_template_part_menuCustomFieldsHTML',$plugin_admin,'menuCustomFieldsHTML',10,3);
		$this->loader->add_action( 'wp_update_nav_menu_item',$plugin_admin, 'menuNavUpdate', 10, 2 );
		$this->loader->add_filter( 'wp_get_nav_menu_items',$plugin_admin, 'excludeMenuItems', 10, 3 );

		/**
		 * Authenticate login
		 */
		$this->loader->add_filter( 'authenticate',$plugin_admin,'checkOtp' , 50, 3 );
	}

	private function definePublicHooks() {
		$plugin_public = new Rekki13_Google_Auth_Public( $this->getRekki13GoogleAuth(), $this->getVersion() );

		/**
		 * Styles and scripts
		 */
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueueStyles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueueScripts' );

		/**
		 * Add shortcodes
		 */
		$this->loader->add_action( 'init', $plugin_public, 'registerShortcodes' );

		/**
		 * Redirects
		 */
		$this->loader->add_action( 'template_redirect', $plugin_public,'redirects' );

		/**
		 * Add form field
		 */
		$this->loader->add_filter( 'login_form_middle', $plugin_public, 'loginField' );
		$this->loader->add_filter( 'login_form_bottom', $plugin_public, 'loginFieldRegistration' );
		$this->loader->add_action( 'login_form', $plugin_public, 'loginFormWPLogin' );

		/**
		 * Ajax
		 */
		$this->loader->add_action( 'wp_ajax_GAuth_action', $plugin_public, 'ajaxCallback' );
		$this->loader->add_action( 'wp_ajax_nopriv_GAuth_action', $plugin_public, 'ajaxCallback' );

	}

	public function run() {
		$this->loader->run();
	}

	public function getRekki13GoogleAuth() {
		return $this->rekki13_google_auth;
	}
	public function getLoader() {
		return $this->loader;
	}

	public function getVersion() {
		return $this->version;
	}

}
