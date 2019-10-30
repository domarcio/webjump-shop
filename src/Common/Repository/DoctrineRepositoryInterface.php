<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Common\Repository;

use Nogues\Common\Entity\EntityInterface;

/**
 * Contract for entity repositories.
 *
 * @package Nogues\Common\Repository
 * @author  Marcio Vinicius <marciovinicius55@gmail.com>
 */
interface DoctrineRepositoryInterface
{
    /**
     * Find one entity by ID.
     *
     * @param int $id
     *
     * @return Nogues\Common\Entity\EntityInterface
     */
    public function find(int $id): EntityInterface;

    /**
     * Find all entitoes.
     *
     * @return array Array of objects
     */
    public function findAll(): array;

    /**
     * Create or update one Entity.
     *
     * @param Nogues\Common\Entity\EntityInterface $Entity
     *
     * @return int
     */
    public function store(EntityInterface $entity): int;

    /**
     * Delete one Entity.
     *
     * @param int $id
     *
     * @return boolean
     */
    public function delete(int $id): bool;
}
