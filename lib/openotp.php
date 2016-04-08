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

/**
 * Class to handle OpenOTP authentication
 * @package rcdevsopenotp
 */
class OC_USER_OPENOTP extends OC_User_Backend{
	/**
 	 * @var \OC_User_Backend[] $backends
	 */
	private static $_backends = null;
    private $_userBackend = null;	
	protected static $instance = null;

	protected $session;
	
	public static function getInstance() {
		if (self::$instance == null) {
			self::$instance = new OC_USER_OPENOTP();
		}
		return self::$instance;
	}
	
	/**
	 * Constructor
	 *
	 * @param IConfig $config
	 * @param ISession $session
	 */
	public function __construct() {
		$this->session = \OC::$server->getSession();
		
	}
	
	public function getSupportedActions() {
		$actions = 0;
		foreach($this->possibleActions AS $action => $methodName) {
			$userBackend=$this->getRealBackend(OCP\User::getUser());
			if($userBackend===null){$userBackend=$this;}
			if(method_exists($this, $methodName)) {
				$actions |= $action;
			}
			
		}
		return $actions;
	}	
	
    public static function registerBackends($usedBackends){
      if(self::$_backends === null){
        foreach ($usedBackends as $backend){
	        if( class_exists($backend) ) self::$_backends[$backend] = new $backend();
		  	else{
				$className = "OC_USER_".$backend; 
				if( class_exists($className) ) self::$_backends[$backend] = new $className();
			}
        }
      }
    }
	
	/**
	 * get user real backend
	 * @param string $uid the username
	 * @return backend
	 */
	public function getRealBackend($uid) {
		if($this->_userBackend !== null){
			return $this->_userBackend;
		}
		foreach (self::$_backends as $backend) {
			if ($backend->userExists($uid)) {
				$this->_userBackend = $backend;
				return $this->_userBackend;
			}
		} 
		return null;
	}
		
    /**
     * check password function
     * @param string $uid user id
     * @param string $password value of the password
     * @return boolean
     * @UseSession
     */
    public function checkPassword($uid, $password) {
		//OC_Log::write('rcdevsopenotp', 'function CheckPassword() ' . $uid . " -> " .$password, \OC_Log::DEBUG);
		
		$userBackend=$this->getRealBackend($uid);
		if ($userBackend===null){
			return false;
		}
		//Get Application Config
		$allow_user_administer_openotp = OCP\Config::getAppValue('user_rcdevsopenotp','rcdevsopenotp_allow_user_administer_openotp');
		$disable_openotp_on_remote = OCP\Config::getAppValue('user_rcdevsopenotp','rcdevsopenotp_disable_openotp_on_remote'); 
		$authentication_method = OCP\Config::getAppValue('user_rcdevsopenotp','rcdevsopenotp_authentication_method'); 
		// 0 => AUTHENTICATION_METHOD_STD (Standard)
		// 1 => AUTHENTICATION_METHOD_STD_OTP (OTP or Standard)
		// 2 => AUTHENTICATION_METHOD_OTP (OTP)
		
		// Get User Config
		$user_enable_openotp = OCP\Config::getUserValue( $uid, 'user_rcdevsopenotp', 'enable_openotp');
		
		// if access is made by remote.php and option is note set to force mtop, keep standard auth methode
		// this for keep working webdav access and sync apps
	    // And news api for android new app
	    // And ocsms app, pictures thumbnails, file sharing	
		$pathinfo = OC_Request::getPathInfo();
		if(
			
    		( 	( basename($_SERVER['SCRIPT_NAME']) === 'remote.php' || 
    		  	preg_match("#^/apps/news/api/v1-2(.*)$#i", $pathinfo) ||
	          	preg_match("#^/apps/ocsms(.*)$#i", $pathinfo) ||
	          	preg_match("#^/apps/files/api/v1/thumbnail(.*)$#i", $pathinfo) ||
	          	preg_match("#^/apps/files_sharing/api/v1/shares(.*)$#i", $pathinfo )
  	  			)
				&& $disable_openotp_on_remote === "on"
			) 
			|| ( $allow_user_administer_openotp === "on" && $user_enable_openotp === "no" && $authentication_method !== AUTHENTICATION_METHOD_STD )
			|| ( $allow_user_administer_openotp !== "on" && $authentication_method === AUTHENTICATION_METHOD_STD )
		){
			return $userBackend->checkPassword($uid, $password);
		}else{
			OC_Log::write('rcdevsopenotp', '********* New Instance OpenOTP *********', \OC_Log::INFO);
			// get App Configs
			$_openotp_configs = OPENOTP_CONFIG::$_openotp_configs;
			
			foreach( $_openotp_configs as $_openotp_confname => $_openotp_config ){				
		        $params[$_openotp_config['name']] = OCP\Config::getAppValue(
		            'user_rcdevsopenotp', $_openotp_config['name'], $_openotp_config['default_value']
		        );
			}
			$params['rcdevsopenotp_remote_addr'] = OC_Request::getRemoteAddress();
			$appPath = OC_App::getAppPath('user_rcdevsopenotp');
			$openotpAuth = new openotpAuth($params, $appPath);
			
			// check OpenOTP WSDL file
			if (!$openotpAuth->checkFile('lib/openotp.wsdl','Could not load OpenOTP WSDL file')){
				OC_Log::write('rcdevsopenotp', "Could not load OpenOTP WSDL file." , OC_Log::ERROR);
		 		//form_set_error('openotp', t('An error occured while processing your request, please report error to administrators'));
				return false;
			}
	
			// Check SOAP extension is loaded
			if (!$openotpAuth->checkSOAPext()){
				OC_Log::write('rcdevsopenotp', "Your PHP installation is missing the SOAP extension." , OC_Log::ERROR);
		 		//form_set_error('openotp', t('An error occured while processing your request, please report error to administrators'));
				return false;
			}
			
			$username = $uid;
			$password = (isset($_POST['openotp_password']) && $_POST['openotp_password'] != NULL) ? $_POST['openotp_password'] : $password;
			$u2f = isset($_POST['openotp_u2f']) ? $_POST['openotp_u2f'] : "";
			$context = isset($_POST['context']) ? $_POST['context'] : "";
			if( $u2f != "" ) $password = NULL;
			$state = isset($_POST['openotp_state']) ? $_POST['openotp_state'] : "";
	
			$t_domain = $openotpAuth->getDomain($username);
			if (is_array($t_domain)){
				$username = $t_domain['username'];
				$domain = $t_domain['domain'];
			}elseif( isset($_POST['openotp_domain']) && $_POST['openotp_domain'] != NULL) $domain = $_POST['openotp_domain'];
			else $domain = $t_domain;
			if( $domain != "" ) OC_Log::write('rcdevsopenotp', 'Domain found in username field '.$domain, \OC_Log::INFO);
			
			if ($state != NULL) {
				// OpenOTP Challenge
				OC_Log::write('rcdevsopenotp', 'New OpenOTP Challenge for user ' . $username, \OC_Log::INFO);
				$resp = $openotpAuth->openOTPChallenge( $username, $domain, $state, $password, $u2f );
			} else {
				// OpenOTP Login
				OC_Log::write('rcdevsopenotp', 'New OpenOTP SimpleLogin for user ' . $username, \OC_Log::INFO);
				$resp = $openotpAuth->openOTPSimpleLogin( $username, $domain, utf8_encode($password), $context );
			}
			if (!$resp || !isset($resp['code'])) {
				OC_Log::write('rcdevsopenotp', "Invalid OpenOTP response for user " . $username , OC_Log::ERROR);
				return false;
			}
			
			switch ($resp['code']) {
				 case 0:
					if ($resp['message']) $msg = $resp['message'];
					else $msg = "Authentication failed for user ".$username;
					OC_Log::write('rcdevsopenotp', "OpenOTP Login attempt failed for user " . $username , OC_Log::INFO);
					
					if( $authentication_method === AUTHENTICATION_METHOD_STD_OTP ){
						$return = $userBackend->checkPassword($uid, $password);
						if($return) OC_Log::write('rcdevsopenotp', "User $username has authenticate with Owncloud password" , OC_Log::INFO);
						else OC_Log::write('rcdevsopenotp', "Standard password Login attempt failed for user " . $username , OC_Log::INFO);
						return $return;
					}
					break;
				 case 1:
					OC_Log::write('rcdevsopenotp', "User $username has authenticated with OpenOTP." , OC_Log::INFO);
					return $username;
					break;
				 case 2:
				 	OC_Log::write('rcdevsopenotp', "OpenOTP Response require Challenge" , OC_Log::DEBUG);
					$rcdevsopenotp_challenge_params = array( 'rcdevsopenotp_otpChallenge' => $resp['otpChallenge'],
													  'rcdevsopenotp_u2fChallenge' => $resp['u2fChallenge'],
													  'rcdevsopenotp_message' => $resp['message'],
													  'rcdevsopenotp_username' => $username,
													  'rcdevsopenotp_session' => $resp['session'],
													  'rcdevsopenotp_timeout' => $resp['timeout'],
													  'rcdevsopenotp_password' => $password,
													  'rcdevsopenotp_appPath' => $appPath,
													  'rcdevsopenotp_domain' => $domain,
						 );
					
					$this->session->set('rcdevsopenotp_challenge_params', $rcdevsopenotp_challenge_params);

					OCP\Util::addHeader('script', array('type' => 'text/javascript', 'src' => 'chrome-extension://pfboblefjcgdjicmnffhdgionmgcdmne/u2f-api.js'), "");

					$src = OC_Helper::linkToRoute('openotpoverlay');
					OCP\Util::addHeader('script', array('type' => 'text/javascript', 'src' => $src), "");

					//OCP\Util::addScript('chrome-extension://pfboblefjcgdjicmnffhdgionmgcdmne/u2f-api.js');
					//header('Content-Security-Policy: script-src  \'self\' \'unsafe-eval\' \'unsafe-inline\' chrome-extension://pfboblefjcgdjicmnffhdgionmgcdmne/u2f-api.js');
					/*OCP\Util::addHeader('script', array('type' => 'text/javascript'), $script);*/
					
					break;
				 default:
					OC_Log::write('rcdevsopenotp', "Authentication failed for user " . $username , OC_Log::ERROR);
					break;
			}			
		} 
			
		return false;
	}


	/**
	 * @brief delete a user
	 * @param string $uid The username of the user to delete
	 * @return bool
	 *
	 * Deletes a user
	 */
	public function deleteUser( $uid ) {
		return $this->__call("deleteUser",array($uid));
	}
	
	/**
	 * @brief Create a new user
	 * @param $uid The username of the user to create
	 * @param $password The password of the new user
	 * @returns true/false
	 *
	 * Creates a new user. Basic checking of username is done in OC_User
	 * itself, not in its subclasses.
	 */
	public function createUser( $uid, $password ) {
		return $this->__call("createUser",array($uid,$password));
	}
	
	/**
	 * @brief Set password
	 * @param $uid The username
	 * @param $password The new password
	 * @returns true/false
	 *
	 * Change the password of a user
	 */
	public function setPassword( $uid, $password ) {
		return $this->__call("setPassword",array('uid'=>$uid,'password'=>$password));
	}

	/**
	 * @brief Get a list of all users
	 * @returns array with all uids
	 *
	 * Get a list of all users.
	 */
	public function getUsers($search = '', $limit = null, $offset = null) {
		return $this->__call("getUsers",array($search,$limit,$offset));
	}
	
	/**
	 * @brief get the user's home directory
	 * @param string $uid the username
	 * @return boolean
	 */
	public function getHome($uid) {
		return $this->__call("getHome",array($uid));
	}

	/**
 	 * @brief get display name of the user
	 * @param string $uid user ID of the user
	 * @return string display name
	 */
	public function getDisplayName($uid) {
		return $this->__call("getDisplayName",array($uid));;
	}


	public function __call($name, $arguments){
		//OC_Log::write('rcdevsopenotp', $name.'().', OC_Log::DEBUG);
		$userBackend=$this->getRealBackend(OCP\User::getUser());
		if($userBackend===null){
			//bug fix lost password link
			if(isset($arguments['uid'])){
				$userBackend=$this->getRealBackend($arguments['uid']);
			}else{
				return false;
			}
		}
		
		$reflectionMethod = new ReflectionMethod(get_class($userBackend),$name);
		return $reflectionMethod->invokeArgs($userBackend,$arguments);
	}		
	/**
	 * check if a user exists
	 * @param string $uid the username
	 * @return boolean
	 */
	public function userExists( $uid ) {
		//OC_Log::write('rcdevsopenotp', 'function userExists() uid:' . $uid, \OC_Log::DEBUG);
		$backend = $this->getRealBackend( $uid );
		if( $backend === null ){
			return false;
		}else{
			//little tricky but if user wants create a user uid is not the same as the backend registered!!!
			return $backend->userExists( $uid );
		}
	}	
	


}
