<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Category\Service;

use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Nogues\Category\Entity\Category;
use Nogues\Common\Filter\FilterInterface;
use Nogues\Common\Repository\DoctrineRepositoryInterface;

final class CategoryService
{
    /**
     * Repository.
     *
     * @var Nogues\Common\Repository\DoctrineRepositoryInterface
     */
    private $repository;

    /**
     * Filter.
     *
     * @var Nogues\Category\Filter\FilterInterface
     */
    private $filter;

    public function __construct(DoctrineRepositoryInterface $repository, FilterInterface $filter)
    {
        $this->repository = $repository;
        $this->filter     = $filter;
    }

    public function store(Category $entity): bool
    {
        $this->filter->setData(['name' => $entity->getName()]);
        if (! $this->filter->isValid()) {
            return false;
        }

        return $this->repository->store($entity) > 0;
    }

    /**
     * Find all categories.
     *
     * @return array Array of objects from `Nogues\Category\Entity\Category`
     */
    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    /**
     * Find one category.
     *
     * @param int $id
     *
     * @return Nogues\Category\Entity\Category
     */
    public function findById(int $id): Category
    {
        return $this->repository->find($id);
    }

    /**
     * Find categories by IDs
     *
     * @param array $ids Integers ids
     *
     * @return array
     *
     * @throws \Exception Exception if array does not of integers.
     */
    public function findByIds(array $ids): array
    {
        return $this->repository->findByIds($ids);
    }

    /**
     * Find categories by names
     *
     * @param array $names
     *
     * @return array
     *
     * @throws \Exception Exception if array does not of strings.
     */
    public function findByNames(array $names): array
    {
        return $this->repository->findByNames($names);
    }

    /**
     * Delete one category.
     *
     * @param int $id
     *
     * @return boolean
     */
    public function deleteOne(int $id): bool
    {
        $entity = $this->findById($id);
        if (null === $entity->getId()) {
            return false;
        }

        try {
            return $this->repository->delete($id);
        } catch (ForeignKeyConstraintViolationException $e) {
            // Do nothing
        }

        return false;
    }
}
