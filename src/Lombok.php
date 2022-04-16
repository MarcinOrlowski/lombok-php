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

namespace Lombok;

use Lombok\Attributes\Accessors;
use Lombok\Attributes\ClassConfig;
use Lombok\Attributes\Contracts\AccessorContract;
use Lombok\Exceptions\PublicPropertyException;
use Lombok\Exceptions\StaticPropertyException;

final class Lombok
{
    /**
     * @var array[int => ClassConfig]
     */
    protected static array $config = [];

    /**
     * Names of configured properties in given class. Used to prevent applying class level
     * attributes to properties that are already configured on property level.
     *
     * @var array[int => string[]]
     */
    protected static array $configuredProperties = [];

    /* ****************************************************************************************** */

    /**
     * @return mixed|void
     *
     * @throws \Lombok\Exceptions\PublicPropertyException
     * @throws \Lombok\Exceptions\StaticPropertyException
     * @throws \BadMethodCallException
     */
    public static function call(object $targetObj, string $methodName, array $args)
    {
        static::construct($targetObj);

        $id = \spl_object_id($targetObj);
        $objectConfig = static::$config[ $id ] ?? null;
        if ($objectConfig === null) {
            return;
        }

        // Check getters first as these are usually more often used
        /** @var ClassConfig $objectConfig */
        $getter = $objectConfig->getGetter($methodName);
        if ($getter !== null) {
            return $getter->getValue($targetObj);
        }

        $item = $objectConfig->getSetter($methodName);
        if ($item !== null) {
            $item->setValue($targetObj, $args[0]);
            return $targetObj;
        }

        $exMsg = \sprintf('Method not found: %1$s::%2$s()', \get_class($targetObj), $methodName);
        throw new \BadMethodCallException($exMsg);
    }

    /* ****************************************************************************************** */

    /**
     * Configures Lombok for given object.
     */
    public static function construct(object $targetObj): void
    {
        $id = \spl_object_id($targetObj);
        if (\array_key_exists($id, static::$config)) {
            return;
        }

        try {
            // Get all the properties of the class.
            $reflection = new \ReflectionClass($targetObj);

            $config = new ClassConfig();

            // Look for class attributes first
            $clsAttrs = [];
            foreach ($reflection->getAttributes() as $singleClassAttribute) {
                $clsAttrs[ $singleClassAttribute->getName() ] = $singleClassAttribute;
            }

            // Look for property attributes now
            foreach ($reflection->getProperties() as $singlePropertyAttribute) {
                $property = $reflection->getProperty($singlePropertyAttribute->getName());

                // And then see if Getter or Setter attribute is applied to it.
                $getters = static::setupPropertyAccessors($property, Getter::class, []);
                $appliedLocalGetters = $getters->count() > 0;
                if (!$appliedLocalGetters && \array_key_exists(Getter::class, $clsAttrs)) {
                    $getters = static::setupPropertyAccessors($property, Getter::class, $clsAttrs);
                }
                $config->addGetters($getters);
                // Do not apply class level attributes if we configured any accessor already
                // based on property attribute and not class level annotation.
                // That let's us to set subset of class attributes to a differently annotated
                // property while still apply all the class attributes to remaining properties.
                $setterClsAnnotations = $appliedLocalGetters ? [] : $clsAttrs;
                $setters = static::setupPropertyAccessors($property, Setter::class,
                    $setterClsAnnotations);
                $config->addSetters($setters);
            }

            static::$config[ $id ] = $config;

        } catch (\ReflectionException $ex) {
            throw new \RuntimeException($ex->getMessage(), $ex->getCode(), $ex);
        }
    }

    /**
     * Removes Lombok configuration for specified object. Must be called from object's
     * destructor as \spl_object_id() is documented to reuse IDs.
     */
    public static function destruct(object $targetObj): void
    {
        if (static::$config !== null) {
            $id = \spl_object_id($targetObj);
            unset(static::$config[ $id ]);
        }
    }

    /* ****************************************************************************************** */

    /**
     * Scans $classProperty property's attributes and configures necessary handlers
     * for supported attributes (like #[Setter] or #[Getter], etc.)
     *
     * @param \ReflectionProperty    $property  Property to be inspected.
     * @param string                 $attrClass Attribute class to look for i.e. Getter::class.
     * @param \ReflectionAttribute[] $clsAnnotations
     *
     * @throws \Lombok\Exceptions\PublicPropertyException
     * @throws \Lombok\Exceptions\StaticPropertyException
     */
    protected static function setupPropertyAccessors(
        \ReflectionProperty $property, string $attrClass, array $clsAnnotations = []): Accessors
    {
        $clsName = $property->getDeclaringClass()->getName();
        $propName = $property->getName();

        $map = new Accessors();

        // Get all attributes of required $attrClass only (assuming any is set for given property)
        $propAttrs = $property->getAttributes($attrClass);

        if (empty($propAttrs)) {
            // For class level attributes we silently skip not supported property types
            // instead of throwing an exception.
            if (!($property->isStatic() || $property->isPublic())) {
                // The property does not have any attributes we look for. Let's check if we
                // one we are looking for set on class level and then apply it to the property.
                $clsAttr = $clsAnnotations[ $attrClass ] ?? null;
                if ($clsAttr !== null) {
                    /** @var AccessorContract $propAttrInstance */
                    $propAttrInstance = $clsAttr->newInstance();
                    $functionName = $propAttrInstance->getFunctionName($property);

                    // Map created virtual accessor function to the target property.
                    $map->add($functionName, $property);
                }
            }
        } else {
            // Static properties are not supported
            if ($property->isStatic()) {
                throw new StaticPropertyException($clsName, $propName);
            }

            // Assert target property is not "public" as having accessors for public properties
            // is not supported as it simply makes no much sense.
            if ($property->isPublic()) {
                throw new PublicPropertyException($clsName, $propName);
            }

            foreach ($propAttrs as $propAttr) {
                /**
                 * @var \ReflectionAttribute $propAttr
                 *
                 * Lombok's attribute accessor MUST implement AccessorContract.
                 * There's no check for this as of now simply for performance reasons.
                 * @var AccessorContract     $propAttrInstance
                 */
                $propAttrInstance = $propAttr->newInstance();
                $functionName = $propAttrInstance->getFunctionName($property);

                // Map created virtual accessor function to the target property.
                $map->add($functionName, $property);
            }
        }

        return $map;
    }

} // end of class
