<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Providers;

use BComeSafe\Libraries\Cache\SecondsFileStore;
use BComeSafe\Models\School;
use BComeSafe\Models\SchoolDefault;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (php_sapi_name() != 'cli') {
            if (!\Request::is('system/*')) {
                $model = School::getSettings();
                \App::setLocale($model->locale);
            } else {
                $defaults = SchoolDefault::getDefaults();
                \App::setLocale($defaults->locale);
            }
        }

        \Cache::extend(
            'seconds',
            function ($app) {
                return \Cache::repository(new SecondsFileStore($app['files'], storage_path() . '/framework/cache'));
            }
        );
    }

    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'Illuminate\Contracts\Auth\Registrar',
            'BComeSafe\Services\Registrar'
        );

    }
}
