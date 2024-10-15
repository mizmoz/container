v2.0.1

- Fix bug with injecting the container in to classes that use the ManageContainerTrait

v2.0.0

- Update has() method to match PSR-11 v2 interface
- Update to PHP 8.3
- Add PHPStan static analysis
- Add provides() to ContainerInterface to return all services provided by the container

v1.3.0

- Add afterSetAppContainer() to the ManageContainerInterface which must be called after the setAppContainer method is called

v1.2.0

- Search extended class and used traits for MangeContainerTrait when attempting to inject the container

v1.1.0

- Add addValue to Container to allow adding raw values that do not require resolving

v1.0.0

- First release of the container
