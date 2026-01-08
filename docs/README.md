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
composer require marcin-orlowski/lombok-php
```

---

## Usage ##

Now, further usage depends on the type of annotations you wish to use. As PHP annotations do not
work just by themselves automatically, you need to connect your classes with `Lombok PHP` yourself
to allow the automation to occur.

### Using helper class ###

The simplest approach is to extend the `\Lombok\Helper` class which will set it all up for you.

```php
class Entity extends \Lombok\Helper {
    ...
```

If your class implements its own `__construct()` method, see [Manual wiring](#manual-wiring) for how
to approach that case.

### Manual wiring ###

When your class extends `\Lombok\Helper`, it essentially inherits `Helper`'s constructor and
destructor implementations, which in turn call `Lombok PHP`'s configuration methods. If your class
has its own constructor then you have two options. If possible, simply extend the `\Lombok\Helper`
class and as the first line of your constructor, call the helper's constructor:

```php
public function __construct() {
    // Enable Lombok's provided methods
    parent::__construct();
  
    ... [rest of your code can use Lombok's provided methods] ...
}
```

**NOTE:** Once `Lombok PHP::__construct()` is called, your constructor can call any of
the `Lombok PHP`'s provided methods right away!

Then you need to do the same for the destructor. If you do not extend `\Lombok\Helper`, then your
altered code should look like this:

```php
public function __destruct() {
    ... [your original destructor code] ...

    // last thing to do before we die, disconnect Lombok
    parent::__destruct();
}
```

In the majority of cases, you should be able to do that and it should be perfectly sufficient.
Alternatively, if the use of `Lombok\Helper` is not possible, you need to implement the following
methods:

In your class' constructor, call Lombok's `construct()` method to set your object up:

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

**IMPORTANT**: You **MUST** have a destructor! This step is crucial as `Lombok PHP` needs to know
when the object it supports is being destroyed in order to remove its internal configuration. This
is because the `spl_object_id()` method is used internally to identify each object, and the returned
identifier is only guaranteed to be unique during the object's lifetime. The method is explicitly
documented to reuse identifiers of destroyed objects for new ones, so it could lead to unexpected
results if the old object's configuration remains in place without the cleanup step.

Finally, you must implement the `__call()` method, which is needed to allow `Lombok PHP` to handle
all the magic methods it implements:

```php
public function __call(string $methodName, array $args) {
    return \Lombok\Lombok::call($this, $methodName, $args);
}
```

---

## Limitations ##

* While `Lombok PHP` does its magic at runtime, there is no possibility it to "announce" its magic
  methods back the PHP, so no "magic method" can i.e. implement an interface method, that it
  otherwise would implement if it was a real method. That's most significant limitation of PHP here.
* Due to the nature of PHP annotations, any class using `Lombok PHP` must either extend the
  provided `\Lombok\Helper` class or wire magic methods manually.
* By design, `Lombok PHP` does not support accessors for properties that has either `public`
  visibility or is `static`.
* The visibility of generated accessors is currently always `public` in the current implementation.
* As all methods provided by `Lombok PHP` are handled on-the-fly, some IDEs or static analyzers may
  complain about calling non-existing methods. Since IDEs are not yet aware of `Lombok PHP`, they
  will also not offer auto-completion for methods provided by `Lombok PHP`. If this bothers you,
  create a PHPDocs block with `@method` annotations for each magic method.


## License

* Written and copyrighted &copy;2022-2026 by Marcin Orlowski <mail (#) marcinOrlowski (.) com>
* `Lombok PHP` is open-source software licensed under
  the [MIT license](http://opensource.org/licenses/MIT)
