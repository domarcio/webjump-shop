<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Category\Repository;

use Doctrine\ORM\EntityManager;
use Nogues\Category\Repository\CategoryRepository;
use Psr\Container\ContainerInterface;

/**
 * Factory to work with Category.
 *
 * @package Nogues\Category\Repository
 * @author  Marcio Vinicius <marciovinicius55@gmail.com>
 */
final class CategoryRepositoryFactory
{
    public function __invoke(ContainerInterface $container): CategoryRepository
    {
        $entityManager = $container->get(EntityManager::class);
        return new CategoryRepository($entityManager);
    }
}
