<?php

namespace Course\Api\Controllers;

/**
 * Contains definitions for error codes returned in the response
 * Class ErrorCodes
 * @package Course\Api\Controllers
 */
class ErrorCodes
{
    const GENERIC_ERROR      = 1;
    const INVALID_PARAMETER  = 2;
    const BAD_REQUEST        = 3;
    const INVALID_AUTH_TOKEN = 4;

    const USER_CREATE_USERNAME_ALREADY_TAKEN = 100;

    const USER_LOGIN_USERNAME_DOES_NOT_EXIST = 200;
    const USER_LOGIN_INCORRECT_PASSWORD      = 201;

    const USER_NOT_LOGGED_ID = 401;

    const HUNT_IS_NOT_ACTIVE       = 1000;
    const USER_ALREADY_JOINED_TEAM = 1001;

    const USER_IS_NOT_TEAM_OWNER       = 1100;
    const USER_IS_NOT_PART_OF_THE_HUNT = 1101;
}
