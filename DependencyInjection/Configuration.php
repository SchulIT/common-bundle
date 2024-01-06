<?php

namespace SchulIT\CommonBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface {

    public function getConfigTreeBuilder(): TreeBuilder {
        $builder = new TreeBuilder('common');
        $root = $builder->getRootNode();

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
                    ->scalarNode('project_url')
                        ->isRequired()
                    ->end()
                    ->scalarNode('logo')
                        ->defaultValue(null)
                    ->end()
                    ->scalarNode('small_logo')
                        ->defaultValue(null)
                    ->end()
                    ->scalarNode('logo_link')
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
            ->arrayNode('messenger')
                ->children()
                    ->scalarNode('transport')->defaultValue('messenger.transport.async')->setDeprecated('schulit/common-bundle', '4.3.0')->end()
                ->end()
            ->end()
            ->arrayNode('disable')
                ->children()
                    ->booleanNode('cron')->defaultFalse()->end()
                    ->booleanNode('orm')->defaultFalse()->end()
                    ->booleanNode('messenger')->defaultFalse()->end()
                ->end()
            ->end()
        ->end();

        return $builder;
    }
}