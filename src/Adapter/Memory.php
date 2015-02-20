<?php namespace Elepunk\Evaluator\Adapter;

use Illuminate\Support\Fluent;
use Illuminate\Support\Arr as A;
use Elepunk\Evaluator\Contracts\Adapter;
use Elepunk\Evaluator\Traits\ExpressionCheckerTrait;
use Elepunk\Evaluator\Exceptions\MissingExpressionException;

class Memory implements Adapter
{
    use ExpressionCheckerTrait;

    /**
     * Orchestra Memory component
     * 
     * @var \Orchestra\Memory\MemoryManager
     */
    protected $memory;

    /**
     * Construct new memory adapter
     * 
     * @param \Orchestra\Memory\MemoryManager $memory
     */
    public function __construct($memory)
    {
        $this->memory = $memory;
    }

    /**
     * Load expressions from cache
     * 
     * @return \Elepunk\Evaluator\Adapter\File
     */
    public function load()
    {
        $this->expressions = $this->memory->get('elepunk_evaluator', []);

        return $this;
    }

    /**
     * Reload the expression cache
     * 
     * @return void
     */
    public function reload()
    {
        $this->memory->put('elepunk_evaluator', $this->expressions());
    }

    /**
     * Add a new expression for evaluation
     * 
     * @param string $key
     * @param array  $expressions
     * @return \Elepunk\Evaluator\Contracts\Adapter
     */
    public function add($key, $expression)
    {
        if ( ! is_array($expression)) {
            $this->storeExpression($key, $expression);
        } else {
            $expression = new Fluent($expression);

            if ($this->verifyExpression($expression)) {
                $this->storeExpression($key, $expression);    
            }
        }

        return $this;
    }

    /**
     * Retrieve an expression
     * 
     * @param  string $key
     * @return \Illuminate\Support\Fluent
     * @throws  \Elepunk\Evaluator\Exceptions\MissingExpressionException
     */
    public function get($key)
    {
        $expression = A::get($this->expressions, $key, null);

        if (is_null($expression)) {
            throw new MissingExpressionException("Expression {{$key}} is not defined");
        }

        return $expression;
    }

    /**
     * Remove an expression
     * 
     * @param  string $key
     * @return \Elepunk\Evaluator\Contracts\Adapter
     */
    public function remove($key)
    {
        A::forget($this->expressions, $key);

        $this->reload();

        return $this;
    }

    /**
     * Retreive all available expressions
     * 
     * @return array
     */
    public function expressions()
    {
        return $this->expressions;
    }

    /**
     * Save the expression
     * 
     * @param  string $key
     * @param  mixed $expression
     * @return void
     */
    protected function storeExpression($key, $expression)
    {
        if (A::has($this->expressions, $key)) {
            $this->expressions = A::set($this->expressions, $key, $expression);
        } else {
            $this->expressions = A::add($this->expressions, $key, $expression);
        }

        $this->reload();
    }
}