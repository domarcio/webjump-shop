<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Test\CategoryTest\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Nogues\Product\Entity\Product as ProductEntity;
use Nogues\Product\Repository\ProductRepositoryFactory;
use Nogues\Test\CommonTest\AbstractTestCase;

final class ProductRepositoryTest extends AbstractTestCase
{
    private $repository;

    protected function setUp()
    {
        $container  = $this->getEntityManager();
        $factory    = new ProductRepositoryFactory();

        $entityManager = $container->reveal()->get(EntityManager::class);

        $classes = $entityManager->getMetadataFactory()->getAllMetadata();
        (new SchemaTool($entityManager))->createSchema($classes);

        $this->repository = $factory($container->reveal(), null, get_class($container->reveal()));

        parent::setUp();
    }

    public function tearDown()
    {
        $container     = $this->getEntityManager();
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
        //$this->assertInstanceOf(\DateTimeImmutable::class, $result->getCreatedAt());
        //$this->assertInstanceOf(\DateTime::class, $result->getUpdatedAt());
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
}
