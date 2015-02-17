<?php namespace Elepunk\Evaluator;

use \Closure;
use Elepunk\Evaluator\Collection;
use Elepunk\Evaluator\Contracts\Adapter;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class Evaluator
{
    /**
     * Symfony Expression Language instance
     * 
     * @var \Symfony\Component\ExpressionLanguage\ExpressionLanguage
     */
    protected $expression;

    /**
     * Evaluator adapter instance
     * 
     * @var \Elepunk\Evaluator\Contracts\Adapter
     */
    protected $adapter;

    /**
     * Construct new evaluator instance
     * 
     * @param \Symfony\Component\ExpressionLanguage\ExpressionLanguge $expression
     * @param \Elepunk\Evaluator\Contracts\Adapter $adapter
     * 
     * @return  void
     */
    public function __construct(ExpressionLanguage $expression, Adapter $adapter)
    {
        $this->expression = $expression;
        $this->adapter = $adapter;
    }

    /**
     * Get the Expression Engine instance
     * 
     * @return \Symfony\Component\ExpressionLanguge\ExpressionLanguage
     */
    public function getExpressionEngine()
    {
        return $this->expression;
    }

    /**
     * Get evaluator current adapter instance
     * 
     * @return \Elepunk\Evaluator\Contracts\Adapter
     */
    public function expression()
    {
        return $this->adapter;
    }

    /**
     * Apply the condition rules to the collection
     * 
     * @return [type] [description]
     */
    public function apply(Collection $collection)
    {

    }

    /**
     * [ifValid description]
     * @param  Closure $callback [description]
     * @return [type]            [description]
     */
    public function ifValid(Closure $callback)
    {

    }
}