<?php

namespace Mizmoz\Container;

use Mizmoz\Container\Contract\ManageContainerInterface;
use Psr\Container\ContainerInterface;

class InjectContainer
{
    /**
     * Inject the container if it uses the ManageContainerTrait
     *
     * @param ContainerInterface $container
     * @param $entry
     * @return mixed
     */
    public static function inject(ContainerInterface $container, $entry)
    {
        if (! is_object($entry)) {
            return $entry;
        }

        if (! in_array(ManageContainerTrait::class, class_uses($entry))
            && ! $entry instanceof ManageContainerInterface) {
            return $entry;
        }

        // set the container
        return $entry->setAppContainer($container);
    }
}