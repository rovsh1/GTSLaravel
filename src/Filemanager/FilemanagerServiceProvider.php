<?php

namespace Gsdk\Filemanager;

use Gsdk\Filemanager\Services\StorageService;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class FilemanagerServiceProvider extends ServiceProvider
{
    protected $storage = 'upload';

    public function boot()
    {
        StorageService::useStorage($this->storage);
    }
}
