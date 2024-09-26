<?php

namespace Mizmoz\Container\Contract;

use Psr\Container\ContainerInterface;

interface ManageContainerInterface
{
    /**
     * Called after the app container has been set
     *
     */
    public function afterSetAppContainer(): void;

    /**
     * Get the container
     *
     * @return ContainerInterface
     */
    public function getAppContainer(): ContainerInterface;

    /**
     * Set the container, must call afterSetAppContainer() after setting the container
     *
     * @param ContainerInterface $container
     * @return $this
     */
    public function setAppContainer(ContainerInterface $container): static;
}