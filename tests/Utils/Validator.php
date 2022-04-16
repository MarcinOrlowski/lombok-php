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

namespace LombokTest\Utils;

use Lombok\Attributes\Type;

/**
 * Data validator helper
 */
class Validator
{
    /**
     * Checks if given $classOrObject is either an object or name of existing class.
     */
    public static function assertIsObjectOrExistingClass(string        $varName,
                                                         string|object $classOrObject): void
    {
        static::assertIsType($varName, $classOrObject, [Type::EXISTING_CLASS,
                                                        Type::OBJECT,
        ]);
    }

    /**
     * Checks if $item (of name $key) is of type that is include in $allowed_types (there's `OR` connection
     * between specified types).
     *
     * @param string          $varName      Label or name of the variable to use exception message.
     * @param mixed           $value        Variable to be asserted.
     * @param string|string[] $allowedTypes Array of allowed types for $value, i.e. [Type::INTEGER]
     */
    public static function assertIsType(string       $varName, mixed $value,
                                        string|array $allowedTypes): void
    {
        $allowedTypes = (array)$allowedTypes;

        // Type::EXISTING_CLASS is artificial type, so we need separate logic to handle it.
        $filteredAllowedTypes = $allowedTypes;
        $idx = \array_search(Type::EXISTING_CLASS, $filteredAllowedTypes, true);
        if ($idx !== false) {
            // Remove the type, so gettype() test loop won't see it.
            unset($filteredAllowedTypes[ $idx ]);
            if (\is_string($value) && \class_exists($value)) {
                // It's existing class, no need to test further.
                return;
            }
        }

        $type = \gettype($value);
        if (empty($filteredAllowedTypes)) {
            throw new \RuntimeException("List of allowed types cannot be empty.}");
        }
        if (!\in_array($type, $filteredAllowedTypes, true)) {
            throw static::buildException($varName, $type, $filteredAllowedTypes);
        }
    }

    /**
     * @param string          $varName      Name of the variable (to be included in error message)
     * @param string          $type         Current type of the $value
     * @param string|string[] $allowedTypes Array of allowed types (Type::*) or single element.
     *
     * @throws \RuntimeException
     */
    protected static function buildException(string       $varName, string $type,
                                             string|array $allowedTypes): \RuntimeException
    {
        $allowedTypes = (array)$allowedTypes;
        switch (\count($allowedTypes)) {
            case 0:
                throw new \RuntimeException('allowedTypes array must not be empty.');

            case 1:
                $msg = '"%1$s" must be type(s) of %2$s but %3$s found.';
                break;

            default;
                $msg = '"%1$s" must be one of allowed types: %2$s but %3$s found.';
                break;
        }

        return new \RuntimeException(
            \sprintf($msg, $varName, \implode(', ', $allowedTypes), $type)
        );
    }

    /**
     * Ensures $obj (that is value coming from variable, which name is passed in $varName)
     * is instance of $cls class.
     *
     * @param string $varName Name of variable that the $obj value is coming from. Used for exception message.
     * @param object $obj     Object to check instance of
     * @param string $cls     Target class we want to check $obj agains.
     *
     * @throws \InvalidArgumentException
     */
    public static function assertInstanceOf(string $varName, object $obj, string $cls): void
    {
        if (!($obj instanceof $cls)) {
            throw new \InvalidArgumentException(
                \sprintf('"%s" must be instance of "%s".', $varName, $cls)
            );
        }
    }

    /* ****************************************************************************************** */

} // end of class
