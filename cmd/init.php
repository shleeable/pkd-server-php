<?php
declare(strict_types=1);

use GetOpt\GetOpt;
use GetOpt\Option;
use FediE2EE\PKDServer\ServerConfig;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$getopt = new GetOpt([
    Option::create(null, 'force', GetOpt::NO_ARGUMENT)
        ->setDescription('Force re-installation'),
]);
$getopt->process();

if (!($GLOBALS['config'] instanceof ServerConfig)) {
    throw new TypeError();
}
$db = $GLOBALS['config']->getDb();
$driver = $db->getDriver();
$dbDriver = $driver;
if ($dbDriver === 'pgsql') {
    $dbDriver = 'postgresql';
}

$dir = dirname(__DIR__) . '/sql/' . $dbDriver;
if (!is_dir($dir)) {
    echo 'Unsupported driver: ', $driver, PHP_EOL;
    exit(1);
}

$files = scandir($dir);
foreach ($files as $file) {
    if (in_array($file, ['.', '..'])) {
        continue;
    }
    $path = $dir . '/' . $file;
    if (is_file($path)) {
        if (pathinfo($path, PATHINFO_EXTENSION) !== 'sql') {
            continue;
        }
        $sql = file_get_contents($path);
        if (empty($sql)) {
            continue;
        }
        try {
            $db->exec($sql);
        } catch (Throwable $ex) {
            echo 'Error running ', $path, ':', PHP_EOL,
            $ex->getMessage(), PHP_EOL;
            exit(1);
        }
    }
}
