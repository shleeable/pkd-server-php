<?php
declare(strict_types=1);
namespace FediE2EE\PKDServer\Fuzzing;

use FediE2EE\PKD\Crypto\Merkle\{
    InclusionProof,
    IncrementalTree,
    Tree
};
use ParagonIE\ConstantTime\Base64UrlSafe;
use PhpFuzzer\Config;
use RangeException;
use TypeError;

/** @var Config $config */

require_once dirname(__DIR__) . '/vendor/autoload.php';

$config->setTarget(function (string $input): void {
    // Test InclusionProof construction
    try {
        $decoded = json_decode($input, true);
        if (is_array($decoded)) {
            $index = $decoded['index'] ?? 0;
            $proof = $decoded['proof'] ?? [];

            if (is_int($index) && is_array($proof)) {
                $inclusionProof = new InclusionProof($index, $proof);

                // Access properties
                assert(property_exists($inclusionProof, 'index'));
                assert(property_exists($inclusionProof, 'proof'));
                assert(is_int($inclusionProof->index));
                assert(is_string($inclusionProof->proof));
            }
        }
    } catch (TypeError|\Exception) {
        // Expected for malformed data
    }

    // Test IncrementalTree::fromJson
    try {
        $tree = IncrementalTree::fromJson($input);
        assert(is_string($tree->toJson()));
        assert(is_string($tree->getRoot()));
        assert(is_string($tree->getEncodedRoot()));
    } catch (TypeError|RangeException|\Exception) {
        // Expected for malformed data
    }

    // Test IncrementalTree with Base64 encoded input (codebase pattern)
    try {
        $tree = IncrementalTree::fromJson(Base64UrlSafe::decodeNoPadding($input));
        $encoded = Base64UrlSafe::encodeUnpadded($tree->toJson());

        // Verify round-trip
        $from = IncrementalTree::fromJson(Base64UrlSafe::decodeNoPadding($encoded));
        assert($from instanceof IncrementalTree);
    } catch (TypeError|RangeException|\Exception) {
        // Expected for malformed data
    }

    // Test Tree construction with leaf data
    try {
        $leaves = [];
        if (strlen($input) >= 32) {
            // Split input into 32-byte chunks as leaf hashes
            $chunks = str_split($input, 32);
            foreach ($chunks as $chunk) {
                if (strlen($chunk) === 32) {
                    $leaves[] = $chunk;
                }
            }
        }

        if (!empty($leaves)) {
            $tree = new Tree($leaves, 'sha256');
            $root = $tree->getRoot();
            $tree->getEncodedRoot();

            // Test inclusion proof generation and verification
            if (count($leaves) > 0) {
                $proof = $tree->getInclusionProof($leaves[0]);
                $tree->verifyInclusionProof($root, $leaves[0], $proof);
            }
        }
    } catch (TypeError|\Exception) {
        // Expected for edge cases
    }

    // Test IncrementalTree operations
    try {
        $tree = new IncrementalTree([], 'sha256');

        // Add leaves from fuzzed input
        $chunks = str_split($input, 32);
        foreach ($chunks as $chunk) {
            if (strlen($chunk) > 0) {
                $tree->addLeaf($chunk);
            }
        }

        $tree->updateRoot();
        $root = $tree->getRoot();

        // Verify proofs for added leaves
        foreach ($chunks as $chunk) {
            if (strlen($chunk) > 0) {
                $proof = $tree->getInclusionProof($chunk);
                $tree->verifyInclusionProof($root, $chunk, $proof);
            }
        }
    } catch (TypeError|\Exception) {
        // Expected for edge cases
    }
});
