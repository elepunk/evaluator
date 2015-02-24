<?php namespace Elepunk\Evaluator;

use Illuminate\Foundation\AliasLoader;
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
        $this->registerManager();

        $this->registerFacade();
    }

    /**
     * Register evaluator manager
     *
     * @return void
     */
    protected function registerManager()
    {
        $this->app->singleton('elepunk.evaluator', function () {
            return new EvaluatorManager($this->app);
        });
    }

    /**
     * Register facade
     *
     * @return void
     */
    protected function registerFacade()
    {
        $loader = AliasLoader::getInstance();

        $loader->alias('Evaluator', 'Elepunk\Evaluator\Facades\Evaluator');
    }
}
