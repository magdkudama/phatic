<?php

namespace MagdKudama\Phatic\Tests\DependencyInjection\Compiler;

use MagdKudama\Phatic\DependencyInjection\Compiler\ProcessorPass;
use MagdKudama\Phatic\Tests\TestCase;
use Mockery as m;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use InvalidArgumentException;
use Symfony\Component\DependencyInjection\Definition;

class ProcessorPassTest extends TestCase
{
    protected $builder;

    /** @var CompilerPassInterface */
    protected $pass;

    public function setUp()
    {
        $this->builder = m::mock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $this->pass = new ProcessorPass();

        $this->builder
            ->shouldReceive('getDefinition')
            ->with('phatic.processors')
            ->andReturn(new Definition())
            ->once();

        $this->builder
            ->shouldReceive('getDefinition')
            ->with('phatic.finder')
            ->andReturn(new Definition())
            ->once();

        $this->builder
            ->shouldReceive('getDefinition')
            ->with('phatic.twig')
            ->andReturn(new Definition())
            ->once();

        $this->builder
            ->shouldReceive('getDefinition')
            ->with('phatic.filesystem')
            ->andReturn(new Definition())
            ->once();

        $this->builder
            ->shouldReceive('getDefinition')
            ->with('phatic.dispatcher')
            ->andReturn(new Definition())
            ->once();

        $this->builder
            ->shouldReceive('getDefinition')
            ->with('phatic.config')
            ->andReturn(new Definition())
            ->once();
    }

    public function tearDown()
    {
        m::close();
    }

    public function testEmptyTaggedServicesWorks()
    {
        $this->builder
            ->shouldReceive('findTaggedServiceIds')
            ->with('phatic.processor')
            ->andReturn([])
            ->once();

        $this->pass->process($this->builder);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNotExtendingClassThrowsException()
    {
        $this->builder
            ->shouldReceive('findTaggedServiceIds')
            ->with('phatic.processor')
            ->andReturn(['service_1' => []])
            ->once();

        $def = new Definition();
        $def->setClass('MagdKudama\Phatic\Tests\DependencyInjection\Compiler\Fixtures\FakeClass');

        $this->builder
            ->shouldReceive('getDefinition')
            ->with("service_1")
            ->andReturn($def)
            ->once();

        $this->pass->process($this->builder);
    }

    public function testExtendingClassWorks()
    {
        $this->builder
            ->shouldReceive('findTaggedServiceIds')
            ->with('phatic.processor')
            ->andReturn(['service_1' => []])
            ->once();

        $def = new Definition();
        $def->setClass('MagdKudama\Phatic\Tests\DependencyInjection\Compiler\Fixtures\ProcessorExtension');

        $this->builder
            ->shouldReceive('getDefinition')
            ->with("service_1")
            ->andReturn($def)
            ->once();

        $this->pass->process($this->builder);
    }
}