<?php
declare(strict_types=1);
namespace FediE2EE\PKDServer\Config;

use ParagonIE\Certainty\RemoteFetch;
use const FediE2EE\PKDServer\PKD_SERVER_ROOT;

if (file_exists(__DIR__ . '/local/certainty.php')) {
    return require_once __DIR__ . '/local/certainty.php';
}

return new RemoteFetch(PKD_SERVER_ROOT . '/tmp');
