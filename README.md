# PHP DI Container

## Aims

- Be lightweight
- Use PHP-FIG PSR-11
- Auto resolve when possible

## Getting Started

```php
use Mizmoz\Container;
$container = new Container;

// Add an item to the container
$container->add(MyClass::class, function () {
    return new MyClass('some', 'params');
});

// get the item
$myClass = $container->get(MyClass::class);

// Add an item which accepts arguments in the constructor
$container->add(MyClass::class, function ($name, $params) {
    return new MyClass($name, $params);
});

// get the item with arguments
$myClass = $container->get(MyClass::class, $name, $params);

// Add a singleton
$container->add(MyClass::class, function ($name, $params) {
    return new MyClass($name, $params);
}, Container::SINGLETON);

// get the singleton
$container->get(MyClass::class) === $container->get(MyClass::class);

```

## API

- `Container add(string $id, callable $item, string $type = Container::FACTORY)`
- `Container addServiceProvider(Contract\ServiceProvider $serviceProvider)`
- `mixed get(string $id, ...$arguments)`
- `bool has(string $id)`
