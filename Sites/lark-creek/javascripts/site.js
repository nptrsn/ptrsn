/*!
 * File:       site.js
 * Desc:       Core site Javascripts
 * Author:     Lion Associates
 * Author URI: http://www.lionassociates.com
 */

/* JS from Soekan (Old Theme) */
function validateEmail(emailAddress) {
	/* NOTE:  This regular expression is identical to the one used by Enterprise for e-mail address validation */
	var regExp = 
	/^\s*[a-zA-Z\d][a-zA-Z\d\.!#$%&'*+\-\/=?^_`{|}~]*@([a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,6}\s*$/;
	if (emailAddress.length <= 200 && regExp.test(emailAddress)) {
	 return true;
	} else {
	 alert("Invalid e-mail address");
	 return false;
	}
}


// Controls the mobile navigation
$(document).ready(function() {
	$('#mobile-button').click(function(){
		if ($(this).hasClass('open')) {
			$('#master-wrap').animate({'right':"0px"},200).css('position','relative');
			$(this).removeClass('open');
		} else {
			$('#master-wrap').animate({'right':"240px"},400).css('position','fixed');
			$(this).addClass('open');
		}
	});

	$('#arrow_left, #arrow_right').on( 'click', function() {
		return false;
	} );

});