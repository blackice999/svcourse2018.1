<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 3/11/2017
 * Time: 5:03 PM
 */

namespace Course\Api\Exceptions;


class Precondition
{
    /**
     * @param bool $condition
     * @param string $message
     * @throws PreconditionException
     */
    public static function isTrue(bool $condition, string $message)
    {
        if ($condition !== true) {
            throw new PreconditionException($message);
        }
    }

    /**
     * Throws an exception if the given value is empty
     *
     * @param mixed $value - the value we're evaluating if it's empty or not
     * @param string $variableName - the variable name we're evaluating
     * @throws PreconditionException
     */
    public static function isNotEmpty($value, string $variableName)
    {
        if (empty($value)) {
            throw new PreconditionException("$variableName is empty");
        }
    }

    /**
     * @param $string
     * @param $minLength
     * @param $maxLength
     * @param $variableName
     * @throws PreconditionException
     */
    public static function lengthIsBetween($string, $minLength, $maxLength, $variableName)
    {
        $length = strlen($string);
        if (!($length >= $minLength && $length <= $maxLength)) {
            throw new PreconditionException("$variableName should be between $minLength and $maxLength characters");
        }
    }

    /**
     * @param $value
     * @param string $variableName
     * @throws PreconditionException
     */
    public static function isPositiveInteger($value, string $variableName)
    {
        if (!is_numeric($value) || $value <= 0) {
            throw new PreconditionException(
                $variableName . ' is not positive integer');
        }
    }

    /**
     * @param $value
     * @param array $valueArray
     * @param string $variableName
     * @throws PreconditionException
     */
    public static function isInArray($value, array $valueArray, string $variableName)
    {
        if (!in_array($value, $valueArray)) {
            throw new PreconditionException(
                $variableName . ' is not in array[' . implode(', ', $valueArray) . ']');
        }
    }
}