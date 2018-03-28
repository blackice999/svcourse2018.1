<?php

namespace Course\Services\Http;

use Course\Api\Controllers\ErrorCodes;
use Course\Api\Exceptions\Precondition;
use Course\Api\Model\UserModel;
use Course\Services\Http\Exceptions\HttpException;
use Course\Services\Authentication\Exceptions\DecryptException;
use Course\Services\Authentication\Authentication;

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
        return $decodedBody;
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
        // Fetch all the request headers
        $headers = getallheaders();
        // The headers array will contain lower case array keys so we
        // need to make sure the given header name is also lower case
        $headerNameLowerCase = strtolower($headerName);
        // Check if the header exists otherwise throw an exception
        if (!isset($headers[$headerNameLowerCase])) {
            throw new HttpException("Header with key $headerName is missing", ErrorCodes::BAD_REQUEST);
        }

        return $headers[$headerNameLowerCase];
    }

    /**
     *
     * @return UserModel
     */
    public static function getAuthenticatedUser(): UserModel
    {
        try {
            // fetches the authorization header
            $authToken = self::getHeader('Authorization');
            // decrypts the authorization header and generates an user model
            $userModel = Authentication::decryptToken($authToken);

            // Generates an error if the decrypted data is not an instance of UserModel
            if (!($userModel instanceof UserModel)) {
                Response::showBadRequestResponse(ErrorCodes::INVALID_AUTH_TOKEN, 'Invalid authentication token');
            }

            return $userModel;

        } catch (DecryptException $e) {
            Response::showBadRequestResponse(ErrorCodes::INVALID_AUTH_TOKEN, 'Could not decrypt token');
        } catch (HttpException $e) {
            Response::showBadRequestResponse($e->getCode(), $e->getMessage());
        }
    }
}