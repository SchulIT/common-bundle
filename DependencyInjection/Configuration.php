<?php

namespace SchoolIT\CommonBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface {

    public function getConfigTreeBuilder() {
        $builder = new TreeBuilder();
        $root = $builder->root('common');

        $root->children()
            ->arrayNode('app')
                ->children()
                    ->scalarNode('url')
                        ->isRequired()
                    ->end()
                    ->scalarNode('name')
                        ->isRequired()
                    ->end()
                    ->scalarNode('version')
                        ->isRequired()
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
            ->arrayNode('disable')
                ->children()
                    ->booleanNode('saml')->defaultFalse()->end()
                    ->booleanNode('mail')->defaultFalse()->end()
                ->end()
            ->end()
        ->end();

        return $builder;
    }
}