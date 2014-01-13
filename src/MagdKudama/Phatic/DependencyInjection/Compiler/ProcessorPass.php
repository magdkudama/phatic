<?php

namespace MagdKudama\Phatic\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use ReflectionClass;
use InvalidArgumentException;

class ProcessorPass implements CompilerPassInterface
{
    const CLASS_NAME = 'MagdKudama\Phatic\Processor';

    public function process(ContainerBuilder $container)
    {
        $processorsDefinition = $container->getDefinition('phatic.processors');

        $calls = [];
        foreach ($container->findTaggedServiceIds('phatic.processor') as $id => $attributes) {
            $definition = $container->getDefinition($id);
            $refClass = new ReflectionClass($definition->getClass());
            if (!$refClass->implementsInterface(self::CLASS_NAME)) {
                throw new InvalidArgumentException("Processors must implement class " . self::CLASS_NAME);
            }

            $calls[] = ['add', [new Reference($id)]];
        }

        $processorsDefinition->setMethodCalls($calls);
    }
}