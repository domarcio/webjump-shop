<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Common\Repository;

use Doctrine\ORM\UnitOfWork;
use Nogues\Common\Entity\EntityInterface;

/**
 * Common abstract repostitory to works with Doctrine repositories.
 *
 * @package Nogues\Common\Repository
 * @author  Marcio Vinicius <marciovinicius55@gmail.com>
 */
abstract class AbstractDoctrineRepository implements DoctrineRepositoryInterface
{
    /**
     * Entity Manager.
     *
     * @var Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * Entity name.
     *
     * @var string
     */
    protected $entityName;

    /**
     * Find an Entity by ID.
     *
     * @param int $id
     *
     * @return EntityInterface
     */
    public function find(int $id): EntityInterface
    {
        $entityName = $this->entityName;
        $repository = $this->entityManager->getRepository($this->entityName);
        return $repository->find($id) ?: new $entityName();
    }

    /**
     * Find all entities.
     *
     * @return array Array of objects from Entity
     */
    public function findAll(): array
    {
        $repository = $this->entityManager->getRepository($this->entityName);
        return $repository->findAll();
    }

    /**
     * Create or update an Entity.
     *
     * @param EntityInterface $entity
     *
     * @return int
     */
    public function store(EntityInterface $entity): int
    {
        $states      = [UnitOfWork::STATE_NEW, UnitOfWork::STATE_MANAGED];
        $entityState = 0;

        try {
            $this->entityManager->persist($entity);
            $this->entityManager->flush();

            $entityState = $this->entityManager->getUnitOfWork()->getEntityState($entity);
        } catch (\Exception $e) {
            throw $e;
        }

        return in_array($entityState, $states) ? 1 : 0;
    }

    /**
     * Delete entity.
     *
     * @param int $id
     *
     * @return boolean
     */
    public function delete(int $id): bool
    {
        $em = $this->entityManager;
        $entityState = 0;

        $entity = $this->find($id);
        if (null === $entity->getId()) {
            return false;
        }

        try {
            $em->remove($entity);

            $entityState = $em->getUnitOfWork()->getEntityState($entity);
            $em->flush();
        } catch (\Exception $e) {
            throw $e;
        }

        return $entityState === UnitOfWork::STATE_REMOVED;
    }
}
