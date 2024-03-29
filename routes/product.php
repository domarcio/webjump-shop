<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

use Nogues\Product\Entity\Product;

$productService  = $container->get(Nogues\Product\Service\ProductService::class);
$categoryService = $container->get(Nogues\Category\Service\CategoryService::class);

if ('list' === $action) {
    $products = $productService->findAll();

    require 'template/products.php';
}

if ('add' === $action) {
    $categories = $categoryService->findAll();
    $entity     = new Product();

    if ('POST' === $_SERVER['REQUEST_METHOD']) {
        $sku         = filter_input(INPUT_POST, 'sku', FILTER_SANITIZE_STRING);
        $name        = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $price       = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
        $quantity    = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
        $categories  = $_POST['categories'] ?? [];

        $product = new Product();
        $product->setSku($sku);
        $product->setName($name);
        $product->setPrice($price);
        $product->setAvailableQuantity($quantity);
        $product->setDescription($description);

        if (! empty($categories)) {
            $categories = $categoryService->findByIds($categories);
        }
        foreach ($categories as $category) {
            $product->addCategory($category);
        }

        $productService->store($product);
        header('Location: /?' . $_SERVER['QUERY_STRING']);
    }
    $notifications = $productService->getNotification();

    require 'template/addProduct.php';
}

if ('update' === $action) {
    $id         = $_GET['id'];
    $entity     = $productService->findByPublicId($id);
    $categories = $categoryService->findAll();

    if (null === $entity->getId()) {
        header('Location: /?handler=category&action=list');
        exit;
    }

    $selectedCategories    = [];
    $categoriesFromProduct = $entity->getCategories()->toArray();
    array_walk($categoriesFromProduct, function ($c) use (&$selectedCategories) {
        $selectedCategories[] = $c->getId();
    });

    if ('POST' === $_SERVER['REQUEST_METHOD']) {
        $sku         = filter_input(INPUT_POST, 'sku', FILTER_SANITIZE_STRING);
        $name        = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $price       = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
        $quantity    = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

        $entity->setSku($sku ?: '');
        $entity->setName($name ?: '');
        $entity->setPrice($price ?: 0.0);
        $entity->setAvailableQuantity($quantity ?: 0);
        $entity->setDescription($description ?: '');

        if (null !== ($entityCategories = $entity->getCategories())) {
            $entityCategories->clear();
            unset($entityCategories);
        }

        $categoriesFoundByIDs = ! empty($_POST['categories'])
            ? $categoryService->findByIds($_POST['categories'])
            : [];
        foreach ($categoriesFoundByIDs as $category) {
            $entity->addCategory($category);
        }

        $result = $productService->store($entity);
        if ($result) {
            header('Location: /?' . $_SERVER['QUERY_STRING']);
            exit;
        }
    }
    $notifications = $productService->getNotification();

    require 'template/addProduct.php';
}

if ('delete' === $action && $publicId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING)) {
    $productService->deleteOneByPublicId($publicId);

    header('Location: /?handler=product&action=list');
    exit;
}
