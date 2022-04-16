<?php
declare(strict_types=1);

namespace Lombok\Attributes;

class Type
{
    /** @var string */
    public const ARRAY = 'array';
    /** @var string */
    public const BOOL = 'bool';
    /** @var string */
    public const FLOAT = 'float';
    /** @var string */
    public const INT = 'integer';
    /** @var string */
    public const NULL = 'null';
    /** @var string */
    public const OBJECT = 'object';
    /** @var string */
    public const STRING = 'string';
    /** @var string */
    public const EXISTING_CLASS = 'existing_class';
}
