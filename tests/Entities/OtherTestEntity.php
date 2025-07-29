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

class OtherTestEntity extends \Lombok\Helper
{
    #[Setter, Getter]
    protected int $number = 0;

    #[Setter, Getter]
    protected ?int $numberOrNull = 0;

    #[Getter]
    protected string $justGetter = 'foo';

    #[Setter, Getter]
    protected string $text = '';

    #[Setter, Getter]
    protected ?string $nullable = 'test';

    #[Setter, Getter]
    protected \DateTime $stamp;

    #[Setter, Getter]
    /**
     * @var mixed
     * @phpstan-ignore-next-line
     * */
    protected $mixed;

    #[Setter, Getter]
    protected string|int $union;

}
