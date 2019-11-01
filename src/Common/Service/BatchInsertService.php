<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Common\Service;

use Doctrine\DBAL\Connection;
use Exception;

/**
 * Batch insert in MySQL
 *
 * @package Nogues\Common
 * @author  Marcio Vinicius <marciovinicius55@gmail.com>
 */
final class BatchInsertService
{
    /**
     * Table name.
     *
     * @var string
     */
    private $table = null;

    /**
     * Fields names.
     *
     * @var array
     */
    private $rows = [];

    /**
     * Fields names.
     *
     * @var string
     */
    private $rowsString = '';

    /**
     * Values to insert.
     *
     * @var array
     */
    private $values = [];

    /**
     * Bind values.
     *
     * @var string
     */
    private $bindString;

    /**
     * Values flattened.
     *
     * @var string
     */
    private $valuesFlattened;

    /**
     * Connection.
     *
     * @var Doctrine\DBAL\Connection
     */
    private $connection;

    /**
     * Batch insert.
     *
     * @param string                   $table Table name.
     * @param Doctrine\DBAL\Connection $connection
     */
    public function __construct(string $table, Connection $connection)
    {
        $this->table      = $table;
        $this->connection = $connection;
    }

    /**
     * Set the Rows.
     *
     * @param array $rows Fields names.
     *
     * @return void
     */
    public function setRows(array $rows): void
    {
        $this->rows       = $rows;
        $this->rowsString = sprintf('`%s`', implode('`,`', $this->rows));
    }

    /**
     * Set the values.
     *
     * @param $values Array of arrays with values to insert.
     *
     * @return void
     */
    public function setValues(array $values): void
    {
        if (empty($this->rows)) {
            throw new Exception('You must setRows() before setValues');
        }

        $this->values = $values;

        $valueCount = count($values);
        $fieldCount = count($this->rows);

        // Build the Placeholder String
        $placeholders = [];
        for ($i = 0; $i < $valueCount; $i++) {
            $placeholders[] = '(' . rtrim(str_repeat('?,', $fieldCount), ',') . ')';
        }
        $this->bindString = implode(',', $placeholders);

        // Build the Flat Value Array
        $valueList = [];
        foreach ($values as $value) {
            if (is_array($value)) {
                foreach ($value as $v) {
                    $valueList[] = $v;
                }
            } else {
                $valueList[] = $values;
            }
        }

        $this->valuesFlattened = $valueList;
        unset($valueList);
    }

    /**
     * Insert into the DB.
     *
     * @param boolean $ignore Use an INSERT IGNORE (Default: false)
     *
     * @return boolean
     */
    public function insert($ignore = false): bool
    {
        $this->validate();

        $insertString = 'INSERT INTO `%s` (%s) VALUES %s';

        // Optional ignore string
        if ($ignore) {
            $insertString = 'INSERT IGNORE INTO `%s` (%s) VALUES %s';
        }

        $query = sprintf($insertString, $this->table, $this->rowsString, $this->bindString);
        $this->connection->executeQuery($query, $this->valuesFlattened);

        return true;
    }

    /**
     * Validates the data before calling SQL. Throw exceptions for any errors.
     *
     * @return void
     */
    private function validate(): void
    {
        if (empty($this->table)) {
            throw new Exception('Batch Table must be defined');
        }

        $requiredCount = count($this->rows);

        if (0 === $requiredCount) {
            throw new Exception('Batch Rows cannot be empty');
        }

        foreach ($this->values as $value) {
            if (count($value) !== $requiredCount) {
                throw new Exception('Batch Values must match the same column count of ' . $requiredCount);
            }
        }
    }
}
