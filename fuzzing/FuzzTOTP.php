<?php
declare(strict_types=1);
namespace FediE2EE\PKDServer\Fuzzing;

use PhpFuzzer\Config;
use TypeError;
use ValueError;

/** @var Config $config */

require_once dirname(__DIR__) . '/vendor/autoload.php';

/**
 * Reimplementation of TOTPTrait methods for direct fuzzing
 */
class TOTPFuzzer
{
    public const int TOTP_WINDOW_TIME = 30;

    public static function generateTOTP(string $secret, ?int $time = null): string
    {
        if (is_null($time)) {
            $time = time();
        }
        $currentTime = (int) floor($time / self::TOTP_WINDOW_TIME);
        $binaryTime = pack('N*', 0) . pack('N*', $currentTime);
        $hash = hash_hmac('sha512', $binaryTime, $secret, true);
        $offset = self::ord(substr($hash, -1)) & 0x0F;
        $truncatedHash = substr($hash, $offset, 4);
        $value = unpack('N', $truncatedHash)[1];
        $value &= 0x7FFFFFFF;
        return substr(sprintf('%08d', $value), -8);
    }

    public static function verifyTOTP(string $secret, string $otp, int $windows = 2): bool
    {
        $time = time();
        for ($i = -$windows; $i <= $windows; $i++) {
            $trialTime = $time + ($i * self::TOTP_WINDOW_TIME);
            $expected = self::generateTOTP($secret, $trialTime);
            if (hash_equals($expected, $otp)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Constant-time ord() using unpack()
     */
    public static function ord(string $chr): int
    {
        return unpack('C', $chr)[1];
    }
}

$config->setTarget(function (string $input): void {
    // Test generateTOTP with arbitrary secrets
    try {
        // Use fuzzed input as secret
        if (strlen($input) > 0) {
            TOTPFuzzer::generateTOTP($input);

            // Test with various time values
            TOTPFuzzer::generateTOTP($input, 0);
            TOTPFuzzer::generateTOTP($input, PHP_INT_MAX);
            TOTPFuzzer::generateTOTP($input, -1);
        }
    } catch (TypeError|ValueError) {
        // Expected for edge cases
    }

    // Test verifyTOTP with arbitrary input
    try {
        if (strlen($input) >= 8) {
            $secret = substr($input, 0, max(1, strlen($input) - 8));
            $otp = substr($input, -8);
            TOTPFuzzer::verifyTOTP($secret, $otp);
        }
    } catch (TypeError|ValueError) {
        // Expected for edge cases
    }

    // Test constant-time ord()
    try {
        if (strlen($input) > 0) {
            for ($i = 0; $i < min(16, strlen($input)); $i++) {
                TOTPFuzzer::ord($input[$i]);
            }
        }
    } catch (TypeError|ValueError) {
        // Expected for edge cases
    }

    // Test pack/unpack operations directly
    try {
        // Pattern from generateTOTP
        if (strlen($input) >= 4) {
            $binaryData = substr($input, 0, 4);
            $unpacked = unpack('N', $binaryData);
            if ($unpacked !== false) {
                assert(pack('N*', $unpacked[1]));
            }
        }

        // Variable-length unpack
        if (strlen($input) >= 8) {
            assert(unpack('N*', $input));
        }
    } catch (TypeError|ValueError) {
        // Expected for edge cases
    }
});
