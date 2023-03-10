<?php

namespace Custom\Framework\Database\Eloquent;

use Custom\Framework\Foundation\Support\Providers\ServiceProvider;

class MacrosServiceProvider extends ServiceProvider
{
    public function register()
    {
        $macrosPath = __DIR__ . '/Macros';

        require_once $macrosPath . '/joinTranslatable.php';
    }
}
