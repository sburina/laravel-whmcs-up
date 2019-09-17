<?php

namespace Sburina\Whmcs;

use Illuminate\Support\Arr;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider as BaseProvider;

class UserProvider implements BaseProvider
{
    /**
     * @var \Sburina\Whmcs\Whmcs
     */
    protected $client;

    public function __construct()
    {
        $this->client = app('whmcs');
    }


    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     *
     * @return WhmcsUser|null
     */
    public function retrieveById($identifier)
    {
        $userAttributes = [];
        if (session()->has(config('whmcs.session_key'))) {
            $userAttributes = session()->get(config('whmcs.session_key'));
        } else {
            $res = (array) $this->client->sbGetClientsDetails(null, $identifier);
            if (Arr::has($res, 'result') && $res['result'] === 'success') {
                $userAttributes = (array) $res['client'];
            }
        }

        return (sizeof($userAttributes) > 0) ? new WhmcsUser($userAttributes) : null;
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed  $identifier
     * @param  string  $token
     *
     * @return null
     */
    public function retrieveByToken($identifier, $token)
    {
        return null;
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  Authenticatable  $user
     * @param  string  $token
     *
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        //
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     *
     * @return WhmcsUser|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        $userAttributes = [];
        if (session()->has(config('whmcs.session_key'))) {
            $userAttributes = session()->get(config('whmcs.session_key'));
        } else {
            $res = (array) $this->client->sbGetClientsDetails($credentials['email']);
            if (Arr::has($res, 'result') && $res['result'] === 'success') {
                $userAttributes = (array) $res['client'];
            }
        }

        return (sizeof($userAttributes) > 0) ? new WhmcsUser($userAttributes) : null;
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  Authenticatable  $user
     * @param  array  $credentials
     *
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        /** @var array $res */
        $res = (array) $this->client->sbValidateLogin(
            $credentials['email'],
            $credentials['password']
        );

        if (Arr::has($res, 'result') && $res['result'] === 'success') {
            session()->put(config('whmcs.session_key'), $this->retrieveByCredentials($credentials)->getAttributes());

            return true;
        }

        return false;
    }
}
