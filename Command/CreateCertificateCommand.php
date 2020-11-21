<?php

namespace SchulIT\CommonBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateCertificateCommand extends Command {

    private $types = [ ];

    public function __construct(array $types, ?string $name = null) {
        parent::__construct($name);

        $this->types = $types;
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

        $config = [
            'digest_alg' => 'sha512',
            'private_key_bits' => 4096,
            'private_key_type' => OPENSSL_KEYTYPE_RSA
        ];

        $privKey = openssl_pkey_new($config);
        $data = [ ];

        $data['countryName'] = $io->ask('countryName', 'DE');
        $data['stateOrProvinceName'] = $io->ask('stateOrProvinceName', 'Nordrhein-Westfalen');
        $data['localityName'] = $io->ask('localityName', 'Aachen');
        $data['organizationName'] = $io->ask('organizationName', 'SchulIT');
        $data['organizationalUnitName'] = $io->ask('organizationalUnitName', 'SchulIT IT');
        $data['commonName'] = $io->ask('commonName', 'schulit.de');
        $data['emailAddress'] = $io->ask('emailAddress', 'admin@schulit.de');

        $csr = openssl_csr_new($data, $privKey, $config);
        $cert = openssl_csr_sign($csr, null, $privKey, 10*365, $config);

        openssl_x509_export($cert, $certout);
        openssl_pkey_export($privKey, $keyout);

        file_put_contents($keyFile, $keyout);
        file_put_contents($certFile, $certout);

        openssl_x509_free($cert);
        openssl_pkey_free($privKey);

        $io->success(sprintf('Certificate generated successfully (saved as %s)', $certFile));
        return 0;
    }
}