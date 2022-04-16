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
 * Stores result of attribute scanning from all the classes Lombok is used with.
 */
class ClassConfig
{
    public function __construct()
    {
        $this->settersMap = new Accessors();
        $this->gettersMap = new Accessors();
    }

    /* ****************************************************************************************** */

    protected Accessors $settersMap;

    public function getSettersMap(): Accessors
    {
        return $this->settersMap;
    }

    public function addSetters(Accessors $map): static
    {
        foreach ($map as $methodName => $classProperty) {
            /**
             * @var string              $methodName
             * @var \ReflectionProperty $classProperty
             */
            $this->settersMap->add($methodName, $classProperty);
        }
        return $this;
    }

    public function getSetter(string $methodName): ?\ReflectionProperty
    {
        return $this->getSettersMap()->get($methodName);
    }

    /* ****************************************************************************************** */

    protected Accessors $gettersMap;

    public function getGettersMap(): Accessors
    {
        return $this->gettersMap;
    }

    public function getGetter(string $methodName): ?\ReflectionProperty
    {
        return $this->getGettersMap()->get($methodName);
    }

    public function addGetters(Accessors $map): static
    {
        foreach ($map as $methodName => $classProperty) {
            /**
             * @var string              $methodName
             * @var \ReflectionProperty $classProperty
             */
            $this->gettersMap->add($methodName, $classProperty);
        }
        return $this;
    }

} // end of class
