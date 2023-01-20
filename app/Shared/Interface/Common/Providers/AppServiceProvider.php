<?php

namespace GTS\Shared\Interface\Common\Providers;

use Illuminate\Support\ServiceProvider;

use GTS\Shared\Interface\Common\Contracts\DomainServiceProvider;

//use Ustabor\Interfaces\Contracts\DomainServiceProvider;
//use Ustabor\Interfaces\Providers\AuthServiceProvider;
//use Ustabor\Interfaces\Providers\DateTimeServiceProvider;
//use Ustabor\Interfaces\Providers\EventServiceProvider;
//use Ustabor\Interfaces\Providers\FormatServiceProvider;
//use Ustabor\Interfaces\Providers\LocaleServiceProvider;
//use Ustabor\Interfaces\Providers\NotificationServiceProvider;
//use Ustabor\Interfaces\Providers\SettingsServiceProvider;
//use Ustabor\Interfaces\Providers\ValidatorServiceProvider;
//use Ustabor\Interfaces\Providers\VariablesServiceProvider;
//use Ustabor\Interfaces\Providers\ViewServiceProvider;
//use Ustabor\Interfaces\Support\ReleaseInfo;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
//		$this->app->register(SettingsServiceProvider::class);
//		$this->app->register(AuthServiceProvider::class);
//		$this->app->register(ViewServiceProvider::class);
//		$this->app->register(FormatServiceProvider::class);
//		$this->app->register(DateTimeServiceProvider::class);
//		$this->app->register(LocaleServiceProvider::class);
//		$this->app->register(EventServiceProvider::class);
//		$this->app->register(NotificationServiceProvider::class);
//		$this->app->register(ValidatorServiceProvider::class);
//		$this->app->register(VariablesServiceProvider::class);
//
//		$this->app->singleton('release', fn() => new ReleaseInfo(base_path('package.json')));

        if ($this->app->has(DomainServiceProvider::class)) {
            $this->app->register($this->app->get(DomainServiceProvider::class));
        }
    }

    public function boot()
    {
        app('translator')->addJsonPath(resource_path('system/lang'));
    }

}
