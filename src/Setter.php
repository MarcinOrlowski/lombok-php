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

namespace Lombok;

use Attribute;
use Lombok\Attributes\BaseAttribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_CLASS)]
class Setter extends BaseAttribute
{
    public function getFunctionName(\ReflectionProperty $property): string
    {
        return 'set' . $this->toCamelCase($property->getName());
    }

} // end of class
