<?php

namespace Mizmoz\Container;

use Mizmoz\Container\Contract\ContainerInterface;
use Mizmoz\Container\Contract\ResolverInterface;
use Mizmoz\Container\Exception\FatalNotFoundException;
use Mizmoz\Container\Exception\NotFoundException;
use Mizmoz\Container\Exception\ResolutionFailedException;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;

class Resolver implements ResolverInterface
{
    /**
     * @inheritDoc
     */
    public function resolve(string $id, ContainerInterface $container)
    {
        try {
            // check if this is a class
            $reflection = new ReflectionClass($id);

            // get the constructor
            $constructor = $reflection->getConstructor();

            if (! $constructor) {
                if (! $reflection->isInstantiable()) {
                    throw new ResolutionFailedException("Cannot resolve '" . $id . "' as it is not instantiable");
                }

                // no constructor so we can just go ahead an instantiate this class
                return $reflection->newInstanceWithoutConstructor();
            }

            // get the constructor so we can find what parameters is needs
            $parameters = $this->resolveParameters($id, $constructor->getParameters(), $container);

            // return the new instance
            return $reflection->newInstanceArgs($parameters);
        } catch (ReflectionException $e) {
            throw new FatalNotFoundException("Entry id '" . $id . "' not found in container");
        }
    }

    /**
     * Resolve the parameters
     *
     * @param string $id
     * @param ReflectionParameter[] $parameters
     * @param ContainerInterface $container
     * @return array
     */
    private function resolveParameters(string $id, array $parameters, ContainerInterface $container): array
    {
        $resolved = [];
        foreach ($parameters as $parameter) {
            $resolved[] = $this->getParameterValue($id, $parameter, $container);
        }
        return $resolved;
    }

    /**
     * Get the parameter value
     *
     * @param string $id
     * @param ReflectionParameter $parameter
     * @param ContainerInterface $container
     * @return mixed
     */
    private function getParameterValue(string $id, ReflectionParameter $parameter, ContainerInterface $container)
    {
        if (! ($class = $parameter->getClass())) {
            if ($parameter->isDefaultValueAvailable()) {
                // a default value is available so use that
                return $parameter->getDefaultValue();
            }

            // no default, eek
            throw new FatalNotFoundException("Cannot resolve '" . $parameter->getName() . "' for class '" . $id . "'");
        }

        try {
            // attempt to resolve the value
            return $container->get($class->getName());
        } catch (NotFoundException $e) {
            if ($parameter->isDefaultValueAvailable()) {
                // a default value is available so use that
                return $parameter->getDefaultValue();
            }
            throw $e;
        }
    }
}