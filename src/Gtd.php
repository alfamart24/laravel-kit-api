<?php

namespace Alfamart24\Gtdapi;

use Illuminate\Support\Facades\Facade;

class Gtd extends Facade
{
    protected static function getFacadeAccessor()
    {
        parent::getFacadeAccessor();
        return 'gtdapi';
    }
}
