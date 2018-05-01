<?php

namespace Course\Api\Model;

use Course\Api\Exceptions\Precondition;
use Course\Services\Authentication\Authentication;

class Event
{
    private $userModel = null;
    public $eventName;
    protected $data;

    /**
     * Event constructor.
     * @param string $eventName
     * @param \stdClass $data
     * @throws \Course\Api\Exceptions\PreconditionException
     * @throws \Course\Services\Authentication\Exceptions\DecryptException
     */
    public function __construct(string $eventName, \stdClass $data)
    {
        Precondition::propertyExists($data, 'authorizationToken', 'data');
        $this->userModel = Authentication::decryptToken($data->authorizationToken);
    }

    /**
     * @return UserModel
     */
    protected function getUserModel()
    {
        return $this->userModel;
    }
}