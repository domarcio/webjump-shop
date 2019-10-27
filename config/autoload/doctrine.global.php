<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

return [
    'doctrine' => [
        'orm' => [
            'auto_generate_proxy_classes' => false,
            'proxy_dir'                   => 'data/cache/EntityProxy',
            'proxy_namespace'             => 'EntityProxy',
            'underscore_naming_strategy'  => true,
        ],
        'connection' => [
            'orm_default' => [
                'host'     => 'nogues-db',
                'driver'   => 'pdo_mysql',
                'user'     => 'root',
                'password' => '123456',
                'dbname'   => 'shop',
                'charset'  => 'UTF8',
            ],
        ],
        'annotation' => [
            'metadata' => [
                './mapping/'
            ],
        ],
    ],
];
