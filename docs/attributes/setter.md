![Lombok PHP](../../artwork/lombok-php-logo.png)

---

# Table of contents #

* [« Main README](../../README.md)
* [« Documentation table of contents](../README.md)
* [« Available attributes](README.md)
  * **Setter**
    * [Description](#description)
    * [Important notes](#notes)
    * [Examples](#examples)

---

## Description ##

Provides a setter method for all non-public and non-static properties of the class. It can be
applied to each property individually or to the whole class.

## Notes ##

* Each setter provided will return an instance of the object for easy chaining.

* While mixing accessor attributes at the class scope and property scope, the class scope will *
  *only** be applied if no property attributes were used. This allows for setting a wider scope for
  the whole class while narrowing it for specific properties. For example, if you
  assign `#[Setter, Getter]` to your `class`, but `#[Getter]` to a specific property, that property
  will NOT have a setter added.

* When applied to each property individually, an exception will be thrown if the accessor method
  name collides with an existing class method. However, when applied to the whole class, conflicts
  are silently ignored, the original method remains, and no accessor is provided.

* When applied to each property individually, an exception will be thrown if applied to either
  a `public` or `static` property, as neither is supported by design. However, when applied to the
  whole class, all the `public` and `static` properties will be silently ignored.

* All method names provided use the [SnakeCase](https://en.wikipedia.org/wiki/Snake_case) naming
  convention regardless of the property name's style. For example, `myProperty` will be transformed
  to `setMyProperty()` and `my_property` will be transformed to `setMyProperty()`.

## Examples ##

```php
use Lombok\Setter;

class Entity extends \Lombok\Helper
{
    #[Setter]
    protected int $number = 0;
    
    #[Setter]
    protected boolean $visible = true;
}
```

`Lombok PHP` will provide `setNumber(int): static` and `setVisible(bool): static` methods.
Similarly, in when applied to the whole class:

```php
use Lombok\Setter;

#[Setter]
class Entity extends \Lombok\Helper
{
    protected int $number = 0;
    protected boolean $visible = true;
    protected string|bool $union;
    
    public string $code;
    public static string $name;
}
```

`Lombok PHP` will provide `setNumber(int): static`, `setVisible(bool): static`,
and `setUnion(string|bool): static` methods. Unsupported property types of `$code` and `$name` will
be silently skipped.

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

In the above case, `Lombok PHP` will provide both accessors for the `$name` property (`getName()`
and `setName()`), but only a getter for `$id`.

---

Method name conflict resolution:

```php
use Lombok\Setter;

class Entity extends \Lombok\Helper
{
    #[Setter]
    protected int $id = 0;
    
    public function setId(int $id) {}
}
```

This will throw a `MethodAlreadyExistsException` as the `setId()` method already exists.

The behavior is different for class-level attributes. In this case, the original `setId()` remains
and no `Lombok PHP` accessor is provided.

```php
use Lombok\Setter;

#[Setter]
class Entity extends \Lombok\Helper
{
    
    protected int $id = 0;
    
    public function setId(int $id) {}
}
```
