<?php

use Mockery as m;
use Elepunk\Evaluator\Evaluator;

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

    protected function getMockDependencies()
    {
        $expression = m::mock('\Symfony\Component\ExpressionLanguage\ExpressionLanguage');
        $adapter = m::mock('\Elepunk\Evaluator\Contracts\Adapter');

        return [$expression, $adapter];
    }
}