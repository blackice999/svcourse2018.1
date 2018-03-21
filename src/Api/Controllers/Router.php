<?php

namespace Course\Api\Controllers;

use Course\Services\Http\Exceptions\HttpException;
use Course\Services\Http\HttpConstants;

class Router
{
    private $class = '';
    private $method = '';
    private $classPath = '';

    const METHOD_MAPPING = [
        HttpConstants::METHOD_GET => 'get',
        HttpConstants::METHOD_POST => 'create',
        HttpConstants::METHOD_PUT => 'update',
        HttpConstants::METHOD_DELETE => 'delete',
    ];

    public function __construct(string $path, string $method)
    {
        if (!in_array($method, array_keys(self::METHOD_MAPPING))) {
            throw new HttpException('Method Now Allowed', HttpConstants::STATUS_CODE_METHOD_NOT_ALLOWED);
        }

        // Split the string by backslashes
        $words = explode('/', rtrim($path, '/'));
        $this->class = __NAMESPACE__ . '\\';
        // Create the controller name using the words created by splitting the path
        // The controller name is camelCase so we'll call ucfirst on each word
        foreach ($words as $word) {
            $this->class .= ucfirst($word);
        }

        $this->class .= 'Controller';
        $this->classPath = $this->class . '.php';

        $this->method = self::METHOD_MAPPING[$method];
    }

    public function processRequest()
    {
        if (!method_exists($this->class, $this->method)) {
            throw new HttpException('Method Now Allowed', HttpConstants::STATUS_CODE_METHOD_NOT_ALLOWED);
        }
        $controller = new $this->class;
        return $controller->{$this->method}();
    }
}