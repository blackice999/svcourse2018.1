<?php

namespace Course\Services\Http\Exceptions;

use Course\Api\Exceptions\ApiException;
use Course\Services\Http\Response;

/**
 * Class HttpException
 * Exception thrown when we want to display an HTTP error with an http status and message
 * @package Course\Services\Http\Exceptions
 */
class HttpException extends ApiException
{
    public function __construct(string $message, int $code)
    {
        parent::__construct($message, $code);
    }

    public function toResponse()
    {
        $response = new Response($this->getCode(), $this->getMessage(), ['errorCode' => $this->getCode(), 'errorMessage' => $this->getMessage()]);
        $response->displayJsonResponse();
    }
}