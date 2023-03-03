<?php

namespace App\Admin\Services\Acl\Facades;

use Illuminate\Support\Facades\Facade;

class Route extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'acl.router';
    }
}
