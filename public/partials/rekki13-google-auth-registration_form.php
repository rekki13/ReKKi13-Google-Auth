<div class="container">
    <div class="row">
        <div class="col">
	        <?php if ( isset( $_GET['created'] ) && $_GET['created'] == 'true' ) : ?>
                <p><?=__('Your profile has been created successfully','rekki13-google-auth')?>
                    <a href="<?= site_url().'/rekki13-login-page';?>"><?=__('Sign in','rekki13-google-auth')?></a></p>
	        <?php endif; ?>
            <form method="post" name="myForm" class="myForm"  id="rekki13_google_auth_form">
                <p>
                    <label for="uname"><?=__('Username','rekki13-google-auth')?></label>
                    <input id="uname" type="text" name="uname"/>
                </p>
                <p>
                    <label for="uemail"><?=__('Email','rekki13-google-auth')?></label>
                    <input id="uemail" type="email" name="uemail"/>
                </p>
                <p>
                    <label for="upass"><?=__('Password','rekki13-google-auth')?></label> <input type="password" name="upass" id="upass"/>
                </p>
                <p class="checkbox">
                    <label for="GAuth_enabled"><?=__('Add Google Auth?','rekki13-google-auth')?></label>
                    <input type="checkbox" name="GAuth_enabled"
                           id="GAuth_enabled">
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
                <input type="submit" value="<?=__('Sign Up','rekki13-google-auth')?>"/>
                <p><?=__('Already have an account?','rekki13-google-auth')?> <a href="<?= site_url('rekki13-login-page')?>" target="_blank"><?=__('Sign in','rekki13-google-auth')?></a></p>
            </form>
        </div>
    </div>
</div>