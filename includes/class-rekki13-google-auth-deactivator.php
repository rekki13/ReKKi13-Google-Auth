<?php

class Rekki13_Google_Auth_Deactivator {

	public static function deactivate() {
		$pagesArr = ['rekki13-login-page','rekki13-registration-page','rekki13-account-page'];

		foreach ($pagesArr as $page):
			if (  get_page_by_path( $page ) ) {
				wp_delete_post( get_page_by_path( $page )->ID ,true);
			}
		endforeach;
	}
}
