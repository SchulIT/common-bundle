<?php

namespace SchulIT\CommonBundle\Controller;

use SchulIT\CommonBundle\Autoconfig\Roles\RoleConfigExporter;
use SchulIT\CommonBundle\Autoconfig\Saml\SamlConfigExporter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class AutoconfigController extends AbstractController {

    #[Route('/roles')]
    public function roles(RoleConfigExporter $exporter): JsonResponse {
        return $this->json(
            $exporter->getConfig()
        );
    }

    #[Route('/saml')]
    public function saml(SamlConfigExporter $exporter): JsonResponse {
        return $this->json(
            $exporter->getConfig()
        );
    }
}