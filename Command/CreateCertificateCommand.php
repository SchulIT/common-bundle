<?php

namespace SchulIT\CommonBundle\Command;

use SchulIT\CommonBundle\Security\CertificateCreator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateCertificateCommand extends Command {

    private $types = [ ];
    private $certificateCreator;

    public function __construct(array $types, CertificateCreator  $certificateCreator, ?string $name = null) {
        parent::__construct($name);

        $this->types = $types;
        $this->certificateCreator = $certificateCreator;
    }

    public function configure() {
        $this
            ->setName('app:create-certificate')
            ->setDescription('Creates a new self-signed certificate')
            ->addOption(
                'type',
                't',
                InputOption::VALUE_REQUIRED,
                'Type of certificate (e.g. saml or oauth2)'
            )
            ->addOption(
                'countryName',
                'c',
                InputOption::VALUE_REQUIRED
            )
            ->addOption(
                'stateOrProvinceName',
                's',
                InputOption::VALUE_REQUIRED
            )
            ->addOption(
                'localityName',
                'l',
                InputOption::VALUE_REQUIRED
            )
            ->addOption(
                'organizationName',
                'o',
                InputOption::VALUE_REQUIRED
            )
            ->addOption(
                'organizationalUnitName',
                null,
                InputOption::VALUE_REQUIRED
            )
            ->addOption(
                'commonName',
                null,
                InputOption::VALUE_REQUIRED
            )
            ->addOption(
                'emailAddress',
                null,
                InputOption::VALUE_REQUIRED
            );
    }

    public function run(InputInterface $input, OutputInterface $output) {
        $io = new SymfonyStyle($input, $output);

        $type = $input->getOption('type');
        $availableTypes = array_keys($this->types);

        if(!in_array($type, $availableTypes)) {
            $io->error('Invalid certificate type - available types: ' . implode(', ', $availableTypes));
            return 1;
        }

        $keyFile = $this->types[$type]['keyFile'];
        $certFile = $this->types[$type]['certFile'];

        $countryName = $input->getOption('countryName') ?? $io->ask('countryName', 'DE');
        $stateOrProvinceName = $input->getOption('stateOrProvinceName') ?? $io->ask('stateOrProvinceName', 'Nordrhein-Westfalen');
        $localityName = $input->getOption('localityName') ?? $io->ask('localityName', 'Aachen');
        $organizationName = $input->getOption('organizationName') ?? $io->ask('organizationName', 'SchulIT');
        $organizationalUnitName = $input->getOption('organizationalUnitName') ?? $io->ask('organizationalUnitName', 'SchulIT IT');
        $commonName = $input->getOption('commonName') ?? $io->ask('commonName', 'schulit.de');
        $emailAddress = $input->getOption('emailAddress') ?? $io->ask('emailAddress', 'admin@schulit.de');

        $this->certificateCreator->createCertificate(
            $certFile, $keyFile, $countryName, $stateOrProvinceName,
            $localityName,  $organizationName, $organizationalUnitName,
            $commonName, $emailAddress);

        $io->success(sprintf('Certificate generated successfully (saved as %s)', $certFile));
        return 0;
    }
}