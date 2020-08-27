<?php

namespace Matok\Bundle\MediaBundle\DependencyInjection;

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
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('matok_media');
        $treeBuilder
            ->getRootNode()
                ->children()
                    ->scalarNode('base_dir')->cannotBeEmpty()->isRequired()
                    ->end()
                    ->arrayNode('configs')
                        ->prototype('array')
                            ->children()
                                ->scalarNode('directory')->end()
                                ->scalarNode('allow_original_size')->defaultTrue()->end()
                                ->arrayNode('thumbnails')
                                    ->prototype('scalar')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ;

        return $treeBuilder;
    }



}
