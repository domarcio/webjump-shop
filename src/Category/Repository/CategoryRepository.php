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
final class CategoryRepository extends AbstractDoctrineRepository implements CategoryRepositoryInterface
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

    /**
     * @inheritDoc
     */
    public function findByIds(array $ids): array
    {
        $ids = filter_var_array($ids, FILTER_VALIDATE_INT);
        $ids = array_filter($ids);
        if (empty($ids)) {
            throw new \Exception('Categories IDs are empty.');
        }

        $repository = $this->entityManager->getRepository($this->entityName);
        return $repository->findBy(['id' => $ids]);
    }

    /**
     * @inheritDoc
     */
    public function findByNames(array $names): array
    {
        $names = array_filter($names, function ($value) {
            return is_string($value);
        });

        if (empty($names)) {
            throw new \Exception('Names are empty.');
        }

        $repository = $this->entityManager->getRepository($this->entityName);
        return $repository->findBy(['name' => $names]);
    }
}
