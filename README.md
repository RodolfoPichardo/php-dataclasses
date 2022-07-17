# PHP Dataclasses

A declarative way of producing structured data on PHP. Heavily inspired on the python dataclass module.

The goal is to create shorter and readable classes, while also doing structural validation.

## Basic usage
 
The simplest dataclass won't need any methods, keeping the code elegantly clean and digestible at a glance.

Example:

```php
class User extends Dataclass {
  public int $id;
  public string $name;
  public float $balance;
}
```

And then, to construct the object of class _User_, we simply pass an array with our expected data.

```php
$user = new User([
  "id" => 1,
  "name" => "John Doe",
  "balance" => 104.02
]);
```

## Requirements

This library requires PHP 8.1 or newer.

## Installation

There is no automatic installation as of yet, instead, clone this repository and copy the src directory onto your project.

## TODO list

- [x] Support primitives
- [x] Support enumerators
- [x] Support arrays of primitives
- [x] Support nested dataclasses
- [x] Support defaults
- [x] Support arrays of dataclasses
- [x] Support dictionaries of dataclasses
- [ ] Support properties with multiple possible types
- [ ] Build with a CI
- [ ] Add to composer as a module
- [ ] Create alternative constructors for special cases
- [x] Add contributions directive
