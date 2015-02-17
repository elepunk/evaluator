<?php namespace Elepunk\Evaluator\Traits;

trait ExpressionCheckerTrait
{
    /**
     * [$expressions description]
     * @var [type]
     */
    protected $expressions = [];

    /**
     * [$reservedKeys description]
     * @var [type]
     */
    protected $reservedKeys = ['target', 'action'];

    /**
     * [verifyExpression description]
     * @param  [type] $expression [description]
     * @return [type]             [description]
     */
    protected function verifyExpression($expression)
    {
        foreach ($this->reservedKeys as $key) {
            if (is_null($expression->get($key))) {
                throw new \Exception("Expression is missing {$key}");
            }
        }

        return true;
    }
}