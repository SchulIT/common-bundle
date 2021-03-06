<?php

namespace SchulIT\CommonBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface {

    public function getConfigTreeBuilder() {
        $builder = new TreeBuilder('common');

        if (method_exists($builder, 'getRootNode')) {
            $root = $builder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $root = $builder->root('common');
        }

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
                    ->scalarNode('transport')->defaultValue('messenger.transport.async')->end()
                ->end()
            ->end()
            ->arrayNode('disable')
                ->children()
                    ->booleanNode('saml')->defaultFalse()->end()
                    ->booleanNode('mail')->defaultFalse()->end()
                    ->booleanNode('cron')->defaultFalse()->end()
                    ->booleanNode('orm')->defaultFalse()->end()
                    ->booleanNode('messenger')->defaultTrue()->end()
                ->end()
            ->end()
        ->end();

        return $builder;
    }
}