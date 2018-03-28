<?php

namespace Course\Services\Http;

use Course\Api\Exceptions\Precondition;

class Response
{
    private $statusCode;
    private $message;
    private $data;

    /**
     * Response constructor.
     * @param int $statusCode
     * @param string $message
     * @param array $data
     * @throws \Course\Api\Exceptions\PreconditionException
     */
    public function __construct(int $statusCode, string $message, array $data = [])
    {
        // We want to prevent any new lines in the HTTP status message
        Precondition::isTrue(strpos($message, "\n") === false, 'new line found in the message');
        $this->statusCode = $statusCode;
        $this->message = $message;
        $this->data = $data;
    }

    /**
     * Sets the response headers and displays a json response
     */
    public function displayJsonResponse()
    {
        header("HTTP/1.1 $this->statusCode $this->message");
        header('Content-Type: application/json');
        echo json_encode($this->data);
        die; // make sure nothing else is executed beyond this point
    }

    /**
     * Sets the response headers
     */
    public function displayEmptyResponse()
    {
        header("HTTP/1.1 $this->statusCode $this->message");
        die; // make sure nothing else is executed beyond this point
    }

    /**
     * Generates a 200 OK response with a json response body
     *
     * @param array $data
     */
    public static function showSuccessResponse( array $data = [])
    {
        $response = new Response(HttpConstants::STATUS_CODE_OK, 'OK', $data);
        $response->displayJsonResponse();
    }

    /**
     * Generates a 400 Bad Request response with a json containing an internal error code and message
     *
     * @param int $errorCode - Internal error code number
     * @param string $message - Internal error message
     */
    public static function showBadRequestResponse(int $errorCode, string $message)
    {
        $response = new Response(
            HttpConstants::STATUS_CODE_BAD_REQUEST,
            'Bad Request',
            ['errorCode' => $errorCode, 'errorMessage' => $message]
        );
        $response->displayJsonResponse();
    }

    /**
     * Generates a 401 - Unauthenticated error response
     */
    public static function showUnauthorizedResponse()
    {
        $response = new Response(HttpConstants::STATUS_CODE_UNAUTHENTICATED, 'Unauthenticated');
        $response->displayEmptyResponse();
    }

    /**
     * Generates a 500 Internal Server Error response with a json containing an internal error code and message
     *
     * @param int $errorCode - Internal error code number
     * @param string $message - Internal error message
     */
    public static function showInternalErrorResponse(int $errorCode, string $message)
    {
        $response = new Response(
            HttpConstants::STATUS_CODE_INTERNAL_SERVER_ERROR,
            'Internal Server Error',
            ['errorCode' => $errorCode, 'errorMessage' => $message]
        );
        $response->displayJsonResponse();
    }
}