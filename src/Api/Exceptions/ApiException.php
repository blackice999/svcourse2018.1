<?php

namespace Course\Api\Exceptions;

/**
 * Class ApiException
 * Standard exception, the only difference is the class name so we can differentiate
 * exception thrown by the API from other 3rd party code
 * @package Course\Api\Exceptions
 */
class ApiException extends \Exception
{

}