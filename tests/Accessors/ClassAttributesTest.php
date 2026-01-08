<?php
declare(strict_types=1);

/**
 * Lombok PHP - Write less code!
 *
 * @author    Marcin Orlowski <mail (#) marcinOrlowski (.) com>
 * @copyright ©2022-2026 Marcin Orlowski
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL-3.0
 * @link      https://github.com/MarcinOrlowski/lombok-php
 */

namespace LombokTest\Accessors;

use LombokTest\Entities\TestEntityWithClassAttributes;
use LombokTest\Entities\TestEntityWithClassAttributesAndPublicStaticProperties;
use LombokTest\TestCase;

class ClassAttributesTest extends TestCase
{
    public function testNumber(): void
    {
        $obj = new TestEntityWithClassAttributes();

        $val = \random_int(0, 1000);
        $obj->setNumber($val);
        $this->assertEquals($val, $obj->getNumber());
    }

    /**
     * Ensures that for property attribute prevents class attributes from
     * being applied to already configured property.
     */
    public function testClassGetSetPropGetter(): void
    {
        $obj = new TestEntityWithClassAttributes();

        $obj->getGetterOnlyProperty();
        $this->expectException(\BadMethodCallException::class);
        $obj->setGetterOnlyProperty('123');
    }

    /**
     * Ensures that instantiating class with class level annotated accessors
     * will not throw any exception for non-supported properties (i.e. public or static)
     * and there will be no accessor method generated for these properties.
     */
    public function testClassAttrSkipPublicAndStaticProperties(): void
    {
        $obj = new TestEntityWithClassAttributesAndPublicStaticProperties();

        $this->expectException(\BadMethodCallException::class);
        $obj->getThisShouldBeIgnored();
    }

}
