<?php namespace Elepunk\Evaluator\Adapter;

use Illuminate\Support\Fluent;
use Illuminate\Support\Arr as A;
use Elepunk\Evaluator\Contracts\Adapter;
use Elepunk\Evaluator\Traits\ExpressionCheckerTrait;
use Elepunk\Evaluator\Exceptions\MissingExpressionException;

class File implements Adapter
{
    use ExpressionCheckerTrait;

    /**
     * {@inheritdoc}
     */
    public function add($key, array $expressions)
    {
        $expression = new Fluent($expressions);

        if ($this->verifyExpression($expression)) {
            $this->expressions = A::add($this->expressions, $key, $expression);    
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        $expression = A::get($this->expressions, $key, null);

        if (is_null($expression)) {
            throw new MissingExpressionException("Expression {{$key}} is not defined");
        }

        return $expression;
    }
}