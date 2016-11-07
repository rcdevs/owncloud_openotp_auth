<?php
/**
 * ownCloud - RCDevs OpenOTP Two-factor Authentication
 *
 * @package user_rcdevsopenotp
 * @author Julien RICHARD
 * @copyright 2016 RCDEVS info@rcdevs.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * Displays <a href="http://opensource.org/licenses/AGPL-3.0">GNU AFFERO GENERAL PUBLIC LICENSE</a>
 * @license http://opensource.org/licenses/AGPL-3.0 GNU AFFERO GENERAL PUBLIC LICENSE
 *
 */

//namespace OCA\RcdevsOpenotp\AppInfo;
OC::$CLASSPATH['OC_USER_OPENOTP'] = 'user_rcdevsopenotp/lib/openotp.php';
OC::$CLASSPATH['OPENOTP_CONFIG'] = 'user_rcdevsopenotp/lib/openotp.config.php';
OC::$CLASSPATH['openotpAuth'] = 'user_rcdevsopenotp/lib/openotp.class.php';

define('AUTHENTICATION_METHOD_STD',"0");
define('AUTHENTICATION_METHOD_STD_OTP',"1");
define('AUTHENTICATION_METHOD_OTP',"2");

OC::$CLASSPATH['OC_USER_OPENOTP_Hooks'] = 'user_rcdevsopenotp/lib/hooks.php';
OCP\Util::connectHook('OC_User', 'post_login', 'OC_USER_OPENOTP_Hooks', 'openotp_post_login');

OCP\App::registerAdmin( 'user_rcdevsopenotp','adminsettings' );
OCP\App::registerPersonal( 'user_rcdevsopenotp', 'personnalsettings' );
OCP\Util::addScript('user_rcdevsopenotp', 'context');
OCP\Util::addScript('user_rcdevsopenotp', 'loginform');
OCP\Util::addStyle('user_rcdevsopenotp', 'settings');

//OC_User::registerBackend("OPENOTP");
$usedBackends = OC_User::getUsedBackends();
OC_User::clearBackends();
OC_USER_OPENOTP::registerBackends($usedBackends);
OC_User::useBackend('OPENOTP');


$isadmin = OC_User::isAdminUser(OC_User::getUser());
if($isadmin){
	\OCP\App::addNavigationEntry([
		// the string under which your app will be referenced in owncloud
		'id' => 'user_rcdevsopenotp',

		// sorting weight for the navigation. The higher the number, the higher
		// will it be listed in the navigation
		'order' => 100,

		// the route that will be shown on startup
		'href' => \OCP\Util::linkToRoute('user_rcdevsopenotp.page.index'),
		//'href' => \OCP\Util::linkToRoute('rcdevsopenotp_adminsettings'),
		//'href' => OC_Helper::linkTo( "rcdevsopenotp", "adminsettings.php" ),


		// the icon that will be shown in the navigation
		// this file needs to exist in img/
		'icon' => \OCP\Util::imagePath('user_rcdevsopenotp', 'app.svg'),

		// the title of your application. This will be used in the
		// navigation or on the settings page of your app
		'name' => \OC_L10N::get('user_rcdevsopenotp')->t('Rcdevs Openotp')
	]);
}
if(OCP\App::isEnabled('user_webdavauth') || OCP\App::isEnabled('user_ldap')) {
	OCP\Util::writeLog('rcdevsopenotp',
		'user_ldap and user_webdavauth are incompatible with OpenOTP Two-factors authentication. OpenOTP server already works with user stored in a Directory backend',
		OCP\Util::WARN);
}	