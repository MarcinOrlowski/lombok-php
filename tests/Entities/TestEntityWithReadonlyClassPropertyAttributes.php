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

namespace LombokTest\Entities;

use Lombok\Getter;
use Lombok\Setter;

readonly class TestEntityWithReadonlyClassPropertyAttributes extends \Lombok\Helper
{
    #[Setter, Getter]
    protected int $number;
}
