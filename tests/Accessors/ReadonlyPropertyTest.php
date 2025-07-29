<?php
declare(strict_types=1);

/**
 * Lombok PHP - Write less code!
 *
 * @author    Marcin Orlowski <mail (#) marcinOrlowski (.) com>
 * @copyright ©2022-2025 Marcin Orlowski
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL-3.0
 * @link      https://github.com/MarcinOrlowski/lombok-php
 */

namespace LombokTest\Accessors;

use Lombok\Exceptions\ReadonlyPropertyException;
use LombokTest\Entities\TestEntityWithLevelAttributesProperties;
use LombokTest\Entities\TestEntityWithReadonlyPropertiesAndAttributes;
use LombokTest\TestCase;

class ReadonlyPropertyTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        if (version_compare(PHP_VERSION, '8.1', '<')) {
            $this->markTestSkipped('Requires PHP 8.1 or higher');
        }
    }

    /**
     * Ensures instantiation of class with readonly properties will throw ReadonlyPropertyException.
     */
    public function testReadonly(): void
    {
        $this->expectException(ReadonlyPropertyException::class);
        new TestEntityWithReadonlyPropertiesAndAttributes();
    }

    /**
     * Ensures class with "readonly" property and attributes at class level can be instantiated
     * correctly, yet all the "setters" for "readonly" should not be generated.
     */
    public function testReadonlyProperty(): void
    {
        $obj = new TestEntityWithLevelAttributesProperties();

        $this->expectException(\BadMethodCallException::class);
        $obj->setNumber(123);
    }

}
