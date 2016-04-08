/**
 * ownCloud - user_rcdevsopenotp
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Julien RICHARD <julien.richard@rcdevs.com>
 * @copyright RCDevs - Julien RICHARD 2015
 */

(function ($, OC) {

	$(document).ready(function () {

		$('#openotp_settings #saveconfig').click(function () {
			var url = OC.generateUrl('/apps/user_rcdevsopenotp/saveconfig');
			var post = {
				post: $( "#openotp_settings" ).serialize()
			};
			
	        $.post( url, post, function(response){
				if($('#message').is(":visible")){
					$('#message').fadeOut("fast"); 
				}
	            if( response.status == "success" ){
	            	$('#message').removeClass('error').addClass('success').html(response.message).fadeIn('fast');
	            }else{
	            	$('#message').removeClass('success').addClass('error').html(response.message).fadeIn('fast');
	            }
	        });
	        return false;
		});		
		
		$('#openotp_psettings input[name="enable_openotp"]:radio').change(function() {
				var url = OC.generateUrl('/apps/user_rcdevsopenotp/saveconfig');
				var post = {
					post: $( "#openotp_psettings" ).serialize()
				};
		
		        $.post( url, post, function(response){
					if($('#message').is(":visible")){
						$('#message').fadeOut("fast"); 
					} 
		            if( response.status == "success" ){
		            	$('#message').removeClass('error').addClass('success').html(response.message).fadeIn('fast');
		            }else{
		            	$('#message').removeClass('success').addClass('error').html(response.message).fadeIn('fast');
		            }
		        });
		        return false;					  
		    });
		
	});

})(jQuery, OC);