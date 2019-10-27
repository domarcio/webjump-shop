<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Category\Entity;

/**
 * Category entity.
 *
 * @package Nogues\Category\Entity
 * @author  Marcio Vinicius <marciovinicius55@gmail.com>
 */
final class Category
{
    /**
     * Primary Key ID.
     *
     * @var int
     */
    private $id;

    /**
     * Category name.
     *
     * @var string
     */
    private $name;

    /**
     * Category parent ID.
     *
     * @var int
     */
    private $parentId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
    }
}
