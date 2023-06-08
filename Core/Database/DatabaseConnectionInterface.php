<?php

/**
 * File: DatabaseConnectionInterface.php
 * Description: This file contains the DatabaseConnectionInterface interface, which defines the contract for a database connection.
 */

namespace Core\Database;

use PDO;

interface DatabaseConnectionInterface
{
    /**
     * Establishes a database connection.
     *
     * @return void
     */
    public static function connect();

    /**
     * Executes a query and returns the result as an array of rows.
     *
     * @param string $query The SQL query to execute.
     * @param array $params The query parameters.
     * @return array The result set as an array of rows.
     */
    public function executeQuery(string $query, array $params = []): array;

    /**
     * Executes a statement (e.g., INSERT, UPDATE, DELETE) and returns the number of affected rows.
     *
     * @param string $query The SQL statement to execute.
     * @param array $params The query parameters.
     * @return int The number of affected rows.
     */
    public function executeStatement(string $query, array $params = []): int;

    /**
     * Disconnects from the database.
     *
     * @return void
     */
    public static function disconnect(): void;

    /**
     * Retrieves the PDO connection instance.
     *
     * @return PDO The PDO connection instance.
     */
    public static function getConnection(): PDO;
}
