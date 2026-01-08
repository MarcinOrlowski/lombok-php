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

namespace Lombok;

/**
 * Helper class that wires Lombok internals for the current **READONLY** class.
 *
 * Use this class when adding Lombok to "readonly" annotated class.
 */
abstract readonly class HelperReadonly
{
    /**
     * Configures Lombok for $this instance object.
     */
    public function __construct()
    {
        Lombok::construct($this);
    }

    /**
     * Removes Lombok's internal data related to $this instance object.
     *
     * NOTE: this step is MANDATORY or bad things will happen!
     */
    public function __destruct()
    {
        Lombok::destruct($this);
    }

    /**
     * Handles Lombok provided methods or throws \BadMethodCallException if method
     * name is not matching any of the methods provided.
     *
     * NOTE: because Lombok will try to automatically configure itself for objects
     * that are not configured at the moment of __call() invocation (i.e. cloned objects)
     * standard configuration phase exceptions can be thrown. But if you are not using "clone"
     * keyword, then this will never happen and can be safely ignored.
     *
     * @param array<int, mixed> $args
     *
     * @return mixed|void
     */
    public function __call(string $methodName, array $args)
    {
        return Lombok::call($this, $methodName, $args);
    }

} // end of class
