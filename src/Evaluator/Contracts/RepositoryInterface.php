<?php namespace Elepunk\Evaluator\Contracts;


interface RepositoryInterface {

    /**
     * Load expressions list
     *
     * @return void
     */
    public function load();

    /**
     * Dump all available expressions
     *
     * @return array
     */
    public function dump();

    /**
     * Retrieve a specific expression
     *
     * @param string $key
     * @return string
     */
    public function get($key);

    /**
     * Add a new expression
     *
     * @param string $key
     * @param string $expression
     * @return void
     */
    public function add($key, $expression);

}
