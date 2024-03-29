<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Test\CategoryTest\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Nogues\Category\Entity\Category as CategoryEntity;
use Nogues\Category\Service\CategoryServiceFactory;
use Nogues\Test\CommonTest\AbstractTestCase;

final class CategoryServiceTest extends AbstractTestCase
{
    private $service;

    protected function setUp()
    {
        $container  = $this->getContainer();
        $factory    = new CategoryServiceFactory();

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
        $entity = new CategoryEntity();
        $result = $this->service->store($entity);
        $this->assertFalse($result);

        $entity = new CategoryEntity();
        $entity->setName('   ');
        $result = $this->service->store($entity);
        $this->assertFalse($result);
    }

    public function testIfCreatedSuccessfully()
    {
        $entity1 = new CategoryEntity();
        $entity1->setName('Bar');
        $result = $this->service->store($entity1);
        $this->assertTrue($result);

        $entity2 = new CategoryEntity();
        $entity2->setName('Bar');
        $entity2->setParent($entity1);
        $result = $this->service->store($entity2);
        $this->assertTrue($result);
    }

    public function testFindAll()
    {
        $tmpEntity = new CategoryEntity();
        $tmpEntity->setName('Foo');
        $this->service->store($tmpEntity);

        $tmpEntity = new CategoryEntity();
        $tmpEntity->setName('Bar');
        $this->service->store($tmpEntity);

        $categories = $this->service->findAll();
        $this->assertCount(2, $categories);
    }

    public function testFindById()
    {
        $tmpEntity = new CategoryEntity();
        $tmpEntity->setName('Foo');
        $this->service->store($tmpEntity);

        $entity = $this->service->findById(1);
        $this->assertEquals(1, $entity->getId());
    }

    public function testDeleteOneSuccessfully()
    {
        $tmpEntity = new CategoryEntity();
        $tmpEntity->setName('Foo');
        $this->service->store($tmpEntity);

        $result = $this->service->deleteOne(1);
        $this->assertTrue($result);
    }

    public function testDeleteOneNotSuccessfully()
    {
        $result = $this->service->deleteOne(1);
        $this->assertFalse($result);
    }

    public function testFindCategoriesById()
    {
        $category = new CategoryEntity();
        $category->setName('Foo');
        $this->service->store($category);

        $category = new CategoryEntity();
        $category->setName('Bar');
        $this->service->store($category);

        $result = $this->service->findByIds([1, 2]);
        $this->assertCount(2, $result);

        $result = $this->service->findByIds([100, 200]);
        $this->assertCount(0, $result);
    }

    public function testFindCategoriesByNames()
    {
        $category = new CategoryEntity();
        $category->setName('Foo');
        $this->service->store($category);

        $category = new CategoryEntity();
        $category->setName('Bar');
        $this->service->store($category);

        $result = $this->service->findByNames(['Foo', 'Bar']);
        $this->assertCount(2, $result);

        $result = $this->service->findByNames(['xpto', 'buzz']);
        $this->assertCount(0, $result);
    }
}
