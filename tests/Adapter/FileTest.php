<?php

use Mockery as m;
use Illuminate\Support\Fluent;
use Elepunk\Evaluator\Adapter\File;

class FileTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testAddMethod()
    {
        $stub = [
            'target' => 'foo',
            'rule' => 'foo > bar',
            'action' => '10%'
        ];

        $adapter = new File();

        $this->assertInstanceOf('\Elepunk\Evaluator\Adapter\File', $adapter->add('foo', $stub));
    }

    public function testGetMethod()
    {
        $stub = [
            'target' => 'foo',
            'rule' => 'foo > bar',
            'action' => '10%'
        ];

        $expected = new Fluent($stub);

        $adapter = new File();

        $adapter->add('foo', $stub);

        $this->assertEquals($expected, $adapter->get('foo'));
    }
}