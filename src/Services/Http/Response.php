<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 3/11/2017
 * Time: 4:35 PM
 */

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
        Precondition::isTrue(strpos($message, "\n") === false, 'new line found in the message');
        $this->statusCode = $statusCode;
        $this->message = $message;
        $this->data = $data;
    }

    public function displayJsonResponse()
    {
        header("HTTP/1.1 $this->statusCode $this->message");
        header('Content-Type: application/json');
        echo json_encode($this->data);
        die; // make sure nothing else is executed beyond this point
    }

    /**
     * @param array $data
     */
    public static function showSuccessResponse( array $data = [])
    {
        $response = new Response(HttpConstants::STATUS_CODE_OK, 'OK', $data);
        $response->displayJsonResponse();
    }

    public static function showBadRequestResponse(int $errorCode, string $message)
    {
        $response = new Response(HttpConstants::STATUS_CODE_BAD_REQUEST, 'Bad Request', ['errorCode' => $errorCode, 'errorMessage' => $message]);
        $response->displayJsonResponse();
    }

    public static function showUnauthorizedResponse()
    {
        $response = new Response(HttpConstants::STATUS_CODE_UNAUTHENTICATED, 'Unauthenticated');
        $response->displayJsonResponse();
    }

    public static function showInternalErrorResponse(int $errorCode, string $message)
    {
        $response = new Response(HttpConstants::STATUS_CODE_INTERNAL_SERVER_ERROR, 'Internal Server Error', ['errorCode' => $errorCode, 'errorMessage' => $message]);
        $response->displayJsonResponse();
    }
}