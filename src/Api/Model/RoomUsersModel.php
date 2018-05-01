<?php

namespace Course\Api\Model;

use Course\Services\Persistence\Exceptions\NoResultsException;
use Course\Services\Persistence\MySql;

/**
 * Class UserModel
 * Active Record for the users table
 * @package Course\Api\Model
 */
class RoomUsersModel extends ActiveRecord
{
    /**
     * @var array
     * Contains the database configuration used in the ActiveRecord parent
     */
    private static $config = [
        ActiveRecord::CONFIG_TABLE_NAME => 'room_users',
        ActiveRecord::CONFIG_PRIMARY_KEYS => ['id'],
        ActiveRecord::CONFIG_DB_COLUMNS => ['id', 'roomId', 'userId'],
    ];

    /**
     * Fetches the user by it's id and returns an UserModel
     *
     * @param int $id - User id
     * @return RoomModel
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
     * Inserts a new record into the users table and returns the newly created user as a UserModel
     *
     * @param int $roomId
     * @param int $userId
     * @return RoomModel
     * @internal param string $name
     * @internal param int $userId
     */
    public static function create(int $roomId, int $userId): self
    {
        $id = MySql::insert(self::getTableName(), ['roomId' => $roomId, 'userId' => $userId]);
        return self::loadById($id);
    }

    public static function areThereAreEnoughUsers(int $roomId): bool
    {
        return count(MySql::getMany(self::getTableName(), ['roomId' => $roomId])) > 2;
    }


    public static function isThereAtleastOneUser(int $roomId): bool
    {
        return count(MySql::getMany(self::getTableName(), ['roomId' => $roomId])) > 1;
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