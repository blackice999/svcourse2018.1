<?php

namespace Course\Services\Authentication;

use Course\Services\Authentication\Exceptions\DecryptException;

/**
 * Class Authentication
 * @package Course\Services\Authentication
 */
class Authentication
{
    /**
     * Encrypt the password to md5 with the configured salt
     *
     * @param $string
     * @return string
     */
    public static function encryptPassword($string)
    {
        return md5(PASSWORD_SALT . $string);
    }

    /**
     * @param $plainText
     * @return string
     */
    public static function openSSLEncrypt($plainText)
    {
        $decodedKey = base64_decode(TOKEN_ENCRYPTION_KEY);
        $decodedIV = base64_decode(TOKEN_ENCRYPTION_IV);

        return openssl_encrypt($plainText, TOKEN_ENCRYPTION_ALGORITHM, $decodedKey, OPENSSL_RAW_DATA, $decodedIV);
    }

    /**
     * @param $cipherText
     * @return string
     */
    public static function openSSLDecrypt($cipherText)
    {
        $decodedKey = base64_decode(TOKEN_ENCRYPTION_KEY);
        $decodedIV = base64_decode(TOKEN_ENCRYPTION_IV);

        return openssl_decrypt($cipherText, TOKEN_ENCRYPTION_ALGORITHM, $decodedKey, OPENSSL_RAW_DATA, $decodedIV);

    }

    /**
     * Encrypt data and return a token string
     *
     * @param mixed $data
     *
     * @return string
     */
    public static function generateToken($data): string
    {
        return base64_encode(self::openSSLEncrypt(serialize($data)));
    }

    /**
     * Decrypt token and return the decrypted data
     *
     * @param string $token
     *
     * @return mixed
     * @throws DecryptException
     */
    public static function decryptToken(string $token)
    {
        $data = unserialize(self::openSSLDecrypt(base64_decode($token)));

        if (false === $data) {
            throw new DecryptException('Invalid token');
        }

        return $data;
    }
}
