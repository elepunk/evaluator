<?php namespace Elepunk\Evaluator\Contracts;

interface AdapterInterface
{
    /**
     * Load expressions from cache
     * 
     * @return \Elepunk\Evaluator\Contracts\AdapterInterface
     */
    public function load();

    /**
     * Reload the expression cache
     * 
     * @return void
     */
    public function reload();

    /**
     * Add a new expression for evaluation
     * 
     * @param string $key
     * @param array  $expressions
     * @return \Elepunk\Evaluator\Contracts\AdapterInterface
     */
    public function add($key, $evaluations);

    /**
     * Retrieve an expression
     * 
     * @param  string $key
     * @return \Illuminate\Support\Fluent
     * @throws  \Elepunk\Evaluator\Exceptions\MissingExpressionException
     */
    public function get($key);

    /**
     * Remove an expression
     * 
     * @param  string $key
     * @return \Elepunk\Evaluator\Contracts\AdapterInterface
     */
    public function remove($key);

    /**
     * Retreive all available expressions
     * 
     * @return array
     */
    public function expressions();
}
