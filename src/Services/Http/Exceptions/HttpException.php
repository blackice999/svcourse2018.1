<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 3/11/2017
 * Time: 5:43 PM
 */

namespace Course\Services\Http\Exceptions;

use Course\Api\Exceptions\ApiException;
use Course\Services\Http\Response;

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