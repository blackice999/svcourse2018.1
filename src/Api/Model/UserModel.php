<?php

namespace Course\Api\Model;

use Course\Services\Persistence\Exceptions\NoResultsException;
use Course\Services\Persistence\MySql;

/**
 * Class UserModel
 * Active Record for the users table
 * @package Course\Api\Model
 */
class UserModel extends ActiveRecord
{
    /**
     * @var array
     * Contains the database configuration used in the ActiveRecord parent
     */
    private static $config = [
        ActiveRecord::CONFIG_TABLE_NAME => 'users',
        ActiveRecord::CONFIG_PRIMARY_KEYS => ['id'],
        ActiveRecord::CONFIG_DB_COLUMNS => ['id', 'username', 'password'],
    ];

    /**
     * Fetches the user by it's id and returns an UserModel
     *
     * @param int $id - User id
     * @return UserModel
     * @throws NoResultsException
     * @throws \Course\Services\Persistence\Exceptions\ConnectionException
     * @throws \Course\Services\Persistence\Exceptions\QueryException
     */
    public static function loadById(int $id): self
    {
        $result = MySql::getOne(self::getTableName(), ['id' => $id]);
        return new self($result);
    }

    /**
     * Fetches the user by it's username and returns an UserModel
     *
     * @param string $username - User's username field
     * @return UserModel
     * @throws NoResultsException
     * @throws \Course\Services\Persistence\Exceptions\ConnectionException
     * @throws \Course\Services\Persistence\Exceptions\QueryException
     */
    public static function loadByUsername(string $username): self
    {
        $result = MySql::getOne(self::getTableName(), ['username' => $username]);
        return new self($result);
    }

    /**
     * Checks if the username exists in the database
     *
     * @param string $username
     * @return bool
     * @throws \Course\Services\Persistence\Exceptions\ConnectionException
     * @throws \Course\Services\Persistence\Exceptions\QueryException
     */
    public static function usernameExists(string $username): bool
    {
        try {
            MySql::getOne(self::getTableName(), ['username' => $username]);
            return true;
        } catch (NoResultsException $e) {
            return false;
        }
    }

    /**
     * Inserts a new record into the users table and returns the newly created user as a UserModel
     *
     * @param string $username
     * @param string $password
     * @return UserModel
     * @throws NoResultsException
     * @throws \Course\Services\Persistence\Exceptions\ConnectionException
     * @throws \Course\Services\Persistence\Exceptions\QueryException
     */
    public static function create(string $username, string $password): self
    {
        $id = MySql::insert(self::getTableName(), ['username' => $username, 'password' => $password]);
        return self::loadById($id);
    }

    /**
     * Used in the ActiveRecord parent to fetch the db config
     *
     * @return array
     */
    protected static function getConfig(): array
    {
        return self::$config;
    }
}