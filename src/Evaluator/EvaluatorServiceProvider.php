<?php namespace Elepunk\Evaluator;

use Illuminate\Support\ServiceProvider;

class EvaluatorServiceProvider extends ServiceProvider {

    /**
     * @var bool
     */
    protected $defer = true;

    /**
     *
     */
    public function register()
    {
        $this->app->bindShared('elepunk.evaluator', function ($app) {
            return new EvaluatorManager($app);
        });
    }

    /**
     *
     */
    public function boot()
    {
        $path = __DIR__.'/../';

        $this->package('elepunk/evaluator', 'elepunk/evaluator', $path);

        $this->loadExpresssions();
    }

    /**
     *
     */
    protected function loadExpresssions()
    {
        $this->app->before(function () {
            $this->app['elepunk.']->repository()->load();
        });
    }

}
