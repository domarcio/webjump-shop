<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Product\Service;

use Nogues\Common\Filter\FilterInterface;
use Nogues\Common\Notification;
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

    /**
     * Notifications of service.
     *
     * @var Nogues\Common\Notification
     */
    private $notification;

    public function __construct(DoctrineRepositoryInterface $repository, FilterInterface $filter, Notification $notification)
    {
        $this->repository   = $repository;
        $this->filter       = $filter;
        $this->notification = $notification;
    }

    /**
     * Create or update a Product.
     *
     * @param Product $entity
     *
     * @return int
     */
    public function store(Product $entity): bool
    {
        $this->filter->setData([
            'name'               => $entity->getName(),
            'sku'                => $entity->getSku(),
            'price'              => $entity->getPrice(),
            'available_quantity' => $entity->getAvailableQuantity(),
        ]);

        if ($this->filter->isValid()) {
            return $this->repository->store($entity) > 0;
        }

        foreach ($this->filter->getMessages() as $message) {
            $this->notification->add($message);
        }

        return false;
    }

    /**
     * Get notification.
     *
     * @return Nogues\Common\Notification
     */
    public function getNotification(): \Nogues\Common\Notification
    {
        return $this->notification;
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
     * Find one product by public (uuid) ID.
     *
     * @param string $publicId
     *
     * @return Nogues\Product\Entity\Product
     */
    public function findByPublicId(string $id): Product
    {
        return $this->repository->findByPublicId($id);
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
