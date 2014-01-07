<?php

namespace MagdKudama\Phatic\Tests\DependencyInjection\Fixtures;

use MagdKudama\Phatic\Extension;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MyTestExtension implements Extension
{
    public function load(array $config, ContainerBuilder $container)
    {
        $container->setParameter('phatic.mytest.param', $config['param']);
    }

    public function getConfig(ArrayNodeDefinition $builder)
    {
        $builder->
            children()
                ->scalarNode('param')->end()
            ->end();
    }
}