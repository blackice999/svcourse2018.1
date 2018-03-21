<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 3/19/2017
 * Time: 12:37 PM
 */

namespace Course\Api\Model;


use Course\Services\Persistence\Exceptions\NoResultsException;
use Course\Services\Persistence\MySql;

class UserModel extends ActiveRecord
{
    private static $config = [
        ActiveRecord::CONFIG_TABLE_NAME => 'users',
        ActiveRecord::CONFIG_PRIMARY_KEYS => ['id'],
        ActiveRecord::CONFIG_DB_COLUMNS => ['id', 'username', 'password'],
    ];

    public static function loadById(int $id): self
    {
        $result = MySql::getOne(self::getTableName(), ['id' => $id]);
        return new self($result);
    }

    public static function loadByUsername(string $username): self
    {
        $result = MySql::getOne(self::getTableName(), ['username' => $username]);
        return new self($result);
    }

    public static function loadUserFromSession() : self
    {
        $userId = $_SESSION['userId'];
        return self::loadById($userId);
    }

    public static function usernameExists(string $username): bool
    {
        try {
            MySql::getOne(self::getTableName(), ['username' => $username]);
            return true;
        } catch (NoResultsException $e) {
            return false;
        }
    }

    public static function create(string $username, string $password): self
    {
        $id = MySql::insert(self::getTableName(), ['username' => $username, 'password' => $password]);
        return self::loadById($id);
    }

    protected static function getConfig(): array
    {
        return self::$config;
    }
}