<?php

namespace SchulIT\CommonBundle;

use SchulIT\CommonBundle\DependencyInjection\CommonExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CommonBundle extends Bundle {

    public function build(ContainerBuilder $container): void {
        parent::build($container);

        if(class_exists("Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass")) {
            $container->addCompilerPass(
                \Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass::createAttributeMappingDriver(
                    ['SchulIT\CommonBundle\Entity'],
                    [realpath(__DIR__ . '/Entity')]
                )
            );
        }
    }

    public function getContainerExtension(): ?ExtensionInterface {
        return new CommonExtension();
    }
}