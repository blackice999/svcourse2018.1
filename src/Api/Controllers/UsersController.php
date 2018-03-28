<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 3/11/2017
 * Time: 4:07 PM
 */

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
     *
     */
    public function get()
    {
        $userModel = Request::getAuthenticatedUser();

        Response::showSuccessResponse([
            'username' => $userModel->username
        ]);
    }

    /**
     * @throws PreconditionException
     */
    public function create()
    {
        $body = Request::getJsonBody();

        try {
            Precondition::lengthIsBetween($body->username, 4, 20, 'username');
            Precondition::lengthIsBetween($body->password, 6, 20, 'password');
        } catch (PreconditionException $e) {
            Response::showBadRequestResponse(ErrorCodes::INVALID_PARAMETER, $e->getMessage());
        }

        if (UserModel::usernameExists($body->username)) {
            Response::showBadRequestResponse(ErrorCodes::USER_CREATE_USERNAME_ALREADY_TAKEN, 'username already taken');
        }

        $password = Authentication::encryptPassword($body->password);
        $userModel = UserModel::create($body->username, $password);

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