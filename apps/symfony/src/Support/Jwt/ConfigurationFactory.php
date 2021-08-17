<?php

namespace App\Support\Jwt;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha512;
use Lcobucci\JWT\Signer\Key\InMemory;

class ConfigurationFactory
{
    public function __invoke(string $secretKey, string $publicKey): Configuration
    {
        return Configuration::forAsymmetricSigner(
            new Sha512(),
            InMemory::base64Encoded($secretKey),
            InMemory::base64Encoded($publicKey)
        );
    }
}
