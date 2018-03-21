<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 3/19/2017
 * Time: 2:54 PM
 */

namespace Course\Services\Utils;

use Course\Services\Utils\Exceptions\DecryptException;

class StringUtils
{
    public static function encryptPassword($string)
    {
        return md5(PASSWORD_SALT . $string);
    }

    /**
     * Encrypt data and return a token string
     *
     * @param mixed $data
     *
     * @return string
     */
    public static function encryptData($data): string
    {
        return base64_encode(serialize($data));
    }

    /**
     * Decrypt token and return data
     *
     * @param string $token
     *
     * @return mixed
     * @throws DecryptException
     */
    public static function decryptData(string $token)
    {
        $data = unserialize(base64_decode($token));

        if (false === $data) {
            throw new DecryptException('Invalid token');
        }

        return $data;
    }
}
