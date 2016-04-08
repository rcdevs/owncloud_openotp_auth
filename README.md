# Rcdevs Openotp

RCDevs OpenOTP Plugin for OwnCould version 1.0.1
Copyright (c) 2010-2013 RCDevs SA, All rights reserved.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.


**********   DESCRIPTION   **********

It displays an overlay on Challenge-Response session, after fill in 
username and password. The plugin supports global, and per user settings 
configuration. 
This authentication method are available:
- Standard authentication (Disable OpenOTP)
- Two-Factor OR Standard authentication (Enable OpenOTP or Owncloud Password)
- Two-Factor authentication (Enable OpenOTP for all user)

It's possible to force all user to authenticate with OTP, or allow users to 
manage their athentication method if "Allow users to administer Two-factor on 
their profile settings page" is selected.

RCDevs OpenOTP Application for Owncloud will transparently handle any OpenOTP Login 
Mode including:
- LDAP 
- OTP Only
- LDAP/OTP
- U2F Fido authentication method, see https://fidoalliance.org/ for more details. 
- LDAP/U2F
- LDAP and (OTP OR U2F)


OPENOTP SERVER

OpenOTP is the RCDevs user authentication solution. OpenOTP is a server
application which provides multiple (highly configurable) authentication
schemes for your LDAP users, based on one-time passwords (OTP) technologies
 and including: - OATH HOTP/TOTP/OCRA Software/Hardware Tokens - Google 
Authenticator - Mobile-OTP (mOTP) Software Tokens - SMS One-Time Passwords
- Mail / Secure Mail One-Time Passwords - Yubikey
Visit https://www.rcdevs.com/products/openotp/
Follow the quick start guide:
https://www.rcdevs.com/downloads/documents.php?id=OpenOTP+Authentication+Server%0A
or download our appliances:
https://www.rcdevs.com/downloads/index.php?id=VMWare+Appliances

**********   INSTALLATION   **********

Require Owncloud >= 8 
Version 1.0

1)	If your PHP installation does not have the soap extension, install the php-soap 
	package for your Linux distribution. With RedHat, do it with 'yum install php-soap'.
2)  Upload user_rcdevsopenotp directory under the 'apps' directory of your ownCloud.
3)	RCDevsOpenOTP Application folder should have read write permission for the web server 
	user (under debian/ubutnu : chown -R www-data:www-data user_rcdevsopenotp)
4)	Navigate to the 'Apps' page in Admin.
	Click on 'user_rcdevsopenotp' in the application list. Then click the 'Enable' button.
5)	Add Custom Content Security Policy in your config/config.php file 
	'custom_csp_policy' => 'script-src * \'self\' \'unsafe-eval\' \'unsafe-inline\'; '


**********   USAGE  **********

-	Navigate to the 'Admin' page, or go to the 'RCDevs OpenOTP' Application Menu to set at 
	least the server url and the Client Id, Click 'Save'
-	Configure if you want the authentication requests to be sent to OpenOTP on remote access,
	the loginMode will be forced to LDAP because the Desktop/Mobile application sends authentication
	for every requests so Two-factor is not possible right now.
	!! IMPORTANT !! We recommand to check "Force Remote Password on Desktop/Mobile Apps authentication"
	and use the Remote Password (=local Owncloud Password) 
-	Allow users to administer Two-factor on their profile settings page or not
-	At first Login, owncloud displays a popup with a Random Generated Password to use for remote connection.
	If users don't keep safely the code on first login, they will be able to do it in "Personal" area on nexts logins.
-	During configuration of your plugin:
		-> Select "Two-Factor OR Standard authentication (Enable OpenOTP or Owncloud 
		Password)", even if you are not able to connect, Owncloud password remains active.
		-> Disable "Allow users to administer Two-factor on their profile settings page"
-	After successfully authenticate with OTP, enable OpenOTP for all user
	If "Allow users to administer Two-factor on their profile settings page" is checked, users are able
	to deactivate Two-Factor
-	!! IMPORTANT !! keep an admin user working without otp in case of a problem. If not you can:
		->  Switch authentication method to Standard (Owncloud password):
			"UPDATE *PREFIX*appconfig SET configvalue = 0 WHERE appid = 'user_rcdevsopenotp' AND configkey = 'rcdevsopenotp_authentication_method'
		->  Disable openOTP authentication for one (admin?) user:
			"DELETE FROM *PREFIX*appconfig WHERE userid = 'username' AND appid = 'user_rcdevsopenotp' AND configkey = 'enable_openotp'
			Replace *PREFIX* by owncloud table prefix 'oc_' by default



**********   CHANGELOG  **********
1.0.1
	Enhanced remote Password
	Add FidoU2F.js library to avoid to installation of the U2F plugin in Google Chrome 
1.0.0
     Initial public release.
 