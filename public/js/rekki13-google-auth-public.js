(function( $ ) {
	$(document).ready(function() {
		$('#GAuth_QRCODE').html("");
		var data=new Object();
		data['action']	= 'GAuth_action';
		data['nonce']	= objects.GAnonce;
		GAuth_enabled();
		$.post(objects.ajaxurl, data, function(response) {
			$('#GAuth_secret').val(response['new-secret']);
			var qrcode="otpauth://totp/WordPress:"+escape('rekki13-google-auth')+"?secret="+$('#GAuth_secret').val()+"&issuer=WordPress";
			$('#GAuth_QRCODE').qrcode(qrcode);
			$('#GAuth_QR_INFO').show('slow');
			$('#GAuth_password').val(response['new-secret'].match(new RegExp(".{0,4}","g")).join(' '));
		});
	});
	$('#GAuth_enabled').bind('change',function() {
		GAuth_enabled();
	});

	function GAuth_enabled() {
		if ($('#GAuth_enabled').is(':checked')) {
			$('#GAuth_info').show();
		} else {
			$('#GAuth_info').hide();

		}
	}
})( jQuery );
