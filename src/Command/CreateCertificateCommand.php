<?php

namespace SchulIT\CommonBundle\Command;

use SchulIT\CommonBundle\Security\CertificateCreator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\MapInput;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:create-certificate', description: 'Erzeugt ein selbst-signiertes Zertifikat.')]
readonly class CreateCertificateCommand {

    public function __construct(
        private array  $types,
        private CertificateCreator $certificateCreator) {
    }

    public function __invoke(
        #[MapInput] CreateCertificateInput $input,
        SymfonyStyle $io
    ): int {

        $availableTypes = array_keys($this->types);

        if(!in_array($input->type, $availableTypes)) {
            $io->error('Ungültige Zertifikatsart - gültige Werte: ' . implode(', ', $availableTypes));
            return 1;
        }

        $keyFile = $this->types[$input->type]['keyFile'];
        $certFile = $this->types[$input->type]['certFile'];

        $this->certificateCreator->createCertificate(
            $certFile,
            $keyFile,
            $input->countryName,
            $input->stateOrProvinceName,
            $input->localityName,
            $input->organizationName,
            $input->organizationalUnitName,
            $input->commonName,
            $input->emailAddress
        );

        $io->success(sprintf('Zertifikat erfolgreich gespeichert (unter %s)', $certFile));
        return Command::SUCCESS;
    }
}