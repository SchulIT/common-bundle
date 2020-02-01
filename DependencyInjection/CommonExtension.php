<?php

namespace SchoolIT\CommonBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class CommonExtension extends Extension implements PrependExtensionInterface {

    public function load(array $configs, ContainerBuilder $container) {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $options = $this->resolveOptions($config['app']['url']);

        $container->setParameter('app.common.name', $config['app']['name']);
        $container->setParameter('app.common.host', $options['host']);
        $container->setParameter('app.common.path', $options['path']);
        $container->setParameter('app.common.ssl', $options['scheme'] === 'https');
        $container->setParameter('app.common.version', $config['app']['version']);
        $container->setParameter('app.common.project_url', $config['app']['project_url']);
        $container->setParameter('app.common.logo', $config['app']['logo']);
        $container->setParameter('app.common.locales', $config['locales']);
        $container->setParameter('app.common.menu', $config['menu']);
        $container->setParameter('app.common.templates.mail', $config['templates']['mail']);
        $container->setParameter('app.common.templates.logs', $config['templates']['logs']);
        $container->setParameter('app.common.templates.clear_logs', $config['templates']['clear_logs']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        if(!isset($config['disable']) || $config['disable']['saml'] !== true) {
            $loader->load('lightsaml.yaml');
        }

        if(!isset($config['disable']) || $config['disable']['mail'] !== true) {
            $loader->load('swiftmailer.yaml');
        }

        $loader->load('controller.yaml');
    }

    public function resolveOptions(string $url) {
        $options = [
            'scheme' => 'http',
            'host' => null,
            'path' => '/'
        ];

        $parts = parse_url($url);

        if(isset($parts['scheme'])) {
            $options['scheme'] = $parts['scheme'];
        }

        if(isset($parts['host'])) {
            $options['host'] = $parts['host'];
        }

        if(isset($parts['path'])) {
            $options['path'] = $parts['path'];
        }

        return $options;
    }

    public function getAlias() {
        return 'common';
    }

    public function prepend(ContainerBuilder $container) {
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