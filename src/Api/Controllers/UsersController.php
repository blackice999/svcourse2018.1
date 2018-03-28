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

class UsersController implements Controller
{
    /**
     * Handler for /users GET
     * Fetches the authenticated user's details
     */
    public function get()
    {
        // Get the user model from the authorization token
        $userModel = Request::getAuthenticatedUser();

        // Return a success response with the username
        Response::showSuccessResponse([
            'username' => $userModel->username
        ]);
    }

    /**
     * Handler for /users POST
     * Creates a new user
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

        // Check if the username is already taken
        if (UserModel::usernameExists($body->username)) {
            Response::showBadRequestResponse(ErrorCodes::USER_CREATE_USERNAME_ALREADY_TAKEN, 'username already taken');
        }

        // Encrypt the given password
        $password = Authentication::encryptPassword($body->password);
        // Create the user
        $userModel = UserModel::create($body->username, $password);

        // Generate an authorization token so it won't have to login afterwards
        Response::showSuccessResponse([
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