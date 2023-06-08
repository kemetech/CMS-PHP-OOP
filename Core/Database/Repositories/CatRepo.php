<?php

namespace Core\Database\Repositories;

use Core\Database\Repositories\Repository;
use Core\Database\MySqlConnection;
use Core\Database\QueryBuilder;
use App\Models\Category;

class CatRepo extends Repository
{
    public function __construct()
    {
        $this->table = 'categories';
        $this->pdo = MySqlConnection::getConnection();
        $this->querybuilder = new QueryBuilder($this->table, $this->pdo);
    }

    /**
     * Finds a category by a specified column value.
     *
     * @param Category $post The Category object to store the result.
     * @param mixed $value The column value to search for.
     * @param string $col The column to search in.
     * @param int $limit The maximum number of results to retrieve.
     * @param string $operator The comparison operator.
     */
    public function findBy(Category $post, $value, $col = 'catId', $limit = 1, $operator = '=')
    {
        $data = $this->querybuilder->select()
            ->where($col, $operator, $value)
            ->limit($limit)
            ->get();
        MySqlConnection::disconnect();
        if ($data) {
            $post->setId($data->catId);
            $post->setName($data->catName);
            $post->setDesc($data->catDesc);
        }
    }

    /**
     * Saves a category to the database.
     *
     * @param Category $post The Category object to save.
     * @return int The ID of the inserted category.
     */
    public function save(Category $post)
    {
        $data = [
            'catName' => $post->getName(),
            'catDesc' => $post->getDesc(),
        ];

        $data = $this->querybuilder->insert($data);
        MySqlConnection::disconnect();
        return $data;
    }

    /**
     * Updates a category in the database.
     *
     * @param Category $post The Category object to update.
     * @return int The number of affected rows.
     */
    public function update(Category $post)
    {
        $data = [
            'catName' => $post->getName(),
            'catDesc' => $post->getDesc(),
        ];
        $data = $this->querybuilder->update($data);
        MySqlConnection::disconnect();
        return $data;
    }
}
