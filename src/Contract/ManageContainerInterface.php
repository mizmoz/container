<?php

namespace Mizmoz\Container\Contract;

use Psr\Container\ContainerInterface;

interface ManageContainerInterface
{
    /**
     * Get the container
     *
     * @return ContainerInterface
     */
    public function getAppContainer(): ContainerInterface;

    /**
     * Set the container
     *
     * @param ContainerInterface $container
     * @return $this
     */
    public function setAppContainer(ContainerInterface $container);
}