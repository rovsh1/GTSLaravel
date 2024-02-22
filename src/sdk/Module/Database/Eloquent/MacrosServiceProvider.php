<?php

namespace Sdk\Module\Database\Eloquent;

use Illuminate\Support\ServiceProvider;

class MacrosServiceProvider extends ServiceProvider
{
    public function register()
    {
        $macrosPath = __DIR__ . '/Macros';

        require_once $macrosPath . '/joinTranslatable.php';
    }
}
