<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

$categoryService = $container->get(Nogues\Category\Service\CategoryService::class);

if ('add' === $action) {
    $categories = $categoryService->findAll();

    require 'template/addCategory.php';
    exit;
}