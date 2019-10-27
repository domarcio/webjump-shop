<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Category\Repository;

use \Exception;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\UnitOfWork;
use Nogues\Category\Entity\Category as CategoryEntity;

/**
 * Contract for entity repositories.
 *
 * @package Nogues\Category\Repository
 * @author  Marcio Vinicius <marciovinicius55@gmail.com>
 */
final class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * Entity Manager.
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Entity name.
     *
     * @var string
     */
    private $entityName;

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
        $repository = $this->entityManager->getRepository($this->entityName);
        $entity     = $repository->find($id);
        return $entity ?: new CategoryEntity();
    }

    /**
     * Find all categories.
     *
     * @return array Array of objects from `Nogues\Category\Entity\Category`
     */
    public function findAll(): array
    {
        $categoryRepository = $this->entityManager->getRepository($this->entityName);
        return $categoryRepository->findAll();
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
        $states      = [UnitOfWork::STATE_NEW, UnitOfWork::STATE_MANAGED];
        $entityState = 0;

        try {
            $this->entityManager->persist($category);
            $this->entityManager->flush();

            $entityState = $this->entityManager->getUnitOfWork()->getEntityState($category);
        } catch (Exception $e) {
            return false;
        }

        return in_array($entityState, $states) ? 1 : 0;
    }

    /**
     * Delete one Category.
     *
     * @param int $id
     *
     * @return boolean
     */
    public function delete(int $id): bool
    {
        $em = $this->entityManager;
        $entityState = 0;

        try {
            $category = $em->getReference($this->entityName, $id);
            $em->remove($category);

            $entityState = $em->getUnitOfWork()->getEntityState($category);
            $em->flush();
        } catch (Exception $e) {
            return false;
        }

        return $entityState === UnitOfWork::STATE_REMOVED;
    }
}
