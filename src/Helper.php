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

namespace Lombok;

abstract class Helper
{
    public function __construct()
    {
        Lombok::construct($this);
    }

    public function __destruct()
    {
        Lombok::destruct($this);
    }

    /**
     * @return mixed|void
     * @throws \Lombok\Exceptions\PublicPropertyException
     * @throws \Lombok\Exceptions\StaticPropertyException
     */
    public function __call(string $methodName, array $args)
    {
        return Lombok::call($this, $methodName, $args);
    }

} // end of class
