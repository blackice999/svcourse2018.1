<?php

namespace Course\Api\Exceptions;


class Precondition
{
    /**
     * Throws an exception if the given condition fails
     *
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
     * Checks if a string length is between $minLength and $maxLength and throws an exception if not
     *
     * @param string $string - The string we want to evaluate
     * @param int $minLength - The minimum string length allowed
     * @param int $maxLength - The maximum string length allowed
     * @param string $variableName - The name of the variable we're evaluating
     * @throws PreconditionException
     */
    public static function lengthIsBetween(string $string, int $minLength, int $maxLength, string $variableName)
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
            throw new PreconditionException("$variableName is not positive integer");
        }
    }

    /**
     * Checks if a given value is in an given array
     *
     * @param mixed $value - value that we'll check if it's in the array
     * @param array $valueArray - the array in which we'll search for $value
     * @param string $variableName - the variable name we're evaluating
     * @throws PreconditionException
     */
    public static function isInArray($value, array $valueArray, string $variableName)
    {
        if (!in_array($value, $valueArray)) {
            // Generated a comma separated values string from an array
            $arrayAsString = implode(', ', $valueArray);
            throw new PreconditionException("$variableName is not in array[$arrayAsString]");
        }
    }

    /**
     * Checks if a given value is in an given array
     *
     * @param \stdClass $object
     * @param string $property
     * @param string $variableName - the variable name we're evaluating
     * @throws PreconditionException
     */
    public static function propertyExists(\stdClass $object, string $property, string $variableName)
    {
        if (!property_exists($object, $property)) {
            throw new PreconditionException("$variableName does not have property [$property]");
        }
    }
}