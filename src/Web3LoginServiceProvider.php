<?php

namespace Kevinpurwito\Web3Login;

use Illuminate\Support\ServiceProvider;

class Web3LoginServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 */
	public function boot()
	{
		$this->offerPublishing();

		if (Web3Login::$registersRoutes) {
			$this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
		}

		if (Web3Login::$runsMigrations) {
			$this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
		}
	}

	/**
	 * Register the application services.
	 */
	public function register()
	{
		// Automatically apply the package configuration
		$this->mergeConfigFrom(__DIR__ . '/../config/web3.php', 'web3');
	}

	protected function offerPublishing()
	{
		if (! function_exists('config_path')) {
			// function not available and 'publish' not relevant in Lumen
			return;
		}

		// config
		$this->publishes([
			__DIR__ . '/../config/web3.php' => config_path('web3.php'),
		], 'web3-config');
	}
}
