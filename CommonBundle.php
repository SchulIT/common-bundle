<?php

namespace SchulIT\CommonBundle;

use SchulIT\CommonBundle\DependencyInjection\CommonExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CommonBundle extends Bundle {

    public function build(ContainerBuilder $container) {
        parent::build($container);

        if($container->hasParameter('app.common.disable.database') === false || $container->getParameter('app.common.disable.database') === false) {
            $container->addCompilerPass(
                \Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass::createAnnotationMappingDriver(
                    ['SchulIT\CommonBundle\Entity'],
                    [realpath(__DIR__ . '/Entity')]
                )
            );
        }
    }

    public function getContainerExtension() {
        return new CommonExtension();
    }
}