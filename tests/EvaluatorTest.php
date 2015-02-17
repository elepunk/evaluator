<?php

use Mockery as m;
use Illuminate\Support\Fluent;
use Elepunk\Evaluator\Evaluator;
use Elepunk\Evaluator\Collection;

class EvaluatorTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testGetExpressionEngineMethod()
    {
        list($expression, $adapter) = $this->getMockDependencies();

        $evaluator = new Evaluator($expression, $adapter);

        $this->assertInstanceOf('\Symfony\Component\ExpressionLanguage\ExpressionLanguage', $evaluator->getExpressionEngine());
    }

    public function testExpressionMethod()
    {
        list($expression, $adapter) = $this->getMockDependencies();

        $evaluator = new Evaluator($expression, $adapter);

        $this->assertInstanceOf('\Elepunk\Evaluator\Contracts\Adapter', $evaluator->expression());
    }

    public function testEvaluateMethod()
    {
        list($expression, $adapter) = $this->getMockDependencies();

        $evaluator = new Evaluator($expression, $adapter);

        $stub = [
            'foo' => 20,
            'bar' => 10
        ];

        $expression->shouldReceive('evaluate')
            ->once()
            ->with('foo > bar', $stub)
            ->andReturn(true);

        $this->assertTrue($evaluator->evaluate('foo > bar', $stub));
    }

    public function testEvaluateRuleMethod()
    {
        list($expression, $adapter) = $this->getMockDependencies();

        $evaluator = new Evaluator($expression, $adapter);

        $stub = [
            'foo' => 20,
            'bar' => 10
        ];

        $adapter->shouldReceive('add')
            ->once()
            ->with('foo', 'foo > bar')
            ->andReturn($adapter);

        $evaluator->expression()->add('foo', 'foo > bar');

        $adapter->shouldReceive('get')
            ->once()
            ->with('foo')
            ->andReturn('foo > bar');

        $expression->shouldReceive('evaluate')
            ->once()
            ->with('foo > bar', $stub)
            ->andReturn(true);

        $this->assertTrue($evaluator->evaluateRule('foo', $stub));
    }

    public function testConditionWithoutRuleMethod()
    {
        list($expression, $adapter) = $this->getMockDependencies();

        $evaluator = new Evaluator($expression, $adapter);

        $stub = [
            'target' => 'price',
            'action' => '10%',
        ];

        $item = [
            'name' => 'Foobar',
            'price' => '100'
        ];

        $expected = new Collection(['name' => 'Foobar', 'price' => '110']);
        $expected->setOriginalValue(100);
        $expected->setCalculatedValue(110);

        $adapter->shouldReceive('add')
            ->once()
            ->with('foo', $stub)
            ->andReturn($adapter);

        $evaluator->expression()->add('foo', $stub);

        $adapter->shouldReceive('get')
            ->once()
            ->with('foo')
            ->andReturn(new Fluent($stub));

        $expression->shouldReceive('evaluate')
            ->once()
            ->with('(value/100)*target', ['target' => 100, 'value' => '10'])
            ->andReturn(10);

        $expression->shouldReceive('evaluate')
            ->once()
            ->with('first + second', ['first' => 100, 'second' => '10'])
            ->andReturn(110);

        $this->assertEquals($expected, $evaluator->condition('foo', $item));
    }

    public function testConditionWithRuleMethod()
    {
        list($expression, $adapter) = $this->getMockDependencies();

        $evaluator = new Evaluator($expression, $adapter);

        $stub = [
            'target' => 'price',
            'action' => '-10%',
            'rule' => 'price > 50'
        ];

        $item = [
            'name' => 'Foobar',
            'price' => '100'
        ];

        $falseItem = [
            'name' => 'Foo',
            'price' => '40'
        ];

        $expected = new Collection(['name' => 'Foobar', 'price' => '90']);
        $expected->setOriginalValue(100);
        $expected->setCalculatedValue(90);

        $adapter->shouldReceive('add')
            ->once()
            ->with('foo', $stub)
            ->andReturn($adapter);

        $evaluator->expression()->add('foo', $stub);

        $adapter->shouldReceive('get')
            ->twice()
            ->with('foo')
            ->andReturn(new Fluent($stub));

        $expression->shouldReceive('evaluate')
            ->once()
            ->with('price > 50', $item)
            ->andReturn(true);

        $expression->shouldReceive('evaluate')
            ->once()
            ->with('(value/100)*target', ['target' => 100, 'value' => '10'])
            ->andReturn(10);

        $expression->shouldReceive('evaluate')
            ->once()
            ->with('first - second', ['first' => 100, 'second' => '10'])
            ->andReturn(90);

        $this->assertEquals($expected, $evaluator->condition('foo', $item));

         $expression->shouldReceive('evaluate')
            ->once()
            ->with('price > 50', $falseItem)
            ->andReturn(false);

        $this->assertEquals(new Collection($falseItem), $evaluator->condition('foo', $falseItem));
    }

    public function testConditionWithMultiplierMethod()
    {
        list($expression, $adapter) = $this->getMockDependencies();

        $evaluator = new Evaluator($expression, $adapter);

        $stub = [
            'target' => 'price',
            'action' => '10%',
            'rule' => 'price > 50',
            'multiplier' => 'quantity'
        ];

        $item = [
            'name' => 'Foobar',
            'price' => '100',
            'quantity' => 2
        ];

        $expected = [
            'name' => 'Foobar',
            'price' => '220',
            'quantity' => 2
        ];

        $expected = new Collection(['name' => 'Foobar', 'price' => '220', 'quantity' => 2]);
        $expected->setOriginalValue(100);
        $expected->setCalculatedValue(220);

        $adapter->shouldReceive('add')
            ->once()
            ->with('foo', $stub)
            ->andReturn($adapter);

        $evaluator->expression()->add('foo', $stub);

        $adapter->shouldReceive('get')
            ->once()
            ->with('foo')
            ->andReturn(new Fluent($stub));

        $expression->shouldReceive('evaluate')
            ->once()
            ->with('price > 50', $item)
            ->andReturn(true);

        $expression->shouldReceive('evaluate')
            ->once()
            ->with('value * multiplier', ['value' => 100, 'multiplier' => '2'])
            ->andReturn(200);

        $expression->shouldReceive('evaluate')
            ->once()
            ->with('(value/100)*target', ['target' => 200, 'value' => '10'])
            ->andReturn(20);

        $expression->shouldReceive('evaluate')
            ->once()
            ->with('first + second', ['first' => 200, 'second' => '20'])
            ->andReturn(220);

        $this->assertEquals($expected, $evaluator->condition('foo', $item));
    }

    public function testConditionWithCallbackMethod()
    {
        list($expression, $adapter) = $this->getMockDependencies();

        $evaluator = new Evaluator($expression, $adapter);

        $stub = [
            'target' => 'price',
            'action' => '10%',
        ];

        $item = [
            'name' => 'Foobar',
            'price' => '100'
        ];
        $expected = new Collection(['name' => 'Foobar', 'price' => '110']);
        $expected->setOriginalValue(100);
        $expected->setCalculatedValue(110);

        $adapter->shouldReceive('add')
            ->once()
            ->with('foo', $stub)
            ->andReturn($adapter);

        $evaluator->expression()->add('foo', $stub);

        $adapter->shouldReceive('get')
            ->once()
            ->with('foo')
            ->andReturn(new Fluent($stub));

        $expression->shouldReceive('evaluate')
            ->once()
            ->with('(value/100)*target', ['target' => 100, 'value' => '10'])
            ->andReturn(10);

        $expression->shouldReceive('evaluate')
            ->once()
            ->with('first + second', ['first' => 100, 'second' => '10'])
            ->andReturn(110);

        $callback = function ($s) {
            return $s;
        };

        $this->assertEquals($expected, $evaluator->condition('foo', $item, $callback));
    }

    protected function getMockDependencies()
    {
        $expression = m::mock('\Symfony\Component\ExpressionLanguage\ExpressionLanguage');
        $adapter = m::mock('\Elepunk\Evaluator\Contracts\Adapter');

        return [$expression, $adapter];
    }
}