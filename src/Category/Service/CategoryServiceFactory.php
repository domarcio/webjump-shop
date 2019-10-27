<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Category\Service;

use Doctrine\ORM\EntityManager;
use Nogues\Category\Filter\CategoryFilter;
use Nogues\Category\Repository\CategoryRepository;
use Psr\Container\ContainerInterface;

/**
 * Factory to work with Category Service.
 *
 * @package Nogues\Category\Service
 * @author  Marcio Vinicius <marciovinicius55@gmail.com>
 */
final class CategoryServiceFactory
{
    public function __invoke(ContainerInterface $container): CategoryService
    {
        $entityManager = $container->get(EntityManager::class);
        $repository    = new CategoryRepository($entityManager);
        $filter        = new CategoryFilter();

        return new CategoryService($repository, $filter);
    }
}
