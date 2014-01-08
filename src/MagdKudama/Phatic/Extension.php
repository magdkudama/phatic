<?php

namespace MagdKudama\Phatic;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

interface Extension
{
    /**
     * @return void
     */
    function load(array $config, ContainerBuilder $container);

    /**
     * @return void
     */
    function getConfig(ArrayNodeDefinition $builder);
}