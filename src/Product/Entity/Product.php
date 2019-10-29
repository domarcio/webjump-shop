<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Product\Entity;

use Nogues\Category\Entity\Category;
use Nogues\Common\Entity\EntityInterface;
use Ramsey\Uuid\Uuid;

/**
 * Product entity.
 *
 * @package Nogues\Product\Entity
 * @author  Marcio Vinicius <marciovinicius55@gmail.com>
 */
class Product implements EntityInterface
{
    /**
     * Primary Key ID.
     *
     * @var int
     */
    protected $id;

    /**
     * Public ID.
     *
     * @var \Ramsey\Uuid\UuidInterface
     */
    protected $publicId;

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
     * @var \DateTimeImmutable
     */
    protected $createdAt;

    /**
     * Updated at.
     *
     * @var \DateTime
     */
    protected $updatedAt;

    /**
     * Array of Category entitiy.
     *
     * @var array
     */
    private $categories = [];

    public function __construct()
    {
        $this->publicId = Uuid::uuid4();
    }

    /**
     * Set dates on pre persist and update.
     *
     * @return void
     */
    public function updatedTimestamps(): void
    {
        $this->updatedAt = new \DateTime('now');
        if (null === $this->getCreatedAt()) {
            $this->createdAt = new \DateTimeImmutable('now');
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPublicId(): ?\Ramsey\Uuid\UuidInterface
    {
        return $this->publicId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(string $sku): void
    {
        $this->sku = $sku;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getAvailableQuantity(): ?int
    {
        return $this->availableQuantity;
    }

    public function setAvailableQuantity(int $availableQuantity): void
    {
        $this->availableQuantity = $availableQuantity;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description = null): void
    {
        $this->description = $description;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * Add a category to product.
     *
     * @param Category $category
     *
     * @return void
     */
    public function addCategory(Category $category): void
    {
        $this->categories[] = $category;
    }
}
