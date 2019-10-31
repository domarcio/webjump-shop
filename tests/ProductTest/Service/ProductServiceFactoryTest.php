<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Test\ProductTest\Service;

use Nogues\Product\Service\ProductService;
use Nogues\Product\Service\ProductServiceFactory;
use Nogues\Test\CommonTest\AbstractTestCase;

final class ProductServiceFactoryTest extends AbstractTestCase
{
    public function testIfFactoryCreatedSuccessfully()
    {
        $container = $this->getContainer();
        $factory   = new ProductServiceFactory();
        $service   = $factory($container->reveal(), null, get_class($container->reveal()));

        $this->assertInstanceOf(ProductService::class, $service);
    }
}
