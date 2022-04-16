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

namespace LombokTest\Accessors;

use LombokTest\Entities\OtherTestEntity;
use LombokTest\Entities\TestEntity;
use LombokTest\TestCase;

class MultipleEntitiesTest extends TestCase
{
    /**
     * Ensures Lombok handles multiple objects
     * correctly.
     */
    public function testMultipleEntities(): void
    {
        $objA = new TestEntity();
        $objB = new OtherTestEntity();

        $valA = $this->getRandomString();
        $objA->setText($valA);

        $this->assertEquals($valA, $objA->getText());
        $this->assertNotEquals($valA, $objB->getText());

        $valB = $this->getRandomString();
        $objB->setText($valB);

        $this->assertEquals($valB, $objB->getText());
    }

    /**
     * Ensures multiple instances of the same class are
     * handled correctly.
     */
    public function testMultipleInstances(): void
    {
        $objA = new TestEntity();
        $objB = new TestEntity();

        $valA = $this->getRandomString();
        $objA->setText($valA);

        $valB = $this->getRandomString();
        $objB->setText($valB);

        $this->assertEquals($valA, $objA->getText());
        $this->assertEquals($valB, $objB->getText());
    }

    /**
     * Ensures cloned object of class using Lombok is
     * also handled correctly and also acts as original.
     */
    public function testObjectCloning(): void
    {
        $objA = new TestEntity();

        $valA = $this->getRandomString();
        $objA->setText($valA);

        $objB = clone $objA;

        $this->assertEquals($valA, $objA->getText());
        $this->assertEquals($valA, $objB->getText());
    }
}
