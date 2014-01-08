<?php

namespace MagdKudama\Phatic;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

interface Extension
{
    /**
     * @param array $config
     * @param ContainerBuilder $container
     * @return void
     */
    function load(array $config, ContainerBuilder $container);

    /**
     * @param ArrayNodeDefinition $builder
     * @return void
     */
    function getConfig(ArrayNodeDefinition $builder);

    /**
     * @return string|null
     */
    public function getExtensionDependency();
}