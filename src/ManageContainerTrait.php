<?php

namespace Mizmoz\Container;

use Psr\Container\ContainerInterface;
use Mizmoz\Container\Exception\ContainerNotSetException;

trait ManageContainerTrait
{
    /**
     * @var ContainerInterface
     */
    private $appContainer;

    /**
     * Called after setting the app container
     */
    public function afterSetAppContainer() {}

    /**
     * Get the container
     *
     * @return ContainerInterface
     */
    public function getAppContainer(): ContainerInterface
    {
        if (! $this->appContainer) {
            throw new ContainerNotSetException();
        }

        return $this->appContainer;
    }

    /**
     * Set the container
     *
     * @param ContainerInterface $container
     * @return $this
     */
    public function setAppContainer(ContainerInterface $container)
    {
        $this->appContainer = $container;
        $this->afterSetAppContainer();
        return $this;
    }

    /**
     * Remove the container for the sleep vars
     */
    public function __sleep()
    {
        $properties = array_keys(get_object_vars($this));

        return array_filter($properties, function ($key) {
            return $key !== 'appContainer';
        });
    }
}