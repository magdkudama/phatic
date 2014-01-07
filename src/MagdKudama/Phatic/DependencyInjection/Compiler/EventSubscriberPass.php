<?php

namespace MagdKudama\Phatic\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use ReflectionClass;
use InvalidArgumentException;

class EventSubscriberPass implements CompilerPassInterface
{
    const CLASS_NAME = 'Symfony\Component\EventDispatcher\EventSubscriberInterface';

    public function process(ContainerBuilder $container)
    {
        $dispatcherDefinition = $container->getDefinition('phatic.dispatcher');

        foreach ($container->findTaggedServiceIds('phatic.subscriber') as $id => $attributes) {
            $refClass = new ReflectionClass($container->getDefinition($id)->getClass());
            if (!$refClass->implementsInterface(self::CLASS_NAME)) {
                throw new InvalidArgumentException("Subscriber classes must implement class " . self::CLASS_NAME);
            }

            $dispatcherDefinition->addMethodCall('addSubscriber', [new Reference($id)]);
        }
    }
}