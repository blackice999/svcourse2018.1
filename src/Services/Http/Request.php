<?php

namespace Course\Services\Http;

use Course\Api\Controllers\ErrorCodes;
use Course\Api\Exceptions\Precondition;
use Course\Api\Model\UserModel;
use Course\Services\Http\Exceptions\HttpException;
use Course\Services\Utils\Exceptions\DecryptException;
use Course\Services\Utils\StringUtils;

/**
 * Class Request
 * Contains helper functions to handle requests
 * @package Course\Services\Http
 */
class Request
{
    /**
     * Get the raw body and decode it from a json string
     * @return array|object
     * @throws \Course\Api\Exceptions\PreconditionException
     */
    public static function getJsonBody()
    {
        $rawBody = file_get_contents("php://input");
        Precondition::isNotEmpty($rawBody, 'rawBody');
        $decodedBody = @json_decode($rawBody);
        Precondition::isNotEmpty($decodedBody, 'decodedBody');
        return json_decode($decodedBody);
    }

    /**
     * Get a http header value by it's name
     *
     * @param string $headerName - Header name
     *
     * @return string - header value
     * @throws HttpException
     */
    public static function getHeader(string $headerName): string
    {
        $headerNameUpperCase = strtoupper($headerName);
        $headerKey = "HTTP_$headerNameUpperCase";
        // Headers will be stored in the superglobal $_SERVER
        // @see http://php.net/manual/ro/language.variables.superglobals.php
        // @see http://php.net/manual/ro/reserved.variables.server.php
        if (!isset($_SERVER[$headerKey])) {
            throw new HttpException("Header with key $headerName is missing", ErrorCodes::BAD_REQUEST);
        }

        return $_SERVER[$headerKey];
    }

    /**
     * @return UserModel
     */
    public static function getAuthUser(): UserModel
    {
        try {
            $authToken = self::getHeader('Authorization');
            $userModel = StringUtils::decryptData($authToken);

            if (!($userModel instanceof UserModel)) {
                Response::showBadRequestResponse(ErrorCodes::INVALID_AUTH_TOKEN, 'Invalid auth token');
            }

            return $userModel;

        } catch (DecryptException $e) {
            Response::showBadRequestResponse(ErrorCodes::INVALID_AUTH_TOKEN, 'Could not decrypt token');
        } catch (HttpException $e) {
            Response::showBadRequestResponse($e->getCode(), $e->getMessage());
        }
    }
}