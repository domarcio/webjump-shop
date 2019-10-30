<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Test\CategoryTest\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\Tools\SchemaTool;
use Nogues\Category\Entity\Category as CategoryEntity;
use Nogues\Category\Repository\CategoryRepositoryFactory;
use Nogues\Product\Entity\Product as ProductEntity;
use Nogues\Product\Repository\ProductRepositoryFactory;
use Nogues\Test\CommonTest\AbstractTestCase;

final class ProductRepositoryTest extends AbstractTestCase
{
    private $repository;

    protected function setUp()
    {
        $container  = $this->getContainer();
        $factory    = new ProductRepositoryFactory();

        $entityManager = $container->reveal()->get(EntityManager::class);

        $classes = $entityManager->getMetadataFactory()->getAllMetadata();
        (new SchemaTool($entityManager))->createSchema($classes);

        $this->repository = $factory($container->reveal(), null, get_class($container->reveal()));

        parent::setUp();
    }

    public function tearDown()
    {
        $container     = $this->getContainer();
        $entityManager = $container->reveal()->get(EntityManager::class);

        $classes = $entityManager->getMetadataFactory()->getAllMetadata();
        (new SchemaTool($entityManager))->dropSchema($classes);

        parent::tearDown();
    }

    public function testIfCreatedSuccessfully()
    {
        $productEntity = new ProductEntity();
        $productEntity->setName('Foo');
        $productEntity->setSku('foo-123456-bar');
        $productEntity->setPrice(1500.70);
        $productEntity->setAvailableQuantity(100);
        $productEntity->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit.');

        $result = $this->repository->store($productEntity);
        $this->assertEquals(1, $result);
    }

    public function testFind()
    {
        $productEntity = new ProductEntity();
        $productEntity->setName('Foo');
        $productEntity->setSku('foo-123456-bar');
        $productEntity->setPrice(1500.70);
        $productEntity->setAvailableQuantity(100);
        $productEntity->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $this->repository->store($productEntity);

        $entity1 = $this->repository->find(1);
        $this->assertEquals(1, $entity1->getId());
        $this->assertInstanceOf(\Ramsey\Uuid\UuidInterface::class, $entity1->getPublicId());
        $this->assertInstanceOf(\DateTimeImmutable::class, $entity1->getCreatedAt());
        $this->assertInstanceOf(\DateTime::class, $entity1->getUpdatedAt());

        $entity2 = $this->repository->find(1);

        // Check if are same UUIDs
        $this->assertEquals($entity1->getPublicId(), $entity2->getPublicId());
    }

    public function testFindByPublicId()
    {
        $productEntity = new ProductEntity();
        $productEntity->setName('Foo');
        $productEntity->setSku('foo-123456-bar');
        $productEntity->setPrice(1500.70);
        $productEntity->setAvailableQuantity(100);
        $productEntity->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $this->repository->store($productEntity);

        $entity1 = $this->repository->find(1);
        $entity2 = $this->repository->findByPublicId((string) $entity1->getPublicId());
        $this->assertEquals(
            (string) $entity1->getPublicId(),
            (string) $entity2->getPublicId()
        );

        $invalidEntity = $this->repository->findByPublicId('');
        $this->assertNull($invalidEntity->getId());
    }

    public function testIfUpdatedSuccessfully()
    {
        $productEntity = new ProductEntity();
        $productEntity->setName('Foo');
        $productEntity->setSku('foo-123456-bar');
        $productEntity->setPrice(1500.70);
        $productEntity->setAvailableQuantity(100);
        $productEntity->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $this->repository->store($productEntity);

        $originalCreatedAt = $productEntity->getCreatedAt();
        $originalUpdatedAt = $productEntity->getUpdatedAt();

        $entity1 = $this->repository->find(1);
        $entity1->setName('XPTO');

        sleep(1);
        $this->repository->store($entity1);

        $entity2 = $this->repository->find(1);
        $this->assertEquals(1, $entity2->getId());
        $this->assertEquals('XPTO', $entity2->getName());
        $this->assertEquals($entity1->getCreatedAt()->getTimestamp(), $originalCreatedAt->getTimestamp());
        $this->assertTrue($entity2->getUpdatedAt()->getTimestamp() > $originalUpdatedAt->getTimestamp());
    }

    public function testFoundAll()
    {
        $productEntity = new ProductEntity();
        $productEntity->setName('Foo');
        $productEntity->setSku('foo-123456-bar');
        $productEntity->setPrice(1500.70);
        $productEntity->setAvailableQuantity(100);
        $productEntity->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $this->repository->store($productEntity);

        $productEntity = new ProductEntity();
        $productEntity->setName('Bar');
        $productEntity->setSku('bar-123456-foo');
        $productEntity->setPrice(1500.00);
        $productEntity->setAvailableQuantity(100);
        $productEntity->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $this->repository->store($productEntity);

        $products = $this->repository->findAll();
        $this->assertCount(2, $products);
    }

    public function testNotFoundAll()
    {
        $products = $this->repository->findAll();
        $this->assertCount(0, $products);
    }

    public function testIfDeletedSuccessfully()
    {
        $productEntity = new ProductEntity();
        $productEntity->setName('Bar');
        $productEntity->setSku('bar-123456-foo');
        $productEntity->setPrice(1500.00);
        $productEntity->setAvailableQuantity(100);
        $productEntity->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $this->repository->store($productEntity);

        $result = $this->repository->delete(1);

        $this->assertTrue($result);
    }

    public function testCreatedWithManyCategoriesSuccessfully()
    {
        $productEntity = new ProductEntity();
        $productEntity->setName('Foo');
        $productEntity->setSku('foo-123456-bar');
        $productEntity->setPrice(1500.70);
        $productEntity->setAvailableQuantity(100);
        $productEntity->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit.');

        // Get category repository by factory
        $container                 = $this->getContainer();
        $categoryRepositoryFactory = new CategoryRepositoryFactory();
        $categoryRepository        = $categoryRepositoryFactory($container->reveal(), null, get_class($container->reveal()));

        // Add category `Foo` to Product
        $categoryEntityFoo = new CategoryEntity();
        $categoryEntityFoo->setName('Foo');
        $categoryRepository->store($categoryEntityFoo);
        $productEntity->addCategory($categoryEntityFoo);

        // Add category `Bar` to Product
        $categoryEntityBar = new CategoryEntity();
        $categoryEntityBar->setName('Bar');
        $categoryRepository->store($categoryEntityBar);
        $productEntity->addCategory($categoryEntityBar);

        // Save product with categories
        $result = $this->repository->store($productEntity);
        $this->assertEquals(1, $result);

        // Test categories returned
        unset($productEntity);
        $productEntity = $this->repository->find(1);
        $categoriesProduct = $productEntity->getCategories();

        foreach ($categoriesProduct as $category) {
            $this->assertInstanceOf(CategoryEntity::class, $category);
            $this->assertTrue(is_int($category->getId()));
        }
    }

    public function testCreatedManyCategoriesWithParentCategorySuccessfully()
    {
        $productEntity = new ProductEntity();
        $productEntity->setName('Foo');
        $productEntity->setSku('foo-123456-bar');
        $productEntity->setPrice(1500.70);
        $productEntity->setAvailableQuantity(100);
        $productEntity->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit.');

        // Get category repository by factory
        $container                 = $this->getContainer();
        $categoryRepositoryFactory = new CategoryRepositoryFactory();
        $categoryRepository        = $categoryRepositoryFactory($container->reveal(), null, get_class($container->reveal()));

        // Add category `Bar` to Product with Parent Category.
        $categoryEntityFoo = new CategoryEntity();
        $categoryEntityFoo->setName('Foo');
        $categoryRepository->store($categoryEntityFoo);

        $categoryEntityBar = new CategoryEntity();
        $categoryEntityBar->setName('Bar');
        $categoryEntityBar->setParent($categoryEntityFoo);
        $categoryRepository->store($categoryEntityBar);

        $productEntity->addCategory($categoryEntityBar);

        // Save product with category
        $result = $this->repository->store($productEntity);
        $this->assertEquals(1, $result);

        // Test categories returned
        unset($productEntity);
        $productEntity = $this->repository->find(1);
        $categoriesProduct = $productEntity->getCategories();

        foreach ($categoriesProduct as $category) {
            $this->assertInstanceOf(CategoryEntity::class, $category);
            $this->assertTrue(is_int($category->getId()));

            // Test parent
            $this->assertInstanceOf(CategoryEntity::class, $category->getParent());
        }
    }

    public function testCreatedManyCategoriesWithChildrenCategorySuccessfully()
    {
        $productEntity = new ProductEntity();
        $productEntity->setName('FooBarProduct');
        $productEntity->setSku('foo-123456-bar');
        $productEntity->setPrice(1500.70);
        $productEntity->setAvailableQuantity(100);
        $productEntity->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit.');

        // Get category repository by factory
        $container                 = $this->getContainer();
        $categoryRepositoryFactory = new CategoryRepositoryFactory();
        $categoryRepository        = $categoryRepositoryFactory($container->reveal(), null, get_class($container->reveal()));

        // Add category `Bar` to Product with Parent Category.
        $categoryEntityFoo = new CategoryEntity();
        $categoryEntityFoo->setName('Foo');
        $categoryRepository->store($categoryEntityFoo);

        $categoryEntityBar = new CategoryEntity();
        $categoryEntityBar->setName('Bar');
        $categoryEntityBar->setParent($categoryEntityFoo);
        $categoryRepository->store($categoryEntityBar);

        // Add to product the Category that does not a parent, but it's a parent of another category (Bar)
        $productEntity->addCategory($categoryEntityFoo);

        // Save product with category
        $result = $this->repository->store($productEntity);
        $this->assertEquals(1, $result);

        // Test categories returned
        unset($productEntity);
        $productEntity = $this->repository->find(1);
        $categoriesProduct = $productEntity->getCategories();
var_dump( $categoryRepository->findAll() );
        foreach ($categoriesProduct as $category) {
            $this->assertInstanceOf(CategoryEntity::class, $category);
            $this->assertTrue(is_int($category->getId()));

            // Test children
            var_dump($category);
            //$this->assertInstanceOf(PersistentCollection::class, $category->getChildren());
        }
    }
}
