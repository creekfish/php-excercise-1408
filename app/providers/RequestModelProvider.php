<?php

namespace Creekfish\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application;
use Creekfish\Models\Factories\ModelRequestFactory;

/**
 * Class RequestModelProvider
 * @package Creekfish\Providers
 *
 * Laravel IoC Service Provider for creating API resource models
 * from request data.
 *
 * @author Bill Herring <arcreekfish@gmail.com>
 */
class RequestModelProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'ModelRequestFactory',
            function (Application $app) {
                return new ModelRequestFactory();
            }
        );


        // NOTE: every model that can be created from a request (POST) must
        // have an entry here, and models that have no public API should NOT be here.

        $this->app->bind(
            'UserFromRequest',
            function (Application $app, $request = null) {
                return $app->make('ModelRequestFactory')->createModelFromRequest('User', $request);
            }
        );

    }
}