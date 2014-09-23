<?php

use Mockery as m;
use Elepunk\Evaluator\Evaluator;

class EvaluatorTest extends \PHPUnit_Framework_TestCase {

    public function tearDown()
    {
        m::close();
    }

    public function testEvaluateMethod()
    {
        $stub = [
            'foo' => 20,
            'bar' => 10
        ];

        $expression = m::mock('Symfony\Component\ExpressionLanguage\ExpressionLanguage');
        $repository = m::mock('Elepunk\Evaluator\Contracts\RepositoryInterface');

        $evaluator = new Evaluator($expression, $repository);

        $repository->shouldReceive('get')->once()->with('foo')->andReturn($e = 'foo > bar');
        $expression->shouldReceive('evaluate')->once()->with($e, $stub)->andReturn(true);

        $this->assertTrue($evaluator->evaluate('foo', $stub));
    }

    public function testExpressionEngineMethod()
    {
        $expression = m::mock('Symfony\Component\ExpressionLanguage\ExpressionLanguage');
        $repository = m::mock('Elepunk\Evaluator\Contracts\RepositoryInterface');

        $evaluator = new Evaluator($expression, $repository);

        $this->assertInstanceOf('Symfony\Component\ExpressionLanguage\ExpressionLanguage', $evaluator->expressionEngine());
    }

}