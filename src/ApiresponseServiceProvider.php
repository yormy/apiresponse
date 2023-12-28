<?php

namespace Yormy\Apiresponse;

use Illuminate\Support\ServiceProvider;

class ApiresponseServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publish();
        $this->registerTranslations();
    }

    public function register()
    {
        // ...
    }

    public function registerTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'apiresponse');
    }


    private function publish(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/apiresponse'),
            ], 'translations');
        }
    }
}
