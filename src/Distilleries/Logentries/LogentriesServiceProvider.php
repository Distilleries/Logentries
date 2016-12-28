<?php

namespace Distilleries\Logentries;

use Illuminate\Support\ServiceProvider;

class LogentriesServiceProvider extends ServiceProvider
{
    /**
     * Package name.
     *
     * @var string
     */
    protected $package = 'logentries';

    /**
     * LogEntries token.
     *
     * @var string
     */
    private $token = '';

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['log'];
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (! empty($this->token)) {
            $this->bootLogEntries();
        }
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->token = $this->app['config']['logentries.token'];
        
        if (! empty($this->token)) {
            $this->registerLogEntries();
        }
    }

    /**
     * Register LogEntries instance in application container.
     *
     * @return void
     */
    protected function registerLogEntries()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/config.php',
            $this->package
        );

        $logger = new LogEntries(
            Driver::getLogger($this->token, true, $this->app->make('request')->secure(), LOG_DEBUG)
        );

        $this->app->instance('log', $logger);

        if (isset($this->app['log.setup'])) {
            call_user_func($this->app['log.setup'], $logger);
        }
    }

    /**
     * Boot LogEntries service.
     *
     * @return void
     */
    protected function bootLogEntries()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang/', $this->package);

        $this->publishes([
            __DIR__ . '/../../config/config.php' => config_path($this->package . '.php'),
        ]);
    }
}