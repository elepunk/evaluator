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
            'bar' => 20,
            'baz' => 10
        ];

        list($expression, $repository) = $this->getMocks();
        $evaluator = new Evaluator($expression, $repository);

        $repository->shouldReceive('get')
            ->once()
            ->with('foo')
            ->andReturn($e = 'bar > baz');

        $expression->shouldReceive('evaluate')
            ->once()
            ->with($e, $stub)
            ->andReturn(true);

        $this->assertTrue($evaluator->evaluate('foo', $stub));
    }

    /**
     * @expectedException \LogicException
     */
    public function testIfTrueMethodThrowException()
    {
        list($expression, $repository) = $this->getMocks();
        $evaluator = new Evaluator($expression, $repository);

        $stub = [
            'bar' => 20,
            'baz' => 10
        ];

        $repository->shouldReceive('get')
            ->once()
            ->with('foo')
            ->andReturn($e = 'bar + baz');

        $expression->shouldReceive('evaluate')
            ->once()
            ->with($e, $stub)
            ->andReturn(30);

        $callback = function ($s) {
            return $s;
        };

        $evaluator->ifTrue('foo', $stub, $callback);
    }

    public function testIfTrueMethod()
    {
        list($expression, $repository) = $this->getMocks();
        $evaluator = new Evaluator($expression, $repository);

        $stub = [
            'bar' => 20,
            'baz' => 10
        ];

        $repository->shouldReceive('get')
            ->once()
            ->with('foo')
            ->andReturn($e = 'bar > baz');

        $expression->shouldReceive('evaluate')
            ->once()
            ->with($e, $stub)
            ->andReturn(true);

        $callback = function ($s) {
            return $s;
        };

        $this->assertEquals($stub, $evaluator->ifTrue('foo', $stub, $callback));

        $repository->shouldReceive('get')
            ->once()
            ->with('foobar')
            ->andReturn($e = 'bar < baz');

        $expression->shouldReceive('evaluate')
            ->once()
            ->with($e, $stub)
            ->andReturn(false);

        $this->assertFalse($evaluator->ifTrue('foobar', $stub, $callback));
    }

    public function testExpressionEngineMethod()
    {
        list($expression, $repository) = $this->getMocks();
        $evaluator = new Evaluator($expression, $repository);

        $this->assertInstanceOf('Symfony\Component\ExpressionLanguage\ExpressionLanguage', $evaluator->expressionEngine());
    }

    public function testRepositoryMethod()
    {
        list($expression, $repository) = $this->getMocks();
        $evaluator = new Evaluator($expression, $repository);

        $this->assertInstanceOf('Elepunk\Evaluator\Contracts\RepositoryInterface', $evaluator->repository());
    }

    protected function getMocks()
    {
        $expression = m::mock('\Symfony\Component\ExpressionLanguage\ExpressionLanguage');
        $repository = m::mock('\Elepunk\Evaluator\Contracts\RepositoryInterface');

        return [$expression, $repository];
    }

}
