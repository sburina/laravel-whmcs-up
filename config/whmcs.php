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
	'url'             => env('WHMCS_URL', 'http://localhost/whmcs'),

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
	'auth'            => [
		'type' => env('WHMCS_AUTH_TYPE', 'api'),

		'api' => [
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
	| API Credentials
	|--------------------------------------------------------------------------
	|
	| an access key can be configured to allow IP restrictions to be bypassed.

	| It works by defining a secret key/passphrase in the WHMCS configuration.php
	| file which is then passed into all API calls. To configure it, add a line
	| as follows to your configuration.php file in the root WHMCS directory.
	|
	| @see https://developers.whmcs.com/api/access-control/
	|
	*/
	'api_access_key'  => env('WHMCS_API_ACCESS_KEY', ''),

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
	'autoauth'        => [
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
	'session_key'     => env('WHMCS_SESSION_USER_KEY', 'user'),

	/*
	|--------------------------------------------------------------------------
	| Convert numbers from strings to floats in results
	|--------------------------------------------------------------------------
	|
	| WHMCS API returns numbers (prices, etc..) as strings in its JSON results.
	| This option will reformat the response so all the numbers with two decimals
	| will be converted to floats in the resulting array. If you just need to
	| display the results, leave the option turned off.
	|
	| Default: false
	|
	*/
	'use_floats'      => false,

	/*
	|--------------------------------------------------------------------------
	| Return the results as associative arrays
	|--------------------------------------------------------------------------
	|
	| true: get result as an associative array.
	| false: get the result as a stdClass object.
	|
	*/
	'result_as_array' => true,
];
