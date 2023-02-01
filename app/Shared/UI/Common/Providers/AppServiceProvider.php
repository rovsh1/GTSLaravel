<?php

namespace GTS\Shared\UI\Common\Providers;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\ServiceProvider;

use GTS\Shared\UI\Common\Contracts\UIServiceProvider;

//use Ustabor\UIs\Providers\AuthServiceProvider;
//use Ustabor\UIs\Providers\DateTimeServiceProvider;
//use Ustabor\UIs\Providers\EventServiceProvider;
//use Ustabor\UIs\Providers\FormatServiceProvider;
//use Ustabor\UIs\Providers\LocaleServiceProvider;
//use Ustabor\UIs\Providers\NotificationServiceProvider;
//use Ustabor\UIs\Providers\SettingsServiceProvider;
//use Ustabor\UIs\Providers\ValidatorServiceProvider;
//use Ustabor\UIs\Providers\VariablesServiceProvider;
//use Ustabor\UIs\Providers\ViewServiceProvider;
//use Ustabor\UIs\Support\ReleaseInfo;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
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

        $this->app->bind(ClientInterface::class, Client::class);

        if ($this->app->has(UIServiceProvider::class)) {
            $this->app->register($this->app->get(UIServiceProvider::class));
        }
    }

    public function boot()
    {
        //app('translator')->addJsonPath(resource_path('system/lang'));
    }

}
