<?php

namespace App\Admin\Providers;

use Gsdk\Filemanager\FilemanagerServiceProvider as ServiceProvider;

class FilemanagerServiceProvider extends ServiceProvider
{
    protected string $storage = 'upload';
}