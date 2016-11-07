<?php
/**
 * ownCloud - RCDevs OpenOTP Two-factor Authentication
 *
 * @package rcdevsopenotp
 * @author Julien RICHARD
 * @copyright 2016 RCDEVS info@rcdevs.com
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU AFFERO GENERAL PUBLIC
 * License along with this library. If not, see <http://www.gnu.org/licenses/>.
 * Displays <a href="http://opensource.org/licenses/AGPL-3.0">GNU AFFERO GENERAL PUBLIC LICENSE</a>
 * @license http://opensource.org/licenses/AGPL-3.0 GNU AFFERO GENERAL PUBLIC LICENSE
 *
 */


OC_Util::checkAdminUser();
$_openotp_configs = OPENOTP_CONFIG::$_openotp_configs;
$_openotp_admintmpl = new OCP\Template('user_rcdevsopenotp', 'adminsettings');
$_openotp_admintmpl->assign('openotp_allconfig', $_openotp_configs);

// Deprecated: before ajax call
if($_POST) {
	// CSRF check
	OCP\JSON::callCheck();
}

foreach( $_openotp_configs as $_openotp_confname => $_openotp_config ){
    if ($_POST && isset($_POST[$_openotp_config['name']]) ) {        
        OCP\Config::setAppValue('user_rcdevsopenotp',$_openotp_config['name'],$_POST[$_openotp_config['name']]);
    }
    $_openotp_admintmpl->assign(
        $_openotp_config['name'],
        OCP\Config::getAppValue(
            'user_rcdevsopenotp',$_openotp_config['name'],$_openotp_config['default_value']
        )
    );	
}

return $_openotp_admintmpl->fetchPage();