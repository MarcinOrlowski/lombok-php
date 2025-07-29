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

use LombokTest\Entities\TestEntity;
use LombokTest\TestCase;

class TestConstructDestruct extends TestCase
{
    public function testConstructDestruct(): void
    {
        $obj = new TestEntity();
        $val = $this->getRandomString();
        $obj->setText($val);
        $this->assertEquals($val, $obj->getText());

        $obj = new TestEntity();
        $this->assertNotEquals($val, $obj->getText());
    }

}
