<?php

namespace GTS\Shared\Interface\Site\Providers;

//use Ustabor\Domain\Site\Providers\AuthServiceProvider;
//use Ustabor\Infrastructure\Enums\AppSource;
use Illuminate\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider {

	private $providers = [
		//FormatServiceProvider::class,
		RouteServiceProvider::class,
		//AuthServiceProvider::class
	];

	public function register() {
		//$this->app->instance('appSource', AppSource::API);

		foreach ($this->providers as $provider) {
			$this->app->register($provider);
		}
	}

}
