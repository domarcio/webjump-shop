<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Category\Repository;

use \Exception;

use Doctrine\ORM\EntityManager;
use Nogues\Category\Entity\Category as CategoryEntity;
use Nogues\Common\Repository\DoctrineRepositoryTrait;

/**
 * Contract for entity repositories.
 *
 * @package Nogues\Category\Repository
 * @author  Marcio Vinicius <marciovinicius55@gmail.com>
 */
final class CategoryRepository implements CategoryRepositoryInterface
{
    use DoctrineRepositoryTrait {
        find as traitFind;
        store as traitStore;
    }

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->entityName    = CategoryEntity::class;
    }

    /**
     * Find one category by ID.
     *
     * @param int $id
     *
     * @return Nogues\Category\Entity\Category
     */
    public function find(int $id): CategoryEntity
    {
        return $this->traitFind($id) ?: new CategoryEntity();
    }

    /**
     * Create or update one Category.
     *
     * @param Nogues\Category\Entity\Category $category
     *
     * @return int
     */
    public function store(CategoryEntity $category): int
    {
        return $this->traitStore($category);
    }
}
