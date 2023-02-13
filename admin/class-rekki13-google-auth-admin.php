<?php


class Rekki13_Google_Auth_Admin {

	private $rekki13_google_auth;

	private $version;

	public function __construct( $rekki13_google_auth, $version ) {
		$this->rekki13_google_auth = $rekki13_google_auth;
		$this->version             = $version;
	}

	public function enqueueStyles() {
		wp_enqueue_style( $this->rekki13_google_auth, plugin_dir_url( __FILE__ ) . 'css/rekki13-google-auth-admin.css', array(), $this->version, 'all' );
	}

	public function enqueueScripts() {
		wp_enqueue_script( $this->rekki13_google_auth, plugin_dir_url( __FILE__ ) . 'js/rekki13-google-auth-admin.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Add page state
	 */
	public function pagePostState( $post_states, $post ) {
		$pages = [
			'rekki13-login-page',
			'rekki13-registration-page',
			'rekki13-account-page'
		];

		if(in_array($post->post_name, $pages)){
			$post_states[] = __( 'Created by ReKKi13 Google Auth', 'rekki13-google-auth' );
		}

		return $post_states;
	}

	/**
	 * User tables
	 */
	public function modifyUserTable( $column ) {
		$column['GAuth_enabled']      = 'User GAuth enabled';
		$column['GAuth_secret']       = 'User Secret Key';
		$column['GAuth_password']     = 'User Pass';
		$column['GAuth_lasttimeslot'] = 'User Last time code';

		return $column;
	}

	public function modifyUserTableRow( $val, $column_name, $user_id ) {
		switch ( $column_name ) {
			case 'GAuth_enabled' :
				return get_the_author_meta( 'GAuth_enabled', $user_id );
				break;
			case 'GAuth_lasttimeslot' :
				return get_the_author_meta( 'GAuth_lasttimeslot', $user_id );
				break;
			case 'GAuth_secret' :
				return get_the_author_meta( 'GAuth_secret', $user_id );
				break;
			case 'GAuth_password' :
				return get_the_author_meta( 'GAuth_password', $user_id );
				break;
			default:
		}

		return $val;
	}


	/**
	 * User registration
	 */
	public function createAccount() {
		$user               = ( isset( $_POST['uname'] ) ? $_POST['uname'] : '' );
		$pass               = ( isset( $_POST['upass'] ) ? $_POST['upass'] : '' );
		$email              = ( isset( $_POST['uemail'] ) ? $_POST['uemail'] : '' );
		$GAuth_enabled      = ( isset( $_POST['GAuth_enabled'] ) ? $_POST['GAuth_enabled'] : 'no' );
		$GAuth_secret       = ( isset( $_POST['GAuth_secret'] ) ? $_POST['GAuth_secret'] : '' );
		$GAuth_password     = ( isset( $_POST['GAuth_password'] ) ? $_POST['GAuth_password'] : '' );
		$GAuth_lasttimeslot = ( isset( $_POST['GAuth_lasttimeslot'] ) ? $_POST['GAuth_lasttimeslot'] : '' );

		if ( ! username_exists( $user ) && ! email_exists( $email ) ) {
			$user_id = wp_create_user( sanitize_text_field( $user ), $pass, sanitize_text_field( $email ) );
			if ( ! is_wp_error( $user_id ) ) {
				update_user_meta( $user_id, 'first_name', sanitize_text_field( $user ) );
				add_user_meta( $user_id, 'GAuth_enabled', $GAuth_enabled );
				add_user_meta( $user_id, 'GAuth_secret', $GAuth_secret );
				add_user_meta( $user_id, 'GAuth_password', wp_hash_password( $GAuth_password ) );
				add_user_meta( $user_id, 'GAuth_lasttimeslot', $GAuth_lasttimeslot );
				wp_redirect( get_permalink() . '?created=true' );
				exit;
			}
		}
	}


	public function userInfoUpdate() {
		global $current_user;

		$user_firstName = $current_user->user_firstname;
		$userPass       = $current_user->user_pass;
		$userEmail      = $current_user->user_email;

		if ( isset( $_POST['action'] ) && $_POST['action'] == 'userInfoUpdate' ) {

			if ( $_POST['uname'] != $user_firstName ) {
				$user_firstName = $_POST['uname'];
			}
			if ( $_POST['uemail'] != $userEmail ) {
				$userEmail = $_POST['uemail'];
			}
			if ( $_POST['upass'] != null ) {
				$userPass = $_POST['upass'];
			}

			wp_update_user( [
				'ID'         => $current_user->ID,
				'first_name' => sanitize_text_field($user_firstName),
				'user_email' => sanitize_text_field($userEmail),
				'user_pass'  => $userPass,
			] );

			if ( isset( $_POST['GAuth_enabled'] ) ) {
				update_user_meta( $current_user->ID, 'GAuth_enabled', 'on' );
				update_user_meta( $current_user->ID, 'GAuth_secret', $_POST['GAuth_secret'] );
				update_user_meta( $current_user->ID, 'GAuth_password', $_POST['GAuth_password'] );
			} else {
				update_user_meta( $current_user->ID, 'GAuth_enabled', 'no' );
				update_user_meta( $current_user->ID, 'GAuth_secret', '' );
				update_user_meta( $current_user->ID, 'GAuth_password', '' );
			}
			wp_redirect( get_permalink() . '?updated=true' );
		}
	}

	public function checkOtp( $user, $username = '', $password = '' ) {
		$userstate = $user;

		if ( get_user_by( 'email', $username ) === false ) {
			$user = get_user_by( 'login', $username );
		} else {
			$user = get_user_by( 'email', $username );
		}
		if ( ! empty( $_POST['googleotp'] ) ) {
			$otp = trim( $_POST['googleotp'] );
		} else {
			$otp = '';
		}

		if ( isset( $user->ID ) && trim( get_user_option( 'GAuth_enabled', $user->ID ) ) == 'on' ) {

			$GAuth_secret = trim( get_user_option( 'GAuth_secret', $user->ID ) );

			$lasttimeslot = trim( get_user_option( 'GAuth_lasttimeslot', $user->ID ) );


			if ( $timeslot = $this->verify( $GAuth_secret, $otp, $lasttimeslot ) ) {
				update_user_option( $user->ID, 'GAuth_lasttimeslot', $timeslot, true );

				return $userstate;
			}
		}
		if ( isset( $user->ID ) && trim( get_user_option( 'GAuth_enabled', $user->ID ) ) == 'no' || null ) {
			return $userstate;
		}
	}


	public function verify( $secretkey, $thistry, $lasttimeslot ) {
		if ( strlen( $thistry ) != 6 ) {
			return false;
		} else {
			$thistry = intval( $thistry );
		}

		$firstcount = - 1;
		$lastcount  = 1;

		$tm = floor( time() / 30 );

		$secretkey = Base32::decode( $secretkey );
		for ( $i = $firstcount; $i <= $lastcount; $i ++ ) {
			$time     = chr( 0 ) . chr( 0 ) . chr( 0 ) . chr( 0 ) . pack( 'N*', $tm + $i );
			$hm       = hash_hmac( 'SHA1', $time, $secretkey, true );
			$offset   = ord( substr( $hm, - 1 ) ) & 0x0F;
			$hashpart = substr( $hm, $offset, 4 );
			$value    = unpack( "N", $hashpart );
			$value    = $value[1];
			$value    = $value & 0x7FFFFFFF;
			$value    = $value % 1000000;
			if ( $value === $thistry ) {
				if ( $lasttimeslot >= ( $tm + $i ) ) {
					return false;
				}

				return $tm + $i;
			}
		}

		return false;
	}

	function menuCustomFields( $item_id, $item ) {
		$args = array(
			'item_id' => $item_id,
			'item' => $item
		);

		get_template_part( 'menuCustomFieldsHTML',null,$args);
	}

	function menuCustomFieldsHTML($slug , $name , $args) {
		require 'partials/rekki13-google-auth-menu-fields.php';
	}


	function menuNavUpdate( $menu_id, $menu_item_db_id ) {
		if ( isset( $_POST['menu-item'][ $menu_item_db_id ]['rekki13_google_auth_id'] ) && $_POST['menu-item'][ $menu_item_db_id ]['rekki13_google_auth_id'] ) {
			update_post_meta( $menu_item_db_id, '_rekki13_nav_item_options', $_POST['menu-item'][ $menu_item_db_id ]['rekki13_google_auth_id'] );
		} else {
			delete_post_meta( $menu_item_db_id, '_rekki13_nav_item_options' );
		}
	}

	function excludeMenuItems( $items, $menu, $args ) {
		foreach ( $items as $key => $item ) {
			$menu_item_fields = get_post_meta( $item->ID, '_rekki13_nav_item_options', true );
			switch ( $menu_item_fields ):
				case 'Logged':
					if ( ! is_user_logged_in() && ! is_admin() ) {
						unset( $items[ $key ] );
					}
					break;
				case 'Non Logged':
					if ( is_user_logged_in() && ! is_admin() ) {
						unset( $items[ $key ] );
					}
					break;
			endswitch;
		}

		return $items;
	}
}
