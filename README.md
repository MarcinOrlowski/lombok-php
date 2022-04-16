![Lombok PHP - write less code!](artwork/lombok-php-logo.png)

---

[![Latest Stable Version](https://poser.pugx.org/marcin-orlowski/lombok-php/v)](https://packagist.org/packages/marcin-orlowski/laravel-api-response-builder)
[![License](https://poser.pugx.org/marcin-orlowski/lombok-php/license)](https://packagist.org/packages/marcin-orlowski/lombok-php)

---

## Table of contents ##

* [Introduction](#introduction)
* [Why should I use it?](#benefits)
* [Usage examples](#examples)
* [Features](#features)
* [Extensive documentation](docs/README.md)
* [License](#license)
* [Changelog](CHANGES.md)

---

## Introduction ##

`Lombok PHP` is a handy package providing set of PHP8+ attributes aimed to help you reduce
boilerplate code in your project, by providing commonly used functionality you can easily apply.

And yes, the project name is shamelessly borrowed from the beloved Java's
[Project Lombok](https://projectlombok.org/). It is not affiliated in any way though - it's just a
scope and (target) functionality.

## Benefits ##

* Less code to write and to maintain,
* No more repetitive boilerplate code,
* Can coexist with other attributes (i.e. [Doctrine](https://www.doctrine-project.org/), ORMs etc),
* No additional dependencies,
* Supports object cloning,
* Production ready.

## Examples ##

Vanilla PHP:

```php
class Entity {
    protected int    $id;
    protected string $name;
    protected ?int   $age;

    public function getId(): int
    {
      return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }
    public function setAge(?int $age): static
    {
        $this->age = $age;
        return $this;
    }
}
```

Using `Lombok PHP`:

```php
use Lombok\Getter;
use Lombok\Setter;

#[Setter, Getter]
class Entity extends \Lombok\Helper {
    #[Getter]
    protected int $id;

    protected string $name;
    protected ?int $age;
}
```

## License ##

* Written and copyrighted &copy;2022 by Marcin Orlowski <mail (#) marcinorlowski (.) com>
* Lombok for PHP is open-sourced software licensed under
  the [LGPL 3.0](https://opensource.org/licenses/LGPL-3.0)
