<?php

namespace Course\Api\Controllers;

use Course\Api\Exceptions\Precondition;
use Course\Api\Exceptions\PreconditionException;
use Course\Api\Model\UserModel;
use Course\Services\Http\Exceptions\HttpException;
use Course\Services\Http\HttpConstants;
use Course\Services\Http\Request;
use Course\Services\Http\Response;
use Course\Services\Authentication\Authentication;

class UsersLoginController implements Controller
{
    /**
     * @throws HttpException
     */
    public function get()
    {
        throw new HttpException('Method Now Allowed', HttpConstants::STATUS_CODE_METHOD_NOT_ALLOWED);
    }

    /**
     * Handler for /users/login POST
     * Checks the username and password and generates an authentication token
     *
     * @throws PreconditionException
     * @throws \Course\Services\Persistence\Exceptions\ConnectionException
     * @throws \Course\Services\Persistence\Exceptions\NoResultsException
     * @throws \Course\Services\Persistence\Exceptions\QueryException
     */
    public function create()
    {
        // Get the request body
        $body = Request::getJsonBody();

        try {
            // Check if the username length is between 4 and 20 characters
            Precondition::lengthIsBetween($body->username, 4, 20, 'username');
            // Check if the password length is between 6 and 20 characters
            Precondition::lengthIsBetween($body->password, 6, 20, 'password');
        } catch (PreconditionException $e) {
            // Return an error response if any precondition exception is thrown
            Response::showBadRequestResponse(ErrorCodes::INVALID_PARAMETER, $e->getMessage());
        }

        // Check if the username exists
        if (!UserModel::usernameExists($body->username)) {
            // if it doesn't exist return a 401 Unauthorized response
            Response::showUnauthorizedResponse();
        }

        // Encrypt the given password
        $password = Authentication::encryptPassword($body->password);
        $userModel = UserModel::loadByUsername($body->username);

        // Check if the encrypted password from the db matches the given encrypted password
        if ($userModel->password != $password) {
            Response::showUnauthorizedResponse();
        }

        Response::showSuccessResponse([
            'userId' => $userModel->id,
            'authorizationToken' => Authentication::generateToken($userModel),
        ]);
    }

    /**
     * @throws HttpException
     */
    public function update()
    {
        throw new HttpException('Method Now Allowed', HttpConstants::STATUS_CODE_METHOD_NOT_ALLOWED);
    }

    /**
     * @throws HttpException
     */
    public function delete()
    {
        throw new HttpException('Method Now Allowed', HttpConstants::STATUS_CODE_METHOD_NOT_ALLOWED);
    }
}