<?php

namespace Mizmoz\Container\Contract;

use Mizmoz\Container\Exception\NotFoundException;

interface ResolverInterface
{
    /**
     * Resolve the passed id
     *
     * @param string $id
     * @param ContainerInterface $container
     * @throws NotFoundException
     * @return mixed
     */
    public function resolve(string $id, ContainerInterface $container): mixed;
}