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

use Lombok\Exceptions\PublicPropertyException;
use Lombok\Exceptions\StaticPropertyException;
use LombokTest\Entities\TestEntity;
use LombokTest\Entities\TestEntityWithPublicProperties;
use LombokTest\Entities\TestEntityWithStaticProperties;
use LombokTest\TestCase;

class AccessorsTest extends TestCase
{
    /**
     * Ensures that calling non-existing method will throw \BadMethodCallException.
     */
    public function testUnknownAccessor(): void
    {
        $obj = new TestEntity();

        $this->expectException(\BadMethodCallException::class);
        $obj->doesNotExist();
    }

    /**
     * Tests provided accessors.
     */
    public function testNumber(): void
    {
        $obj = new TestEntity();

        $val = \random_int(0, 1000);
        $obj->setNumber($val);
        $this->assertEquals($val, $obj->getNumber());
    }

    /**
     * Tests if provided setter fail when provided value is of incorrect type.
     */
    public function testNumberInvalidValueType(): void
    {
        $obj = new TestEntity();

        $val = 'invalid';
        $this->expectException(\TypeError::class);
        $obj->setNumber($val);
    }

    /**
     * Tests accessors for nullable property.
     */
    public function testNullableNumber(): void
    {
        $obj = new TestEntity();

        $val = \random_int(0, 1000);
        $obj->setNumberOrNull($val);
        $this->assertEquals($val, $obj->getNumberOrNull());

        $obj->setNumberOrNull(null);
        $this->assertNull($obj->getNumberOrNull());
    }

    /**
     * Tests handling of properties with union typehint
     */
    public function testUnion(): void
    {
        $obj = new TestEntity();

        $val = \random_int(0, 1000);
        $obj->setUnion($val);
        $this->assertEquals($val, $obj->getUnion());

        $val = 'string';
        $obj->setUnion($val);
        $this->assertEquals($val, $obj->getUnion());
    }

    /**
     * Tests if default getter for boolean type property is prefixed with "is".
     */
    public function testBooleanGetter(): void
    {
        $obj = new TestEntity();
        $val = true;
        $obj->setBoolean($val);
        $this->assertEquals($val, $obj->isBoolean());
    }

    /**
     * Tests if default getter for property with union typehint that includes
     * boolean type is NOT prefixed with "is".
     */
    public function testBooleanGetterUnion(): void
    {
        $obj = new TestEntity();
        $val = true;
        $obj->setBooleanOrString($val);
        $this->assertEquals($val, $obj->getBooleanOrString());
    }

    /**
     * Ensures exception is thrown when accessor is applied to static property.
     */
    public function testStaticPropertiesAreNotSupported(): void
    {
        $this->expectException(StaticPropertyException::class);
        new TestEntityWithStaticProperties();
    }

    /**
     * Ensures exception is thrown when accessor is applied to public property.
     */
    public function testPublicProperty(): void
    {
        $this->expectException(PublicPropertyException::class);
        new TestEntityWithPublicProperties();
    }

    public function testExplicitMixedTypehint(): void
    {
        $obj = new TestEntity();
        $val = true;
        $obj->setMixed($val);
        $this->assertEquals($val, $obj->getMixed());
        $obj->setMixed($val);
        $this->assertEquals($val, $obj->getMixed());
        $obj->setMixed($val);
        $this->assertEquals($val, $obj->getMixed());
    }

}
