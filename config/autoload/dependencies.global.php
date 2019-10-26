<?php

declare(strict_types=1);

return [
    'dependencies' => [
        'factories' => [
            Doctrine\Common\Cache\Cache::class => Nogues\Common\Container\DoctrineArrayCacheFactory::class,
            Doctrine\ORM\EntityManager::class  => Nogues\Common\Container\DoctrineFactory::class,
        ],
    ],
];
