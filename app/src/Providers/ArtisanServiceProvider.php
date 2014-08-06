<?php

use Illuminate\Support\ServiceProvider;

class ArtisanServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerInspireCommand();

		$this->commands('commands.inspire');
		$this->commands('commands.app:refresh');
		$this->commands('commands.app:install');
		$this->commands('commands.app:seed');
	}

	/**
	 * Register the Inspire Artisan command.
	 *
	 * @return void
	 */
	protected function registerInspireCommand()
	{
		// Each available Artisan command must be registered with the console so
		// that it is available to be called. We'll register every command so
		// the console gets access to each of the command object instances.
		$this->app->bindShared('commands.inspire', function()
		{
			return new InspireCommand;
		});

		$this->app->bindShared('commands.app:refresh', function()
		{
			return new AppRefreshCommand;
		});

		$this->app->bindShared('commands.app:install', function()
		{
			return new AppInstallCommand;
		});

		$this->app->bindShared('commands.app:seed', function()
		{
			return new AppSeedCommand;
		});
	}

}