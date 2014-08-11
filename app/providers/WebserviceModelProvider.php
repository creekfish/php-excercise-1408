<?php

namespace Creekfish\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application;
use Creekfish\Models\Factories\ModelRequestFactory;

/**
 * Class WebserviceModelProvider
 * @package Creekfish\Providers
 *
 * Laravel IoC Service Provider for binding and creating webservice API
 * models, drivers, etc.
 *
 * @author Bill Herring <arcreekfish@gmail.com>
 */
class WebserviceModelProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

    }

    public function boot()
    {
        parent::boot();

        /*
         * Bind driver instances to interfaces for this project
         */

        \App::bind(
            'Creekfish\Models\Webservices\RestApiDriverInterface',
            'Creekfish\Models\Webservices\Drivers\RestCurlDriver'
        );

    }


}