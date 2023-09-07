<?php

namespace App\Admin\Providers;

use Sdk\Filemanager\FilemanagerServiceProvider as ServiceProvider;

class FilemanagerServiceProvider extends ServiceProvider
{
    protected string $storage = 'upload';
}