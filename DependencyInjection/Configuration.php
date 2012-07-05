<?php

namespace Msi\Bundle\PageBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('msi_page');

        $rootNode
            ->children()
                ->arrayNode('template_choices')
                ->useAttributeAsKey('name')
                ->prototype('scalar')->end()
            ->end()
                ->arrayNode('layout_choices')
                ->useAttributeAsKey('name')
                ->prototype('scalar')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
