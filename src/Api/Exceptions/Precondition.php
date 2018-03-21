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
    public static function isTrue(bool $condition, string $message)
    {
        if ($condition !== true) {
            throw new PreconditionException($message);
        }
    }

    public static function lengthIsBetween($string, $minLength, $maxLength, $variableName)
    {
        $length = strlen($string);
        if (!($length >= $minLength && $length <= $maxLength)) {
            throw new PreconditionException($variableName . ' should be between ' . $minLength . ' and ' . $maxLength . ' characters');
        }
    }

    public static function isPositiveInteger($value, string $variableName)
    {
        if (!is_numeric($value) || $value <= 0) {
            throw new PreconditionException(
                $variableName . ' is not positive integer');
        }
    }

    public static function isInArray($value, array $valueArray, string $variableName)
    {
        if (!in_array($value, $valueArray)) {
            throw new PreconditionException(
                $variableName . ' is not in array[' . implode(', ', $valueArray) . ']');
        }
    }
}