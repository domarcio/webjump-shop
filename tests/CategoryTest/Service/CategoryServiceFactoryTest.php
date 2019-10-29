<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Test\CategoryTest\Service;

use Nogues\Category\Service\CategoryService;
use Nogues\Category\Service\CategoryServiceFactory;
use Nogues\Test\CommonTest\AbstractTestCase;

final class CategoryServiceFactoryTest extends AbstractTestCase
{
    public function testIfFactoryCreatedSuccessfully()
    {
        $container = $this->getContainer();
        $factory   = new CategoryServiceFactory();
        $service   = $factory($container->reveal(), null, get_class($container->reveal()));

        $this->assertInstanceOf(CategoryService::class, $service);
    }
}
