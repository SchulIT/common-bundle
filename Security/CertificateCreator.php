<?php

namespace SchulIT\CommonBundle\Security;

/**
 * Helps to create certificates (which can be created from the CreateCertificateCommand or by code, e.g. for testing purposes)
 */
class CertificateCreator {
    public function createCertificate(string $certFile, string $keyFile, string $countryName, string $stateOrProvinceName,
                                      string $localityName, string $organizationName, string $organizationalUnitName,
                                      string $commonName, string $emailAddress) {
        $config = [
            'digest_alg' => 'sha512',
            'private_key_bits' => 4096,
            'private_key_type' => OPENSSL_KEYTYPE_RSA
        ];

        $privKey = openssl_pkey_new($config);
        $data = [ ];

        $data['countryName'] = $countryName;
        $data['stateOrProvinceName'] = $stateOrProvinceName;
        $data['localityName'] = $localityName;
        $data['organizationName'] = $organizationName;
        $data['organizationalUnitName'] = $organizationalUnitName;
        $data['commonName'] = $commonName;
        $data['emailAddress'] = $emailAddress;

        $csr = openssl_csr_new($data, $privKey, $config);
        $cert = openssl_csr_sign($csr, null, $privKey, 10*365, $config);

        openssl_x509_export($cert, $certout);
        openssl_pkey_export($privKey, $keyout);

        file_put_contents($keyFile, $keyout);
        file_put_contents($certFile, $certout);
    }
}