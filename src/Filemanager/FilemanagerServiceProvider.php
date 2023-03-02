<?php

namespace Gsdk\Filemanager;

use Custom\Framework\Foundation\Support\Providers\ServiceProvider;
use Gsdk\Filemanager\Services\StorageService;

class FilemanagerServiceProvider extends ServiceProvider
{
    protected $storage = 'upload';

    public function boot()
    {
        StorageService::useStorage($this->storage);
    }
}
