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
			
			
		$('#check_server_url').click(function () {
			check_server_url();
		});					
		
		if ( $("#openotp_settings").length ) {
			check_server_url();			
		}
		
	});

})(jQuery, OC);

function check_server_url() {
	var url = OC.generateUrl('/apps/user_rcdevsopenotp/check_server_url');
	var server_url_val = $( "#openotp_settings #rcdevsopenotp_server_url" ).val();
	
	$("#check_server_loading").fadeIn();
    $.post( url, { server_url: server_url_val }, function(response){
		/*if($('#message_check_server_url').is(":visible")){
			$('#message_check_server_url').fadeOut("fast"); 
		}*/
        if( response.status == "success" ){
			$("#check_server_loading").fadeOut();
			console.log(response.openotpStatus);
			if( response.openotpStatus === false){ 
				$('#message_status').removeClass('success').addClass('error').fadeIn('fast');
				$('#message_check_server_url').fadeOut('fast');
			}else{
        		$('#message_status').removeClass('error').addClass('success').fadeIn('fast');
        		$('#message_check_server_url').removeClass('error').html(response.message).fadeIn('fast');
			}
        }else{
			$("#check_server_loading").fadeOut();
        	$('#message_status').removeClass('success').addClass('error').fadeIn('fast');
        	$('#message_check_server_url').fadeOut('fast');
        }
    });			
}