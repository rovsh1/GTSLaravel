<?php

namespace App\Hotel\Providers;

use Gsdk\Filemanager\FilemanagerServiceProvider as ServiceProvider;

class FilemanagerServiceProvider extends ServiceProvider
{
    protected string $storage = 'upload';
}
