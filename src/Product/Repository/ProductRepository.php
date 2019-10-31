<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Product\Repository;

use Doctrine\ORM\EntityManager;
use Nogues\Common\Repository\AbstractDoctrineRepository;
use Nogues\Product\Entity\Product;

/**
 * Product repository.
 *
 * @package Nogues\Product\Repository
 * @author  Marcio Vinicius <marciovinicius55@gmail.com>
 */
final class ProductRepository extends AbstractDoctrineRepository implements ProductRepositoryInterface
{
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->entityName    = Product::class;
    }

    /**
     * @inheritDoc
     */
    public function findByPublicId(string $publicId): Product
    {
        $repository = $this->entityManager->getRepository($this->entityName);
        $entity     = $repository->findOneBy(['publicId' => $publicId]);
        return $entity ?: new Product();
    }

    /**
     * @inheritDoc
     */
    public function deleteByPublicId(string $publicId): bool
    {
        $repository = $this->entityManager->getRepository($this->entityName);
        $entity     = $repository->findOneBy(['publicId' => $publicId]);

        return $this->delete($entity->getId());
    }
}
