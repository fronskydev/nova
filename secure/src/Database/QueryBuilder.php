<?php

namespace src\Database;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class QueryBuilder
{
    private Connection $connection;
    private int $lastInsertedId = 0;
    private string $sql = "";

    public function __construct() {
        $this->connection = DBConnection::getInstance()->getConnection();
    }

    /**
     * Set the raw SQL query to be executed.
     *
     * @param string $sql The raw SQL query to be executed.
     *
     * @return self Returns the current instance of the class to allow method chaining.
     */
    public function raw(string $sql): self
    {
        $this->sql = $sql;
        return $this;
    }

    /**
     * Set the SQL query to perform a SELECT operation on the specified table with the given columns.
     *
     * @param string $table The name of the table from which to select data.
     * @param array $columns An array of column names to include in the SELECT statement. Defaults to ["*"] (all columns).
     *
     * @return self Returns the current instance of the class to allow method chaining.
     */
    public function select(string $table, array $columns = ["*"]): self
    {
        $this->sql = "SELECT " . implode(", ", $columns) . " FROM $table";
        return $this;
    }

    /**
     * Add a WHERE clause to the SQL query.
     *
     * @param string $column The column name to use in the WHERE clause.
     * @param string $operator The comparison operator to use in the WHERE clause (e.g., "=", ">", "<=", "LIKE", etc.).
     * @param mixed $value The value to compare against in the WHERE clause.
     *
     * @return self Returns the current instance of the class to allow method chaining.
     */
    public function where(string $column, string $operator, mixed $value): self {
        if (!is_int($value)) {
            $value = "'" . $value . "'";
        }

        $this->sql .= " WHERE $column $operator $value";
        return $this;
    }

    /**
     * Add an AND WHERE clause to the SQL query.
     *
     * @param string $column The column name to use in the additional AND WHERE clause.
     * @param string $operator The comparison operator to use in the additional AND WHERE clause (e.g., "=", ">", "<=", "LIKE", etc.).
     * @param mixed $value The value to compare against in the additional AND WHERE clause.
     *
     * @return self Returns the current instance of the class to allow method chaining.
     */
    public function andWhere(string $column, string $operator, mixed $value): self {
        if (!is_int($value)) {
            $value = "'" . $value . "'";
        }

        $this->sql .= " AND $column $operator $value";
        return $this;
    }

    /**
     * Add OR WHERE clause to the SQL query.
     *
     * @param string $column The column name to use in the additional OR WHERE clause.
     * @param string $operator The comparison operator to use in the additional OR WHERE clause (e.g., "=", ">", "<=", "LIKE", etc.).
     * @param mixed $value The value to compare against in the additional OR WHERE clause.
     *
     * @return self Returns the current instance of the class to allow method chaining.
     */
    public function orWhere(string $column, string $operator, mixed $value): self {
        if (!is_int($value)) {
            $value = "'" . $value . "'";
        }

        $this->sql .= " OR $column $operator $value";
        return $this;
    }

    /**
     * Set the SQL query to perform an INSERT operation into the specified table with the given data.
     *
     * @param string $table The name of the table into which the data will be inserted.
     * @param array $data An associative array containing the data to be inserted, where keys represent column names and values represent corresponding values.
     *
     * @return self Returns the current instance of the class to allow method chaining.
     */
    public function insert(string $table, array $data): self {
        $columns = implode(", ", array_keys($data));
        $values = implode(", ", array_map(static function ($value) {
            return is_string($value) ? "'" . addslashes($value) . "'" : $value;
        }, $data));

        $this->sql = "INSERT INTO $table ($columns) VALUES ($values)";
        return $this;
    }

    /**
     * Set the SQL query to perform an UPDATE operation on the specified table with the given data.
     *
     * @param string $table The name of the table to update.
     * @param array $data An associative array containing the data to be updated, where keys represent column names and values represent corresponding values.
     *
     * @return self Returns the current instance of the class to allow method chaining.
     */
    public function update(string $table, array $data): self {
        $setStatements = implode(", ", array_map(static function ($column) use ($data) {
            $value = $data[$column];
            return "$column = " . (is_string($value) ? "'" . addslashes($value) . "'" : $value);
        }, array_keys($data)));

        $this->sql = "UPDATE $table SET $setStatements";
        return $this;
    }

    /**
     * Set the SQL query to perform a DELETE operation on the specified table.
     *
     * @param string $table The name of the table from which to delete data.
     *
     * @return self Returns the current instance of the class to allow method chaining.
     */
    public function delete(string $table): self {
        $this->sql = "DELETE FROM $table";
        return $this;
    }

    /**
     * Execute the prepared SQL query and return the result as an associative array.
     *
     * This method prepares the SQL query stored in the $sql property, executes it,
     * and fetches all results as an associative array. If the query is an INSERT operation,
     * it updates the last inserted ID.
     *
     * @return array The result of the executed query as an associative array.
     * @throws Exception If there is an error during query execution.
     */
    public function execute(): array
    {
        $stmt = $this->connection->prepare($this->sql);
        $result = $stmt->executeQuery()->fetchAllAssociative();

        if (stripos($this->sql, "INSERT INTO") === 0) {
            $this->lastInsertedId = $this->connection->lastInsertId();
        }

        return $result;
    }

    /**
     * Retrieves the last inserted ID from the database.
     *
     * @return int The last inserted ID.
     */
    public function getLastInsertedId(): int
    {
        return $this->lastInsertedId;
    }

    /**
     * Get the prepared SQL query.
     *
     * @return string The prepared SQL query.
     */
    public function getSQL(): string
    {
        return $this->sql;
    }
}
