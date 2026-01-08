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

namespace Lombok\Exceptions;

final class MethodAlreadyExistsException extends \Exception
{
    public function __construct(string|object $objectOrCls, string $methodName, int $code = 0,
                                ?\Throwable   $previous = null)
    {
        $clsName = \is_object($objectOrCls)
            ? \get_class($objectOrCls)
            : $objectOrCls;

        $message = \sprintf('Method already exists: %1$s::%2$s()', $clsName, $methodName);
        parent::__construct($message, $code, $previous);
    }

} // end of class
