<?php namespace Elepunk\Evaluator\Traits;

use Illuminate\Support\Fluent;
use Elepunk\Evaluator\Exceptions\MissingKeyException;

trait ExpressionCheckerTrait
{
    /**
     * Available expressions
     *
     * @var array
     */
    protected $expressions = [];

    /**
     * Reserved keys for an expression
     *
     * @var array
     */
    protected $reservedKeys = ['target', 'action'];

    /**
     * Vaildate if expression contains the reserve keys
     *
     * @param  array $expression
     * @return boolean
     * @throws  \Elepunk\Evaluator\Exceptions\MissingKeyException
     */
    protected function verifyExpression(Fluent $expression)
    {
        foreach ($this->reservedKeys as $key) {
            if (is_null($expression->get($key))) {
                throw new MissingKeyException("Expression is missing {$key}");
            }
        }

        return true;
    }
}
