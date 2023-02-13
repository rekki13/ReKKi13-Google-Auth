<?php
global $current_user;

if ( $current_user->exists() ) { ?>
    <div class="container">
        <div class="row">
            <div class="col">
				<?php if ( isset( $_GET['updated'] ) && $_GET['updated'] == 'true' ) : ?>
                    <p><?=__('Your profile has been updated','rekki13-google-auth')?></p>
				<?php endif; ?>

                <form method="POST" action="<?php the_permalink(); ?>" id="rekki13_google_auth_form" class="userInfo">
                    <p>
                        <label for="uname"><?= __( 'Username', 'rekki13-google-auth' ) ?></label>
                        <input type="text" value="<?php the_author_meta( 'first_name', $current_user->ID ); ?>" name="uname" id="uname">
                    </p>
                    <p>
                        <label for="uemail"><?= __( 'Email', 'rekki13-google-auth' ) ?></label>
                        <input type="text" value="<?php the_author_meta( 'user_email', $current_user->ID ); ?>" name="uemail" id="uemail">
                    </p>
                    <p>
                        <label for="upass"><?= __( 'Password', 'rekki13-google-auth' ) ?></label>
                        <input type="text" value="" name="upass" placeholder="HARDPASS" id="upass">
                    </p>
                    <p class="checkbox">
                        <label for="GAuth_enabled"><?= __( 'Is enabled Google Auth?', 'rekki13-google-auth' ) ?></label>
                        <input type="checkbox" name="GAuth_enabled" id="GAuth_enabled" <?= ( get_user_meta(  $current_user->ID ,'GAuth_enabled',true) == 'on'  ? 'checked' : '' ) ?> >
                    </p>
                    <div id="GAuth_info">
                        <p>
                            <input name="GAuth_secret" id="GAuth_secret" value="" readonly="readonly" type="text" size="25" hidden/>
                        </p>
                        <p>
                            <input name="GAuth_password" id="GAuth_password" readonly="readonly" value="XXXX XXXX XXXX XXXX" type="text" size="25" hidden/>
                        </p>
		                <?=__('Please scan this code on your google authenticator','rekki13-google-auth')?>
                        <div id="GAuth_QRCODE"></div>
                    </div>
                    <input type="submit" value="<?= __( 'Save', 'rekki13-google-auth' ) ?>">
                    <input name="action" type="hidden" id="action" value="userInfoUpdate"/>
                </form>
                <a href="<?php echo site_url().'/wp-login.php?action=logout'?>"><?= __( 'Logout?', 'rekki13-google-auth' ) ?></a>
            </div>
        </div>
    </div>
	<?php
}

