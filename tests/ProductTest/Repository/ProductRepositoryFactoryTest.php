<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Test\ProductTest\Repository;

use Nogues\Product\Repository\{
    ProductRepository,
    ProductRepositoryFactory
};
use Nogues\Test\CommonTest\AbstractTestCase;

final class ProductRepositoryFactoryTest extends AbstractTestCase
{
    public function testIfFactoryCreatedSuccessfully()
    {
        $container  = $this->getContainer();
        $factory    = new ProductRepositoryFactory();
        $repository = $factory($container->reveal(), null, get_class($container->reveal()));

        $this->assertInstanceOf(ProductRepository::class, $repository);
    }
}
