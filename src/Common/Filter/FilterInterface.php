<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Common\Filter;

interface FilterInterface
{
    /**
     * Set data with values to filter.
     *
     * @param array $data
     *
     * @return void
     */
    public function setData(array $data): void;

    /**
     * True if data is valid.
     * @return boolean
     */
    public function isValid(): bool;

    /**
     * Get filter messages.
     *
     * @return Generator
     */
    public function getMessages(): \Generator;
}
