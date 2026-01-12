<?php
declare(strict_types=1);
namespace FediE2EE\PKDServer\Fuzzing;

use FediE2EE\PKDServer\ActivityPub\ActivityStream;
use FediE2EE\PKDServer\Exceptions\ActivityPubException;
use JsonException;
use PhpFuzzer\Config;
use stdClass;
use TypeError;

/** @var Config $config */

require_once dirname(__DIR__) . '/vendor/autoload.php';

$config->setTarget(function (string $input): void {
    // Test fromString() path
    try {
        $stream = ActivityStream::fromString($input);

        // Exercise serialization methods
        assert(is_string($stream->jsonSerialize()));
        assert(strlen((string) $stream) > 0);
        assert($stream->isDirectMessage());
    } catch (ActivityPubException|JsonException|TypeError) {
        // Expected for malformed input
    }

    // Test fromDecoded() path with decoded input
    try {
        $decoded = json_decode($input, false);
        if ($decoded instanceof stdClass) {
            $stream = ActivityStream::fromDecoded($decoded);

            // Exercise property access
            assert(property_exists($stream, 'id'));
            assert(property_exists($stream, 'type'));
            assert(property_exists($stream, 'actor'));
            assert(is_string($stream->id));
            assert(is_string($stream->type));
            assert(is_string($stream->actor));
            if (property_exists($stream, 'object')) {
                assert($stream->isDirectMessage());
            }
        }
    } catch (ActivityPubException|JsonException|TypeError) {
        // Expected for malformed input
    }
});
