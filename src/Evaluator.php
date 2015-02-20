<?php namespace Elepunk\Evaluator;

use \Closure;
use Illuminate\Support\Fluent;
use Elepunk\Evaluator\Collection;
use Illuminate\Support\Str as S;
use Elepunk\Evaluator\Contracts\Adapter;
use Elepunk\Evaluator\Contracts\Evaluator as EvaluatorInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class Evaluator implements EvaluatorInterface
{
    /**
     * Symfony Expression Language instance
     * 
     * @var \Symfony\Component\ExpressionLanguage\ExpressionLanguage
     */
    protected $expressionEngine;

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
        $this->expressionEngine = $expression;
        $this->adapter = $adapter;
    }

    /**
     * {@inheritdoc}
     */
    public function getExpressionEngine()
    {
        return $this->expressionEngine;
    }

    /**
     * {@inheritdoc}
     */
    public function expression()
    {
        return $this->adapter;
    }

    /**
     * {@inheritdoc}
     */
    public function evaluate($expression, $item, Closure $callback = null)
    {   
        $evaluate = $this->getExpressionEngine()->evaluate($expression, $item);

        if ( ! is_null($callback) && $evaluate ) {
            return call_user_func($callback, $item);
        }

        return $evaluate;
    }

    /**
     * {@inheritdoc}
     */
    public function evaluateRule($expressionKey, $item, Closure $callback = null)
    {
        $expression = $this->expression()->get($expressionKey);

        $evaluate = $this->evaluate($expression, $item);

        if ( ! is_null($callback) && $evaluate ) {
            return call_user_func($callback, $item);
        }

        return $evaluate;
    }

    /**
     * {@inheritdoc}
     */
    public function condition($expressionKey, $item, Closure $callback = null)
    {
        if ( ! $item instanceof Collection ) {
            $collection = new Collection($item);
        }

        $expression = $this->expression()->get($expressionKey);

        if ( ! is_null($expression->rule)) {
            $evaluation = $this->evaluate($expression->rule, $collection->toArray());
            if ( ! $evaluation) {
                return $collection;
            }
        }

        $result = $this->calculate($expression, $collection);
        $collection->setCalculatedValue($result);
        $collection->put($expression->target, $result);

        if ( ! is_null($callback)) {
            return call_user_func($callback, $collection);
        }

        return $collection;
    }

    /**
     * Calculate the condition applied
     * 
     * @param  \Illuminate\Support\Fluent $expression
     * @param  \Elepunk\Evaluator\Collection $item
     * @return integer
     */
    protected function calculate(Fluent $expression, Collection $item)
    {
        $target = $item->get($expression->target);
        $item->setOriginalValue($target);

        $action = $expression->action;

        $operator = $this->getArithmeticOperator($action);
        $target = $this->isMutiplying($expression, $item);

        if ($this->isPercentage($action)) {
            $value = $this->evaluate('(value/100)*target', [
                'target' => $target,
                'value' => $this->getCalculationValue($expression->action)
            ]);
        }
        
        $calculated = $this->evaluate("first {$operator} second", [
            'first' => $target, 
            'second' => $value
        ]);

        return $calculated;
    }

    /**
     * Determine if condition has multiplier
     *
     * @param  \Illuminate\Support\Fluent $expression
     * @param  \Elepunk\Evaluator\Collection $item
     * @return integer
     */
    protected function isMutiplying(Fluent $expression, Collection $item)
    {
        if (is_null($expression->multiplier)) {
            return $item->get($expression->target);
        }

        return $this->evaluate("value * multiplier", [
            'value' => $this->getCalculationValue($item->get($expression->target)),
            'multiplier' => $item->get($expression->multiplier)
        ]);
    }

    /**
     * Extract the arithmetic operator from condition
     * 
     * @param  string $input
     * @return string
     */
    protected function getArithmeticOperator($input)
    {
        preg_match('/[+-\/*]/', $input, $matches);

        if (count($matches) > 0) {
            return $matches[0];
        }

        return '+';
    }

    /**
     * Extract the digits from condition
     * 
     * @param  string $input
     * @return string
     */
    protected function getCalculationValue($input)
    {
        preg_match('/([0-9]+)/', $input, $matches);

        return $matches[0];
    }

    /**
     * Determine if condition is percentage
     * 
     * @param  string  $input
     * @return boolean
     */
    protected function isPercentage($input)
    {
        return S::contains($input, '%');
    }
}