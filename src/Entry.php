<?php

namespace Mizmoz\Container;

use Mizmoz\Container\Contract\ContainerInterface;

class Entry
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var callable
     */
    private $entry;

    /**
     * @var string
     */
    private $type;

    /**
     * @var mixed
     */
    private $cached;

    /**
     * Entry constructor.
     *
     * @param string $id
     * @param callable $entry
     * @param string $type
     */
    public function __construct(string $id, callable $entry, string $type = Container::EXCLUSIVE)
    {
        $this->id = $id;
        $this->entry = $entry;
        $this->type = $type;
    }

    /**
     * Inject the container if it uses the ManageContainerTrait
     *
     * @param $entry
     * @param ContainerInterface $container
     * @return mixed
     */
    private function injectContainer($entry, ContainerInterface $container)
    {
        if (! is_object($entry)) {
            return $entry;
        }

        if (! in_array(ManageContainerTrait::class, class_uses($entry))) {
            return $entry;
        }

        // set the container
        return $entry->setAppContainer($container);
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
        $call = $this->entry;

        if ($this->type === Container::SHARED) {
            if ($this->cached) {
                return $this->cached;
            }

            return $this->cached = $this->injectContainer($call(), $container);
        }

        return $this->injectContainer($call(), $container);
    }
}