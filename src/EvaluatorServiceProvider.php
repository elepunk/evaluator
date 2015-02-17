<?php namespace Elepunk\Evaluator;

use Illuminate\Support\ServiceProvider;
use Elepunk\Evaluator\EvaluatorManager;

class EvaluatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap available services
     * 
     * @return void
     */
    public function boot()
    {
        //
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