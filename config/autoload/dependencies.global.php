<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

return [
    'dependencies' => [
        'factories' => [
            // Doctrine fatories
            Doctrine\Common\Cache\Cache::class => Nogues\Common\Container\DoctrineArrayCacheFactory::class,
            Doctrine\ORM\EntityManager::class  => Nogues\Common\Container\DoctrineFactory::class,

            // Category app factories
            Nogues\Category\Repository\Category::class => Nogues\Category\Repository\CategoryFactory::class
        ],
    ],
];
