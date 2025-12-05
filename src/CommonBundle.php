<?php

namespace SchulIT\CommonBundle;

use SchulIT\CommonBundle\Autoconfig\Roles\RoleConfigExporter;
use SchulIT\CommonBundle\Autoconfig\Saml\SamlConfigExporter;
use SchulIT\CommonBundle\Command\ClearLogsCommand;
use SchulIT\CommonBundle\Monolog\DatabaseHandler;
use Scienta\DoctrineJsonFunctions\Query\AST\Functions\Mariadb\JsonValue;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class CommonBundle extends AbstractBundle {

    public function configure(DefinitionConfigurator $definition): void {
        $definition->rootNode()
            ->children()
                ->arrayNode('app')
                    ->children()
                        ->scalarNode('url')->isRequired()->end()
                        ->scalarNode('name')->isRequired()->end()
                        ->scalarNode('version')->isRequired()->end()
                        ->scalarNode('project_url')->isRequired()->end()
                        ->scalarNode('logo')->defaultValue(null)->end()
                        ->scalarNode('small_logo')->defaultValue(null)->end()
                        ->scalarNode('logo_link')->defaultValue(null)->end()
                    ->end()
                ->end()
                ->arrayNode('disable')
                    ->children()
                        ->booleanNode('cron')->defaultFalse()->end()
                        ->booleanNode('orm')->defaultFalse()->end()
                        ->booleanNode('messenger')->defaultFalse()->end()
                        ->booleanNode('autoconfig')->defaultFalse()->end()
                    ->end()
                ->end()
                ->arrayNode('autoconfig')
                    ->children()
                        ->stringNode('entity_id')->end()
                        ->stringNode('app_name')->end()
                        ->stringNode('app_icon')->end()
                        ->stringNode('saml_cert_file')->end()
                        ->variableNode('role_hierarchy')->end()
                        ->arrayNode('ignore_roles')
                            ->defaultValue([])
                            ->prototype('string')->end()
                        ->end()
                        ->stringNode('role_attribute_name')->defaultValue('urn:roles')->end()
                        ->stringNode('index_route_name')->defaultValue('index')->end()
                        ->stringNode('login_acs_route_name')->defaultValue('lightsaml_sp.login_check')->end()
                    ->end()
                ->end()
            ->end()
            ;
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void {
        $builder->setParameter('app.common.name', $config['app']['name']);
        $builder->setParameter('app.common.url', $config['app']['url']);
        $builder->setParameter('app.common.version', $config['app']['version']);
        $builder->setParameter('app.common.project_url', $config['app']['project_url']);
        $builder->setParameter('app.common.logo', $config['app']['logo']);
        $builder->setParameter('app.common.small_logo', $config['app']['small_logo']);
        $builder->setParameter('app.common.logo_link', $config['app']['logo_link']);

        $loader = new YamlFileLoader($builder, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.yaml');

        if (isset($config['disable']) && $config['disable']['orm'] === true) {
            $builder->removeDefinition(DatabaseHandler::class);
            $builder->removeDefinition(ClearLogsCommand::class);
        }

        if(!isset($config['disable']) || $config['disable']['autoconfig'] !== true){
            $loader->load('autoconfig.yaml');

            $samlConfigExporter = $builder->getDefinition(SamlConfigExporter::class);
            $samlConfigExporter->setArgument('$entityId', $config['autoconfig']['entity_id']);
            $samlConfigExporter->setArgument('$appName', $config['autoconfig']['app_name']);
            $samlConfigExporter->setArgument('$appIcon', $config['autoconfig']['app_icon']);
            $samlConfigExporter->setArgument('$certFile', $config['autoconfig']['saml_cert_file']);
            $samlConfigExporter->setArgument('$indexRouteName', $config['autoconfig']['index_route_name']);
            $samlConfigExporter->setArgument('$samlAcsRouteName', $config['autoconfig']['login_acs_route_name']);

            $roleConfigExporter = $builder->getDefinition(RoleConfigExporter::class);
            $roleConfigExporter->setArgument('$roleHierarchy', $config['autoconfig']['role_hierarchy']);
            $roleConfigExporter->setArgument('$roleAttributeName', $config['autoconfig']['role_attribute_name']);
            $roleConfigExporter->setArgument('$ignoreRoles', $config['autoconfig']['ignore_roles']);
        }

        $loader->load('controller.yaml');
    }

    public function build(ContainerBuilder $container): void {
        parent::build($container);

        if(class_exists("Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass")) {
            $container->addCompilerPass(
                Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass::createAttributeMappingDriver(
                    ['SchulIT\CommonBundle\Entity'],
                    [realpath(__DIR__ . '/Entity')],
                    reportFieldsWhereDeclared: true
                )
            );
        }
    }

    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void {
        $builder->prependExtensionConfig('two_factor', [
            'google' => [
                'server_name' => '%app.common.host%'
            ]
        ]);

        $builder->prependExtensionConfig('composer_dependency_list', [
            'list_template' => '@Common/dependencies/list.html.twig',
            'license_template' => '@Common/dependencies/license.html.twig'
        ]);

        $builder->prependExtensionConfig('chrisguitarguy_request_id', [
            'request_header' => 'Request-Id',
            'trust_request_header' => false,
            'response_header' => 'Request-Id',
            'enable_monolog' => true,
            'enable_twig' => true
        ]);

        $builder->prependExtensionConfig('doctrine', [
            'orm' => [
                'dql' => [
                    'string_functions' => [
                        'JSON_VALUE' => JsonValue::class
                    ]
                ]
            ]
        ]);
    }
}