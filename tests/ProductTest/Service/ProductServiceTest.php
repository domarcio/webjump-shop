<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Test\ProductTest\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Nogues\Category\Entity\Category as CategoryEntity;
use Nogues\Category\Repository\CategoryRepositoryFactory;
use Nogues\Product\Entity\Product as ProductEntity;
use Nogues\Product\Service\ProductServiceFactory;
use Nogues\Test\CommonTest\AbstractTestCase;

final class ProductServiceTest extends AbstractTestCase
{
    private $service;

    protected function setUp()
    {
        $container  = $this->getContainer();
        $factory    = new ProductServiceFactory();

        $entityManager = $container->reveal()->get(EntityManager::class);

        $classes = $entityManager->getMetadataFactory()->getAllMetadata();
        (new SchemaTool($entityManager))->createSchema($classes);

        $this->service = $factory($container->reveal(), null, get_class($container->reveal()));

        parent::setUp();
    }

    protected function tearDown()
    {
        $container     = $this->getContainer();
        $entityManager = $container->reveal()->get(EntityManager::class);

        $classes = $entityManager->getMetadataFactory()->getAllMetadata();
        (new SchemaTool($entityManager))->dropSchema($classes);

        parent::tearDown();
    }

    public function testIfCreatedNotSuccessfully()
    {
        $entity = new ProductEntity();
        $result = $this->service->store($entity);
        $this->assertFalse($result);

        $entity = new ProductEntity();
        $entity->setName('   ');
        $result = $this->service->store($entity);
        $this->assertFalse($result);

        // Test empty Sku
        $entity = new ProductEntity();
        $entity->setName('FooBarProduct');
        $entity->setSku(' ');
        $entity->setPrice(1500.70);
        $entity->setAvailableQuantity(100);
        $entity->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $result = $this->service->store($entity);
        $this->assertFalse($result);

        // Test empty Price
        $entity = new ProductEntity();
        $entity->setName('FooBarProduct');
        $entity->setSku('foo-123456-bar');
        $entity->setPrice(0.0); // 0.0 = is an empty price
        $entity->setAvailableQuantity(100);
        $entity->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $result = $this->service->store($entity);
        $this->assertFalse($result);

        // Test empty quantity
        $entity = new ProductEntity();
        $entity->setName('FooBarProduct');
        $entity->setSku('foo-123456-bar');
        $entity->setPrice(1500.70);
        $entity->setAvailableQuantity(0);
        $entity->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $result = $this->service->store($entity);
        $this->assertFalse($result);
    }

    public function testIfCreatedSuccessfully()
    {
        $entity = new ProductEntity();
        $entity->setName('FooBarProduct');
        $entity->setSku('foo-123456-bar');
        $entity->setPrice(1500.70);
        $entity->setAvailableQuantity(50);
        $entity->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $result = $this->service->store($entity);
        $this->assertTrue($result);
    }

    public function testIfCreatedSuccessfullyWithCategories()
    {
        $entityManager = $this->entityManager;

        // Get category repository by factory
        $container                 = $this->getContainer();
        $categoryRepositoryFactory = new CategoryRepositoryFactory();
        $categoryRepository        = $categoryRepositoryFactory($container->reveal(), null, get_class($container->reveal()));

        $category1 = new CategoryEntity();
        $category1->setName('Foo');
        $categoryRepository->store($category1);

        $category2 = new CategoryEntity();
        $category2->setName('Bar');
        $categoryRepository->store($category2);
        unset($category2, $category1);

        $entity = new ProductEntity();
        $entity->setName('FooBarProduct');
        $entity->setSku('foo-123456-bar');
        $entity->setPrice(1500.70);
        $entity->setAvailableQuantity(50);
        $entity->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit.');

        // Get category and add to product
        $category1 = $entityManager->getReference(CategoryEntity::class, 1);
        $entity->addCategory($category1);

        // Get category and add to product
        $category2 = $entityManager->getReference(CategoryEntity::class, 2);
        $entity->addCategory($category2);
        $result = $this->service->store($entity);
        $this->assertTrue($result);
    }

    public function testFindAll()
    {
        $entity = new ProductEntity();
        $entity->setName('FooBarProduct');
        $entity->setSku('foo-123456-bar');
        $entity->setPrice(1500.70);
        $entity->setAvailableQuantity(50);
        $entity->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $this->service->store($entity);

        $findAll = $this->service->findAll();
        $this->assertCount(1, $findAll);
    }

    public function testFindById()
    {
        $entity = new ProductEntity();
        $entity->setName('FooBarProduct');
        $entity->setSku('foo-123456-bar');
        $entity->setPrice(1500.70);
        $entity->setAvailableQuantity(50);
        $entity->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $this->service->store($entity);

        $entity = $this->service->findById(1);
        $this->assertEquals(1, $entity->getId());
    }

    public function testDeleteOneSuccessfully()
    {
        $entity = new ProductEntity();
        $entity->setName('FooBarProduct');
        $entity->setSku('foo-123456-bar');
        $entity->setPrice(1500.70);
        $entity->setAvailableQuantity(50);
        $entity->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $this->service->store($entity);

        $result = $this->service->deleteOne(1);
        $this->assertTrue($result);

        $entity = $this->service->findById(1);
        $this->assertNull($entity->getId());

        $entity = new ProductEntity();
        $entity->setName('FooBarProduct');
        $entity->setSku('foo-123456-bar');
        $entity->setPrice(1500.70);
        $entity->setAvailableQuantity(50);
        $entity->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $this->service->store($entity);

        $result = $this->service->deleteOneByPublicId((string) $entity->getPublicId());
        $this->assertTrue($result);
    }

    public function testDeleteUnsuccessfully()
    {
        $result = $this->service->deleteOne(1);
        $this->assertFalse($result);
    }

    public function testFindByPublicId()
    {
        $entity = new ProductEntity();
        $entity->setName('FooBarProduct');
        $entity->setSku('foo-123456-bar');
        $entity->setPrice(1500.70);
        $entity->setAvailableQuantity(50);
        $entity->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $this->service->store($entity);

        $find = $this->service->findByPublicId((string) $entity->getPublicId());
        $this->assertEquals(1, $find->getId());
    }
}
