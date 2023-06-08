<?php

namespace Core\Database\Repositories;

use Core\Database\MySqlConnection;
use Core\Database\QueryBuilder;
use App\Models\ModelInterface;

class Repository
{
    protected $querybuilder;
    protected $pdo;
    protected $table;

    /**
     * Retrieves all records from the repository's table.
     *
     * @return array An array of records.
     */
    public function findAll()
    {
        $data = $this->querybuilder->select()
            ->getAll();
        return $data;
    }

    /**
     * Retrieves a record from the repository's table by its ID.
     *
     * @param mixed $Id The ID of the record to retrieve.
     * @return mixed The retrieved record.
     */
    public function findById($Id)
    {
        $data = $this->querybuilder->select()
            ->where('id', '=', $Id)
            ->limit(1)
            ->get();
        return $data;
    }

    /**
     * Checks if a value is unique for a given column.
     *
     * @param string $col The column to check.
     * @param mixed $value The value to check for uniqueness.
     * @return bool True if the value is unique, false otherwise.
     */
    public function unique($col, $value)
    {
        $count = $this->querybuilder->where($col, '=', $value)
            ->count();
        $this->querybuilder->resetParams();
        if ($count) {
            return false;
        }
        return true;
    }

    /**
     * Checks if a value is unique for a given column, excluding a specific record.
     *
     * @param string $col The column to check.
     * @param mixed $value The value to check for uniqueness.
     * @param string $col2 The column of the record to exclude.
     * @param mixed $value2 The value of the record to exclude.
     * @return bool True if the value is unique, false otherwise.
     */
    public function uniqueUpdate($col, $value, $col2, $value2)
    {
        $count = $this->querybuilder->multiWhere($col, '=', $value, $col2, '!=', $value2, 'AND')
            ->count();
        $this->querybuilder->resetParams();
        if ($count) {
            return false;
        }
        return true;
    }

    /**
     * Deletes a record from the repository's table based on a given column and value.
     *
     * @param string $col The column to match.
     * @param mixed $value The value to match.
     * @return int The number of affected rows.
     */
    public function delete($col, $value)
    {
        $data = $this->querybuilder->where($col, '=', $value)
            ->limit(1)
            ->delete();

        return $data;
    }
}
