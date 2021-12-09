<?php
/**
 * Owncloud - RCDevs OpenOTP Two-factor Authentication
 *
 * @package openotp_auth
 * @author Julien RICHARD
 * @copyright 2018 RCDEVS info@rcdevs.com
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

use OCA\TwoFactor_RCDevsOpenOTP\AppInfo\Application;

$app = new Application();
// Register the configuration settings templates
$app->registerSettings();
\OC::$CLASSPATH['OCA\\TwoFactor_RCDevsOpenOTP\\Settings\\OpenotpConfig'] = 'openotp_auth/settings/openotp.config.php';
\OC::$CLASSPATH['OCA\\TwoFactor_RCDevsOpenOTP\\AuthService\\OpenotpAuth'] = 'openotp_auth/lib/Provider/openotp.class.php';

if(class_exists('\\OCP\\AppFramework\\Http\\EmptyContentSecurityPolicy')) { 
	$manager = \OC::$server->getContentSecurityPolicyManager();
	$policy = new \OCP\AppFramework\Http\EmptyContentSecurityPolicy();
    $policy->addAllowedScriptDomain('\'unsafe-inline\'');
	$manager->addDefaultPolicy($policy);
}

\OCP\Util::addStyle('openotp_auth', 'settings');
\OCP\Util::addScript('openotp_auth', 'script');
\OCP\Util::addScript('openotp_auth', 'fidou2f');

//TODO: OC_User - Static method of private class must not be called
$isadmin = \OC_User::isAdminUser(\OC_User::getUser());
if($isadmin){
	\OC::$server->getNavigationManager()->add(function () {
	    $urlGenerator = \OC::$server->getURLGenerator();
	    return [
	        'id' => 'openotp_auth',
	        'order' => 100,
	        'href' => $urlGenerator->linkToRoute('openotp_auth.settings.index'),
	        'icon' => $urlGenerator->imagePath('openotp_auth', 'app.svg'),
			'name' => "OpenOTP"
	    ];
	});
}

