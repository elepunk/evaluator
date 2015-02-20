<?php namespace Elepunk\Evaluator;

use Orchestra\Support\Providers\ServiceProvider;

class EvaluatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap available services
     * 
     * @return void
     */
    public function boot()
    {
        $path = realpath(__DIR__.'/../resources');

        $this->addConfigComponent('elepunk/evaluator', 'elepunk/evaluator', $path.'/config');
    }

    /**
     * Register available services
     * 
     * @return void
     */
    public function register()
    {
        $this->app->singleton('elepunk.evaluator', function () {
            return new EvaluatorManager($this->app);
        });
    }
}