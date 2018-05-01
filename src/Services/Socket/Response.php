<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/25/2018
 * Time: 7:43 PM
 */

namespace Course\Services\Socket;
class Response
{
    const END_OF_RESPONSE = "\n";

    public static function getResponse($eventName, $jsonBody)
    {
        return "$eventName:$jsonBody" . self::END_OF_RESPONSE;
    }
}