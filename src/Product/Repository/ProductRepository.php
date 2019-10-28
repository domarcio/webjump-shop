<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Product\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\UnitOfWork;
use Nogues\Product\Entity\Product;

/**
 * Product repository.
 *
 * @package Nogues\Product\Repository
 * @author  Marcio Vinicius <marciovinicius55@gmail.com>
 */
final class ProductRepository implements ProductRepositoryInterface
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
        $this->entityName    = Product::class;
    }

    /**
     * Find one product by ID.
     *
     * @param int $id
     *
     * @return Nogues\Product\Entity\Product
     */
    public function find(int $id): Product
    {
        $repository = $this->entityManager->getRepository($this->entityName);
        $entity     = $repository->find($id);
        return $entity ?: new Product();
    }

    /**
     * Find one product by public (uuid) ID.
     *
     * @param string $publicId
     *
     * @return Nogues\Product\Entity\Product
     */
    public function findByPublicId(string $publicId): Product
    {
        $repository = $this->entityManager->getRepository($this->entityName);
        $entity     = $repository->findOneBy(['publicId' => $publicId]);
        return $entity ?: new Product();
    }

    /**
     * Find all products.
     *
     * @return array Array of objects from `Nogues\Product\Entity\Product`
     */
    public function findAll(): array
    {
        return [];
    }

    /**
     * Create or update one Product.
     *
     * @param Nogues\Product\Entity\Product $product
     *
     * @return int
     */
    public function store(Product $product): int
    {
        $states      = [UnitOfWork::STATE_NEW, UnitOfWork::STATE_MANAGED];
        $entityState = 0;

        try {
            $this->entityManager->persist($product);
            $this->entityManager->flush();

            $entityState = $this->entityManager->getUnitOfWork()->getEntityState($product);
        } catch (Exception $e) {
            return false;
        }

        return in_array($entityState, $states) ? 1 : 0;
    }

    /**
     * Delete one Product.
     *
     * @param int $id
     *
     * @return boolean
     */
    public function delete(int $id): bool
    {
        return 0;
    }
}
