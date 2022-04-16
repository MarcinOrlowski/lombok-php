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

namespace Lombok\Attributes;

use Lombok\Attributes\Contracts\AccessorContract;

abstract class BaseAttribute implements AccessorContract
{
    /**
     * Converts provided string to CamelCase notation. For now it only
     * understands snake_case.
     */
    protected function toCamelCase(string $str): string
    {
        return \implode('', \array_map(static function(string $item): string {
            return \ucfirst($item);
        }, \explode('_', $str)));
    }

    /**
     * Checks if given $newValue item is of the type property typehints allow.
     */
    public function isType(\ReflectionProperty $property, string $requiredType): bool
    {
        $typeOrUnion = $property->getType();

        // No type hint.
        if ($typeOrUnion === null) {
            return true;
        }

        if ($typeOrUnion instanceof \ReflectionNamedType) {
            return $typeOrUnion->getName() === $requiredType;
        } elseif ($typeOrUnion instanceof \ReflectionUnionType) {
            foreach ($typeOrUnion->getTypes() as $type) {
                /** @var \ReflectionNamedType $type */
                if ($type->getName() === $requiredType) {
                    return true;
                }
            }
        }
        return false;
    }

} // end of class
