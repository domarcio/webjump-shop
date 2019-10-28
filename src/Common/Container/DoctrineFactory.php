<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Common\Container;

use Doctrine\Common\Cache\Cache;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\YamlDriver;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;

use Interop\Container\ContainerInterface;

/**
 * Factory for create a Doctrine configurations.
 *
 * @package Nogues\Common\Container
 * @author  Marcio Vinicius <marciovinicius55@gmail.com>
 */
final class DoctrineFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->has('config') ? $container->get('config') : [];
        $config = $config['doctrine'] ?? false;

        if (empty($config)) {
            throw new \Exception('Doctrine configuration does not available.');
        }

        // Configurations
        $proxyDir                 = $config['orm']['proxy_dir'] ?? 'data/cache/EntityProxy';
        $proxyNamespace           = $config['orm']['proxy_namespace'] ?? 'EntityProxy';
        $autoGenerateProxyClasses = $config['orm']['auto_generate_proxy_classes'] ?? false;
        $underscoreNamingStrategy = $config['orm']['underscore_naming_strategy'] ?? false;

        // Doctrine ORM
        $doctrine = new Configuration();
        $doctrine->setProxyDir($proxyDir);
        $doctrine->setProxyNamespace($proxyNamespace);
        $doctrine->setAutoGenerateProxyClasses($autoGenerateProxyClasses);

        if ($underscoreNamingStrategy) {
            $doctrine->setNamingStrategy(new UnderscoreNamingStrategy());
        }

        // ORM Mapping by YAML
        $driver = new YamlDriver($config['annotation']['metadata']);
        $doctrine->setMetadataDriverImpl($driver);

        // Cache
        $cache = $container->get(Cache::class);
        $doctrine->setQueryCacheImpl($cache);
        $doctrine->setResultCacheImpl($cache);
        $doctrine->setMetadataCacheImpl($cache);

        $entityManager = EntityManager::create($config['connection']['orm_default'], $doctrine);

        $types = $config['configuration']['orm_default']['types'] ?? [];
        foreach ($types as $name => $className) {
            if (! Type::hasType($name)) {
                Type::addType($name, $className);
                $entityManager->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping($name, $name);
            }
        }

        return $entityManager;
    }
}
