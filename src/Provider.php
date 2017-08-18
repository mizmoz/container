<?php

namespace Mizmoz\Container;

use Mizmoz\Container\Contract\ContainerInterface;
use Mizmoz\Container\Contract\ServiceProviderInterface;

class Provider
{
    /**
     * @var ServiceProviderInterface
     */
    private $serviceProvider;

    /**
     * Provider constructor.
     *
     * @param ServiceProviderInterface $serviceProvider
     */
    public function __construct(ServiceProviderInterface $serviceProvider)
    {
        $this->serviceProvider = $serviceProvider;
    }

    /**
     * Get the entry
     *
     * @param string $id
     * @param ContainerInterface $container
     * @return mixed
     */
    public function __invoke(string $id, ContainerInterface $container)
    {
        $providerContainer = new ProviderContainer($container, $this->serviceProvider);

        // register the items in the service provider
        $this->serviceProvider->register($providerContainer);

        // call done to check that everything that was promised was provided
        $providerContainer->done();

        // get the item by id
        return $providerContainer->getContainer()->get($id);
    }
}