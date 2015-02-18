<?php

use Mockery as m;
use Illuminate\Support\Fluent;
use Elepunk\Evaluator\Adapter\Memory;

class MemoryTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testLoadMethod()
    {
        $stub = [
            'target' => 'foo',
            'rule' => 'foo > bar',
            'action' => '10%'
        ];

        $memory = m::mock('\Orchestra\Contracts\Memory\Provider');
        $adapter = new Memory($memory);

        $this->assertEquals([], $adapter->expressions());

        $memory->shouldReceive('get')
            ->once()
            ->with('elepunk_evaluator', [])
            ->andReturn($stub);

        $this->assertInstanceOf('\Elepunk\Evaluator\Adapter\Memory', $adapter->load());
        $this->assertEquals($stub, $adapter->expressions());
    }

    /**
     * @test
     */
    public function testAddMethod()
    {
        $stub = [
            'target' => 'foo',
            'rule' => 'foo > bar',
            'action' => '10%'
        ];

        $memory = m::mock('\Orchestra\Contracts\Memory\Provider');
        $adapter = new Memory($memory);

        $memory->shouldReceive('put')
            ->once()
            ->with('elepunk_evaluator', ['foo' => new Fluent($stub)]);
        
        $this->assertInstanceOf('\Elepunk\Evaluator\Adapter\Memory', $adapter->add('foo', $stub));
        $this->assertInstanceOf('\Elepunk\Evaluator\Adapter\Memory', $adapter->add('foo', 'foo > bar'));
    }
}