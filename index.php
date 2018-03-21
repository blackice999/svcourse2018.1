<?php
include "autoload.php";
session_start(['cookie_lifetime' => 86400]);

use Course\Api\Controllers\ErrorCodes;
use Course\Api\Controllers\Router;
use Course\Api\Exceptions\ApiException;
use Course\Services\Http\HttpConstants;
use Course\Services\Http\Response;
use Course\Services\Http\Exceptions\HttpException;

try {

    if (empty($_GET['path'])) {
        throw new HttpException('Bad Request', HttpConstants::STATUS_CODE_BAD_REQUEST);
    }

    $router = new Router($_GET['path'], $_SERVER['REQUEST_METHOD']);
    $router->processRequest();

} catch (HttpException $e) {
    $e->toResponse();
} catch (ApiException $e) {
    $response = new Response(HttpConstants::STATUS_CODE_INTERNAL_SERVER_ERROR, 'Internal Server Error');
    Response::showInternalErrorResponse(ErrorCodes::GENERIC_ERROR, 'Uncaught exception');
}