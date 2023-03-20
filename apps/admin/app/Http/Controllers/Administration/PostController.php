<?php

namespace App\Admin\Http\Controllers\Administration;

use App\Admin\Support\Http\Controllers\AbstractEnumController;

class PostController extends AbstractEnumController
{
    protected function getPrototypeKey(): string
    {
        return 'administrator-post';
    }
}
