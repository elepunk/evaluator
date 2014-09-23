<?php

use Mockery as m;
use Elepunk\Evaluator\MemoryRepository;

class MemoryRepositoryTest extends \PHPUnit_Framework_TestCase {

    public function tearDown()
    {
        m::close();
    }

    public function testLoadMethod()
    {
        list($repository, $memory) = $this->getRepository();
        $stub = ['foo' => 'bar == baz'];

        $memory->shouldReceive('get')
            ->once()
            ->with('elepunk_evaluator_expressions', [])
            ->andReturn([]);

        $memory->shouldReceive('put')
            ->once()
            ->with('elepunk_evaluator_expressions', []);

        $repository->load();

        $this->assertEquals([], $repository->dump());
    }

    public function testLoadAndDumpMethod()
    {
        list($repository, $memory) = $this->getRepository();
        $stub = ['foo' => 'bar == baz'];

        $this->assertNull($repository->dump());

        $memory->shouldReceive('get')
            ->once()
            ->with('elepunk_evaluator_expressions', [])
            ->andReturn($stub);

        $repository->load();

        $this->assertEquals($stub, $repository->dump());
    }

    public function testGetMethod()
    {
        list($repository, $memory) = $this->getRepository();

        $stub = ['foo' => 'bar == baz'];

        $memory->shouldReceive('get')
            ->once()
            ->with('elepunk_evaluator_expressions', [])
            ->andReturn($stub);

        $repository->load();

        $this->assertEquals('bar == baz', $repository->get('foo'));
    }

    /**
     * @expectedException \Exception
     */
    public function testGetMethodThrowException()
    {
        list($repository, $memory) = $this->getRepository();

        $repository->get('foo');
    }

    public function testAddMethod()
    {
        list($repository, $memory) = $this->getRepository();

        $stub = ['foo' => 'bar == baz'];
        $newStub = [
            'foo' => 'bar == baz',
            'foobar' => 'bar > baz'
        ];

        $memory->shouldReceive('get')
            ->once()
            ->with('elepunk_evaluator_expressions', [])
            ->andReturn($stub);

        $repository->load();

        $memory->shouldReceive('put')
            ->once()
            ->with('elepunk_evaluator_expressions', $newStub);

        $repository->add('foobar', 'bar > baz');

        $this->assertEquals('bar > baz', $repository->get('foobar'));
    }

    protected function getRepository()
    {
        $memory = m::mock('\Orchestra\Memory\Provider');
        $repository = new MemoryRepository($memory);

        return [$repository, $memory];
    }

}
