<?php

namespace Yormy\Apiresponse;

use Illuminate\Support\Facades\Facade;

class ApiresponseFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Apiresponse::class;
    }
}
