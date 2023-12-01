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

namespace Lombok\Exceptions;

final class ReadonlyPropertyException extends \Exception
{
    public function __construct(string      $cls, string $propertyName, int $code = 0,
                                ?\Throwable $previous = null)
    {
        $message = \sprintf('Read-only properties cannot have setter: %1$s::%2$s', $cls, $propertyName);
        parent::__construct($message, $code, $previous);
    }

} // end of class
