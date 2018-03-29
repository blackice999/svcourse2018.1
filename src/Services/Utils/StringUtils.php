<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 29.03.2018
 * Time: 12:52
 */

namespace Course\Services\Utils;


class StringUtils
{
    public static function sanitizeString(string $value)
    {
        return filter_var($value, FILTER_SANITIZE_STRING);
    }

}