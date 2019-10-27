<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

// Delegate static file requests back to the PHP built-in webserver
if (PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__) {
    return false;
}

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/**
 * Self-called anonymous function that creates its own scope and keep the global namespace clean.
 */
(function () {
    /** @var \Psr\Container\ContainerInterface $container */
    $container = require 'config/container.php';

    $handler = filter_input(INPUT_GET, 'handler', FILTER_SANITIZE_STRING);
    $action  = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);

    $fileHandler = 'routes/' . $handler . '.php';
    if (! file_exists($fileHandler)) {
        throw new Exception('Handler does not exists.');
    }

    require $fileHandler;
})();
