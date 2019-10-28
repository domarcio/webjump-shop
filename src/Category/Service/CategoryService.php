<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Category\Service;

use Nogues\Category\Entity\Category;
use Nogues\Category\Filter\FilterInterface;
use Nogues\Category\Repository\CategoryRepositoryInterface;

final class CategoryService
{
    /**
     * Repository.
     *
     * @var Nogues\Category\Repository\CategoryRepositoryInterface
     */
    private $repository;

    /**
     * Filter.
     *
     * @var Nogues\Category\Filter\FilterInterface
     */
    private $filter;

    public function __construct(CategoryRepositoryInterface $repository, FilterInterface $filter)
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

        return $this->repository->delete($id);
    }
}