Laravel WHMCS-UP
=====

[![Latest Stable Version](https://poser.pugx.org/sburina/laravel-whmcs-up/v/stable)](https://packagist.org/packages/sburina/laravel-whmcs-up)
[![Total Downloads](https://poser.pugx.org/sburina/laravel-whmcs-up/downloads)](https://packagist.org/packages/sburina/laravel-whmcs-up)
[![License](https://poser.pugx.org/sburina/laravel-whmcs-up/license)](https://packagist.org/packages/sburina/laravel-whmcs-up)

This package provides several useful functions for integration of your Laravel application with WHMCS:

- WHMCS API client
- WHMCS user provider
- WHMCS Auto-Login

## Installation

Install the package through [Composer](http://getcomposer.org/).

- Run the Composer require command from the Terminal:

```bash
composer require sburina/laravel-whmcs-up
```

- Run `composer update` to pull in the files.

### After Laravel 5.5

No additional steps required.

### Before Laravel 5.5

- Add the service provider and alias of the package. To do this, open your `config/app.php` file.

- Add a new line to the `providers` array:

```php
Sburina\Whmcs\WhmcsServiceProvider::class
```

- And optionally add a new line to the `aliases` array:

```php
'Whmcs' => Sburina\Whmcs\Facades\Whmcs::class,
```

- From the command-line run:

```bash
php artisan vendor:publish --provider=Sburina\Whmcs\WhmcsServiceProvider
```

- Open `config\whmcs.php` to see the available configuration options. The preferred way of configuring the package is via the environment variables in your project's `.env` file.

Now you can use the package in your Laravel project.

## Usage

This package defines several important WHMCS methods with custom signature, providing somewhat easier use and code completion. These methods are:

```php
// Getters
sbGetProducts($pid = null, $gid = null, $module = null);
sbGetClients($limitstart = null, $limitnum = null, $sorting = null, $search = null);
sbGetClientsDetails($email = null, $clientid = null, $stats = false);

// Login
sbValidateLogin($email, $password2);

// AutoLogin
getAutoLoginUrl($goto = null);
redirectAutoLogin($goto = null);
```

All other WHMCS API methods can be used magically by calling the `\Whmcs::{WHMCS_API_METHOD}` facade.
This also works with all the custom API functions stored in your WHMCS API folder. For complete specification of WHMCS API methods please take a look at the [WHMCS API Index](https://developers.whmcs.com/api/api-index/).

### Examples

Get user's detail using our method:

```php
\Whmcs::sbGetClientsDetails($email);
```

The same thing, using the original WHMCS API method via a magic call:

```php
\Whmcs::GetClientsDetails([
    'email' => 'jane.doe@example.com'
]);
```

Obtain a list of client purchased products

```php
\Whmcs::GetClientsProducts([
    'clientid' => 18122013
]);
```

Retrieve a specific invoice

```php
\Whmcs::GetInvoice([
    'invoiceid' => 100001
]);
```

If you for any reason don't like facades, you can use the `app()` helper.

```php
$whmcs = app('whmcs');
$whmcs->GetInvoice([
    'invoiceid' => 100001
]);
```

## Authenticating against WHMCS user base

If your Laravel application doesn't have its own user base, but you still need to authenticate users before allowing them to access certain pages (_NOT_ WHMCS pages), there are few additional steps to take:

- Register the user provider in your AuthServiceProvider boot() method:

```php
	public function boot()
	{
		$this->registerPolicies();

		Auth::provider('whmcs', function () {
			return new \Sburina\Whmcs\UserProvider();
		});
	}
```
- In `config/auth.php` define the new provider:
```php
	'providers' => [
		 'users' => [
		     'driver' => 'whmcs',
		 ],
```
- in the same file, the `web` guard is already configured to use `users` provider, so there's no need to change anything. You could decide to give the provider a different name, in which case you'd need to define the same name for the appropriate guard.

Now you can simply use the existing Laravel `Auth::routes()` with already provided `auth` pages, exactly the same way as if you had a local user base.

On successful login, the `session_key` named in `config/whmcs.php` (default: `user`) will be populated with user data retrieved from WHMCS, and the login session will start as usual. `auth()->check()` and `auth()->guest()` will work, and `auth()->user()` will return the instance of `WhmcsUser` class with attributes populated with user's data. User's data won't be retrieved from WHMCS again while the login session is in progress and the session key `user` exists.

On logout, the session key `user` will be destroyed and the login session will end.

## Remote login / redirect

User that logged in into your Laravel application this way will not be automatically logged in to WHMCS! To redirect the authenticated user to any protected WHMCS page and log them into WHMCS automatically at the same time, you can use:
```php
return \Whmcs::redirectAutoLogin();
```

`config/whmcs.php` option `autoauth.goto` determines the default URI for such redirects. You can override the default by adding the argument to this method:
```php
return \Whmcs::redirectAutoLogin('cart.php');
```

If you'd prefer just getting the login URL and sending it to the user from your own code, you can do it like so:
```php
$url = \Whmcs::getAutoLoginUrl();
```

Again, you can override the default URI:
```php
$url = \Whmcs::getAutoLoginUrl('cart.php');
```

To learn more about this feature and how to enable it in WHMCS, see [WHMCS AutoAuth](https://docs.whmcs.com/AutoAuth).

## Support

[Please open an issue in github](https://github.com/sburina/laravel-whmcs-up/issues)

## License

This package is released under the MIT License. See the bundled
[LICENSE](https://github.com/sburina/laravel-whmcs-up/blob/master/LICENSE) file for details.
