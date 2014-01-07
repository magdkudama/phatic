<?php

namespace MagdKudama\Phatic;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

interface Extension
{
    function load(array $config, ContainerBuilder $container);

    function getConfig(ArrayNodeDefinition $builder);
}