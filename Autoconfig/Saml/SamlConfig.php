<?php

namespace SchulIT\CommonBundle\Autoconfig\Saml;

readonly class SamlConfig {
    public function __construct(public string $url,
                                public string $entityId,
                                public string $name,
                                public string $description,
                                public string $icon,
                                public array $acsUrls,
                                public string $certificate) { }
}