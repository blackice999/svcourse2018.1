<?php

namespace Course\Api\Model;


use Course\Services\Socket\Response;

class JoinRoomEvent extends Event
{

    const name = "room_1";

    /**
     * StartGameEvent constructor.
     * @param object $data
     * @throws \Course\Api\Exceptions\PreconditionException
     * @throws \Course\Services\Authentication\Exceptions\DecryptException
     */
    public function __construct(object $data)
    {
        parent::__construct(Events::JOIN_ROOM, $data);
    }

    /**
     * @throws \Course\Services\Persistence\Exceptions\ConnectionException
     * @throws \Course\Services\Persistence\Exceptions\NoResultsException
     * @throws \Course\Services\Persistence\Exceptions\QueryException
     */
    public function handle()
    {
        $userId = $this->getUserModel()->id;

        if (!RoomModel::roomExists(JoinRoomEvent::name)) {
            $roomModel = RoomModel::create(JoinRoomEvent::name);
        } else {
            $roomModel = RoomModel::getByRoomName(JoinRoomEvent::name);
        }

        RoomUsersModel::create($roomModel->id, $userId);

        return Response::getResponse(
            ResponseEvents::JOIN_ROOM,
            '{"roomId":' . $roomModel->id . '}'
        );

        if (RoomUsersModel::areThereAreEnoughUsers($roomModel->id)) {
            return Response::getResponse(
                ResponseEvents::START_GAME,
                '{"ok":true}'
            );
        }
    }
}
