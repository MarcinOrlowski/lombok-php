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

namespace Lombok\Attributes\Contracts;

interface AccessorContract
{

    /**
     * Returns name of dynamic method, "generated" for this accessor type.
     * I.e. for getters it is usually get<CamelCaseProprtyName>() etc.
     */
    public function getFunctionName(\ReflectionProperty $property): string;

} // end of class
