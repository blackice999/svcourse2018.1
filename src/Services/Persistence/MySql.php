<?php

namespace Course\Services\Persistence;

/**
 * Class MySql
 * Service that handles the MySql database connection
 * and contains helper functions to perform queries
 * @package Course\Services\Persistence
 */
class MySql
{
    /** @var null \mysqli */
    private static $connection = null;

    /**
     * Singleton function to fetch an existing connection or create a new one and return it
     * @see https://www.tutorialspoint.com/design_pattern/singleton_pattern.htm
     * @return \mysqli
     * @throws Exceptions\ConnectionException
     */
    private static function getConnection()
    {
        if (is_null(self::$connection)) {
            self::$connection = new \mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE_NAME, DB_PORT);
            if (self::$connection->connect_error) {
                throw new Exceptions\ConnectionException('could not connect to the database ' . DB_DATABASE_NAME);
            }
        }

        return self::$connection;
    }

    /**
     * Creates or reuses a database connection and executes a given query
     * @param string $sql
     *
     * @return bool|\mysqli_result
     * @throws Exceptions\QueryException
     * @throws Exceptions\ConnectionException
     */
    private static function query(string $sql)
    {
        // get the database connection and execute the query
        $result = self::getConnection()->query($sql);

        // If we have any errors we want to throw an exception
        if (self::getConnection()->errno > 0) {
            throw new Exceptions\QueryException(self::getConnection()->error, self::getConnection()->errno);
        }

        return $result;
    }

    /**
     * Performs a select query and fetches a single result
     *
     * @param string $tableName - the database table we're performing the query on
     * @param array $where - key => value pair array used to generate the where clause of the query
     *
     * @return array - Array containing the result of the query
     *
     * @throws Exceptions\ConnectionException
     * @throws Exceptions\NoResultsException
     * @throws Exceptions\QueryException
     */
    public static function getOne(string $tableName, array $where): array
    {
        $sql = "select * from `$tableName` where ";

        // compose the where clause
        foreach ($where as $columnName => $columnValue) {
            $sql .= "`$columnName` = '" . self::getConnection()->escape_string($columnValue) . "' AND ";
        }
        // Remove the last AND from the query
        $sql = rtrim($sql, ' AND ');

        // execute the query and fetch the result as array
        $result = mysqli_fetch_assoc(self::query($sql));

        if (empty($result)) {
            throw new Exceptions\NoResultsException("no results in table $tableName by " . json_encode($where));
        }

        return $result;
    }

    /**
     * Performs a select query and fetches the result
     *
     * @param string $tableName - the database table we're performing the query on
     * @param array $where - key => value pair array used to generate the where clause of the query
     *
     * @return array (array results => array(column => value))
     * @throws Exceptions\ConnectionException
     * @throws Exceptions\QueryException
     */
    public static function getMany(string $tableName, array $where = [])
    {
        $sql = "select * from `$tableName`";
        $whereConditions = [];

        foreach ($where as $columnName => $columnValue) {
            $whereConditions[] = "`$columnName` = '" . self::getConnection()->escape_string($columnValue) . "'";
        }

        $sql .= count($whereConditions) > 0 ? ' where ' . implode(' AND ', $whereConditions) : '';
        $result = mysqli_fetch_all(self::query($sql), MYSQLI_ASSOC);

        return $result ?: [];
    }

    /**
     * Fetch an array of results after executing a custom query
     *
     * @param string $query - custom mysql query we want to execute
     *
     * @return array - array of results
     * @throws Exceptions\ConnectionException
     * @throws Exceptions\QueryException
     */
    public static function getManyForCustomQuery(string $query)
    {
        $result = mysqli_fetch_all(self::query($query), MYSQLI_ASSOC);

        return $result ?: [];
    }

    /**
     * Generate an insert query
     *
     * @param string $tableName - database table name
     * @param array $data - array of column => value pairs
     *
     * @return int - the id of the newly inserted row
     * @throws Exceptions\ConnectionException
     * @throws Exceptions\QueryException
     */
    public static function insert(string $tableName, array $data)
    {
        $columnNames = $columnValues = [];

        // Generate arrays of column names and column values, and escapes the value string
        // @see http://php.net/manual/ro/function.mysql-real-escape-string.php
        foreach ($data as $columnName => $columnValue) {
            $columnNames[] = "`$columnName`";
            $columnValues[] = '"' . self::getConnection()->escape_string($columnValue) . '"';
        }
        // Generates comma separated strings from arrays
        $columnsAsString = implode(',', $columnNames);
        $valuesAsString = implode(',', $columnValues);

        // Generate the query and execute it
        self::query("insert into `$tableName` ($columnsAsString) VALUES ($valuesAsString)");

        // Return the id of the newly created row
        return self::getConnection()->insert_id;
    }

    /**
     * Executes an update query
     *
     * @param string $tableName - database table name
     * @param array $data - column => value pairs that we want to update
     * @param array $primaryKeys - array containing the primary keys that we use in the where clause
     *
     * @return int - the number of rows that were updated
     * @throws Exceptions\ConnectionException
     * @throws Exceptions\QueryException
     */
    public static function update(
        string $tableName,
        array $data,
        array $primaryKeys
    ) {
        $columnValueString = '';
        $where = '';

        // Generate the where clause
        foreach ($primaryKeys as $columnName) {
            $escapedValue = self::getConnection()->escape_string($data[$columnName]);
            $where .= "`$columnName` = '$escapedValue' AND ";
            // We don't want to update any primary keys so we'll remove it from $data if set
            unset($data[$columnName]);
        }

        // Remove the last AND from the where clause
        $where = rtrim($where, 'AND ');

        foreach ($data as $columnName => $columnValue) {
            $escapedValue = self::getConnection()->escape_string($columnValue);
            $columnValueString .= "`$columnName` = '$escapedValue', ";
        }

        $columnValueString = rtrim($columnValueString, ', ');

        // Generate the query and execute it
        self::query("UPDATE `$tableName` SET $columnValueString  WHERE $where");

        // Return the number of rows that were updated
        return self::getConnection()->affected_rows;
    }
}