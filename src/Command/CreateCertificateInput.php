<?php

namespace SchulIT\CommonBundle\Command;

use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\Ask;

class CreateCertificateInput {
    #[Argument('Art des Zertifikats', 'type')]
    #[Ask('Art des Zertifikats', default: 'saml')]
    public string $type;

    #[Argument('Land', 'countryName')]
    #[Ask('Land', default: 'DE')]
    public string $countryName;

    #[Argument('Bundesland', 'stateOrProvinceName')]
    #[Ask('Bundesland', default: 'Nordrhein-Westfalen')]
    public string $stateOrProvinceName;

    #[Argument('Stadt', 'localityName')]
    #[Ask('Stadt', default: 'Aachen')]
    public string $localityName;

    #[Argument('Name der Organisation', 'organizationName')]
    #[Ask('Name der Organisation', default: 'SchulIT')]
    public string $organizationName;

    #[Argument('Name der Organisationseinheit', 'organizationalUnitName')]
    #[Ask('Art des Zertifikats', default: 'SchulIT IT-Department')]
    public string $organizationalUnitName;

    #[Argument('Common Name', 'commonName')]
    #[Ask('Common Name', default: 'schulit.de')]
    public string $commonName;

    #[Argument('E-Mail-Adresse', 'emailAddress')]
    #[Ask('E-Mail-Adresse', default: 'admin@schulit.de')]
    public string $emailAddress;
}
