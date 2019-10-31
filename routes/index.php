<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

$productService = $container->get(Nogues\Product\Service\ProductService::class);

// Get all products
$products = $productService->findAll();

require 'template/dashboard.php';
