<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 29.03.2018
 * Time: 12:55
 */

namespace Course\Api\Model;


use Course\Services\Persistence\Exceptions\NoResultsException;
use Course\Services\Persistence\MySql;

class ContactModel extends ActiveRecord
{
    /**
     * @var array
     * Contains the database configuration used in the ActiveRecord parent
     */
    private static $config = [
        ActiveRecord::CONFIG_TABLE_NAME => 'contact',
        ActiveRecord::CONFIG_PRIMARY_KEYS => ['id'],
        ActiveRecord::CONFIG_DB_COLUMNS => ['id', 'first_name', 'last_name', 'email', 'message'],
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
    public static function create(string $first_name, string $last_name, string $email, string $message): self
    {
        $id = MySql::insert(self::getTableName(),
            ['first_name' => $first_name, 'last_name' => $last_name, 'email' => $email, 'message' => $message]
        );
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