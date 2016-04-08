<?php
/**
 * ownCloud - RCDevs OpenOTP Two-factor Authentication
 *
 * @package rcdevsopenotp
 * @author Julien RICHARD
 * @copyright 2015 RCDEVS info@rcdevs.com
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


// Set the content type to Javascript, Disallow caching
header("Content-type: text/javascript");
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

$appWebPath = OC_App::getAppWebPath('user_rcdevsopenotp');
// get session challenge params
$session = \OC::$server->getSession();
$rcdevsopenotp_challenge_params = $session->get('rcdevsopenotp_challenge_params');
 
$otpChallenge = $rcdevsopenotp_challenge_params['rcdevsopenotp_otpChallenge'];
$u2fChallenge = $rcdevsopenotp_challenge_params['rcdevsopenotp_u2fChallenge'];
$message = $rcdevsopenotp_challenge_params['rcdevsopenotp_message'];
$username = $rcdevsopenotp_challenge_params['rcdevsopenotp_username'] ;
$session = $rcdevsopenotp_challenge_params['rcdevsopenotp_session'];
$timeout = $rcdevsopenotp_challenge_params['rcdevsopenotp_timeout'];
$ldappw = $rcdevsopenotp_challenge_params['rcdevsopenotp_password'];
$path = $rcdevsopenotp_challenge_params['rcdevsopenotp_appPath'];
$domain = $rcdevsopenotp_challenge_params['rcdevsopenotp_domain'];

echo openotpAuth::getOverlay($otpChallenge, $u2fChallenge, $message, $username, $session, $timeout, $ldappw, $path, $appWebPath, $domain);
