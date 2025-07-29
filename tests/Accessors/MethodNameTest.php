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

use Lombok\Exceptions\MethodAlreadyExistsException;
use LombokTest\Entities\TestEntity;
use LombokTest\Entities\TestEntityWithGetterNameConflict;
use LombokTest\Entities\TestEntityWithGetterNameConflictWithClassAttributes;
use LombokTest\Entities\TestEntityWithSetterNameConflict;
use LombokTest\Entities\TestEntityWithSetterNameConflictWithClassAttributes;
use LombokTest\TestCase;

class MethodNameTest extends TestCase
{
    /**
     * Ensures accessor name is SnakeCase formatter regardless of the property name.
     */
    public function testSnakeCase(): void
    {
        $obj = new TestEntity();
        $val = $this->getRandomString();
        $obj->setSnakeCase($val);
        $this->assertEquals($val, $obj->getSnakeCase());
    }

    /**
     * Ensures exception is thrown if provided setter method name would
     * conflict with class existing method.
     */
    public function testSetterMethodNameConflict(): void
    {
        $this->expectException(MethodAlreadyExistsException::class);
        new TestEntityWithSetterNameConflict();
    }

    /**
     * Ensures exception is thrown if provided getter method name would
     * conflict with class existing method.
     */
    public function testGetterMethodNameConflict(): void
    {
        $this->expectException(MethodAlreadyExistsException::class);
        new TestEntityWithGetterNameConflict();
    }

    /**
     * Ensures exception are NOT thrown if provided setter method name, generated
     * based on class level attribute would conflict with class existing method.
     * In such case we ignore the conflict and not provide our implementation.
     */
    public function testSetterMethodNameConflictClassLevel(): void
    {
        $obj = new TestEntityWithSetterNameConflictWithClassAttributes();
        $val = $this->getRandomString();
        $obj->setText($val);
        $this->assertNotEquals($val, $obj->getText());
        $this->assertEquals(\get_class($obj)::VALUE, $obj->getText());
    }

    /**
     * Ensures exception are NOT thrown if provided getter method name, generated
     * based on class level attribute would conflict with class existing method.
     * In such case we ignore the conflict and not provide our implementation.
     */
    public function testGetterMethodNameConflictClassLevel(): void
    {
        $obj = new TestEntityWithGetterNameConflictWithClassAttributes();
        $obj->setText($this->getRandomString());
        $this->assertEquals(\get_class($obj)::VALUE, $obj->getText());
    }

}
