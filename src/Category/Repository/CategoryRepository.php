<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Category\Repository;

use Doctrine\ORM\EntityManager;
use Nogues\Category\Entity\Category as CategoryEntity;
use Nogues\Common\Entity\EntityInterface;
use Nogues\Common\Repository\AbstractDoctrineRepository;

/**
 * Contract for entity repositories.
 *
 * @package Nogues\Category\Repository
 * @author  Marcio Vinicius <marciovinicius55@gmail.com>
 */
final class CategoryRepository extends AbstractDoctrineRepository
{
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
    public function find(int $id): EntityInterface
    {
        return parent::find($id) ?: new CategoryEntity();
    }
}
