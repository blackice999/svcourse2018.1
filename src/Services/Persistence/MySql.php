<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 3/19/2017
 * Time: 11:52 AM
 */

namespace Course\Services\Persistence;


class MySql
{
    /** @var null \mysqli */
    private static $connection = null;

    /**
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
     * @param string $sql
     *
     * @return bool|\mysqli_result
     * @throws Exceptions\QueryException
     */
    private function query(string $sql)
    {
        $result = self::getConnection()->query($sql);

        if (self::getConnection()->errno > 0) {
            throw new Exceptions\QueryException(self::getConnection()->error, self::getConnection()->errno);
        }

        return $result;
    }

    /**
     * @param string $tableName
     * @param array  $where
     *
     * @return array|null
     * @throws Exceptions\NoResultsException
     */
    public static function getOne(string $tableName, array $where)
    {
        $sql = 'select * from `' . $tableName . '` where ';

        foreach ($where as $columnName => $columnValue) {
            $sql .= '`' . $columnName . '` = "' . self::getConnection()->escape_string($columnValue) . '" AND ';
        }
        $sql = rtrim($sql, ' AND ');

        $result = mysqli_fetch_assoc(self::query($sql));

        if (empty($result)) {
            throw new Exceptions\NoResultsException('no results in table ' . $tableName . ' by ' . json_encode($where));
        }

        return $result;
    }

    /**
     * @param string $tableName
     * @param array  $where
     *
     * @return array
     * @throws Exceptions\ConnectionException
     * @throws Exceptions\QueryException
     */
    public static function getMany(string $tableName, array $where = [])
    {
        $sql     = 'select * from `' . $tableName . '`';
        $clauses = [];

        foreach ($where as $columnName => $columnValue) {
            $clauses[] = '`' . $columnName . '` = "' . self::getConnection()->escape_string($columnValue) . '"';
        }

        $sql    .= count($clauses) > 0 ? ' where ' . implode(' AND ', $clauses) : '';
        $result = mysqli_fetch_all(self::query($sql), MYSQLI_ASSOC);

        return $result ?: [];
    }

    /**
     * @param string $query
     *
     * @return array
     * @throws Exceptions\ConnectionException
     * @throws Exceptions\QueryException
     */
    public static function getManyForCustomQuery(string $query)
    {
        $result = mysqli_fetch_all(self::query($query), MYSQLI_ASSOC);

        return $result ?: [];
    }

    /**
     * @param string $tableName
     * @param array  $data
     *
     * @return int
     */
    public static function insert(string $tableName, array $data)
    {
        $sql         = 'insert into `' . $tableName . '` ';
        $columnNames = $columnValues = [];

        foreach ($data as $columnName => $columnValue) {
            $columnNames[]  = "`" . $columnName . "`";
            $columnValues[] = '"' . self::getConnection()->escape_string($columnValue) . '"';
        }
        $sql .= '(' . implode(',', $columnNames) . ') ';
        $sql .= 'values (' . implode(',', $columnValues) . ') ';
        self::query($sql);

        return self::getConnection()->insert_id;
    }

    /**
     * @param string $tableName
     * @param array  $data
     *
     * @param array  $primaryKeys
     *
     * @return int
     */
    public static function update(
        string $tableName,
        array $data,
        array $primaryKeys
    ) {
        $sql   = 'update `' . $tableName . '` SET ';
        $set   = '';
        $where = '';

        foreach ($primaryKeys as $columnName) {
            $where .= '`' . $columnName . '` = "'
                . self::getConnection()->escape_string($data[$columnName])
                . '" AND ';
            unset($data[$columnName]);
        }

        $where = rtrim($where, 'AND ');

        foreach ($data as $columnName => $columnValue) {
            $set .= '`' . $columnName . '` = "'
                . self::getConnection()->escape_string($columnValue)
                . '", ';
        }

        $set = rtrim($set, ', ');

        $sql .= $set . ' WHERE ' . $where;

        self::query($sql);

        return self::getConnection()->insert_id;
    }
}