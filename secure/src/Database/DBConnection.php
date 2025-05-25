<?php

namespace src\Database;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Psr\Cache\InvalidArgumentException;

class DBConnection
{
    private static ?DBConnection $instance = null;
    private Connection $conn;

    private function __construct()
    {
        $this->initializeConnection();
    }

    /**
     * Initialize the database connection.
     *
     * This method sets up the database connection by calling the createConnection method
     * and assigning the resulting Connection object to the $conn property.
     *
     * @return void
     */
    private function initializeConnection(): void
    {
        $this->conn = $this->createConnection();
    }

    /**
     * Create a new database connection using the provided configuration parameters.
     *
     * @return Connection The database connection instance.
     */
    private function createConnection(): Connection
    {
        $config = new Configuration();
        $connectionParams = [
            "dbname" => $_ENV["DB_DATABASE"],
            "user" => $_ENV["DB_USERNAME"],
            "password" => $_ENV["DB_PASSWORD"],
            "host" => $_ENV["DB_HOST"],
            "port" => $_ENV["DB_PORT"],
            "driver" => $_ENV["DB_DRIVER"],
            "driverOptions" => [
                "encrypt" => "yes",
                "trustServerCertificate" => "yes",
            ]
        ];
        return DriverManager::getConnection($connectionParams, $config);
    }

    /**
     * Get the singleton instance of the DBConnection class.
     *
     * @return DBConnection|null The singleton instance of the DBConnection class, or null if not initialized.
     */
    public static function getInstance(): ?DBConnection
    {
        if (!self::$instance) {
            self::$instance = new DBConnection();
        }

        return self::$instance;
    }

    /**
     * Get the database connection instance.
     *
     * @return Connection The database connection instance.
     */
    public function getConnection(): Connection
    {
        return $this->conn;
    }
}
