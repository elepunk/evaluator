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

        $memory = m::mock('\Orchestra\Memory\MemoryManager');
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
        $memory = m::mock('\Orchestra\Memory\MemoryManager');
        $adapter = new Memory($memory);

        $memory->shouldReceive('put')
            ->once()
            ->with('elepunk_evaluator', ['foo' => 'foo > bar']);

        $this->assertInstanceOf('\Elepunk\Evaluator\Adapter\Memory', $adapter->add('foo', 'foo > bar'));

        $stub = [
            'target' => 'foo',
            'action' => '10%',
            'rule' => 'foo > 100'
        ];

        $memory->shouldReceive('put')
            ->once()
            ->with('elepunk_evaluator', ['foo' => 'foo > bar', 'bar' => new Fluent($stub)]);

        $this->assertInstanceOf('\Elepunk\Evaluator\Adapter\Memory', $adapter->add('bar', $stub));

        $memory->shouldReceive('put')
            ->once()
            ->with('elepunk_evaluator', ['foo' => 'foo < bar', 'bar' => new Fluent($stub)]);

        $this->assertInstanceOf('\Elepunk\Evaluator\Adapter\Memory', $adapter->add('foo', 'foo < bar'));
    }

    /**
     * @expectedException \Elepunk\Evaluator\Exceptions\MissingKeyException
     */
    public function testAddMethodThrowException()
    {
        $stub = [];

        $memory = m::mock('\Orchestra\Memory\MemoryManager');
        $adapter = new Memory($memory);

        $adapter->add('foo', $stub);
    }

    public function testGetMethod()
    {
        $stub = [
            'target' => 'foo',
            'action' => '10%',
            'rule' => 'foo > 100'
        ];

        $memory = m::mock('\Orchestra\Memory\MemoryManager');
        $adapter = new Memory($memory);

        $memory->shouldReceive('put')
            ->once()
            ->with('elepunk_evaluator', ['foo' => new Fluent($stub)]);

        $adapter->add('foo', $stub);

        $this->assertEquals(new Fluent($stub), $adapter->get('foo'));
    }

    /**
     * @expectedException \Elepunk\Evaluator\Exceptions\MissingExpressionException
     */
    public function testGetMethodThrowException()
    {
        $memory = m::mock('\Orchestra\Memory\MemoryManager');
        $adapter = new Memory($memory);

        $adapter->get('foobar');
    }

    public function testRemoveMethod()
    {
        $stub = [
            'target' => 'foo',
            'rule' => 'foo > bar',
            'action' => '10%'
        ];

        $memory = m::mock('\Orchestra\Memory\MemoryManager');
        $adapter = new Memory($memory);

        $memory->shouldReceive('put')
            ->once()
            ->with('elepunk_evaluator', ['foo' => new Fluent($stub)]);

        $adapter->add('foo', $stub);

        $memory->shouldReceive('put')
            ->once()
            ->with('elepunk_evaluator', ['foo' => new Fluent($stub), 'bar' => new Fluent($stub)]);

        $adapter->add('bar', $stub);

        $this->assertEquals(['foo' => new Fluent($stub), 'bar' => new Fluent($stub)], $adapter->expressions());

        $memory->shouldReceive('put')
            ->once()
            ->with('elepunk_evaluator', ['bar' => new Fluent($stub)]);

        $this->assertInstanceOf('\Elepunk\Evaluator\Adapter\Memory', $adapter->remove('foo'));
        $this->assertEquals(['bar' => new Fluent($stub)], $adapter->expressions());
    }
}