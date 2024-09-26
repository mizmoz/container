<?php

namespace Mizmoz\Container\Contract;

interface ServiceProviderInterface
{
    /**
     * Called as soon as the provider is registered with the container
     *
     * @param ContainerInterface $container
     */
    public function boot(ContainerInterface $container): void;

    /**
     * Register the entries to the provided container
     *
     * @param ContainerInterface $container
     */
    public function register(ContainerInterface $container): void;

    /**
     * Return a list of the ids this provider will register
     * This enables the container to lazy load the provider entries only when requested
     *
     * @return string[]
     */
    public function provides(): array;
}