<?php
declare(strict_types=1);
namespace FediE2EE\PKDServer;

use FediE2EE\PKD\Crypto\{
    Exceptions\CryptoException,
    Exceptions\NotImplementedException,
    Exceptions\ParserException,
    Protocol\HPKEAdapter,
    Protocol\Parser
};
use FediE2EE\PKDServer\Exceptions\{
    DependencyException,
    ProtocolException,
    TableException
};
use FediE2EE\PKDServer\{
    ActivityPub\WebFinger,
    Protocol\Payload
};
use GuzzleHttp\Client;
use FediE2EE\PKDServer\Tables\{
    AuxData,
    PublicKeys,
    Records\ActorKey
};
use ParagonIE\Certainty\Exception\CertaintyException;
use ParagonIE\HPKE\HPKEException;
use SodiumException;

/**
 * This class defines the process for which records are updated in the Public Key Directory.
 */
class Protocol
{
    protected Parser $parser;
    protected ?WebFinger $webFinger = null;

    public function __construct(private readonly ServerConfig $config)
    {
        $this->parser = new Parser();
    }

    /**
     * @throws DependencyException
     * @throws SodiumException
     * @throws CertaintyException
     */
    public function webfinger(?Client $http = null): WebFinger
    {
        if (!is_null($this->webFinger)) {
            return $this->webFinger;
        }
        return new WebFinger($http, $this->config->getCaCertFetch());
    }

    /**
     * This is intended for mocking in unit tests
     *
     * @param WebFinger $wf
     * @return self
     */
    public function setWebFinger(WebFinger $wf): self
    {
        $this->webFinger = $wf;
        return $this;
    }

    /**
     * @throws DependencyException
     * @throws CryptoException
     * @throws NotImplementedException
     * @throws ParserException
     * @throws HPKEException
     * @throws SodiumException
     */
    protected function hpkeUnwrap(string $arbitrary): Payload
    {
        $hpke = $this->config->getHPKE();
        $raw = new HPKEAdapter($hpke->cs)
            ->open($hpke->decapsKey, $hpke->encapsKey, $arbitrary);
        $parsed = $this->parser->parse($raw);
        return new Payload($parsed->getMessage(), $parsed->getKeyMap(), $raw);
    }

    /**
     * @throws CryptoException
     * @throws DependencyException
     * @throws ProtocolException
     * @throws TableException
     * @throws HPKEException
     * @throws NotImplementedException
     * @throws ParserException
     * @throws SodiumException
     */
    public function addKey(string $body): ActorKey
    {
        $payload = $this->hpkeUnwrap($body);
        $table = new PublicKeys($this->config);
        return $table->addKey($payload);
    }

    /**
     * @throws CryptoException
     * @throws DependencyException
     * @throws ProtocolException
     * @throws TableException
     * @throws HPKEException
     * @throws NotImplementedException
     * @throws ParserException
     * @throws SodiumException
     */
    public function revokeKey(string $body): ActorKey
    {
        $payload = $this->hpkeUnwrap($body);
        $table = new PublicKeys($this->config);
        return $table->revokeKey($payload);
    }

    /**
     * @throws CryptoException
     * @throws DependencyException
     * @throws HPKEException
     * @throws NotImplementedException
     * @throws ParserException
     * @throws ProtocolException
     * @throws SodiumException
     * @throws TableException
     */
    public function revokeKeyThirdParty(string $body): bool
    {
        try {
            $parsed = $this->parser->parse($body);
            $payload = new Payload($parsed->getMessage(), $parsed->getKeyMap(), $body);
        } catch (ParserException $e) {
            throw new ProtocolException('Invalid bundle for RevokeKeyThirdParty', 0, $e);
        }

        $table = new PublicKeys($this->config);
        return $table->revokeKeyThirdParty($payload);
    }

    /**
     * @throws CryptoException
     * @throws DependencyException
     * @throws HPKEException
     * @throws NotImplementedException
     * @throws ParserException
     * @throws ProtocolException
     * @throws SodiumException
     * @throws TableException
     */
    public function moveIdentity(string $body): bool
    {
        $payload = $this->hpkeUnwrap($body);
        $table = new PublicKeys($this->config);
        return $table->moveIdentity($payload);
    }

    /**
     * @throws CryptoException
     * @throws DependencyException
     * @throws HPKEException
     * @throws NotImplementedException
     * @throws ParserException
     * @throws ProtocolException
     * @throws SodiumException
     * @throws TableException
     */
    public function burnDown(string $body): bool
    {
        $payload = $this->hpkeUnwrap($body);
        $table = new PublicKeys($this->config);
        return $table->burnDown($payload);
    }

    /**
     * @throws CryptoException
     * @throws DependencyException
     * @throws HPKEException
     * @throws NotImplementedException
     * @throws ParserException
     * @throws ProtocolException
     * @throws SodiumException
     * @throws TableException
     */
    public function fireproof(string $body): bool
    {
        $payload = $this->hpkeUnwrap($body);
        $table = new PublicKeys($this->config);
        return $table->fireproof($payload);
    }

    /**
     * @throws CryptoException
     * @throws DependencyException
     * @throws HPKEException
     * @throws NotImplementedException
     * @throws ParserException
     * @throws ProtocolException
     * @throws SodiumException
     * @throws TableException
     */
    public function undoFireproof(string $body): bool
    {
        $payload = $this->hpkeUnwrap($body);
        $table = new PublicKeys($this->config);
        return $table->undoFireproof($payload);
    }

    /**
     * @throws CryptoException
     * @throws DependencyException
     * @throws HPKEException
     * @throws NotImplementedException
     * @throws ParserException
     * @throws ProtocolException
     * @throws SodiumException
     * @throws TableException
     */
    public function addAuxData(string $body): bool
    {
        $payload = $this->hpkeUnwrap($body);
        $table = new AuxData($this->config);
        return $table->addAuxData($payload);
    }

    /**
     * @throws CryptoException
     * @throws DependencyException
     * @throws HPKEException
     * @throws NotImplementedException
     * @throws ParserException
     * @throws ProtocolException
     * @throws SodiumException
     * @throws TableException
     */
    public function revokeAuxData(string $body): bool
    {
        $payload = $this->hpkeUnwrap($body);
        $table = new AuxData($this->config);
        return $table->revokeAuxData($payload);
    }

    /**
     * @throws CryptoException
     * @throws DependencyException
     * @throws NotImplementedException
     * @throws ParserException
     * @throws ProtocolException
     * @throws SodiumException
     * @throws TableException
     */
    public function checkpoint(string $body): bool
    {
        try {
            $parsed = $this->parser->parse($body);
            $payload = new Payload($parsed->getMessage(), $parsed->getKeyMap(), $body);
        } catch (ParserException $e) {
            throw new ProtocolException('Invalid bundle for Checkpoint', 0, $e);
        }
        $table = new PublicKeys($this->config);
        return $table->checkpoint($payload);
    }
}
