<?php namespace Elepunk\Evaluator;

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
