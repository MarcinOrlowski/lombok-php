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

Now, further usage depends on type of annotations you wish to use. As PHP annotations do not
work just by themselves automagically, you need to connect your classes with `Lombok PHP` yourself
to let all the automation happen.

### Using helper class ###

The simplest approach is to extend `\Lombok\Helper` class which will set it all up for you.

```php
class Entity extends \Lombok\Helper {
    ...
```

If your class implements own `__construct()` method, see [Manual wiring](#manual-wiring) for how
to approach that case.

### Manual wiring ###

When your class extends `\Lombok\Helper` then it basically inherings `Helper`'s constructor
and destructor implementations, which in turn calls `Lombok PHP`'s configuration methods.
If your class has own constructor them you got two options. If you can do that, just extend
`\Lombok\Helper` class but as first line of your constructor just call helper's one:

```php
public function __construct() {
    // Enable Lombok's provided methods
    parent::__construct();
  
    ... [rest of your code can use Lombok's provided methods] ...
}
```

**NOTE:** Once `Lombok PHP::__construct()` is called, your constructor can call any of the
`Lombok PHP`'s provided methods right away!

Then you need to do the same for destructor. If you do not extend `\Lombok\Helper` then your altered
code should look like this:

```php
public function __destruct() {
    ... [your original destructor code] ...

    // last thing to do before we die, disconnect Lombok
    parent::__destruct();
}
```

In majority of cases you should be able to do that and that should be perfectly sufficient.
Alternatively, if use of `Lombok\Helper` is not possible, you need to implement the following
methods:

In your class' constructor call Lombok's `construct()` method to set your object up:

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

**IMPORTANT** You **MUST** have destructor! This step is crucial as `Lombok PHP` must know
when object it supports is being destroyed and to remove its internal configuration. It's
because the `spl_object_id()` is used internally to identify each object and returned
identifier is only guaranteed to be unique during object's lifetime. The method is
explicitly documented to reuse identifiers of destroyed objects for new ones, so it
could lead to unexpected results if old object configuration is still in place without
the cleanup step.

Finally you must implement the `__call()` method, which is needed to let `Lombok PHP`
do the heavy lifting with all the magic methods it implements:

```php
public function __call(string $methodName, array $args) {
    return \Lombok\Lombok::call($this, $methodName, $args);
}
```

---

## Limitations ##

* Due to how PHP annotations work, any class using `Lombok PHP` must either extend provided
  `\Lombok\Helper` class or as wire magic methods.
* By design, `Lombok PHP` does not support accessors for properties with neither `public` visibility
  nor `static` properties.
* Visibility of generated accessors is currently always `public` in current implementation.
* As all methods provided by `Lombok PHP` are handled on-the-fly, some IDEs or static analysers
  cam complain about calling non-existing method. And because IDEs are not aware of `Lombok PHP`
  yet, they will also not offer auto-completion for methods provided by `Lombok PHP`. If you bother
  create PHPDocs block with `@method` annotations for each magic method. Helper tool to generate
  that will be available shortly.
