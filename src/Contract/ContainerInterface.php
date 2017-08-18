<?php

namespace Mizmoz\Container\Contract;

use Mizmoz\Container\Entry;
use Mizmoz\Container\Provider;

interface ContainerInterface extends \Psr\Container\ContainerInterface
{
    /**
     * Exclusive items will be created each time get($id) is called
     */
    const EXCLUSIVE = 'exclusive';

    /**
     * Shared items will always return the same object when get($id) is called
     */
    const SHARED = 'shared';

    /**
     * Add an item to the container
     *
     * @param string $id
     * @param callable|string $entry
     * @param string $type
     * @return Entry
     */
    public function add(string $id, $entry, string $type = self::EXCLUSIVE): Entry;

    /**
     * Add an alias for an entry
     *
     * @param string $id
     * @param string $alias
     * @return ContainerInterface
     */
    public function addAlias(string $id, string $alias): ContainerInterface;

    /**
     * Helper to add an exclusive entry to the container
     *
     * @param string $id
     * @param callable|string $entry
     * @return Entry
     */
    public function addExclusive(string $id, $entry): Entry;

    /**
     * Helper to add a shared entry to the container
     *
     * @param string $id
     * @param callable|string $entry
     * @return Entry
     */
    public function addShared(string $id, $entry): Entry;

    /**
     * Add a service provider to the container
     *
     * @param ServiceProviderInterface $serviceProvider
     * @return Provider
     */
    public function addServiceProvider(ServiceProviderInterface $serviceProvider): Provider;

    /**
     * Inject the container if it uses the ManageContainerTrait
     *
     * @param $entry
     * @return mixed
     */
    public function injectContainer($entry);
}