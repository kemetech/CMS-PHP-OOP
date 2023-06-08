<?php

namespace Core\Database;

interface QueryBuilderInterface
{
    public function select($columns);

    public function count();

    public function where($column, $operator, $value);

    public function orderBy($column, $direction);

    public function limit($limit);

    public function offset($offset);

    public function get();

    public function getSingle();

    public function insert($data);

    public function update($data);

    public function delete();
}
