![Lombok PHP](../artwork/lombok-php-logo.png)

---

## Table of contents ##

* [« Main README](../../README.md)
* [« Documentation table of contents](../README.md)
* [« Available attributes](README.md)
  * **Getter**
    * [Description](#description)
    * [Important notes](#notes)
    * [Examples](#examples)

---

## Description ##

Provides getter method for all the non-public and non-static properties of the class.
Can be applied to each of property individually or to whole class.

## Notes ##

When applied to each property individually, will throw an exception when applied to either `public`
or `static` property as neither is supported by design. When applied to the whole class however,
all the `public` and `static` properties will be silently ignored.

All method names provided use [SnakeCase](https://en.wikipedia.org/wiki/Snake_case) naming
convention regardless of the property name's style. For example, `myProperty` will be transformed to
`getMyProperty()` and for `my_property` will be transformed to `getMyProperty()`.

When target property is `boolean`, the method prefix will be `is` instead of `get`,
i.e. `isVisible()`. This only happens when `boolean` is sole type of the property. If property is
annotated with union, regular `get` prefix will be used:

## Examples ##

```php
use Lombok\Getter;

class Entity extends \Lombok\Helper
{
    #[Getter]
    protected int $number = 0;
    
    #[Getter]
    protected boolean $visible = true;
}
```

`Lombok PHP` will provide `getNumber(): int` and `isVisible(): bool` methods. Similarly,
in when applied to the whole class:

```php
use Lombok\Getter;

#[Getter]
class Entity extends \Lombok\Helper
{
    protected int $number = 0;
    protected boolean $visible = true;
    protected string|bool $union;
    
    public string $code;
    public static string $name;
}
```

`Lombok PHP` will provide `getNumber(): int`, `isVisible(): bool` and `getUnion(): string|bool`
methods. Unsupported property types of `$code` and `$name` will be skipped silently.
