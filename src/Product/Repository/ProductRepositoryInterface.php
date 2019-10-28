<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Product\Repository;

use Nogues\Product\Entity\Product;

/**
 * Contract for entity repositories.
 *
 * @package Nogues\Product\Repository
 * @author  Marcio Vinicius <marciovinicius55@gmail.com>
 */
interface ProductRepositoryInterface
{
    /**
     * Find one product by ID.
     *
     * @param int $id
     *
     * @return Nogues\Product\Entity\Product
     */
    public function find(int $id): Product;

    /**
     * Find one product by public (uuid) ID.
     *
     * @param string $publicId
     *
     * @return Nogues\Product\Entity\Product
     */
    public function findByPublicId(string $publicId): Product;

    /**
     * Find all products.
     *
     * @return array Array of objects from `Nogues\Product\Entity\Product`
     */
    public function findAll(): array;

    /**
     * Create or update one Product.
     *
     * @param Nogues\Product\Entity\Product $product
     *
     * @return int
     */
    public function store(Product $product): int;

    /**
     * Delete one Product.
     *
     * @param int $id
     *
     * @return boolean
     */
    public function delete(int $id): bool;
}
