<?php

namespace Core\Database;

use Core\Config\MysqlConfig;
use PDO;
use Exception;
use RuntimeException;

class MySqlConnection implements DatabaseConnectionInterface
{
    protected static $dsn;
    protected static $username;
    protected static $password;
    protected static $host;
    protected static $dbname;

    private static $pdo;

    public function __construct()
    {
        self::$host = MysqlConfig::getHost();
        self::$username = MysqlConfig::getUser();
        self::$password = MysqlConfig::getPass();
        self::$dbname = MysqlConfig::getDbName();
        self::$dsn = MysqlConfig::getDsn();
    }

    /**
     * Establishes a database connection using PDO.
     *
     * @throws RuntimeException if the connection cannot be established
     */
    public static function connect()
    {
        try {
            self::$pdo = new PDO(self::$dsn, self::$username, self::$password);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            throw new RuntimeException('Connection has not been established yet: ' . $e->getMessage());
        }
    }

    /**
     * Executes a query and returns the result set as an associative array.
     *
     * @param string $query The SQL query
     * @param array $params Optional parameters for prepared statements
     * @return array The result set as an associative array
     */
    public function executeQuery(string $query, array $params = []): array
    {
        $stmt = self::$pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Executes a statement (e.g., INSERT, UPDATE, DELETE) and returns the number of affected rows.
     *
     * @param string $query The SQL query
     * @param array $params Optional parameters for prepared statements
     * @return int The number of affected rows
     */
    public function executeStatement(string $query, array $params = []): int
    {
        $stmt = self::$pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    /**
     * Returns the last inserted ID.
     *
     * @return int The last inserted ID
     */
    public function getLastInsertId(): int
    {
        return (int) self::$pdo->lastInsertId();
    }

    /**
     * Disconnects from the database by setting the PDO instance to null.
     */
    public static function disconnect(): void
    {
        self::$pdo = null;
    }

    /**
     * Returns the PDO instance representing the database connection.
     *
     * @throws RuntimeException if the connection has not been established yet
     * @return PDO The PDO instance representing the database connection
     */
    public static function getConnection(): PDO
    {
        if (!self::$pdo) {
            throw new RuntimeException('Connection has not been established yet');
        }
        return self::$pdo;
    }
}
