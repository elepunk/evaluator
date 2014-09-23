<?php namespace Elepunk\Evaluator;

use Illuminate\Support\Manager;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class EvaluatorManager extends Manager {

    public function getDefaultDriver()
    {
        return $this->app['config']->get('elepunk/evaluator::driver', 'file');
    }

    public function setDefaultDriver($name)
    {
        $this->app['config']->set('elepunk/evaluator::driver', $name);
    }

    protected function createFileDriver()
    {
        $repository = new FileRepository($this->app['config']);
        $expressionEngine = new ExpressionLanguage;

        return new Evaluator($expressionEngine, $repository);
    }

    protected function createMemoryDriver()
    {
        $repository = new MemoryRepository($this->app['orchestra.memory']);
        $expressionEngine = new ExpressionLanguage;

        return new Evaluator($expressionEngine, $repository);
    }

}
