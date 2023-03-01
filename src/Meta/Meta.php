<?php

namespace Gsdk\Meta;

use Illuminate\Support\Facades\Facade;

class Meta extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'meta';
    }
}
