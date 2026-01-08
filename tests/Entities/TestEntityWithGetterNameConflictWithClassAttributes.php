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

namespace LombokTest\Entities;

use Lombok\Getter;
use Lombok\Setter;

#[Getter, Setter]
class TestEntityWithGetterNameConflictWithClassAttributes extends \Lombok\Helper
{
    public const VALUE = 'some value';

    protected string $text = 'property default value';

    public function getText(): string
    {
        return static::VALUE;
    }
}
