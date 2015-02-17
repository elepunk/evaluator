<?php namespace Elepunk\Evaluator\Contracts;

interface Adapter
{
    /**
     * Add a new expression for evaluation
     * 
     * @param string $key
     * @param array  $expressions
     *
     * @return \Elepunk\Evaluator\Contracts\Adapter
     */
    public function add($key, array $evaluations);

    /**
     * Retrieve an expression
     * 
     * @param  [type] $key [description]
     * @return [type]      [description]
     */
    public function get($key);
}