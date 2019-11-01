#!/usr/bin/env php
<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

// Run only by terminal
if (PHP_SAPI !== 'cli') {
    exit(0);
}

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

use Doctrine\ORM\EntityManager;
use Nogues\Product\Console\Command\ImportFileCommand;
use Symfony\Component\Console\Application;

/**
 * Self-called anonymous function that creates its own scope and keep the global namespace clean.
 */
(function () {
    /** @var \Psr\Container\ContainerInterface $container */
    $container       = require 'config/container.php';
    $entityManager   = $container->get(EntityManager::class);

    $application = new Application();

    $application->add(new ImportFileCommand($entityManager));

    $application->run();
})();
