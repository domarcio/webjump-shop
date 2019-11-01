<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Test\CategoryTest\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Nogues\Category\Repository\CategoryRepositoryFactory;
use Nogues\Category\Entity\Category as CategoryEntity;

use Nogues\Test\CommonTest\AbstractTestCase;

final class CategoryRepositoryTest extends AbstractTestCase
{
    private $repository;

    protected function setUp()
    {
        $container  = $this->getContainer();
        $factory    = new CategoryRepositoryFactory();

        $entityManager = $container->reveal()->get(EntityManager::class);

        $classes = $entityManager->getMetadataFactory()->getAllMetadata();
        (new SchemaTool($entityManager))->createSchema($classes);

        $this->repository = $factory($container->reveal(), null, get_class($container->reveal()));

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

    public function testIfCreatedSuccessfully()
    {
        $categoryEntity = new CategoryEntity();
        $categoryEntity->setName('Foo');

        $result = $this->repository->store($categoryEntity);
        $this->assertEquals(1, $result);
    }

    public function testFind()
    {
        $tmpEntity = new CategoryEntity();
        $tmpEntity->setName('Foo');
        $this->repository->store($tmpEntity);

        // Found
        $categoryEntity = $this->repository->find(1);
        $this->assertEquals(1, $categoryEntity->getId());

        // Not found
        $categoryEntity = $this->repository->find(99999);
        $this->assertEmpty($categoryEntity->getId());
    }

    public function testIfUpdatedSuccessfully()
    {
        $tmpEntity = new CategoryEntity();
        $tmpEntity->setName('Foo');
        $this->repository->store($tmpEntity);
        $categoryEntity = $this->repository->find(1);

        $categoryEntity->setName('Bar');
        $result = $this->repository->store($categoryEntity);
        $categoryEntity = $this->repository->find(1);

        $this->assertEquals(1, $result);
        $this->assertEquals('Bar', $categoryEntity->getName());
    }

    public function testFoundAll()
    {
        $tmpEntity = new CategoryEntity();
        $tmpEntity->setName('Foo');
        $this->repository->store($tmpEntity);

        $tmpEntity = new CategoryEntity();
        $tmpEntity->setName('Bar');
        $this->repository->store($tmpEntity);

        $categories = $this->repository->findAll();
        $this->assertCount(2, $categories);
    }

    public function testNotFoundAll()
    {
        $categories = $this->repository->findAll();
        $this->assertCount(0, $categories);
    }

    public function testIfDeletedSuccessfully()
    {
        $tmpEntity = new CategoryEntity();
        $tmpEntity->setName('Foo');
        $this->repository->store($tmpEntity);

        $result = $this->repository->delete(1);

        $this->assertTrue($result);
    }

    public function testIfParentCreatedSuccessfully()
    {
        $categoryEntityFoo = new CategoryEntity();
        $categoryEntityFoo->setName('Foo');
        $result = $this->repository->store($categoryEntityFoo);
        $this->assertEquals(1, $result);

        $categoryEntityBar = new CategoryEntity();
        $categoryEntityBar->setName('Bar');
        $categoryEntityBar->setParent($categoryEntityFoo);
        $result = $this->repository->store($categoryEntityBar);
        $this->assertEquals(1, $result);
    }

    public function testParentAndChildrenName()
    {
        $categoryEntityFoo = new CategoryEntity();
        $categoryEntityFoo->setName('Foo');
        $this->repository->store($categoryEntityFoo);

        $categoryEntityBar = new CategoryEntity();
        $categoryEntityBar->setName('Bar');
        $categoryEntityBar->setParent($categoryEntityFoo);
        $this->repository->store($categoryEntityBar);

        $categoryEntity = $this->repository->find(2);
        $this->assertEquals(1, $categoryEntity->getParent()->getId());
        $this->assertEquals('Foo', $categoryEntity->getParent()->getName());
    }

    public function testRemoveParentCategoryFromEntity()
    {
        $tmpEntity1 = new CategoryEntity();
        $tmpEntity1->setName('Foo');
        $this->repository->store($tmpEntity1);

        $tmpEntity2 = new CategoryEntity();
        $tmpEntity2->setName('Bar');
        $tmpEntity2->setParent($tmpEntity1);
        $this->repository->store($tmpEntity2);

        $entity = $this->repository->find(2);
        $this->assertEquals(1, $entity->getParent()->getId());

        $entity->setParent(null);
        $this->repository->store($entity);

        $entity = $this->repository->find(2);
        $this->assertEquals(2, $entity->getId());
        $this->assertNull($entity->getParent()->getId());
    }

    public function testFindCategoriesByIds()
    {
        $category = new CategoryEntity();
        $category->setName('Foo');
        $this->repository->store($category);

        $category = new CategoryEntity();
        $category->setName('Bar');
        $this->repository->store($category);

        $categories = $this->repository->findByIds([1, 2]);
        $this->assertCount(2, $categories);

        unset($category);
        foreach ($categories as $category) {
            $this->assertInstanceOf(CategoryEntity::class, $category);
        }
    }

    public function testThrowExceptionWhenCategoriesNotFoundByIds()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Categories IDs are empty.');
        $this->repository->findByIds(['foo', 'bar']);
    }

    public function testFindCategoriesByNames()
    {
        $category = new CategoryEntity();
        $category->setName('Foo');
        $this->repository->store($category);

        $category = new CategoryEntity();
        $category->setName('Bar');
        $this->repository->store($category);

        $categories = $this->repository->findByNames(['Foo', 'Bar']);
        $this->assertCount(2, $categories);

        unset($category);
        foreach ($categories as $category) {
            $this->assertInstanceOf(CategoryEntity::class, $category);
        }
    }

    public function testThrowExceptionWhenCategoriesNotFoundByNames()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Names are empty.');
        $this->repository->findByNames([1, 2]);
    }
}
