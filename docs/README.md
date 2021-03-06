![Lombok PHP](../artwork/lombok-php-logo.png)

---

# Table of contents #

* [« Main README](../README.md)
* [Requirements](#requirements)
* [Installation](#installation)
* [Usage](#usage)
  * [Using helper class](#using-helper-class)
  * [Manual wiring](#manual-wiring)
* **[Available attributes](attributes/README.md)**
* [Limitations](#limitations)

---

## Requirements ##

* PHP 8.0 or newer

---

## Installation ##

Add `Lombok PHP` to your project dependency list:

```php
composer install marcin-orlowski/lombok-php
```

---

## Usage ##

Now, further usage depends on type of annotations you wish to use. As PHP annotations do not
"run" by themselves automagically, Lombok's initialization must be triggered from your code.

### Using helper class ###

The simplest approach is to extend `\Lombok\Helper` class which will set it all up for you.

```php
#[Setter, Getter]
class Entity extends \Lombok\Helper {
    ...
```

### Manual wiring ###

Alternatively, if use of `Lombok\Helper` is not possible, you need to implement the following
methods:

In your class' constructor call Lombok's `configure()` method to set your object up:

```php
public function __construct() {
    \Lombok\Lombok::construct($this);
}
```

Then you need to implement [class destructor](https://www.php.net/manual/en/language.oop5.decon.php)
to ensure Lombok knows your object is gone so it shall clean some internals up:

```php
public function __destruct() {
    \Lombok\Lombok::destruct($this);
}
```  

**IMPORTANT** This step is crucial as `Lombok PHP` must know when object it supports is
being destroyed and to remove its internal configuration. It's because the `spl_object_id()`
is used internally to identify each object and returned identifier is only guaranteed to be
unique during object's lifetime. The method is explicitely documented to reuse identifiers
of destroyed objects for new ones, so it could lead to unexpected results if old object
configuration is still in place without the cleanup step.

Finally the `__call()` is need to let `Lombok PHP` do the heavy lifting.

```php
public function __call(string $methodName, array $args) {
    return \Lombok\Lombok::call($this, $methodName, $args);
}
```

---

## Limitations ##

* Due to how PHP annotations work, any class using `Lombok PHP` must either extend `LombokHelper`
  class or initialize `Lombok PHP` manually as well as wire magic methods. See documentation
  for more details.
* By design, `Lombok PHP` does not support accessors for properties with `public` visibility
  (as this simply makes little sense).
* Also `static` properties are not supported.
* Visibility of generated accessors is always `public` in current implementation but more
  control is to be added shortly.
