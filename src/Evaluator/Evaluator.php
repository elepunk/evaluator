<?php namespace Elepunk\Evaluator;

use \Closure;
use \LogicException;
use Elepunk\Evaluator\Contracts\RepositoryInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class Evaluator {

    /**
     * @var \Symfony\Component\ExpressionLanguage\ExpressionLanguage
     */
    protected $expressionEngine;

    /**
     * @var \Elepunk\Evaluator\Contracts\RepositoryInterface
     */
    protected $repository;

    /**
     * Construct new Evaluator instance
     *
     * @param \Symfony\Component\ExpressionLanguage\ExpressionLanguage $expressionEngine
     * @param \Elepunk\Evaluator\Contracts\RepositoryInterface $repository
     */
    public function __construct(ExpressionLanguage $expressionEngine, RepositoryInterface $repository)
    {
        $this->expressionEngine = $expressionEngine;
        $this->repository = $repository;
    }

    /**
     * Evaluate the expression
     *
     * @param string $expressionKey
     * @param mixed $input
     * @return bool|integer
     */
    public function evaluate($expressionKey, $input)
    {
        $expression = $this->repository->get($expressionKey);

        return $this->expressionEngine->evaluate($expression, $input);
    }

    /**
     * Run action if passes evaluation
     *
     * @param string $expressionKey
     * @param string $input
     * @param callable $callback
     * @return mixed
     * @throws \LogicException
     */
    public function ifTrue($expressionKey, $input, Closure $callback)
    {
        $evaluate = $this->evaluate($expressionKey, $input);

        if ( ! is_bool($evaluate)) {
            throw new LogicException("Expression should only return true or false");
        }

        if ($evaluate) {
            return call_user_func($callback, $input);
        }

        return $evaluate;
    }

    /**
     * Get ExpressionLanguage instance
     *
     * @return \Symfony\Component\ExpressionLanguage\ExpressionLanguage
     */
    public function expressionEngine()
    {
        return $this->expressionEngine;
    }

    /**
     * Get RepositoryInterface instance
     *
     * @return \Elepunk\Evaluator\Contracts\RepositoryInterface
     */
    public function repository()
    {
        return $this->repository;
    }

}
