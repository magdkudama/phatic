<?php

namespace MagdKudama\Phatic\Tests\DependencyInjection\Compiler;

use MagdKudama\Phatic\DependencyInjection\Compiler\CommandPass;
use Mockery as m;
use MagdKudama\Phatic\Tests\TestCase;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class CommandPassTest extends TestCase
{
    protected $builder;

    /**
     * @var CompilerPassInterface
     */
    protected $pass;

    public function setUp()
    {
        $this->builder = m::mock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $this->pass = new CommandPass();

        $this->builder
            ->shouldReceive('getDefinition')
            ->with('phatic.commands')
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
            ->with('phatic.command')
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
            ->with('phatic.command')
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
            ->with('phatic.command')
            ->andReturn(['service_1' => []])
            ->once();

        $def = new Definition();
        $def->setClass('MagdKudama\Phatic\Tests\DependencyInjection\Compiler\Fixtures\CommandExtension');

        $this->builder
            ->shouldReceive('getDefinition')
            ->with("service_1")
            ->andReturn($def)
            ->once();

        $this->pass->process($this->builder);
    }
}