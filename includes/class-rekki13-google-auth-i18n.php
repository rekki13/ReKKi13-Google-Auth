<?php

class Rekki13_Google_Auth_i18n {

	public function loadPluginTextdomain() {
		load_plugin_textdomain(
			'rekki13-google-auth',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}
