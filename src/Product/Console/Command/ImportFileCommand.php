<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Product\Console\Command;

use DirectoryIterator;
use Doctrine\ORM\EntityManager;
use Nogues\Common\Service\BatchInsertService;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function filter_var;

/**
 * Import file data to products.
 *
 * @package Nogues\Product\Console\Command
 * @author  Marcio Vinicius <marciovinicius55@gmail.com>
 */
class ImportFileCommand extends Command
{
    /**
     * Command name-
     *
     * @var string
     */
    protected static $defaultName = 'product:import-file';

    /**
     * Bulk persist product.
     *
     * @var BatchInsertService
     */
    private $batchInsertProduct;

    /**
     * Bulk persist category.
     *
     * @var BatchInsertService
     */
    private $batchInsertCategory;

    /**
     * Bulk persis related category with product.
     *
     * @var BatchInsertService
     */
    private $batchInsertRelatedCategory;

    /**
     * MySQL connection.
     *
     * @var \Doctrine\DBAL\COnnection
     */
    private $connection;

    public function __construct(EntityManager $entityManager)
    {
        $connection = $entityManager->getConnection();

        $this->batchInsertProduct = new BatchInsertService('product', $connection);
        $this->batchInsertProduct->setRows([
            'public_id',
            'name',
            'sku',
            'price',
            'available_quantity',
            'description',
            'created_at',
            'updated_at',
        ]);

        $this->batchInsertCategory = new BatchInsertService('category', $connection);
        $this->batchInsertCategory->setRows([
            'name',
        ]);

        $this->batchInsertRelatedCategory = new BatchInsertService('related_category', $connection);
        $this->batchInsertRelatedCategory->setRows([
            'category_id',
            'related_id'
        ]);

        $this->connection = $connection;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Import a file data to Product.')
            ->setHelp(
                'This command allows you to import a file data to Product. Products and your categories are created automatically!'
                . ' Your files needs allocated in "./data/products" directory.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            '',
            'Start import process.',
        ]);

        // Common attributes to iterator file
        $fileHeadStructure = [
            'nome',
            'sku',
            'descricao',
            'quantidade',
            'preco',
            'categoria'
        ];

        $categorySeparator = '|';
        $batchSize         = 100;

        $directory         = './data/products';
        $directoryIterator = new DirectoryIterator($directory);

        foreach ($directoryIterator as $file) {
            if (! $file->isFile() || ! $file->isReadable()) {
                continue;
            }

            $file = new \SplFileObject($file->getPathname());
            $file->setCsvControl(';', '"');
            $file->setFlags(
                \SplFileObject::READ_CSV
                | \SplFileObject::READ_AHEAD
                | \SplFileObject::SKIP_EMPTY
                | \SplFileObject::DROP_NEW_LINE
            );

            $total      = $batchSize;
            $skuUsed    = [];
            $products   = [];
            $categories = [];

            foreach ($file as $row) {
                // Skip head
                if ($row === $fileHeadStructure) {
                    continue;
                }

                $publicId      = (string) Uuid::uuid4();
                $name          = $row['0'] ?? null;
                $sku           = $row['1'] ?? null;
                $description   = $row['2'] ?? null;
                $quantity      = filter_var($row['3'], FILTER_VALIDATE_INT) ?? null;
                $price         = filter_var($row['4'], FILTER_VALIDATE_FLOAT) ?? null;
                $categoriesStr = $row['5'] ?? null;

                if (isset($skuUsed[$sku])) {
                    continue;
                }

                // Prevent throw exception to insert duplicate SKU
                $skuUsed[$sku] = true;

                $now = date('Y-m-d H:i:s');

                $products[] = [
                    $publicId,
                    $name,
                    $sku,
                    $price,
                    $quantity,
                    $description,
                    $now,
                    $now,
                ];

                // Create categories by SKU
                $tmpCategories = explode($categorySeparator, $categoriesStr);
                $tmpCategories = array_filter($tmpCategories, function ($value) {
                    $value = trim($value);
                    return ! empty($value);
                });
                $categories[$sku] = $tmpCategories;

                unset($tmpCategories);

                --$total;
                if (0 === $total) {
                    $total = $batchSize;

                    $this->saveProducts($products);
                    $this->saveCategoriesProduct($categories);

                    $products = $categories = [];
                }
            }

            rename($file->getPathname(), $directory . '/processed/' . $file->getFilename());

            $this->saveProducts($products);
            $this->saveCategoriesProduct($categories);
        }

        $output->writeln([
            'Awesome! Files imported successfully.',
            '',
        ]);
    }

    public function saveProducts(array $products): void
    {
        $this->batchInsertProduct->setValues($products);
        $this->batchInsertProduct->insert();
    }

    public function saveCategoriesProduct(array $categories): void
    {
        foreach ($categories as $category) {
            if (empty($category)) {
                continue;
            }

            $categoriesFromDB = $this->fetchAllCategories($category);

            $save = [];
            foreach ($categoriesFromDB as $c) {
                // Remove category from file already saved in database
                if (($key = array_search($c['name'], $category)) !== false) {
                    unset($category[$key]);
                }
            }

            foreach ($category as $c) {
                $save[] = [$c];
            }

            if (! empty($save)) {
                $this->batchInsertCategory->setValues($save);
                $this->batchInsertCategory->insert();
            }
        }

        // Relating category with product
        $save = [];
        foreach ($categories as $sku => $category) {
            if (empty($category)) {
                continue;
            }

            $productId    = $this->fetchProductIdBySKU($sku);
            $categoryData = array_column($this->fetchAllCategories($category), 'id');

            foreach ($categoryData as $categoryId) {
                $save[] = [$categoryId, $productId];
            }
        }

        $this->batchInsertRelatedCategory->setValues($save);
        $this->batchInsertRelatedCategory->insert(true);
    }

    private function fetchAllCategories(array $categories): array
    {
        $params = str_repeat('?, ', count($categories));
        $params = rtrim($params, ', ');

        $categoryIds = $this->connection->fetchAll(
            sprintf('SELECT id, name FROM category WHERE name IN (%s)', $params),
            $categories
        );

        return $categoryIds ?: [];
    }

    private function fetchProductIdBySKU($sku)
    {
        $id = $this->connection->fetchColumn('SELECT id FROM product WHERE sku = ?', [$sku], 0);
        return $id;
    }
}
