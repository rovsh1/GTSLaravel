<?php

namespace App\Shared\Providers;

use App\Shared\Services\LocaleTranslator;
use Illuminate\Translation\TranslationServiceProvider as ServiceProvider;
use Sdk\Shared\Contracts\Service\TranslatorInterface;

class TranslationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerLoader();

        $this->app->singleton('translator', function ($app) {
            $loader = $app['translation.loader'];

            // When registering the translator component, we'll need to set the default
            // locale as well as the fallback locale. So, we'll grab the application
            // configuration so we can easily get both of these values from there.
            $locale = $app->getLocale();

            $trans = new LocaleTranslator(app(TranslatorInterface::class), $loader, $locale);

            $trans->setFallback($app->getFallbackLocale());

            return $trans;
        });
    }

    public function boot() {}
}
