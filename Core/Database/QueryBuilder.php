<?php

namespace Core\Database;

use PDO;

class QueryBuilder implements QueryBuilderInterface
{
    private $table;
    private $columns = '*';
    private $where = '';
    private $orderBy = '';
    private $limit = '';
    private $offset = '';
    private $params = [];
    private $count;
    protected $pdo;
    protected $con;
    private $innerJoin;
    
    public function __construct($table, $pdo)
    {
        $this->table = $table;
        $this->pdo = $pdo;
    }
    
    /**
     * Specify the columns to be selected.
     *
     * @param mixed $columns
     * @return $this
     */
    public function select($columns = '*')
    {
        $this->columns = is_array($columns) ? implode(',', $columns) : $columns;
        return $this;
    }
    
    /**
     * Add a WHERE clause to the query.
     *
     * @param string $column
     * @param string $operator
     * @param mixed $value
     * @return $this
     */
    public function where($column, $operator, $value)
    {
        $placeholder = ':' . str_replace('.', '_', $column); // Generate a unique placeholder
        $this->where = " WHERE $column $operator $placeholder";
        $this->params[$placeholder] = $value;
        return $this;
    }

    /**
     * Add a multi-condition WHERE clause to the query.
     *
     * @param string $column
     * @param string $operator
     * @param mixed $value
     * @param string $column2
     * @param string $operator2
     * @param mixed $value2
     * @param string $logicOperator
     * @return $this
     */
    public function multiWhere($column, $operator, $value, $column2, $operator2, $value2, $logicOperator)
    {
        $placeholder = ':' . str_replace('.', '_', $column); // Generate a unique placeholder
        $this->where = " WHERE $column $operator $placeholder $logicOperator $column2 $operator2 $value2 ";
        $this->params[$placeholder] = $value;
        return $this;
    }
    
    /**
     * Reset the query parameters.
     *
     * @return $this
     */
    public function resetParams()
    {
        $this->params = [];
        return $this;
    }
    
    /**
     * Add an inner join to the query.
     *
     * @param string $joinedTable
     * @param string $col1
     * @param string $col2
     * @return $this
     */
    public function innerJoin($joinedTable, $col1, $col2)
    {
        $this->innerJoin = "INNER JOIN $joinedTable ON $this->table.$col1 = $joinedTable.$col2";
        return $this;
    }
    
    /**
     * Add an ORDER BY clause to the query.
     *
     * @param string $column
     * @param string $direction
     * @return $this
     */
    public function orderBy($column, $direction = 'ASC')
    {
        $this->orderBy = " ORDER BY $column $direction";
        return $this;
    }
    
    /**
     * Add a LIMIT clause to the query.
     *
     * @param int $limit
     * @return $this
     */
    public function limit($limit)
    {
        $this->limit = " LIMIT $limit";
        return $this;
    }
    
    /**
     * Retrieve the total count of rows matching the query.
     *
     * @return int
     */
    public function count()
    {
        $sql = "SELECT COUNT(*) AS total FROM $this->table $this->where";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $count = $row['total'];

        return $count;
    }
    
    /**
     * Add an OFFSET clause to the query.
     *
     * @param int $offset
     * @return $this
     */
    public function offset($offset)
    {
        $this->offset = " OFFSET $offset";
        return $this;
    }
    
    /**
     * Execute the query and retrieve a single result.
     *
     * @return mixed
     */
    public function get()
    {
        $sql = "SELECT $this->columns FROM $this->table $this->innerJoin $this->where $this->orderBy $this->limit $this->offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->params);
        $res = $stmt->fetch(PDO::FETCH_OBJ);
        $this->params = [];
        return $res;
    }

    /**
     * Execute the query and retrieve all results.
     *
     * @return array
     */
    public function getAll()
    {
        $sql = "SELECT $this->columns FROM $this->table $this->innerJoin $this->where $this->orderBy $this->limit $this->offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->params);
        $res = $stmt->fetchAll(PDO::FETCH_OBJ);
        $this->params = [];
        return $res;
    }

    /**
     * Execute the query and retrieve a single result.
     *
     * @return mixed
     */
    public function getSingle()
    {
        $sql = "SELECT $this->columns FROM $this->table $this->where $this->orderBy $this->limit $this->offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->params);

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Insert a new row into the table.
     *
     * @param array $data
     * @return int
     */
    public function insert($data)
    {
        $columns = implode(',', array_keys($data));
        $placeholders = implode(',', array_fill(0, count($data), '?'));
        $values = array_values($data);
        $sql = "INSERT INTO $this->table ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($values);
        return $this->pdo->lastInsertId();
    }

    /**
     * Update rows in the table.
     *
     * @param array $data
     * @return int
     */
    public function update($data)
    {
        $set = '';

        foreach ($data as $column => $value) {
            $set .= "$column = :$column,";
            $this->params[":$column"] = $value;
        }

        $set = rtrim($set, ',');
        $sql = "UPDATE $this->table SET $set $this->where";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->params);
        return $stmt->rowCount();
    }

    /**
     * Delete rows from the table.
     *
     * @return int
     */
    public function delete()
    {
        $sql = "DELETE FROM $this->table $this->where $this->limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->params);
        return $stmt->rowCount();
    }
}
