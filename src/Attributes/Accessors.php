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

/**
 * Holds all configured accessors for a target object
 */
class Accessors extends \ArrayObject
{
    /**
     * Checks if given method is known/configured already as known accessor to existing property.
     */
    public function isMethodRegistered(string $methodName): bool
    {
        return $this->offsetExists($methodName);
    }

    /**
     * Returns ClassProperty instance if method name is known an refers existing property,
     * or returns NULL otherwise.
     */
    public function get(string $methodName): ?\ReflectionProperty
    {
        /** @var ?\ReflectionProperty $val */
        $val = $this[ $methodName ] ?? null;
        return $val;
    }

    /**
     * Registers accessor method (getter/setter) for specified property.
     *
     * @throws \LogicException if method is already registered.
     */
    public function add(string $methodName, \ReflectionProperty $classProperty): self
    {
        if ($this->isMethodRegistered($methodName)) {
            throw new \LogicException(
                sprintf('Accessor method "%s" is already registered.', $methodName)
            );
        }

        $this[ $methodName ] = $classProperty;
        return $this;
    }

} // end of class
