<?php
/**
 * ownCloud - RCDevs OpenOTP Two-factor Authentication
 *
 * @package user_rcdevsopenotp
 * @author Julien RICHARD
 * @copyright 2015 RCDEVS info@rcdevs.com
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

namespace OCA\User_RcdevsOpenotp\Controller;

use OCP\IRequest;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Controller;

class PageController extends Controller {


	private $userId;

	public function __construct($AppName, IRequest $request, $UserId){
		parent::__construct($AppName, $request);
		$this->config = \OPENOTP_CONFIG::$_openotp_configs;
		$this->userId = $UserId;
	}

	/**
	 * CAUTION: the @Stuff turns off security checks; for this page no admin is
	 *          required and no CSRF check. If you don't know what CSRF is, read
	 *          it up in the docs or you might create a security hole. This is
	 *          basically the only required method to add this exemption, don't
	 *          add it to any other method if you don't exactly know what it does
	 *
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	
	public function index() {
		
		foreach( $this->config as $_openotp_confname => $_openotp_config ){
		    $params[$_openotp_config['name']] = \OCP\Config::getAppValue( 'user_rcdevsopenotp',$_openotp_config['name'],$_openotp_config['default_value'] );
		    //$params[$_openotp_config['name']] = \OC::$server->getConfig()->getAppValue( 'rcdevsopenotp',$_openotp_config['name'],$_openotp_config['default_value'] );
		}
		
		$params['user'] = $this->userId;
		$params['openotp_allconfig'] = $this->config;
		//return new TemplateResponse('rcdevsopenotp', 'main', $params);  // templates/main.php
		return new TemplateResponse('user_rcdevsopenotp', 'adminsettings', $params);  // templates/main.php
	}
	
	/**
	 * @NoAdminRequired
	 */
	public function saveconfig( $post ){
		parse_str($post, $POST);		
	    // Admin Settings && Application Settings page
		if( $POST && isset($POST["openotp_settings_sent"]) ){
			if( $POST['rcdevsopenotp_server_url'] === "" &&  $POST['rcdevsopenotp_client_id'] === ""
			&&	$POST['rcdevsopenotp_default_domain']  === "" && $POST['rcdevsopenotp_proxy_host']  === "" 
			&&	$POST['rcdevsopenotp_proxy_port']  === "" && $POST['rcdevsopenotp_proxy_login']  === ""
			&&	$POST['rcdevsopenotp_proxy_password']  === "" )
				return new DataResponse(['status' => "error", 'message' => "You must fill openotp settings before saving" ]);
			
			foreach( $this->config as $_openotp_confname => $_openotp_config ){
				if($_openotp_config['type'] === "checkbox" && !isset( $POST[$_openotp_config['name']] ) )
					\OCP\Config::setAppValue('user_rcdevsopenotp', $_openotp_config['name'], "off");
				else{
					if( isset($POST[$_openotp_config['name']]) && $POST[$_openotp_config['name']] == "" && isset($_openotp_config['default_value']) )
						\OCP\Config::setAppValue( 'user_rcdevsopenotp', $_openotp_config['name'], $_openotp_config['default_value'] );
					else
						\OCP\Config::setAppValue( 'user_rcdevsopenotp', $_openotp_config['name'], $POST[$_openotp_config['name']] );
				}
			}
			return new DataResponse(['status' => "success", 'message' => "Your settings have been saved succesfully" ]);
	    }
		// Personnal Settings
	    if( !$POST ) return new DataResponse(['status' => "error", 'message' => "An error occured, please contact administrator" ]);
		
		if( $POST && isset($POST["openotp_psettings_sent"]) ){	
			if( isset($POST["enable_openotp"]) ) \OCP\Config::setUserValue( \OCP\USER::getUser(), 'user_rcdevsopenotp', 'enable_openotp', $POST["enable_openotp"] );
			
			return new DataResponse(['status' => "success", 'message' => "Your settings have been saved succesfully" ]);
		}else
			return new DataResponse(['status' => "error", 'message' => "An error occured, please contact administrator" ]);
	}
}