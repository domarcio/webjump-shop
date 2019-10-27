<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Category\Repository;

use Nogues\Category\Entity\Category;

/**
 * Contract for entity repositories.
 *
 * @package Nogues\Category\Repository
 * @author  Marcio Vinicius <marciovinicius55@gmail.com>
 */
interface CategoryRepositoryInterface
{
    /**
     * Find one category by ID.
     *
     * @param int $id
     *
     * @return Nogues\Category\Entity\Category
     */
    public function find(int $id): Category;

    /**
     * Find all categories.
     *
     * @return array Array of objects from `Nogues\Category\Entity\Category`
     */
    public function findAll(): array;

    /**
     * Create or update one Category.
     *
     * @param Nogues\Category\Entity\Category $category
     *
     * @return int
     */
    public function store(Category $category): int;

    /**
     * Delete one Category.
     *
     * @param int $id
     *
     * @return boolean
     */
    public function delete(int $id): bool;
}
