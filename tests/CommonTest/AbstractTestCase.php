<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Test\CommonTest;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\Mapping\Driver\YamlDriver;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

abstract class AbstractTestCase extends TestCase
{
    protected function getEntityManager()
    {
        // Doctrine ORM
        $doctrine = new Configuration();
        $doctrine->setProxyDir(__DIR__ . '/../../data/cache/TestEntityProxy/');
        $doctrine->setProxyNamespace('TestEntityProxy');
        $doctrine->setAutoGenerateProxyClasses(true);

        // Cache
        $cache = new ArrayCache();
        $doctrine->setQueryCacheImpl($cache);
        $doctrine->setResultCacheImpl($cache);
        $doctrine->setMetadataCacheImpl($cache);

        // ORM Mapping by YAML
        $driver = new YamlDriver(__DIR__ . '/../../mapping/');
        $doctrine->setMetadataDriverImpl($driver);

        $connection = [
            'host'     => 'nogues-db',
            'driver'   => 'pdo_mysql',
            'user'     => 'root',
            'password' => '123456',
            'dbname'   => 'shop_test',
            'charset'  => 'UTF8',
        ];

        $entityManager = EntityManager::create($connection, $doctrine);

        $container = $this->prophesize(ContainerInterface::class);
        $container
            ->get(EntityManager::class)
            ->willReturn($entityManager);

        return $container;
    }
}
