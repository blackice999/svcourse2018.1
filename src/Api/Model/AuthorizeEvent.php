<?php
namespace Course\Api\Model;
use Course\Api\Exceptions\Precondition;
use Course\Services\Authentication\Authentication;
class AuthorizeEvent extends Event {
    private $userModel = null;
    /**
     * AuthorizeEvent constructor.
     * @param string $eventName
     * @param object $data
     * @throws \Course\Api\Exceptions\PreconditionException
     * @throws \Course\Services\Authentication\Exceptions\DecryptException
     */
    public function __construct(string $eventName, object $data)
    {
        parent::__construct($eventName, $data);
        Precondition::propertyExists($data, 'authorizationToken', 'data');
        $this->userModel = Authentication::decryptToken($data->authorizationToken);
    }
}