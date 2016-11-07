/**
 * ownCloud - user_rcdevsopenotp
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Julien RICHARD <julien.richard@rcdevs.com>
 * @copyright RCDevs - Julien RICHARD 2016
 */
/* global OC, t */

/*
 * arrive.js
 * v2.2.0
 * https://github.com/uzairfarooq/arrive
 * MIT licensed
 *
 * Copyright (c) 2014-2015 Uzair Farooq
 */

(function(n,q,v){function r(a,b,c){if(e.matchesSelector(a,b.selector)&&(a._id===v&&(a._id=w++),-1==b.firedElems.indexOf(a._id))){if(b.options.onceOnly)if(0===b.firedElems.length)b.me.unbindEventWithSelectorAndCallback.call(b.target,b.selector,b.callback);else return;b.firedElems.push(a._id);c.push({callback:b.callback,elem:a})}}function p(a,b,c){for(var d=0,f;f=a[d];d++)r(f,b,c),0<f.childNodes.length&&p(f.childNodes,b,c)}function t(a){for(var b=0,c;c=a[b];b++)c.callback.call(c.elem)}function x(a,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   b){a.forEach(function(a){var d=a.addedNodes,f=a.target,e=[];null!==d&&0<d.length?p(d,b,e):"attributes"===a.type&&r(f,b,e);t(e)})}function y(a,b){a.forEach(function(a){a=a.removedNodes;var d=[];null!==a&&0<a.length&&p(a,b,d);t(d)})}function z(a){var b={attributes:!1,childList:!0,subtree:!0};a.fireOnAttributesModification&&(b.attributes=!0);return b}function A(a){return{childList:!0,subtree:!0}}function k(a){a.arrive=l.bindEvent;e.addMethod(a,"unbindArrive",l.unbindEvent);e.addMethod(a,"unbindArrive",
	l.unbindEventWithSelectorOrCallback);e.addMethod(a,"unbindArrive",l.unbindEventWithSelectorAndCallback);a.leave=m.bindEvent;e.addMethod(a,"unbindLeave",m.unbindEvent);e.addMethod(a,"unbindLeave",m.unbindEventWithSelectorOrCallback);e.addMethod(a,"unbindLeave",m.unbindEventWithSelectorAndCallback)}if(n.MutationObserver&&"undefined"!==typeof HTMLElement){var w=0,e=function(){var a=HTMLElement.prototype.matches||HTMLElement.prototype.webkitMatchesSelector||HTMLElement.prototype.mozMatchesSelector||HTMLElement.prototype.msMatchesSelector;
		return{matchesSelector:function(b,c){return b instanceof HTMLElement&&a.call(b,c)},addMethod:function(a,c,d){var f=a[c];a[c]=function(){if(d.length==arguments.length)return d.apply(this,arguments);if("function"==typeof f)return f.apply(this,arguments)}}}}(),B=function(){var a=function(){this._eventsBucket=[];this._beforeRemoving=this._beforeAdding=null};a.prototype.addEvent=function(a,c,d,f){a={target:a,selector:c,options:d,callback:f,firedElems:[]};this._beforeAdding&&this._beforeAdding(a);this._eventsBucket.push(a);
		return a};a.prototype.removeEvent=function(a){for(var c=this._eventsBucket.length-1,d;d=this._eventsBucket[c];c--)a(d)&&(this._beforeRemoving&&this._beforeRemoving(d),this._eventsBucket.splice(c,1))};a.prototype.beforeAdding=function(a){this._beforeAdding=a};a.prototype.beforeRemoving=function(a){this._beforeRemoving=a};return a}(),u=function(a,b,c){function d(a){"number"!==typeof a.length&&(a=[a]);return a}var f=new B,e=this;f.beforeAdding(function(b){var d=b.target,h;if(d===n.document||d===n)d=
		document.getElementsByTagName("html")[0];h=new MutationObserver(function(a){c.call(this,a,b)});var g=a(b.options);h.observe(d,g);b.observer=h;b.me=e});f.beforeRemoving(function(a){a.observer.disconnect()});this.bindEvent=function(a,c,h){if("undefined"===typeof h)h=c,c=b;else{var g={},e;for(e in b)g[e]=b[e];for(e in c)g[e]=c[e];c=g}e=d(this);for(g=0;g<e.length;g++)f.addEvent(e[g],a,c,h)};this.unbindEvent=function(){var a=d(this);f.removeEvent(function(b){for(var c=0;c<a.length;c++)if(b.target===a[c])return!0;
		return!1})};this.unbindEventWithSelectorOrCallback=function(a){var b=d(this);f.removeEvent("function"===typeof a?function(c){for(var d=0;d<b.length;d++)if(c.target===b[d]&&c.callback===a)return!0;return!1}:function(c){for(var d=0;d<b.length;d++)if(c.target===b[d]&&c.selector===a)return!0;return!1})};this.unbindEventWithSelectorAndCallback=function(a,b){var c=d(this);f.removeEvent(function(d){for(var e=0;e<c.length;e++)if(d.target===c[e]&&d.selector===a&&d.callback===b)return!0;return!1})};return this},
	l=new u(z,{fireOnAttributesModification:!1,onceOnly:!1},x),m=new u(A,{},y);q&&k(q.fn);k(HTMLElement.prototype);k(NodeList.prototype);k(HTMLCollection.prototype);k(HTMLDocument.prototype);k(Window.prototype)}})(this,"undefined"===typeof jQuery?null:jQuery,void 0);


(function ($, OC) {
	
	$(document).ready(function(){

		// Form reference:
		var theForm = $('form[name="login"]');
		// Add data:
		addHidden(theForm, 'context', '');

	
		var wizardhead = '<form id="openotp_passwordform" class="section">';
		wizardhead += '<div id="openotp_passwordform_wizard"><h1>Get your remote Password</h1>';
		wizardhead += '<h3>Your account has been auto-created with a random password. <br> <u>Keep it safe</u> and use it in your Desktop and Mobile Application or generate a new one.</h3>';			
		wizardhead += "<p style='padding-bottom:10px'>For security reason, this code will be visible only during your first connection. <br>After logging out, you will be able to generate another one in your 'Personal' Section.</p>";			
		
		var settingshead = '<form id="openotp_passwordform"><div>';
		settingshead += '<h4>Remote Password</h4>';
		settingshead += '<p>Your account has been auto-created with a random password. Use it in your Desktop and Mobile Application or generate a new one</p>';			
						
		var form = '<input id="openotp_generatepassword" type="submit" value="Generate Password" />';
		form += '<div id="openotp_show_generatedpassword">%replacepassword%</div>';
		form += '<input type="hidden" id="openotp_generatedpassword" name="openotp_generatedpassword" value=""/>';
		form += '<input id="openotp_savepasswordbutton" type="submit" value="Save" />';
		form += '<div id="openotp_passwordchanged">Your password was changed</div>';
		form += '<div id="openotp_passworderror">Unable to change your password</div>';				
		form += '</div></form>';
		
		var formpwd = form.replace( "%replacepassword%", "Click on Generate" );
		$("#openotp_personnal_settings").append(settingshead + formpwd);
		
		/*
		*	Add Form Password in Wizard
		*/
		if( !($("#openotp_passwordform").length) ){
			$(document).arrive("#firstrunwizard", function() {
			    // 'this' refers to the newly created element
			    /*var $newElem = $(this);
				var form = '<form id="openotp_passwordform" class="section">';
				form += '<h1>Set your remote Password</h1>';
				form += '<h3 style="width:60%">Your account has been auto-create with a random password, you have to choose a new one, and use it to connect with Desktop and Mobile Application</h3>';
				form += '<div id="openotp_passwordchanged">Your password was changed</div>';
				form += '<div id="openotp_passworderror">Unable to change your password</div>';
				form += '<input type="password" id="openotp_pass2" name="openotp_personal-password" placeholder="New password" data-typetoggle="#personal-show"	autocomplete="off" autocapitalize="off" autocorrect="off" />';
				form += '<input id="openotp_passwordbutton" type="submit" value="Change password" /><br/>';
				form += '<div class="strengthify-wrapper"></div>';
				form += '</form>';*/
				$.post(OC.generateUrl('/apps/user_rcdevsopenotp/get_generated_password'), {sent: 1}, function (data) {
			
					if (data.status === "success") {
						var generatedpassword = data.rcdevsopenotp_randompassword;
					} else {
						var generatedpassword = "Click on Generate";
					}
			
					var formpwd = form.replace( "%replacepassword%", generatedpassword );
		
					$("#firstrunwizard").append(wizardhead + formpwd);
				});
			});
		}
		
		$(document).arrive("#openotp_passwordform", function() {
			if( ($("#firstrunwizard #openotp_passwordform").length) ){
				/*
				*	Generate Password
				*/	
				$("#openotp_generatepassword").on( "click", function() {
				
					$.post(OC.generateUrl('/apps/user_rcdevsopenotp/get_new_generated_password'), {action: "get"}, function (data) {
						if (data.status === "success") {
							var newgeneratedpassword = data.rcdevsopenotp_newrandompassword;
							$("#openotp_generatedpassword").val(newgeneratedpassword);
							$("#openotp_show_generatedpassword").html(newgeneratedpassword);
						} else {
							var newgeneratedpassword = "Error, come back later";
						}
					});					
					return false;
				});	
				/*
				*	Save Password
				*/				
				$("#openotp_savepasswordbutton").on( "click", function() {

					$('#openotp_passwordchanged').hide();
					$('#openotp_passworderror').hide();
					var newpassword = $("#openotp_generatedpassword").val();

					if ( newpassword !== '' ) {
				
						$.post(OC.generateUrl('/apps/user_rcdevsopenotp/get_new_generated_password'), {action: "store", password: newpassword}, function (data) {
							if (data.status === "success") {
								$('#openotp_passwordchanged').show();
								$('#openotp_show_generatedpassword').html(t('Click on Generate'));
							} else {
								$('#openotp_passworderror').html(t('Unable to change password')).show();
							}
						});	
					
					}else{
						$('#openotp_passwordchanged').hide();
						$('#openotp_passworderror').show();
						return false;
					}
					return false;
				});				
			}
		});	
		
		/***************  TODO  ***************
		* DOUBLON, on click binding does not work on both wizard and personal settings, find a solution needed!
		/*
		*	Generate Password
		*/	
		$("#openotp_generatepassword").on( "click", function() {
		
			$.post(OC.generateUrl('/apps/user_rcdevsopenotp/get_new_generated_password'), {action: "get"}, function (data) {
				if (data.status === "success") {
					var newgeneratedpassword = data.rcdevsopenotp_newrandompassword;
					$("#openotp_generatedpassword").val(newgeneratedpassword);
					$("#openotp_show_generatedpassword").html(newgeneratedpassword);
				} else {
					var newgeneratedpassword = "Error, come back later";
				}
			});					
			return false;
		});	
		/*
		*	Save Password
		*/				
		$("#openotp_savepasswordbutton").on( "click", function() {

			$('#openotp_passwordchanged').hide();
			$('#openotp_passworderror').hide();
			var newpassword = $("#openotp_generatedpassword").val();

			if ( newpassword !== '' ) {
		
				$.post(OC.generateUrl('/apps/user_rcdevsopenotp/get_new_generated_password'), {action: "store", password: newpassword}, function (data) {
					if (data.status === "success") {
						$('#openotp_passwordchanged').show();
						$('#openotp_show_generatedpassword').html(t('Click on Generate'));
					} else {
						$('#openotp_passworderror').html(t('Unable to change password')).show();
					}
				});	
			
			}else{
				$('#openotp_passwordchanged').hide();
				$('#openotp_passworderror').show();
				return false;
			}
			return false;
		});			
		
		
		
		
		
		
				
			
		/*$(document).arrive("#openotp_passwordform", function() {
		
			$("#openotp_passwordbutton").on( "click", function() {
				if ( $('#openotp_pass2').val() !== '' ) {
					// Serialize the data
					var post = $("#openotp_passwordform").serialize();
					$('#openotp_passwordchanged').hide();
					$('#openotp_passworderror').hide();
					// Ajax foo
					$.post(OC.generateUrl('/apps/user_rcdevsopenotp/change_personal_password'), post, function (data) {
						if (data.status === "success") {
							$('#openotp_pass2').val('');
							$('#openotp_passwordchanged').show();
						} else {
							if (typeof(data.data) !== "undefined") {
								$('#openotp_passworderror').html(data.data.message);
							} else {
								$('#openotp_passworderror').html(t('Unable to change password'));
							}
							$('#openotp_passworderror').show();
						}
					});
					return false;
				} else {
					$('#openotp_passwordchanged').hide();
					$('#openotp_passworderror').show();
					return false;
				}
				return false;
			});	
		});	*/

	});
})(jQuery, OC);

function addHidden(theForm, key, value) {
	new devicecontext().get(function(_context){
		value = _context;
	});
    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = key;
    input.value = value;
	theForm.find('fieldset input[type="hidden"]:last').after(input);
}

