/**
 * ownCloud - user_rcdevsopenotp
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Julien RICHARD <julien.richard@rcdevs.com>
 * @copyright RCDevs - Julien RICHARD 2015
 */

$(document).ready(function(){

	// Form reference:
	var theForm = $('form[name="login"]');
	// Add data:
	addHidden(theForm, 'context', '');

});

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

