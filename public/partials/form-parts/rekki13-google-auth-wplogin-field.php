<p class="hint">
	<label><?= __( 'Google Authenticator code','rekki13-google-auth' ) ?></label>
	<input type="text" name="googleotp" id="googleotp" class="input" value="" size="20" autocomplete="off"/>
	<small><?= __( 'If you don\'t have Google Authenticator enabled for your WordPress account, leave this field empty.', 'rekki13-google-auth' ) ?></small>
</p>
<script type="text/javascript">document.getElementById("googleotp").focus();</script>