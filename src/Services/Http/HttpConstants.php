<?php

namespace Course\Services\Http;

/**
 * Class HttpConstants
 * Contains definitions of HTTP methods and status codes
 * @package Course\Services\Http
 */
class HttpConstants
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    const STATUS_CODE_OK = 200;
    const STATUS_CODE_BAD_REQUEST = 400;
    const STATUS_CODE_UNAUTHENTICATED = 401;
    const STATUS_CODE_NOT_FOUND = 404;
    const STATUS_CODE_METHOD_NOT_ALLOWED = 405;
    const STATUS_CODE_INTERNAL_SERVER_ERROR = 500;
}