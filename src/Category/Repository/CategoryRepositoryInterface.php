<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Category\Repository;

/**
 * Contract for contract repository.
 *
 * @package Nogues\Category\Repository
 * @author  Marcio Vinicius <marciovinicius55@gmail.com>
 */
interface CategoryRepositoryInterface
{
    /**
     * Find categories by IDs
     *
     * @param array $ids Integers ids
     *
     * @return array
     *
     * @throws \Exception Exception if array does not of integers.
     */
    public function findByIds(array $ids): array;

    /**
     * Find categories by names
     *
     * @param array $names
     *
     * @return array
     *
     * @throws \Exception Exception if array does not of strings.
     */
    public function findByNames(array $names): array;
}
