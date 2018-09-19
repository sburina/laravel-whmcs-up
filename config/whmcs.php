<?php

return [
	/*
	|--------------------------------------------------------------------------
	| WHMCS URL
	|--------------------------------------------------------------------------
	|
	| Main URL to your WHMCS
	|
	*/
	'url'      => env('WHMCS_URL', 'http://localhost/whmcs'),

	/*
	|--------------------------------------------------------------------------
	| API Credentials
	|--------------------------------------------------------------------------
	|
	| Prior to WHMCS verison 7.2, authentication was validated based on admin
	| login credentials, and not API Authentication Credentials. This method of
	| authentication is still supported for backwards compatibility but may be
	| deprecated in a future version of WHMCS.
	|
	| Supported auth types': "api", "password"
	|
	| @see https://developers.whmcs.com/api/authentication/
	|
	*/
	'auth'     => [
		'type'     => env('WHMCS_AUTH_TYPE', 'api'),

		'api'      => [
			'identifier' => env('WHMCS_API_ID', ''),
			'secret'     => env('WHMCS_API_SECRET', ''),
		],

		'password' => [
			'username' => env('WHMCS_ADMIN_USERNAME', ''),
			'password' => env('WHMCS_ADMIN_PASSWORD', ''),
		],
	],

	/*
	|--------------------------------------------------------------------------
	| AutoAuth
	|--------------------------------------------------------------------------
	| Auth Key to automatically login the user to WHMCS if already loogged in
	| to this app. Option "goto" allows you to redirect user to a specific WHMCS
	| page after successful login.
	|
	| @see https://docs.whmcs.com/AutoAuth
	|
	*/
	'autoauth' => [
		'key'  => env('WHMCS_AUTOAUTH_KEY'),
		'goto' => 'clientarea.php?action=products',
	],

	/*
	|--------------------------------------------------------------------------
	| Session key to store WHMCS user record
	|--------------------------------------------------------------------------
	|
	| After successful validation, we store the retrieved WHMCS user record to
	| the following session key:
	|
	*/
	'session_key' => env('WHMCS_SESSION_USER_KEY', 'user'),

];
