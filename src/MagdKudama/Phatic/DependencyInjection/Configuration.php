<?php

namespace MagdKudama\Phatic\DependencyInjection;

use MagdKudama\Phatic\Collection\ExtensionCollection;
use MagdKudama\Phatic\Extension;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration
{
    public function getConfigTree(ExtensionCollection $extensions)
    {
        $tree = new TreeBuilder();
        $root = $tree->root('config');

        $extensionsNode = $root
            ->children()
                ->arrayNode('extensions')
                ->addDefaultsIfNotSet()
            ->children();

        /** @var Extension $extension */
        foreach ($extensions as $extension) {
            $extensionNode = $extensionsNode->arrayNode(get_class($extension));
            $extension->getConfig($extensionNode);
        }

        return $tree->buildTree();
    }
}