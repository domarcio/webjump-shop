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
     * @var \DateTimeImmutable
     */
    private $createdAt;

    /**
     * Updated at.
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * Set dates on pre persist and update.
     *
     * @return void
     */
    public function updatedTimestamps(): void
    {
        $this->setUpdatedAt(new \DateTime('now'));
        if (null === $this->getCreatedAt()) {
            $this->setCreatedAt(new \DateTimeImmutable('now'));
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPublicId(): string
    {
        return $this->publicId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function setSku(string $sku): void
    {
        $this->sku = $sku;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getAvailableQuantity(): int
    {
        return $this->availableQuantity;
    }

    public function setavailableQuantity(int $availableQuantity): void
    {
        $this->availableQuantity = $availableQuantity;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description = null): void
    {
        $this->description = $description;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
