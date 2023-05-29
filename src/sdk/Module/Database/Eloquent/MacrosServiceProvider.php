<?php

namespace Sdk\Module\Database\Eloquent;

use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class MacrosServiceProvider extends ServiceProvider
{
    public function register()
    {
        $macrosPath = __DIR__ . '/Macros';

        require_once $macrosPath . '/joinTranslatable.php';
    }
}
