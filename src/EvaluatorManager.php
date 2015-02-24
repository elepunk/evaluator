<?php namespace Elepunk\Evaluator;

use Illuminate\Support\Manager;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class EvaluatorManager extends Manager
{
    /**
     * Retrieve the default driver
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']->get('elepunk/evaluator::driver', 'memory');
    }

    /**
     * Set the default driver
     *
     * @param  string $name
     * @return  void
     */
    public function setDefaultDriver($name)
    {
        $this->app['config']->set('elepunk/evaluator::driver', $name);
    }

    /**
     * Create memory adapter driver
     *
     * @return \Elepunk\Evaluator\Evaluator
     */
    public function createMemoryDriver()
    {
        $adapter = (new Adapter\Memory($this->app['orchestra.memory']))->load();
        $expression = new ExpressionLanguage;

        return new Evaluator($expression, $adapter);
    }
}
