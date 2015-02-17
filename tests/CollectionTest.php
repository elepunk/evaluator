<?php

use Elepunk\Evaluator\Collection;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testSetOriginalValueMethod()
    {
        $stub = new Collection($this->getStub());

        $this->assertInstanceOf('\Elepunk\Evaluator\Collection', $stub->setOriginalValue(100));
    }

    public function testSetCalculatedValueMethod()
    {
        $stub = new Collection($this->getStub());

        $this->assertInstanceOf('\Elepunk\Evaluator\Collection', $stub->setCalculatedValue(200));
    }

    public function testGetOriginalMethod()
    {
        $stub = new Collection($this->getStub());
        $stub->setOriginalValue(100);

        $this->assertEquals(100, $stub->getOriginal());
    }

    public function testGetResultMethod()
    {
        $stub = new Collection($this->getStub());
        $stub->setCalculatedValue(200);

        $this->assertEquals(200, $stub->getResult());
    }

    protected function getStub()
    {
        return [
            'price' => 100
        ];
    }
}