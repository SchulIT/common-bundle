<?php

namespace SchulIT\CommonBundle\DependencyInjection;

use SchulIT\CommonBundle\Autoconfig\Roles\RoleConfigExporter;
use SchulIT\CommonBundle\Autoconfig\Saml\SamlConfigExporter;
use SchulIT\CommonBundle\Command\ClearLogsCommand;
use SchulIT\CommonBundle\Controller\MessengerController;
use SchulIT\CommonBundle\Monolog\DatabaseHandler;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class CommonExtension extends Extension implements PrependExtensionInterface {

    public function load(array $configs, ContainerBuilder $container): void {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('app.common.name', $config['app']['name']);
        $container->setParameter('app.common.url', $config['app']['url']);
        $container->setParameter('app.common.version', $config['app']['version']);
        $container->setParameter('app.common.project_url', $config['app']['project_url']);
        $container->setParameter('app.common.logo', $config['app']['logo']);
        $container->setParameter('app.common.small_logo', $config['app']['small_logo']);
        $container->setParameter('app.common.logo_link', $config['app']['logo_link']);
        $container->setParameter('app.common.locales', $config['locales']);
        $container->setParameter('app.common.menu', $config['menu']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        if (!isset($config['disable']) || $config['disable']['cron'] !== true) {
            $loader->load('cron.yaml');
        }

        if (!isset($config['disable']) || $config['disable']['messenger'] !== true) {
            $loader->load('messenger.yaml');
        }

        if (isset($config['disable']) && $config['disable']['orm'] === true) {
            $container->removeDefinition(DatabaseHandler::class);
            $container->removeDefinition(ClearLogsCommand::class);
        }

        if(!isset($config['disable']) || $config['disable']['autoconfig'] !== true){
            $loader->load('autoconfig.yaml');

            $samlConfigExporter = $container->getDefinition(SamlConfigExporter::class);
            $samlConfigExporter->setArgument('$entityId', $config['autoconfig']['entity_id']);
            $samlConfigExporter->setArgument('$appName', $config['autoconfig']['app_name']);
            $samlConfigExporter->setArgument('$appIcon', $config['autoconfig']['app_icon']);
            $samlConfigExporter->setArgument('$certFile', $config['autoconfig']['saml_cert_file']);
            $samlConfigExporter->setArgument('$indexRouteName', $config['autoconfig']['index_route_name']);
            $samlConfigExporter->setArgument('$loginAcsRouteName', $config['autoconfig']['login_acs_route_name']);

            $roleConfigExporter = $container->getDefinition(RoleConfigExporter::class);
            $roleConfigExporter->setArgument('$roleHierarchy', $config['autoconfig']['role_hierarchy']);
            $roleConfigExporter->setArgument('$roleAttributeName', $config['autoconfig']['role_attribute_name']);
            $roleConfigExporter->setArgument('$ignoreRoles', $config['autoconfig']['ignore_roles']);
        }

        $loader->load('controller.yaml');
    }

    public function getAlias(): string {
        return 'common';
    }

    public function prepend(ContainerBuilder $container): void {
        $container->prependExtensionConfig('two_factor', [
            'google' => [
                'server_name' => '%app.common.host%'
            ]
        ]);

        $container->prependExtensionConfig('composer_dependency_list', [
            'list_template' => '@Common/dependencies/list.html.twig',
            'license_template' => '@Common/dependencies/license.html.twig'
        ]);
    }
}