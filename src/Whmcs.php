<?php

namespace Sburina\Whmcs;

use ReflectionClass;

class Whmcs
{
    /**
     * @var ReflectionClass
     */
    protected $reflector;

    /**
     * Whmcs constructor.
     *
     * @throws \ReflectionException
     */
    public function __construct()
    {
        $this->reflector = new ReflectionClass(__CLASS__);
    }

    /**
     * Magic call to any other WHMCS API methods available.
     *
     * @see https://developers.whmcs.com/api/api-index/
     *
     * @param $name
     * @param $args
     *
     * @return array|null
     */
    public function __call($name, $args)
    {
        $params           = $args[0];
        $params['action'] = $name;

        return (new Client)->post($params);
    }

    /**
     * Retrieve configured products matching provided criteria.
     *
     * @param  int|null  $pid  Obtain a specific product id configuration. Can be a list of ids comma separated
     * @param  int|null  $gid  Retrieve products in a specific group id
     * @param  string|null  $module  Retrieve products utilising a specific module
     *
     * @return array
     */
    public function sbGetProducts($pid = null, $gid = null, $module = null)
    {
        return (new Client)->post([
            'action' => 'getProducts',
            'pid'    => $pid,
            'gid'    => $gid,
            'module' => $module,
        ]);
    }

    /**
     * @param  int|null  $limitstart  The offset for the returned log data (default: 0)
     * @param  int|null  $limitnum  The number of records to return (default: 25)
     * @param  string  $sorting  The direction to sort the results. ASC or DESC. Default: ASC
     * @param  string|null  $search  The search term to look for at the start of email, firstname,
     *                                  lastname, fullname or companyname
     *
     * @return array
     */
    public function sbGetClients($limitstart = null, $limitnum = null, $sorting = null, $search = null)
    {
        return (new Client)->post([
            'action'     => 'getClients',
            'limitstart' => $limitstart,
            'limitnum'   => $limitnum,
            'sorting'    => $sorting,
            'search'     => $search,
        ]);
    }

    /**
     * Obtain the Clients Details for a specific client.
     *
     * Either email or clientid is required!
     *
     * @param  string|null  $email
     * @param  int|null  $clientid
     * @param  bool  $stats
     *
     * @return array
     */
    public function sbGetClientsDetails($email = null, $clientid = null, $stats = false)
    {
        return (new Client)->post([
            'action'   => 'getClientsDetails',
            'email'    => $email,
            'clientid' => $clientid,
            'stats'    => $stats,
        ]);
    }

    /**
     * Validate client login credentials.
     *
     * @param  string  $email  Client or Sub-Account Email Address
     * @param  string  $password2  Password to validate
     *
     * Response Parameters:
     * Parameter        Type    Description
     * result           string  The result of the operation: success or error
     * userid           int     Client ID
     * contactid        int     Contact ID if credentials match with a Sub-Account
     * passwordhash     string  Login session token - returned if Two-Factor Authentication is not required
     * twoFactorEnabled bool    True if Two-Factor Authentication is enabled for the given account
     *
     * @return array
     */
    public function sbValidateLogin($email, $password2)
    {
        return (new Client)->post([
            'action'    => 'ValidateLogin',
            'email'     => $email,
            'password2' => $password2,
        ]);
    }

    /**
     * Generate the AutoLogin URL for WHMCS
     *
     * @param  string|null  $goto  URI part to redirect after login
     *
     * @return string
     */
    public function getAutoLoginUrl($goto = null)
    {
        if (auth()->check()) {
            // Define WHMCS URL & AutoAuth Key
            $whmcsurl  = rtrim(config('whmcs.url'), '/').'/dologin.php';
            $timestamp = time();
            $email     = auth()->user()->email;
            $hash      = sha1($email.$timestamp.config('whmcs.autoauth.key')); # Generate Hash
            // Generate AutoAuth URL & Redirect
            $url = $whmcsurl
                ."?email=$email&timestamp=$timestamp&hash=$hash&goto="
                .urlencode($goto ?? config('whmcs.autoauth.goto'));

            return $url;
        } else {
            return '/';
        }
    }

    /**
     * Redirect to AutoLogin URL
     *
     * @param  string|null  $goto
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function redirectAutoLogin($goto = null)
    {
        return redirect($this->getAutoLoginUrl($goto));
    }
}
