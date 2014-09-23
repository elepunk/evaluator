<?php namespace Elepunk\Evaluator;

use \Exception;
use Illuminate\Support\Arr;
use Elepunk\Evaluator\Contracts\RepositoryInterface;

class FileRepository implements RepositoryInterface {

    /**
     * @var
     */
    protected $config;

    /**
     * @var
     */
    protected $expressions = [];

    /**
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function load()
    {
        $expressions = $this->config->get('elepunk/evaluator::expressions', []);

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
    public function put($key, $expression)
    {
        $this->config->set("elepunk/evaluator::expressions.{$key}", $expression);

        $this->load();
    }

}
