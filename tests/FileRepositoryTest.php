<?php

use Mockery as m;
use Elepunk\Evaluator\FileRepository;

class FileRepositoryTest extends \PHPUnit_Framework_TestCase {

    public function tearDown()
    {
        m::close();
    }

    public function testDumpMethod()
    {
        $stub = [
            'foo' => 'foobar > bar'
        ];

        $repository = new FileRepository($stub);

        $this->assertEquals($stub, $repository->dump());
    }

    public function testGetMethod()
    {
        $stub = [
            'foo' => 'foobar > bar'
        ];

        $repository = new FileRepository($stub);

        $this->assertEquals('foobar > bar', $repository->get('foo'));
    }

    /**
     * @expectedException \Exception
     */
    public function testGetMethodThrowException()
    {
        $stub = [
            'foo' => 'foobar > bar'
        ];

        $repository = new FileRepository($stub);

        $repository->get('foobar');
    }

}
