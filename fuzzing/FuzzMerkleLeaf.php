<?php
declare(strict_types=1);
namespace FediE2EE\PKDServer\Fuzzing;

use FediE2EE\PKD\Crypto\Merkle\InclusionProof;
use FediE2EE\PKDServer\Tables\Records\MerkleLeaf;
use ParagonIE\ConstantTime\Base64UrlSafe;
use PhpFuzzer\Config;
use RangeException;
use SodiumException;
use TypeError;

/** @var Config $config */

require_once dirname(__DIR__) . '/vendor/autoload.php';

$config->setTarget(function (string $input): void {
    // Test MerkleLeaf construction with fuzzed hex values
    try {
        // Split input into parts for different fields
        $parts = str_split($input, max(1, (int) (strlen($input) / 4)));
        $contents = $parts[0] ?? '';
        $contentHash = bin2hex($parts[1] ?? '');
        $signature = bin2hex($parts[2] ?? '');
        $publicKeyHash = bin2hex($parts[3] ?? '');

        // Ensure hex strings have even length
        if (strlen($contentHash) % 2 !== 0) {
            $contentHash .= '0';
        }
        if (strlen($signature) % 2 !== 0) {
            $signature .= '0';
        }
        if (strlen($publicKeyHash) % 2 !== 0) {
            $publicKeyHash .= '0';
        }

        $leaf = new MerkleLeaf(
            $contents,
            $contentHash,
            $signature,
            $publicKeyHash,
            null,
            (string) time()
        );

        // Exercise various methods
        $leaf->getContents();
    } catch (TypeError) {
        // Expected for malformed input
    }

    // Test getSignature with fuzzed hex
    try {
        // Create leaf with valid-length hex for sodium operations
        $sig = str_pad(bin2hex($input), 128, '0');
        $leaf = new MerkleLeaf(
            '{}',
            str_repeat('a', 64),
            $sig,
            str_repeat('b', 64)
        );
        $leaf->getSignature();
    } catch (SodiumException|RangeException|TypeError) {
        // Expected for invalid hex
    }

    // Test serializeForMerkle
    try {
        // Requires exactly 64 hex chars for each field
        if (strlen($input) >= 192) {
            $leaf = new MerkleLeaf(
                '{}',
                substr(bin2hex($input), 0, 64),
                substr(bin2hex($input), 64, 128),
                substr(bin2hex($input), 192, 64)
            );
            $leaf->serializeForMerkle();
        }
    } catch (SodiumException|RangeException|TypeError) {
        // Expected for invalid data
    }

    // Test InclusionProof construction
    try {
        $decoded = json_decode($input, true);
        if (is_array($decoded)) {
            $inclusion = new InclusionProof(
                $decoded['index'] ?? 0,
                $decoded['proof'] ?? []
            );
            assert($inclusion instanceof InclusionProof);
        }
    } catch (TypeError) {
        // Expected for malformed data
    }

    // Test Base64URL operations directly
    try {
        Base64UrlSafe::decodeNoPadding($input);
    } catch (RangeException|TypeError) {
        // Expected for invalid base64
    }
});
