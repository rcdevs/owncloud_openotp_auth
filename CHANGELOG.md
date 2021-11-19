1.2.3
	- Add compatibility for oc10.8.0
	- Add a setting to ignore SSL/TLS certificate errors
1.2.2-1
	- Add compatibility for oc10.0.8 - oc10.0.9
1.2.2
	- implement contextual authentication	
	- app:check-code integrity
1.2.1
1.2.0
	- Add compatibility to Owncloud v10 (10.0.4-10.0.7 tested)
	- Errors replaced by exceptions
1.1.1-1
	- Added Contextual authentication
	  change the LoginMode to LDAP-only for requests comming 
	  from trusted devices on trusted IPs.
	- New openotp.wsdl file including context parameter
1.1.1
	Added support to OpenOTP Software Token with Push Notif Authentication
	Extend php soapclient to add timeout capabilities
	WebService API is now versioned
	U2F javascript scripts updated
	OTP Challenge input doesn't show anymore characters in clear text (type=password)   
1.1
    Application compatible with owncloud 9.0.1	
1.0.1
	Enhanced remote Password
	Add FidoU2F.js library to avoid to installation of the U2F plugin in Google Chrome 
1.0.0
     Initial public release.	 