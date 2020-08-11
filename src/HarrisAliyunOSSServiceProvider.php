<?php
namespace Harris\AliyunOSS;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;

class HarrisAliyunOSSServiceProvider extends RouteServiceProvider {
	
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;
	
	/**
	 * Bootstrap the application events.
	 *
	 * @param \Illuminate\Routing\Router $router        	
	 * @return void
	 */
	public function boot() {
		parent::boot();
	}
	
	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
		parent::register();
		$configPath = realpath( __DIR__ . '/../config/fileuploads.php');
		$this->mergeConfigFrom($configPath, 'fileuploads');
		$this->publishes([$configPath => config_path('fileuploads.php' )], 'config');
	}
	
	/**
	 * Define the routes for the application.
	 * @return void
	 */
	public function map() {
		
	}
	
	/**
	 * Get the services provided by the provider.
	 * @return array
	 */
	public function provides() {
		
	}
}
