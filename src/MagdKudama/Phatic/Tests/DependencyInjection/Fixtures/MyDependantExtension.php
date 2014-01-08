<?php

namespace MagdKudama\Phatic\Tests\DependencyInjection\Fixtures;

use MagdKudama\Phatic\Extension;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MyDependantExtension implements Extension
{
    public function load(array $config, ContainerBuilder $container)
    {
    }

    public function getConfig(ArrayNodeDefinition $builder)
    {
    }

    public function getExtensionDependency()
    {
        return 'MagdKudama\Phatic\Tests\DependencyInjection\Fixtures\MyTestExtension';
    }
}