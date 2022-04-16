<?php
declare(strict_types=1);

/**
 * Lombok PHP - Write less code!
 *
 * @author    Marcin Orlowski <mail (#) marcinOrlowski (.) com>
 * @copyright 2022 Marcin Orlowski
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL-3.0
 * @link      https://github.com/MarcinOrlowski/lombok-php
 */

namespace LombokTest;

use LombokTest\Utils\Validator;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * Generates random string, with optional prefix
     *
     * @param string|null $prefix    Optional prefix to be added to generated string.
     * @param int         $length    Length of the string to be generated.
     * @param string      $separator Optional prefix separator.
     */
    protected function getRandomString(?string $prefix = null, int $length = 24,
                                       string  $separator = '_'): string
    {
        if ($length < 1) {
            throw new \RuntimeException('Length must be greater than 0');
        }
        if ($prefix !== null) {
            $margin = 3;
            if ($length < (\strlen($prefix) + $margin)) {
                $msg = "String length cannot be smaller than prefix length + {$margin} chars";
                throw new \RuntimeException($msg);
            }
            $prefix .= $separator;
        }

        return \substr(($prefix ?? '') . \md5(\uniqid('', true)), 0, $length);
    }

    /**
     * Generates random string, with optional prefix
     *
     * @param string|null $prefix      Optional prefix to be added to generated string.
     * @param int         $length      Length of the string to be generated.
     * @param string      $separator   Optional prefix separator.
     * @param float       $probability Probability (float value in range 0-1) specifying of string
     *                                 being returned in the drawing. Drawings are done with three
     *                                 digits precision. Default value is 0.5 (50%).
     *
     * @throws \Exception
     */
    protected function getRandomStringOrNull(?string $prefix = null, int $length = 24,
                                             string  $separator = '_',
                                             float   $probability = 0.5): ?string
    {
        /** @var float $rand */
        $rand = \random_int(0, 999) / 1000;
        if ($rand >= $probability) {
            return $this->getRandomString($prefix, $length, $separator);
        }
        return null;
    }

    /**
     * Generate Random float value
     *
     * @param float $min    Lowest allowed value.
     * @param float $max    Highest allowed value.
     * @param int   $digits The optional number of decimal digits to round to.
     *                      Default 0 means not rounding.
     *
     * @return float
     */
    protected static function getRandomFloat(float $min, float $max, int $digits = 0): float
    {
        $result = $min + \mt_rand() / \mt_getrandmax() * ($max - $min);
        if ($digits > 0) {
            $result = \round($result, $digits);
        }

        return $result;
    }

    /**
     * Generates random integer value from withing specified range.
     *
     * @param int $min Min allowed value (inclusive)
     * @param int $max Max allowed value (inclusive)
     *
     * @return int
     *
     * @throws \Exception
     */
    protected function getRandomInt(int $min = 0, int $max = 100): int
    {
        return \random_int($min, $max);
    }

    /**
     * Draws random boolean value.
     *
     * @param float $probability
     */
    protected function getRandomBool(float $probability = 0.5): bool
    {
        $rand = \random_int(0, 999) / 1000;
        return $rand > $probability;
    }

    /* ****************************************************************************************** */

    /**
     * Calls protected method of $object, passing optional array of arguments.
     *
     * @param object|string $objectOrClass Object to call $methodName on or name of the class.
     * @param string        $methodName    Name of method to call.
     * @param array         $args          Optional array of arguments (empty array for no args).
     *
     * @return mixed
     *
     * @throws \ReflectionException
     * @throws \RuntimeException
     */
    protected function callProtectedMethod(string|object $objectOrClass, string $methodName,
                                           array         $args = []): mixed
    {
        Validator::assertIsObjectOrExistingClass('objectOrClass', $objectOrClass);

        /**
         * At this point $objectOrClass is either object or string but some static analyzers
         * got problems figuring that out, so this (partially correct) var declaration is
         * to make them believe.
         *
         * @var class-string|object $objectOrClass
         */
        $reflection = new \ReflectionClass($objectOrClass);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs(\is_object($objectOrClass) ? $objectOrClass : null, $args);
    }

    /**
     * Returns value of otherwise non-public property of the class
     *
     * @param string|object $objectOrClass Class name to get property from, or instance of that class
     * @param string        $name          Property name to grab (i.e. `maxLength`)
     *
     * @return mixed
     *
     * @throws \ReflectionException
     */
    protected function getProtectedProperty(string|object $objectOrClass, string $name): mixed
    {
        Validator::assertIsObjectOrExistingClass('objectOrClass', $objectOrClass);

        /**
         * At this point $objectOrClass is either object or string but some static analyzers
         * got problems figuring that out, so this (partially correct) var declaration is
         * to make them believe.
         *
         * @var class-string|object $objectOrClass
         */
        $reflection = new \ReflectionClass($objectOrClass);
        $property = $reflection->getProperty($name);
        $property->setAccessible(true);

        return $property->getValue(\is_object($objectOrClass) ? $objectOrClass : null);
    }

    /**
     * Returns value of otherwise non-public member of the class
     *
     * @param string|object $objectOrClass Class name to get member from, or instance of that class
     * @param string        $name          Name of constant to grab (i.e. `FOO`)
     *
     * @return mixed
     */
    protected function getProtectedConstant(string|object $objectOrClass, string $name): mixed
    {
        Validator::assertIsObjectOrExistingClass('objectOrClass', $objectOrClass);

        /**
         * At this point $obj_or_cls is either object or string but some static analyzers
         * got problems figuring that out, so this (partially correct) var declaration is
         * to make them believe.
         *
         * @var class-string|object $objectOrClass
         */
        return (new \ReflectionClass($objectOrClass))->getConstant($name);
    }
}
