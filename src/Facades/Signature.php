<?php

namespace Kevinpurwito\Web3Login\Facades;

use Illuminate\Support\Facades\Facade;
use Kevinpurwito\Web3Login\Signature as BaseClass;

class Signature extends Facade
{
    public static function getFacadeAccessor()
    {
        return BaseClass::class;
    }
}
