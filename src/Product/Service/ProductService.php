<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Product\Service;

use Nogues\Common\Filter\FilterInterface;
use Nogues\Product\Entity\Product;
use Nogues\Common\Repository\DoctrineRepositoryInterface;

final class ProductService
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
     * @var Nogues\Product\Filter\ProductFilter
     */
    private $filter;

    public function __construct(DoctrineRepositoryInterface $repository, FilterInterface $filter)
    {
        $this->repository = $repository;
        $this->filter     = $filter;
    }

    public function store(Product $entity): bool
    {
        $this->filter->setData([
            'name'               => $entity->getName(),
            'sku'                => $entity->getSku(),
            'price'              => $entity->getPrice(),
            'available_quantity' => $entity->getAvailableQuantity(),
        ]);

        if (! $this->filter->isValid()) {
            return false;
        }

        return $this->repository->store($entity) > 0;
    }

    /**
     * Find all products.
     *
     * @return array Array of objects from `Nogues\Product\Entity\Product`
     */
    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    /**
     * Find one product.
     *
     * @param int $id
     *
     * @return Nogues\Product\Entity\Product
     */
    public function findById(int $id): Product
    {
        return $this->repository->find($id);
    }

    /**
     * Delete one product.
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
