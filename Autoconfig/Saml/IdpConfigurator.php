<?php

namespace SchulIT\CommonBundle\Autoconfig\Saml;

use DateTime;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class IdpConfigurator {
    public function __construct(private string $certFile) { }

    /**
     * @throws FilesystemException
     * @throws InvalidCertificateException
     */
    public function import(string $certificate, bool $force = false): void {
        $cert = openssl_x509_read($certificate);

        if($cert === false) {
            throw new InvalidCertificateException('Input is not a valid X.509 certificate.');
        }

        $info = openssl_x509_parse($cert);

        if($info === false) {
            throw new InvalidCertificateException('Error while parsing certificate.');
        }

        $validUntilTimestamp = $info['validTo_time_t'];
        $validUntil = new DateTime();
        $validUntil->setTimestamp($validUntilTimestamp);

        if($validUntil < new DateTime()) {
            throw new InvalidCertificateException('Certificate is expired.');
        }

        if(is_writable($this->certFile) === false) {
            throw new FilesystemException('Certificate file is not writable.');
        }

        if(file_exists($this->certFile) === true && $force === false) {
            throw new FilesystemException('Certificate file already exists.');
        }

        if(!file_put_contents($this->certFile, $certificate)) {
            throw new FilesystemException('Could not write certificate to file.');
        }
    }
}