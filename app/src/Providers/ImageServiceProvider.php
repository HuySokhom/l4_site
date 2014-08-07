<?php 

use Illuminate\Support\ServiceProvider;

class ImageServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Register 'underlyingclass' instance container to our UnderlyingClass object
        $this->app['image'] = $this->app->share(function($app)
        {
            return new App\Services\Image;
        });

        // Shortcut so developers don't need to add an Alias in app/config/app.php
        $this->app->booting(function()
        {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Image', 'App\Facades\ImageFacade');
        });
    }
}