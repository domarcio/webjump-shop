<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

$categoryService = $container->get(Nogues\Category\Service\CategoryService::class);

if ('add' === $action) {
    $categories = $categoryService->findAll();
    $entity     = new Nogues\Category\Entity\Category();

    if ('POST' === $_SERVER['REQUEST_METHOD']) {
        $parentEntity = $categoryService->findById((int) $_POST['parent']);

        $entity = new Nogues\Category\Entity\Category();
        $entity->setName($_POST['name'] ?? '');

        if (null !== $parentEntity->getId()) {
            $entity->setParent($parentEntity);
        }

        $categoryService->store($entity);

        header('Location: /?' . $_SERVER['QUERY_STRING']);
    }

    require 'template/addCategory.php';
    exit;
}

if ('list' === $action) {
    $categories = $categoryService->findAll();

    require 'template/categories.php';
    exit;
}

if ('update' === $action && ! empty($_GET['id'])) {
    $id         = (int) $_GET['id'];
    $entity     = $categoryService->findById($id);
    $categories = $categoryService->findAll();

    if (null === $entity->getId()) {
        header('Location: /?handler=category&action=list');
        exit;
    }

    if ('POST' === $_SERVER['REQUEST_METHOD']) {
        $parentEntity = $categoryService->findById((int) $_POST['parent']);

        $entity = $categoryService->findById($id);
        $entity->setName($_POST['name'] ?? '');

        // Remove relationships from category entity
        if (null === $parentEntity->getId() && null !== $entity->getParent()->getId()) {
            $entity->setParent(null);
        }

        if (null !== $parentEntity->getId()) {
            $entity->setParent($parentEntity);
        }

        $categoryService->store($entity);

        header('Location: /?' . $_SERVER['QUERY_STRING']);
    }

    require 'template/addCategory.php';
    exit;
}

if ('delete' === $action && ! empty($_GET['id'])) {
    $id = (int) $_GET['id'];
    if ($id > 0) {
        $categoryService->deleteOne($id);
    }

    header('Location: /?handler=category&action=list');
    exit;
}
