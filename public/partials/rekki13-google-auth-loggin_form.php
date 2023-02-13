<?php

$args = array(
	'redirect'       => site_url( $_SERVER['REQUEST_URI'] ),
	'form_id'        => 'rekki13_google_auth_form',
	'label_username' => __('Username','rekki13-google-auth'),
	'label_password' => __('Password','rekki13-google-auth'),
	'label_log_in'   => __('Sign in','rekki13-google-auth'),
	'id_username'    => 'user_login',
	'id_password'    => 'user_pass',
	'id_submit'      => 'wp-submit',
	'remember'       => false,
);

?>
<div class="container">
    <div class="row">
        <div class="col">
			<?php wp_login_form( $args ); ?>
        </div>
    </div>
</div>