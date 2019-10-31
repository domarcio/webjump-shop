<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Product\Service;

use Doctrine\ORM\EntityManager;
use Nogues\Product\Filter\ProductFilter;
use Nogues\Product\Repository\ProductRepository;
use Psr\Container\ContainerInterface;

/**
 * Factory to work with Product Service.
 *
 * @package Nogues\Product\Service
 * @author  Marcio Vinicius <marciovinicius55@gmail.com>
 */
final class ProductServiceFactory
{
    public function __invoke(ContainerInterface $container): ProductService
    {
        $entityManager = $container->get(EntityManager::class);
        $repository    = new ProductRepository($entityManager);
        $filter        = new ProductFilter();

        return new ProductService($repository, $filter);
    }
}
