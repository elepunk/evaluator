<?php

use Mockery as m;
use Illuminate\Support\Fluent;
use Elepunk\Evaluator\Adapter\File;

class FileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
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

        $cache = m::mock('Illuminate\Contracts\Cache\Repository');
        $adapter = new File($cache);

        $this->assertEquals([], $adapter->expressions());

        $cache->shouldReceive('get')
            ->once()
            ->with('elepunk.evaluator', [])
            ->andReturn($stub);

        $this->assertInstanceOf('\Elepunk\Evaluator\Adapter\File', $adapter->load());
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

        $cache = m::mock('Illuminate\Contracts\Cache\Repository');
        $adapter = new File($cache);

        $cache->shouldReceive('forever')
            ->once()
            ->with('elepunk.evaluator', ['foo' => new Fluent($stub)]);

        $this->assertInstanceOf('\Elepunk\Evaluator\Adapter\File', $adapter->add('foo', $stub));
        $this->assertInstanceOf('\Elepunk\Evaluator\Adapter\File', $adapter->add('foo', 'foo > bar'));
    }

    /**
     * @test
     */
    public function testGetMethod()
    {
        $stub = [
            'target' => 'foo',
            'rule' => 'foo > bar',
            'action' => '10%'
        ];

        $expected = new Fluent($stub);

        $cache = m::mock('Illuminate\Contracts\Cache\Repository');
        $adapter = new File($cache);

        $cache->shouldReceive('forever')
            ->once()
            ->with('elepunk.evaluator', ['foo' => new Fluent($stub)]);

        $adapter->add('foo', $stub);

        $this->assertEquals($expected, $adapter->get('foo'));
    }

    /**
     * @expectedException \Elepunk\Evaluator\Exceptions\MissingKeyException
     */
    public function testAddMethodThrowException()
    {
        $stub = [];

        $cache = m::mock('Illuminate\Contracts\Cache\Repository');
        $adapter = new File($cache);

        $adapter->add('foo', $stub);
    }

    /**
     * @expectedException \Elepunk\Evaluator\Exceptions\MissingExpressionException
     */
    public function testGetMethodThrowException()
    {
        $cache = m::mock('Illuminate\Contracts\Cache\Repository');
        $adapter = new File($cache);

        $adapter->get('bar');
    }

    public function testRemoveMethod()
    {
        $stub = [
            'target' => 'foo',
            'rule' => 'foo > bar',
            'action' => '10%'
        ];

        $cache = m::mock('Illuminate\Contracts\Cache\Repository');
        $adapter = new File($cache);

        $cache->shouldReceive('forever')
            ->once()
            ->with('elepunk.evaluator', ['foo' => new Fluent($stub)]);

        $adapter->add('foo', $stub);

        $cache->shouldReceive('forever')
            ->once()
            ->with('elepunk.evaluator', ['foo' => new Fluent($stub), 'bar' => new Fluent($stub)]);

        $adapter->add('bar', $stub);

        $cache->shouldReceive('forever')
            ->twice()
            ->with('elepunk.evaluator', ['bar' => new Fluent($stub)]);

        $adapter->remove('foo');

        $this->assertInstanceOf('\Elepunk\Evaluator\Adapter\File', $adapter->remove('foo'));
        $this->assertEquals(['bar' => new Fluent($stub)], $adapter->expressions());
    }

    /**
     * @test
     */
    public function testExpressionsMethod()
    {
        $stub = [
            'target' => 'foo',
            'rule' => 'foo > bar',
            'action' => '10%'
        ];

        $cache = m::mock('Illuminate\Contracts\Cache\Repository');
        $adapter = new File($cache);

        $this->assertEquals([], $adapter->expressions());

        $cache->shouldReceive('forever')
            ->once()
            ->with('elepunk.evaluator', ['foo' => new Fluent($stub)]);
        
        $adapter->add('foo', $stub);

        $this->assertEquals(['foo' => new Fluent($stub)], $adapter->expressions());
    }
}