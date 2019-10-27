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
class Category
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
     * Parent category.
     *
     * @var self
     */
    private $parent;

    /**
     * Children categories.
     *
     * @var \Doctrine\ORM\PersistentCollection
     */
    private $children;

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

    public function getParent(): self
    {
        return $this->parent;
    }

    public function setParent(self $parent): void
    {
        $this->parent = $parent;
    }

    public function getChildren(): ?\Doctrine\ORM\PersistentCollection
    {
        return $this->children;
    }
}
