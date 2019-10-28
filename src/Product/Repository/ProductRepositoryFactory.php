<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Product\Repository;

use Doctrine\ORM\EntityManager;
use Nogues\Product\Repository\ProductRepository;
use Psr\Container\ContainerInterface;

/**
 * Factory to work with Product.
 *
 * @package Nogues\Product\Repository
 * @author  Marcio Vinicius <marciovinicius55@gmail.com>
 */
final class ProductRepositoryFactory
{
    public function __invoke(ContainerInterface $container): ProductRepository
    {
        $entityManager = $container->get(EntityManager::class);
        return new ProductRepository($entityManager);
    }
}
