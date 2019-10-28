<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Product\Entity;

/**
 * Product entity.
 *
 * @package Nogues\Product\Entity
 * @author  Marcio Vinicius <marciovinicius55@gmail.com>
 */
class Product
{
    /**
     * Primary Key ID.
     *
     * @var int
     */
    private $id;

    /**
     * Public ID.
     *
     * @var string
     */
    private $publicId;

    /**
     * Name.
     *
     * @var string
     */
    private $name;

    /**
     * SKU.
     *
     * @var string
     */
    private $sku;

    /**
     * Price.
     *
     * @var float
     */
    private $price;

    /**
     * Price.
     *
     * @var int
     */
    private $availableQuantity;

    /**
     * Description.
     *
     * @var string
     */
    private $description;

    /**
     * Created at.
     *
     * @var \DateTime
     */
    private $createdAt;

    /**
     * Updated at.
     *
     * @var \DateTime
     */
    private $updatedAt;
}
