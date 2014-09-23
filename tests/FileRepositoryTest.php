<?php

use Mockery as m;
use Elepunk\Evaluator\FileRepository;

class FileRepositoryTest extends \PHPUnit_Framework_TestCase {

    public function tearDown()
    {
        m::close();
    }

    public function testLoadAndDumpMethod()
    {
        list($repository, $config) = $this->getRepository();
        $stub = ['foo' => 'bar == baz'];

        $this->assertNull($repository->dump());

        $config->shouldReceive('get')
            ->once()
            ->with('elepunk/evaluator::expressions', [])
            ->andReturn($stub);

        $repository->load();

        $this->assertEquals($stub, $repository->dump());
    }

    public function testGetMethod()
    {
        list($repository, $config) = $this->getRepository();

        $stub = ['foo' => 'bar == baz'];

        $config->shouldReceive('get')
            ->once()
            ->with('elepunk/evaluator::expressions', [])
            ->andReturn($stub);

        $repository->load();

        $this->assertEquals('bar == baz', $repository->get('foo'));
    }

    /**
     * @expectedException \Exception
     */
    public function testGetMethodThrowException()
    {
        list($repository, $config) = $this->getRepository();

        $repository->get('foo');
    }

    public function testAddMethod()
    {
        list($repository, $config) = $this->getRepository();

        $stub = ['foo' => 'bar == baz'];
        $newStub = [
            'foo' => 'bar == baz',
            'foobar' => 'bar > baz'
        ];

        $config->shouldReceive('get')
            ->once()
            ->with('elepunk/evaluator::expressions', [])
            ->andReturn($stub);

        $repository->load();

        $config->shouldReceive('set')
            ->once()
            ->with('elepunk/evaluator::expressions', $newStub);

        $repository->add('foobar', 'bar > baz');

        $this->assertEquals('bar > baz', $repository->get('foobar'));
    }

    protected function getRepository()
    {
        $config = m::mock('\Illuminate\Config\Repository');
        $repository = new FileRepository($config);

        return [$repository, $config];
    }

}
