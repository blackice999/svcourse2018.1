<?php

namespace Course\Api\Controllers;

use Course\Services\Http\Exceptions\HttpException;
use Course\Services\Http\HttpConstants;

/**
 * Contains the logic to route the request to the proper controller and handler function
 * Class Router
 * @package Course\Api\Controllers
 */
class Router
{
    /**
     * @var string
     * Class name that handles a specific request url
     * e.g. /user/login => Course\Api\Controllers\UserLoginController
     */
    private $controllerClassName = '';
    /**
     * @var mixed|string
     * Controller method that handles a specific HTTP method
     * e.g. /user/login POST => Course\Api\Controllers\UserLoginController::create
     */
    private $controllerMethod = '';

    /** Map of HTTP methods to controller methods */
    const METHOD_MAPPING = [
        HttpConstants::METHOD_GET => 'get',
        HttpConstants::METHOD_POST => 'create',
        HttpConstants::METHOD_PUT => 'update',
        HttpConstants::METHOD_DELETE => 'delete',
    ];

    /**
     * Router constructor.
     * Extracts the controller and the method from the request URL
     * @param string $requestPath - request URL path (e.g. if the request link is: localhost/users/login $path will be users/login)
     * @param string $httpMethod - http method of the request
     * @throws HttpException
     */
    public function __construct(string $requestPath, string $httpMethod)
    {
        // We don't want to allow any other HTTP methods than the ones defined in METHOD_MAPPING
        if (!in_array($httpMethod, array_keys(self::METHOD_MAPPING))) {
            throw new HttpException('Method Now Allowed', HttpConstants::STATUS_CODE_METHOD_NOT_ALLOWED);
        }

        // Split the string by backslashes
        $words = explode('/', rtrim($requestPath, '/'));
        // Prepend the current namespace (Course\Api\Controllers)
        $this->controllerClassName = __NAMESPACE__ . '\\';
        // Create the controller name using the words created by splitting the path
        // The controller name is camelCase so we'll call ucfirst on each word
        foreach ($words as $word) {
            $this->controllerClassName .= ucfirst($word);
        }

        // Append the Controller suffix
        $this->controllerClassName .= 'Controller';

        // Find the controller method using the HTTP method
        $this->controllerMethod = self::METHOD_MAPPING[$httpMethod];
    }

    /**
     * Call the handler function of the request
     * @return mixed
     * @throws HttpException
     */
    public function processRequest()
    {
        // If the given request path doesn't have an controller it means this is not a valid route
        if (!class_exists($this->controllerClassName)) {
            throw new HttpException('Not Found', HttpConstants::STATUS_CODE_NOT_FOUND);
        }
        // If the given request method doesn't have a controller method defined then we won't allow it
        if (!method_exists($this->controllerClassName, $this->controllerMethod)) {
            throw new HttpException('Method Now Allowed', HttpConstants::STATUS_CODE_METHOD_NOT_ALLOWED);
        }

        // Instantiate the controller, call the method and return it's return value
        $controller = new $this->controllerClassName;
        return $controller->{$this->controllerMethod}();
    }
}