<?php

namespace Mizmoz\Container;

use Mizmoz\Container\Contract\ContainerInterface;
use Mizmoz\Container\Contract\ServiceProviderInterface;

abstract class ServiceProviderAbstract implements ServiceProviderInterface
{
    /**
     * @inheritdoc
     */
    public function boot(ContainerInterface $container)
    {
    }
}