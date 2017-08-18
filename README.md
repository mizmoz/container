# [Mizmoz](https://www.mizmoz.com/) / Container

## Aims

- Be lightweight, lazy load all the things.
- Use PHP-FIG PSR-11
- Auto resolve when possible

## Getting Started

### Composer Installation

```
composer require mizmoz/container
```

### Basic Usage

```php
use Mizmoz\Container;
$container = new Container;

// Add an item to the container
$container->add(MyClass::class, function () {
    return new MyClass('some', 'params');
});

// get the item
$myClass = $container->get(MyClass::class);

// Add a shared item
$container->addShared(MyClass::class, function () {
    return new MyClass();
}, Container::SHARED);

// get the shared item
$container->get(MyClass::class) === $container->get(MyClass::class);

// check if an item exists in the container
$container->has(MyClass::class);

// add an alias for easy access to items
$container->addAlias(SomeLogger::class, 'log');

```

### Class resolution

Basic constructor resolution is available.

```php
// Pass the resolver in to the container
$container = new Container(new Resolver);

// silly example
$date = $container->get(DateTime::class);

// add entries to the container to have interfaces resolved
$container->add(SomeInterface::class, function () { return new Some(); });

// now auto resolve the class that requires the SomeInterface entry
$container->get(SomeUser::class);
```

### Container Helper

Use the `ManageContainerTrait` to automatically have the container set on a class when it's returned via `get($id)`

### API

- `Entry add(string $id, callable $entry, string $type = Container::EXCLUSIVE)`
- `Entry addShared(string $id, callable $entry)`
- `Entry addExclusive(string $id, callable $entry)`
- `Provider addServiceProvider(Contract\ServiceProvider $serviceProvider)`
- `Container addAlias(string $id, string $alias)`
- `mixed get(string $id)`
- `bool has(string $id)`

## Todo

- Auto resolver improvements
    - Add ability to resolve basic arguments e.g. Resolve $secret in Connect(string $secret)
- Add event notifications with Mizmoz\Event