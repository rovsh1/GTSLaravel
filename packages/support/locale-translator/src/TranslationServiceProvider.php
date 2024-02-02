<?php

namespace Support\LocaleTranslator;

use Illuminate\Translation\TranslationServiceProvider as ServiceProvider;
use Sdk\Shared\Contracts\Service\TranslatorInterface;
use Support\LocaleTranslator\Illuminate\LocaleTranslator;
use Support\LocaleTranslator\Storage\CacheStorage;

class TranslationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->bootMigrations();
        $this->registerLoader();

        $this->app->singleton(TranslatorInterface::class, Translator::class);
        $this->app->singleton(CacheStorage::class);
        $this->app->singleton(SyncTranslationsService::class, function ($app) {
            return new SyncTranslationsService(
                realpath(__DIR__ . '/../resources/lang'),
                $app[CacheStorage::class]
            );
        });

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

    protected function bootMigrations(): void
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
    }
}
