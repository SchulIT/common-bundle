<?php

namespace SchoolIT\CommonBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface {

    public function getConfigTreeBuilder() {
        $builder = new TreeBuilder();
        $root = $builder->root('common_bundle');

        $root->children()
            ->arrayNode('app')
                ->children()
                    ->scalarNode('name')
                        ->isRequired()
                    ->end()
                    ->scalarNode('version')
                        ->isRequired()
                    ->end()
                    ->scalarNode('host')
                        ->isRequired()
                    ->end()
                    ->scalarNode('path')
                        ->isRequired()
                    ->end()
                    ->booleanNode('ssl')
                        ->defaultValue(true)
                    ->end()
                    ->scalarNode('logo')
                        ->defaultValue(null)
                    ->end()
                ->end()
            ->end()
            ->arrayNode('locales')
                ->prototype('scalar')->end()
            ->end()
            ->scalarNode('menu')
                ->defaultValue('App:Builder:mainMenu')
            ->end()
        ->end();

        return $builder;
    }
}