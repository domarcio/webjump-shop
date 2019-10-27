<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Test\CategoryTest\Repository;

use Nogues\Category\Repository\{
    Category as CategoryRepository,
    CategoryFactory
};
use Nogues\Test\CommonTest\AbstractTestCase;

final class CategoryFactoryTest extends AbstractTestCase
{
    public function testIfFactoryCreatedSuccessfully()
    {
        $container  = $this->getEntityManager();
        $factory    = new CategoryFactory();
        $repository = $factory($container->reveal(), null, get_class($container->reveal()));

        $this->assertInstanceOf(CategoryRepository::class, $repository);
    }
}
