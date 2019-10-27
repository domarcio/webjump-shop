<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

$categoryService = $container->get(Nogues\Category\Service\CategoryService::class);

if ('add' === $action) {
    $categories = $categoryService->findAll();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $parentEntity = $categoryService->findById((int) $_POST['parent']);

        $entity = new Nogues\Category\Entity\Category();
        $entity->setName($_POST['name'] ?? '');

        if (null !== $parentEntity->getId()) {
            $entity->setParent($parentEntity);
        }

        $store = $categoryService->store($entity);

        header('Location: ?' . $_SERVER['QUERY_STRING']);
    }

    require 'template/addCategory.php';
    exit;
}

if ('list' === $action) {
    $categories = $categoryService->findAll();

    require 'template/categories.php';
    exit;
}