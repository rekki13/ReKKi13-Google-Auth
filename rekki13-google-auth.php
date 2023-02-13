<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Rekki13_Google_Auth
 *
 * @wordpress-plugin
 * Plugin Name:       ReKKi13 Google Auth
 * Description:       Test Task google 2 auth
 * Version:           1.0.0
 * Author:            ReKKi13
 * Author URI:        https://rekki13.dev/
 * Text Domain:       rekki13-google-auth
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'REKKI13_GOOGLE_AUTH_VERSION', '1.0.0' );

function activate_rekki13_google_auth() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rekki13-google-auth-activator.php';
	Rekki13_Google_Auth_Activator::activate();
}

function deactivate_rekki13_google_auth() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rekki13-google-auth-deactivator.php';
	Rekki13_Google_Auth_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_rekki13_google_auth' );
register_deactivation_hook( __FILE__, 'deactivate_rekki13_google_auth' );

require plugin_dir_path( __FILE__ ) . 'includes/class-rekki13-google-auth.php';

function run_rekki13_google_auth() {
	$plugin = new Rekki13_Google_Auth();
	$plugin->run();
}
run_rekki13_google_auth();
