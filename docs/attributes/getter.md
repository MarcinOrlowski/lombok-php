![Lombok PHP](../../artwork/lombok-php-logo.png)

---

# Table of contents #

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

* When applied to each property individually, will throw an exception when applied to
  either `public` or `static` property as neither is supported by design. When applied to the
  whole class however, all the `public` and `static` properties will be silently ignored.


* When applied to each property individually, will throw an exception when accessor method name
  collides with existing class method. When applied to the whole class however, conflicts are
  silently ignored and original method remains, with no accessor provided.


* While mixing accessor attributes in class scope and property scope, class scope will **only** be
  applied if no property attribute were used. This lets you set wider scope for the whole class
  and still narrow it for specific properties. For example, if you put `#[Setter, Getter]` to
  your `class`, but `#[Getter]` to specific property, that property will NOT have setter added.


* All method names provided use [SnakeCase](https://en.wikipedia.org/wiki/Snake_case) naming
  convention regardless of the property name's style. For example, `myProperty` will be transformed
  to `getMyProperty()` and for `my_property` will be transformed to `getMyProperty()`.


* When target property is `boolean`, the method prefix will be `is` instead of `get`,
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

---

Example of scope mixing:

```php
use Lombok\Getter;
use Lombok\Setter;

#[Setter, Getter]
class Entity extends \Lombok\Helper
{
    #[Getter]
    protected int $id = 0;
    
    protected ?string $name;
}
```

In the above case, `Lombok PHP` will provide both accessors for `$name` property (`getName()` and
`setName()`) but only getter for `$id`.

---

Method name conflict resolution:

```php
use Lombok\Getter;

class Entity extends \Lombok\Helper
{
    #[Getter]
    protected int $id = 0;
    
    public function getId() {}
}
```

This will throw `MethodAlreadyExistsException` as `getId()` method already exists.

Behavior is different for class level attributes:

```php
use Lombok\Getter;

#[Getter]
class Entity extends \Lombok\Helper
{
    
    protected int $id = 0;
    
    public function getId() {}
}
```

Original `getId()` method remains and no `Lombok PHP` accessor is provided.
