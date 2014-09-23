<?php namespace Elepunk\Evaluator;

use Illuminate\Support\ServiceProvider;

class EvaluatorServiceProvider extends ServiceProvider {

    /**
     * Register the service provider
     *
     * @return void
     */
    public function register()
    {
        $this->app->bindShared('elepunk.evaluator', function ($app) {
            return new EvaluatorManager($app);
        });
    }

    /**
     * Bootstrap application events
     *
     * @return void
     */
    public function boot()
    {
        $path = __DIR__.'/../';

        $this->package('elepunk/evaluator', 'elepunk/evaluator', $path);

        $this->loadExpresssions();
    }

    /**
     * Load available expressions
     *
     * @return void
     */
    protected function loadExpresssions()
    {
        $this->app->before(function () {
            $this->app['elepunk.evaluator']->repository()->load();
        });
    }

}
