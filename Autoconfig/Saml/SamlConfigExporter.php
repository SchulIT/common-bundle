<?php

namespace SchulIT\CommonBundle\Autoconfig\Saml;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

readonly class SamlConfigExporter {

    public function __construct(private UrlGeneratorInterface $urlGenerator,
                                private string $entityId,
                                private string $appName,
                                private string $appIcon,
                                private string $certFile,
                                private string $indexRouteName,
                                private string $samlAcsRouteName,
                                private TranslatorInterface $translator) {

    }

    public function getConfig(): SamlConfig {
        $cert = null;

        if(file_exists($this->certFile)) {
            $cert = file_get_contents($this->certFile);
        }

        return new SamlConfig(
            $this->urlGenerator->generate($this->indexRouteName, [], UrlGeneratorInterface::ABSOLUTE_URL),
            $this->entityId,
            $this->appName,
            $this->translator->trans('description', [], 'autoconfig'),
            $this->appIcon,
            [
                $this->urlGenerator->generate($this->samlAcsRouteName, [], UrlGeneratorInterface::ABSOLUTE_URL)
            ],
            $cert
        );
    }
}