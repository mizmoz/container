<?php

namespace Mizmoz\Container;

use Mizmoz\Container\Contract\ContainerInterface;
use Mizmoz\Container\Contract\ManageContainerInterface;

class Entry
{
    /**
     * @var callable
     */
    private $entry;

    /**
     * @var string
     */
    private string $type;

    /**
     * @var mixed
     */
    private mixed $cached = null;

    /**
     * Entry constructor.
     *
     * @param callable $entry
     * @param string $type
     */
    public function __construct(callable $entry, string $type = ContainerInterface::EXCLUSIVE)
    {
        $this->entry = $entry;
        $this->type = $type;
    }

    /**
     * Inject the container if it uses the ManageContainerTrait
     *
     * @param mixed $entry
     * @param ContainerInterface $container
     * @return mixed
     */
    private function injectContainer(mixed $entry, ContainerInterface $container): mixed
    {
        if ($entry instanceof ManageContainerInterface) {
            return $entry->setAppContainer($container);
        }

        return $entry;
    }

    /**
     * Get the entry
     *
     * @param string $id
     * @param ContainerInterface $container
     * @return mixed
     */
    public function __invoke(string $id, ContainerInterface $container): mixed
    {
        $call = $this->entry;

        if ($this->type === ContainerInterface::SHARED) {
            if ($this->cached) {
                return $this->cached;
            }

            return $this->cached = $this->injectContainer($call(), $container);
        }

        return $this->injectContainer($call(), $container);
    }
}