<?php

namespace App\Shared\Providers;

class HorizonServiceProvider extends \Laravel\Horizon\HorizonServiceProvider
{
    // приходиться переопределять чтобы изменить путь для assets
    public function defineAssetPublishing(): void
    {
        $this->publishes([
            HORIZON_PATH . '/public' => "{$this->app->get('path.admin')}/public/vendor/horizon",
        ], ['horizon-assets', 'laravel-assets']);
    }
}
