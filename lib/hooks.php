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


/**
 * This class contains all hooks.
 */

class OC_USER_OPENOTP_Hooks {
          
	static public function openotp_post_login($parameters) {

		$uid = strtolower($parameters['uid']);
		$userDB = new OC_User_Database();
		$session = \OC::$server->getSession();
		$autocreate_user = OCP\Config::getAppValue('user_rcdevsopenotp','rcdevsopenotp_autocreate_user');
        
       if( !$userDB->userExists($uid) ) {
			if( $autocreate_user === "on" ){
                if (preg_match( '/[^a-zA-Z0-9 _\.@\-]/', $uid)) {
                        OC_Log::write('user_rcdevsopenotp','Invalid username "'.$uid.'", allowed caracters: "a-zA-Z0-9" and "_.@-" ',OC_Log::DEBUG);
                        return false;                                                
                }
                else {
                    $random_password = \OC_Util::generateRandomBytes(16);  
                    $userDB->createUser($uid, $random_password);
                    $userDB->setDisplayName($uid, $uid);

					$session->set('rcdevsopenotp_randompassword_'.$uid, $random_password);
                    
					OC_Log::write('user_rcdevsopenotp','New user has been created with username '.$uid, OC_Log::INFO);
					return true;
                }
			}else{
				OC_Log::write('user_rcdevsopenotp','Cannot create user with username '.$uid.' - Autocreate setting is disabled in admin panel', OC_Log::INFO);
			}
        }else{
        	OC_Log::write('user_rcdevsopenotp','User already exists with username '.$uid, OC_Log::INFO);
        }

		return false;
	}
}