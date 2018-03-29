<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 29.03.2018
 * Time: 12:48
 */

namespace Course\Services\Utils\Exceptions;


use Course\Api\Exceptions\ApiException;

class MethodNotValid extends ApiException
{

    /**
     * MethodNotValid constructor.
     * @param string $string
     */
    public function __construct($string)
    {
    }
}