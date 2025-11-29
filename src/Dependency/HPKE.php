<?php
declare(strict_types=1);
namespace FediE2EE\PKDServer\Dependency;

use ParagonIE\HPKE\HPKE as Ciphersuite;
use ParagonIE\HPKE\Interfaces\DecapsKeyInterface;
use ParagonIE\HPKE\Interfaces\EncapsKeyInterface;

readonly class HPKE
{
    public function __construct(
        public CipherSuite        $cs,
        public DecapsKeyInterface $decapsKey,
        public EncapsKeyInterface $encapsKey,
    ) {}

    /**
     * @api
     */
    public function getCipherSuite(): CipherSuite
    {
        return $this->cs;
    }

    /**
     * @api
     */
    public function getDecapsKey(): DecapsKeyInterface
    {
        return $this->decapsKey;
    }

    /**
     * @api
     */
    public function getEncapsKey(): EncapsKeyInterface
    {
        return $this->encapsKey;
    }
}
