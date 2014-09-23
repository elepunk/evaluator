<?php namespace Elepunk\Evaluator;

use Elepunk\Evaluator\Contracts\RepositoryInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class Evaluator {

    /**
     * @var ExpressionLanguage
     */
    protected $expressionEngine;

    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * @param ExpressionLanguage $expressionEngine
     * @param RepositoryInterface $repository
     */
    public function __construct(ExpressionLanguage $expressionEngine, RepositoryInterface $repository)
    {
        $this->expressionEngine = $expressionEngine;
        $this->repository = $repository;
    }

    /**
     * @param $expressionKey
     * @param $input
     * @return string
     */
    public function evaluate($expressionKey, $input)
    {
        $expression = $this->repository->get($expressionKey);

        return $this->expressionEngine->evaluate($expression, $input);
    }

    /**
     * @return ExpressionLanguage
     */
    public function expressionEngine()
    {
        return $this->expressionEngine;
    }

} 