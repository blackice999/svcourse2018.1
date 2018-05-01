<?php

namespace Course\Api\Model;

use Course\Api\Exceptions\ApiException;
use Course\Api\Exceptions\Precondition;

class Events
{
//    const AUTHORIZE = 'authorize';
    const JOIN_ROOM = 'joinRoom';
    const ALLOWED_EVENTS = [
        self::JOIN_ROOM
    ];
    private static $room = null;

    /**
     * @param $data
     * @return Event
     * @throws \Course\Api\Exceptions\PreconditionException
     * @throws ApiException
     */
    public static function getEvent($data)
    {
        $explode = explode(':', $data, 2);
        if (count($explode) !== 2) {
            throw new ApiException('socket message should be in this form event:jsonBody');
        }
        list($eventType, $jsonBody) = $explode;
        var_dump($eventType);
        Precondition::isInArray($eventType, self::ALLOWED_EVENTS, 'eventType');
        $decodedBody = @json_decode($jsonBody);
        Precondition::isNotEmpty($decodedBody, 'decodedBody');
        $eventClassName = __NAMESPACE__ . "\\" . ucfirst($eventType) . "Event";
        var_dump($decodedBody);
        $event = new $eventClassName($decodedBody);
        if (!method_exists($event, 'handle')) {
            throw new ApiException("method handle doesn't exist for event $eventType");
        }
        return $event->handle();
    }
}