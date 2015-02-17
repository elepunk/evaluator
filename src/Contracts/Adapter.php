<?php namespace Elepunk\Evaluator\Contracts;

interface Adapter
{
    /**
     * Add a new expression for evaluation
     * 
     * @param string $key
     * @param array  $expressions
     * @return \Elepunk\Evaluator\Contracts\Adapter
     */
    public function add($key, array $evaluations);

    /**
     * Retrieve an expression
     * 
     * @param  string $key
     * @return \Illuminate\Support\Fluent
     * @throws  \Elepunk\Evaluator\Exceptions\MissingExpressionException
     */
    public function get($key);
}