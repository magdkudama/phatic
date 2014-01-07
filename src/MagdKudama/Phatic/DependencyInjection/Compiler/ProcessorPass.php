<?php

namespace MagdKudama\Phatic\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use ReflectionClass;
use InvalidArgumentException;

class ProcessorPass implements CompilerPassInterface
{
    const CLASS_NAME = 'MagdKudama\Phatic\AbstractProcessor';

    public function process(ContainerBuilder $container)
    {
        $processorsDefinition = $container->getDefinition('phatic.processors');

        $finder = $container->getDefinition('phatic.finder');
        $view = $container->getDefinition('phatic.twig');
        $filesystem = $container->getDefinition('phatic.filesystem');
        $dispatcher = $container->getDefinition('phatic.dispatcher');
        $appConfig = $container->getDefinition('phatic.config');

        $calls = [];
        foreach ($container->findTaggedServiceIds('phatic.processor') as $id => $attributes) {
            $definition = $container->getDefinition($id);
            $refClass = new ReflectionClass($definition->getClass());
            if (!$refClass->getParentClass() || $refClass->getParentClass()->getName() !== self::CLASS_NAME) {
                throw new InvalidArgumentException("Processors must extend class " . self::CLASS_NAME);
            }

            $calls[] = ['add', [new Reference($id)]];
            $definition->addArgument($finder);
            $definition->addArgument($filesystem);
            $definition->addArgument($view);
            $definition->addArgument($dispatcher);
            $definition->addArgument($appConfig);
        }

        $processorsDefinition->setMethodCalls($calls);
    }
}