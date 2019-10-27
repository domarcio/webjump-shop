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
            Nogues\Category\Repository\CategoryRepository::class  => Nogues\Category\Repository\CategoryRepositoryFactory::class,
            Nogues\Category\Service\CategoryService::class        => Nogues\Category\Service\CategoryServiceFactory::class
        ],
    ],
];
