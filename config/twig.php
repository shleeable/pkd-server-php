<?php
declare(strict_types=1);
namespace FediE2EE\PKDServer\Config;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use const FediE2EE\PKDServer\PKD_SERVER_ROOT;

/* Defer to local config (if applicable) */
if (file_exists(__DIR__ . '/local/twig.php')) {
    return require_once __DIR__ . '/local/twig.php';
}

return (new Environment(
    new FilesystemLoader([PKD_SERVER_ROOT . '/templates'])
));
