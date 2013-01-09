<?php namespace Noherczeg\Breadcrumb;

/**
 * Breadcrumb Service Provider for Laravel 4
 * 
 * This Service Provider is made to make the integration of this package into
 * Laravel 4 more convenient.
 * 
 */

use Illuminate\Support\ServiceProvider;

class BreadcrumbtServiceProvider extends ServiceProvider
{

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['breadcrumb'] = $this->app->share(function($app)
		{
			$request = new \Illuminate\Http\Request();
			return new Breadcrumb($request->root());
		});
	}

}
