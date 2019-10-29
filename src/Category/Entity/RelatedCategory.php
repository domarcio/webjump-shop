<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Category\Entity;

/**
 * RelatedCategory entity.
 *
 * @package Nogues\Category\Entity
 * @author  Marcio Vinicius <marciovinicius55@gmail.com>
 */
class RelatedCategory
{
    /**
     * Primary Key ID
     *
     * @var int
     */
    private $id;

    /**
     * Category ID.
     *
     * @var int
     */
    private $categoryId;

    /**
     * Related Entity ID.
     *
     * @var int
     */
    private $relatedId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    public function getRelatedId(): ?int
    {
        return $this->relatedId;
    }

    public function setRelatedId(int $relatedId): void
    {
        $this->relatedId = $relatedId;
    }
}
