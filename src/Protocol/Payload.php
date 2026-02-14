<?php
declare(strict_types=1);
namespace FediE2EE\PKDServer\Protocol;

use FediE2EE\PKD\Crypto\AttributeEncryption\AttributeKeyMap;
use FediE2EE\PKD\Crypto\Protocol\{
    EncryptedProtocolMessageInterface,
    ProtocolMessageInterface
};
use FediE2EE\PKDServer\Exceptions\ProtocolException;
use FediE2EE\PKDServer\Traits\JsonTrait;
use JsonException as BaseJsonException;
use stdClass;

use function array_map;
use function get_object_vars;
use function is_array;
use function json_decode;
use function ksort;

readonly class Payload
{
    use JsonTrait;
    public function __construct(
        public ProtocolMessageInterface $message,
        public AttributeKeyMap $keyMap,
        public string $rawJson,
    ) {}

    public function decrypt(): ProtocolMessageInterface
    {
        if ($this->message instanceof EncryptedProtocolMessageInterface) {
            return $this->message->decrypt($this->keyMap);
        }
        return $this->message;
    }

    /**
     * @return array<string, mixed>
     * @throws BaseJsonException
     */
    public function decode(): array
    {
        return self::jsonDecode($this->rawJson);
    }

    /**
     * @throws BaseJsonException
     * @throws ProtocolException
     */
    public function getMerkleTreePayload(): string
    {
        $decoded = json_decode($this->rawJson, false, 512, JSON_THROW_ON_ERROR);
        if (!($decoded instanceof stdClass)) {
            throw new ProtocolException('Merkle payload must be a JSON object');
        }

        // Remove fields that are not part of the Merkle leaf
        unset($decoded->{'key-id'}, $decoded->{'symmetric-keys'}, $decoded->otp);

        // Canonicalize: recursively sort object keys for deterministic hashing
        $canonicalized = self::canonicalize($decoded);
        return self::jsonEncode($canonicalized);
    }

    /**
     * Recursively sort object keys in ASCII byte order while preserving
     * the distinction between objects (stdClass) and arrays.
     */
    private static function canonicalize(mixed $value): mixed
    {
        if ($value instanceof stdClass) {
            $props = get_object_vars($value);
            ksort($props);
            $sorted = new stdClass();
            foreach ($props as $k => $v) {
                $sorted->{$k} = self::canonicalize($v);
            }
            return $sorted;
        }
        if (is_array($value)) {
            return array_map(self::canonicalize(...), $value);
        }
        return $value;
    }
}
