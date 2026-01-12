<?php
declare(strict_types=1);
namespace FediE2EE\PKDServer\Fuzzing;

use FediE2EE\PKD\Crypto\Merkle\{
    InclusionProof,
    IncrementalTree
};
use ParagonIE\ConstantTime\Base64UrlSafe;
use PhpFuzzer\Config;
use RangeException;
use TypeError;

/** @var Config $config */

require_once dirname(__DIR__) . '/vendor/autoload.php';

$config->setTarget(function (string $input): void {
    // Test raw JSON decoding patterns
    $decoded = json_decode($input, true);
    if (is_array($decoded)) {
        // Pattern: InclusionProof from JSON (Tables/MerkleState.php:308)
        try {
           $inclusion = new InclusionProof(
                $decoded['index'] ?? 0,
                $decoded['proof'] ?? []
            );
            assert($inclusion instanceof InclusionProof);
        } catch (TypeError) {
            // Expected for invalid types
        }

        // Pattern: key-id extraction (Protocol/Payload.php:43-48)
        if (array_key_exists('key-id', $decoded)) {
            unset($decoded['key-id']);
        }
        if (array_key_exists('symmetric-keys', $decoded)) {
            unset($decoded['symmetric-keys']);
        }
        json_encode($decoded, JSON_UNESCAPED_SLASHES);
    }

    // Test object JSON decoding
    $decodedObj = json_decode($input, false);
    if (is_object($decodedObj)) {
        if (property_exists($decodedObj, 'links') && is_array($decodedObj->links)) {
            foreach ($decodedObj->links as $link) {
                if (is_object($link)) {
                    assert(property_exists($link, 'rel'));
                    assert(property_exists($link, 'href'));
                    assert(property_exists($link, 'type'));
                    assert(!is_null($link->rel));
                    assert(!is_null($link->href));
                    assert(!is_null($link->type));
                }
            }
        }

        // Pattern: Actor JSON (ActivityPub/WebFinger.php:163)
        assert(property_exists($decodedObj, 'id'));
        assert(!is_null($decodedObj->id));
        assert(!is_null($decodedObj->inbox));
        assert(!is_null($decodedObj->publicKey));
    }

    // Test IncrementalTree JSON round-trip (Tables/MerkleState.php:438)
    try {
        $base64Input = Base64UrlSafe::encodeUnpadded($input);
        $tree = IncrementalTree::fromJson(Base64UrlSafe::decodeNoPadding($base64Input));
        assert(is_string($tree->toJson()));
        assert(is_string($tree->getEncodedRoot()));
    } catch (RangeException|TypeError|\Exception) {
        // Expected for malformed tree data
    }

    // Test deeply nested JSON (recursion safety)
    $nested = json_decode($input, true, 32); // Limit depth
    if (is_array($nested)) {
        assert(is_string(json_encode($nested, JSON_THROW_ON_ERROR)));
    }
});
