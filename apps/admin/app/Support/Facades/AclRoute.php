<?php

namespace App\Admin\Support\Facades;

use Illuminate\Support\Facades\Facade;

class AclRoute extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'acl.router';
    }
}
