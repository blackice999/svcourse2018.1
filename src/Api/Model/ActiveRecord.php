<?php

namespace Course\Api\Model;

use Course\Api\Exceptions\Precondition;
use Course\Services\Persistence\MySql;

/**
 * Class ActiveRecord
 * @package Course\Api\Model
 */
abstract class ActiveRecord
{
    const CONFIG_TABLE_NAME   = 'tableName';
    const CONFIG_DB_COLUMNS   = 'dbColumns';
    const CONFIG_PRIMARY_KEYS = 'primaryKeys';

    /** @var array Database row data */
    private $data = [];

    /**
     * ActiveRecord constructor.
     * @param array $data
     */
    protected function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Magic method to fetch properties of ActiveRecord instances from the data array\
     * @see http://php.net/manual/ro/language.oop5.magic.php
     * e.g. when trying to access the property 'username' from UserModel it will call this method
     * and return $this->data['username']
     * @param $name
     * @return mixed
     * @throws \Course\Api\Exceptions\PreconditionException
     */
    public function __get($name)
    {
        Precondition::isTrue(isset($this->data[$name]), 'field ' . $name . ' does not exist');

        return $this->data[$name];
    }

    /**
     * Force the child classes to implement this method
     * @return array
     */
    protected abstract static function getConfig(): array;

    /**
     * Get the database table name from the model's config
     * @return mixed
     */
    public static function getTableName()
    {
        return static::getConfig()[self::CONFIG_TABLE_NAME];
    }

    /**
     * Runs an update query and replaces all the current fields
     * with the current active record instance properties
     */
    public function save()
    {
        // Get all the defined database columns in the child class
        $columns     = static::getConfig()[self::CONFIG_DB_COLUMNS];
        // Get the primary keys from the child class so we can use the in the where clause of the update query
        $primaryKeys = static::getConfig()[self::CONFIG_PRIMARY_KEYS];
        $data        = [];

        // Foreach defined column get the child's current value for that column
        foreach ($columns as $columnName) {
            // generate a column => value array
            $data[$columnName] = $this->{$columnName};
        }

        MySql::update(
            self::getTableName(),
            $data,
            $primaryKeys
        );
    }
}