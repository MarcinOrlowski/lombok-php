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

use Lombok\Exceptions\ReadonlyPropertyException;
use LombokTest\Entities\ReadOnlyClassWithPropertyLevelAttributes;
use LombokTest\Entities\TestEntityWithLevelAttributesProperties;
use LombokTest\TestCase;

class ReadonlyClassTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        if (version_compare(PHP_VERSION, '8.2', '<')) {
            $this->markTestSkipped('Readonly class support requires PHP 8.2+');
        }
    }

    /**
     * Checks if having "readonly" class, with property level attributes, will throw exception
     * when trying to instantiate it with "Setter" annotation.
     */
    public function testReadonlyClass(): void
    {
        $this->expectException(ReadonlyPropertyException::class);
        new ReadOnlyClassWithPropertyLevelAttributes();
    }

    /**
     * Ensures readonly" class and attributes at class level can be instantiated
     * correctly, yet all the "setters" for "readonly" should not be generated.
     */
    public function testReadonlyClassLevel(): void
    {
        $obj = new TestEntityWithLevelAttributesProperties();

        $this->expectException(\BadMethodCallException::class);
        $obj->setNumber(123);
    }

}
