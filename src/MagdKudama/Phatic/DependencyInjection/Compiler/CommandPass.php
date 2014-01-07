<?php

namespace MagdKudama\Phatic\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use ReflectionClass;
use InvalidArgumentException;

class CommandPass implements CompilerPassInterface
{
    const CLASS_NAME = 'MagdKudama\Phatic\Console\Command\ContainerAwareCommand';

    public function process(ContainerBuilder $container)
    {
        $commandsDefinition = $container->getDefinition('phatic.commands');

        $calls = [];
        foreach ($container->findTaggedServiceIds('phatic.command') as $id => $attributes) {
            $refClass = new ReflectionClass($container->getDefinition($id)->getClass());
            if (!$refClass->getParentClass() || $refClass->getParentClass()->getName() !== self::CLASS_NAME) {
                throw new InvalidArgumentException("Commands must extend class " . self::CLASS_NAME);
            }

            $calls[] = ['add', [new Reference($id)]];
        }

        $commandsDefinition->setMethodCalls($calls);
    }
}