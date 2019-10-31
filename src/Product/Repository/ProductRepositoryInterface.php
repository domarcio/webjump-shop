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
     * Find one product by public (uuid) ID.
     *
     * @param string $publicId
     *
     * @return Nogues\Product\Entity\Product
     */
    public function findByPublicId(string $publicId): Product;

    /**
     * Delete a Product public (uuid) ID.
     *
     * @param string $publicId
     *
     * @return boolean
     */
    public function deleteByPublicId(string $publicId): bool;
}
