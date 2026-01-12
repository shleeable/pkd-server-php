# Fuzzing Targets

This directory contains fuzzing targets for [nikic/php-fuzzer](https://github.com/nikic/php-fuzzer).

## Quick Start

```terminal
# Install php-fuzzer globally
composer global require nikic/php-fuzzer

# Run a specific target
php-fuzzer fuzz fuzzing/FuzzActivityStream.php

# Run with iteration limit
php-fuzzer fuzz fuzzing/FuzzProtocol.php --max-runs 50000
```

## Adding New Targets

Create a new `Fuzz*.php` file in this directory.

```php
<?php
declare(strict_types=1);
namespace FediE2EE\PKDServer\Fuzzing;

use PhpFuzzer\Config;

/** @var Config $config */

require_once dirname(__DIR__) . '/vendor/autoload.php';

$config->setTarget(function (string $input): void {
    // Your fuzzing logic here
    // Catch expected exceptions, let unexpected ones crash
});
```

Finally, add the target to CI in `.github/workflows/fuzz.yml`.
