<?php namespace Elepunk\Evaluator;

use Illuminate\Support\Manager;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class EvaluatorManager extends Manager
{
    public function getDefaultDriver()
    {
        return $this->app['config']->get('elepunk/evaluator::driver', 'file');
    }

    public function setDefaultDriver()
    {
        $this->app['config']->set('elepunk/evaluator::driver', $name);
    }

    public function createFileDriver()
    {
        $adapter = new Adapter\File;
        $expression = new ExpressionLanguage;

        return new Evaluator($expression, $adapter);
    }
}