<?php

class Rekki13_Google_Auth_Public {

	private $rekki13_google_auth;

	private $version;

	public function __construct( $rekki13_google_auth, $version ) {
		$this->rekki13_google_auth = $rekki13_google_auth;
		$this->version             = $version;
	}

	public function enqueueStyles() {
		wp_enqueue_style( $this->rekki13_google_auth, plugin_dir_url( __FILE__ ) . 'css/rekki13-google-auth-public.css', array(), $this->version, 'all' );
	}

	public function enqueueScripts() {
		$var = [
			'GAnonce' => wp_create_nonce( 'GAuth' ),
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
		];

		wp_enqueue_script( 'qrcode', plugin_dir_url( __FILE__ ) . 'js/jquery.qrcode.min.js', array( 'jquery-core' ), $this->version, true );
		wp_enqueue_script( 'rekki13_google_auth', plugin_dir_url( __FILE__ ) . 'js/rekki13-google-auth-public.js', array( 'jquery-core' ), $this->version, true );
		wp_localize_script( 'rekki13_google_auth', 'objects', $var );
	}

	public function redirects() {
		if ( (is_page( 'rekki13-account-page' ) || preg_match( '/rekki13-account-page(\/.+)/', $_SERVER['REQUEST_URI'] )) && !is_user_logged_in() ) {
			wp_redirect( site_url() . '/rekki13-login-page' );
		}

		if ( is_page( [ 'rekki13-login-page', 'rekki13-registration-page' ] )
		     && is_user_logged_in()
		) {
			wp_redirect( site_url() . '/rekki13-account-page' );
		}

	}

	public function ajaxCallback() {
		check_ajax_referer( 'GAuth', 'nonce' );

		$secret = $this->createSecret();

		$result = array(
			'new-secret' => $secret,
		);

		header( 'Content-Type: application/json' );
		echo json_encode( $result );

		die();
	}

	/**
	 * Create secret hash
	 */
	public function createSecret() {
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567'; // allowed characters in Base32
		$secret = '';
		for ( $i = 0; $i < 16; $i ++ ) {
			$secret .= substr( $chars, wp_rand( 0, strlen( $chars ) - 1 ), 1 );
		}

		return $secret;
	}


	/**
	 * Create shortcode and display it
	 */
	public function registerShortcodes() {
		add_shortcode( 'rekki13LoginForm', array( $this, 'loginFormDisplay' ) );
		add_shortcode( 'rekki13RegistrationForm', array( $this, 'registerFormDisplay' ) );
		add_shortcode( 'rekki13AccountPage', array( $this, 'accountPage' ) );
	}

	public function loginFormDisplay() {
		if ( ! is_user_logged_in() ):
			require_once 'partials/rekki13-google-auth-loggin_form.php';
		else:
			require_once 'partials/rekki13-google-auth-logged.php';
		endif;
	}

	public function registerFormDisplay() {
		if ( ! is_user_logged_in() ):
			require_once 'partials/rekki13-google-auth-registration_form.php';
		else:
			require_once 'partials/rekki13-google-auth-logged.php';
		endif;
	}

	public function accountPage() {
		if ( ! is_user_logged_in() ):
			wp_redirect( site_url() . '/rekki13-registration-page/', 302 );
			exit;
		else:
			require_once 'partials/rekki13-google-auth-account.php';
		endif;
	}

	/**
	 * Add login field
	 */
	public function loginField() {
		ob_start();
		require_once __DIR__ . '/partials/form-parts/rekki13-google-auth-login-field.php';
		$var = ob_get_contents();
		ob_end_clean();

		return $var;
	}

	public function loginFieldRegistration() {
		ob_start();
		require_once __DIR__ . '/partials/form-parts/rekki13-google-auth-registration-field.php';
		$var = ob_get_contents();
		ob_end_clean();

		return $var;
	}

	public function loginFormWPLogin() {
		require_once __DIR__ . '/partials/form-parts/rekki13-google-auth-wplogin-field.php';
	}
}
