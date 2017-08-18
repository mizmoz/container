<?php

namespace Mizmoz\Container\Contract;

interface ServiceProviderInterface
{
    /**
     * Called as soon as the provider is registered with the container
     *
     * @param ContainerInterface $container
     */
    public function boot(ContainerInterface $container);

    /**
     * Register the entries to the provided container
     *
     * @param ContainerInterface $container
     */
    public function register(ContainerInterface $container);

    /**
     * Return a list of the ids this provider will register
     * This enables the container to lazy load the provider entries only when requested
     *
     * @return array
     */
    public function provides(): array;
}