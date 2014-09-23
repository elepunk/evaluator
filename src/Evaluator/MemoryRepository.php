<?php namespace Elepunk\Evaluator;

use \Exception;
use Illuminate\Support\Arr;
use Elepunk\Evaluator\Contracts\RepositoryInterface;

class MemoryRepository implements RepositoryInterface {

    /**
     * @var \Orchestra\Memory\Provider
     */
    protected $memory;

    /**
     * @var array
     */
    protected $expressions;

    /**
     * Construct new FileRepository instance
     *
     * @param \Orchestra\Memory\Provider $memory
     */
    public function __construct($memory)
    {
        $this->memory = $memory;
    }

    /**
     * {@inheritdoc}
     */
    public function load()
    {
        $expressions = $this->memory->get('elepunk_evaluator_expressions', []);

        if (empty($expressions)) {
            $this->memory->put('elepunk_evaluator_expressions', $expressions);
        }

        $this->expressions = $expressions;
    }

    /**
     * {@inheritdoc}
     */
    public function dump()
    {
        return $this->expressions;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        $expression = Arr::get($this->expressions, $key, null);

        if (is_null($expression)) {
            throw new Exception("Expression ({$key}) is not defined");
        }

        return $expression;
    }

    /**
     * {@inheritdoc}
     */
    public function add($key, $expression)
    {
        Arr::set($this->expressions, $key, $expression);

        $this->memory->put("elepunk_evaluator_expressions", $this->expressions);
    }

}