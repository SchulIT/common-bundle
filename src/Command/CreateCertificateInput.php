<?php

namespace SchulIT\CommonBundle\Command;

use Symfony\Component\Console\Attribute\Ask;
use Symfony\Component\Console\Attribute\Option;

class CreateCertificateInput {
    #[Option('Art des Zertifikats', 'type', 't')]
    #[Ask('Art des Zertifikats')]
    public string $type;

    #[Option('Land', 'countryName', 'c')]
    #[Ask('Land', default: 'DE')]
    public string $countryName;

    #[Option('Bundesland', 'stateOrProvinceName', 's')]
    #[Ask('Bundesland', default: 'Nordrhein-Westfalen')]
    public string $stateOrProvinceName;

    #[Option('Stadt', 'localityName', 'l')]
    #[Ask('Stadt', default: 'Aachen')]
    public string $localityName;

    #[Option('Name der Organisation', 'organizationName', 'o')]
    #[Ask('Name der Organisation', default: 'SchulIT')]
    public string $organizationName;

    #[Option('Name der Organisationseinheit', 'organizationalUnitName', 'ou')]
    #[Ask('Art des Zertifikats', default: 'SchulIT IT-Department')]
    public string $organizationalUnitName;

    #[Option('Common Name', 'commonName', 'cn')]
    #[Ask('Common Name', default: 'schulit.de')]
    public string $commonName;

    #[Option('E-Mail-Adresse', 'emailAddress', 'e')]
    #[Ask('E-Mail-Adresse', default: 'admin@schulit.de')]
    public string $emailAddress;
}