<?php namespace Noherczeg\Breadcrumb;

/**
 * Breadcrumb Service Provider for Laravel 4
 * 
 * This Service Provider is made to make the integration of this package into
 * Laravel 4 more convenient.
 * 
 */

use \Illuminate\Support\ServiceProvider;

class BreadcrumbServiceProvider extends ServiceProvider
{
    /**
     * Boot up the service.
     *
     * @return void
     */
    public function boot()
    {
        // load configs
        $this->app['config']->package('noherczeg/breadcrumb', realpath(__DIR__.'/../config'), 'breadcrumb');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['breadcrumb'] = $this->app->share(function($app)
        {
            $options = $app['config']['noherczeg::breadcrumb'];
            
            return new Breadcrumb($app['request']->root(), $options);
        });
    }

}
