<?php

namespace Sburina\Whmcs;

class Whmcs
{
    /**
     * Magic call to any WHMCS API method.
     *
     * @see https://developers.whmcs.com/api/api-index/
     *
     * @param  string  $name
     * @param  array   $args
     * @return array|object|null
     */
    public function __call($name, $args)
    {
        $params = $args[0] ?? [];
        $params['action'] = $name;

        return (new Client)->post($params);
    }

    /**
     * Retrieve configured products matching provided criteria.
     *
     * @param  int|string|null  $pid  Product id or comma-separated list of ids
     * @param  int|null         $gid  Product group id
     * @param  string|null      $module  Server module name
     * @return array|object
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
     * Retrieve a list of clients.
     *
     * @param  int|null     $limitstart  Offset for returned data (default: 0)
     * @param  int|null     $limitnum    Number of records to return (default: 25)
     * @param  string|null  $sorting     Sort direction: ASC or DESC
     * @param  string|null  $search      Search term for email, name, or company
     * @return array|object
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
     * Obtain the client details for a specific client.
     * Either email or clientid is required.
     *
     * @param  string|null  $email
     * @param  int|null     $clientid
     * @param  bool         $stats
     * @return array|object
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
     * @param  string  $email      Client or sub-account email address
     * @param  string  $password2  Password to validate
     * @return array|object
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
     * Generate the AutoAuth login URL for WHMCS.
     * Requires WHMCS 8.0 or earlier (AutoAuth was removed in WHMCS 8.1).
     *
     * @param  string|null  $goto  URI to redirect to after login
     * @return string
     */
    public function getAutoLoginUrl($goto = null)
    {
        if (!auth()->check()) {
            return '/';
        }

        $key = config('whmcs.autoauth.key');
        if (empty($key)) {
            return '/';
        }

        $whmcsurl  = rtrim(config('whmcs.url'), '/') . '/dologin.php';
        $timestamp = time();
        $user      = auth()->user();
        $email     = $user ? $user->email : null;

        if (empty($email)) {
            return '/';
        }

        $hash = sha1($email . $timestamp . $key);

        return $whmcsurl
            . '?email=' . urlencode($email)
            . '&timestamp=' . $timestamp
            . '&hash=' . $hash
            . '&goto=' . urlencode($goto ?? config('whmcs.autoauth.goto'));
    }

    /**
     * Redirect to AutoAuth login URL.
     * Requires WHMCS 8.0 or earlier (AutoAuth was removed in WHMCS 8.1).
     *
     * @param  string|null  $goto
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectAutoLogin($goto = null)
    {
        return redirect($this->getAutoLoginUrl($goto));
    }
}
