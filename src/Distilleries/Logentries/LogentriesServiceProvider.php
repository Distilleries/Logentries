<?php

namespace Distilleries\Logentries;

use Distilleries\Logentries\Services\LogEntriesWritter;
use Illuminate\Support\ServiceProvider;
use \LeLogger;

class LogentriesServiceProvider extends ServiceProvider
{


    protected $package = 'logentries';

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/config.php',
            $this->package
        );

        $this->registerLogentries();


    }

    protected function registerLogentries()
    {
        $logger = new LogEntriesWritter(
            LeLogger::getLogger(
                $this->app['config']->get('logentries.token'),
                true,
                $this->app->make('request')->secure(),
                LOG_DEBUG
            )
        );

        $this->app->instance('log', $logger);

        if (isset($this->app['log.setup'])) {
            call_user_func($this->app['log.setup'], $logger);
        }

    }

    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang/', $this->package);
        $this->publishes([
            __DIR__ . '/../../config/config.php' => config_path($this->package . '.php')
        ]);
    }


    /**
     * @return string[]
     */
    public function provides()
    {
        return ['log'];
    }
}