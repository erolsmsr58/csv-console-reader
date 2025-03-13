<?php

declare(strict_types=1);

$autoloadPath = realpath(__DIR__ . '/vendor/autoload.php');
if ($autoloadPath === false) {
    throw new RuntimeException('Autoloader not found. Did you run "composer install"?');
}
require_once $autoloadPath;
