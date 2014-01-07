<?php

namespace MagdKudama\Phatic\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use ReflectionClass;
use InvalidArgumentException;

class ViewExtensionPass implements CompilerPassInterface
{
    const CLASS_NAME = 'Twig_Extension';

    public function process(ContainerBuilder $container)
    {
        $twigDefinition = $container->getDefinition('phatic.twig');

        foreach ($container->findTaggedServiceIds('phatic.view') as $id => $attributes) {
            $refClass = new ReflectionClass($container->getDefinition($id)->getClass());
            if (!$refClass->getParentClass() || $refClass->getParentClass()->getName() !== self::CLASS_NAME) {
                throw new InvalidArgumentException("View extensions must extend class " . self::CLASS_NAME);
            }

            $twigDefinition->addMethodCall('addExtension', [new Reference($id)]);
        }
    }
}