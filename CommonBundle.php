<?php

namespace SchoolIT\CommonBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use SchoolIT\CommonBundle\DependencyInjection\CommonExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CommonBundle extends Bundle {

    public function build(ContainerBuilder $container) {
        parent::build($container);

        $container->addCompilerPass(
            DoctrineOrmMappingsPass::createAnnotationMappingDriver(
                ['SchoolIT\CommonBundle\Entity'],
                [ realpath(__DIR__ . '/Entity') ]
            )
        );
    }

    public function getContainerExtension() {
        return new CommonExtension();
    }
}