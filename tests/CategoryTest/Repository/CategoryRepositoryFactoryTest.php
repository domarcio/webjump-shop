<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Test\CategoryTest\Repository;

use Nogues\Category\Repository\{
    CategoryRepository,
    CategoryRepositoryFactory
};
use Nogues\Test\CommonTest\AbstractTestCase;

final class CategoryRepositoryFactoryTest extends AbstractTestCase
{
    public function testIfFactoryCreatedSuccessfully()
    {
        $container  = $this->getContainer();
        $factory    = new CategoryRepositoryFactory();
        $repository = $factory($container->reveal(), null, get_class($container->reveal()));

        $this->assertInstanceOf(CategoryRepository::class, $repository);
    }
}
