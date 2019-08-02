<?php

namespace Sburina\Whmcs\Facades;

use Illuminate\Support\Facades\Facade;

class Whmcs extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'whmcs';
    }
}
