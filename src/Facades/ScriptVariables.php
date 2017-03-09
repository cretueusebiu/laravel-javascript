<?php

namespace Eusebiu\JavaScript\Facades;

use Illuminate\Support\Facades\Facade;

class ScriptVariables extends Facade
{
    /**
     * The name of the binding in the IoC container.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'javascript';
    }
}
