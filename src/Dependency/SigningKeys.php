<?php
declare(strict_types=1);
namespace FediE2EE\PKDServer\Dependency;

use FediE2EE\PKD\Crypto\PublicKey;
use FediE2EE\PKD\Crypto\SecretKey;

class SigningKeys
{
    public function __construct(
        public readonly SecretKey $secretKey,
        public readonly PublicKey $publicKey,
    ) {}
}
