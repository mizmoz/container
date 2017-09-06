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

        if (! static::classUses($entry, ManageContainerTrait::class)
            && ! $entry instanceof ManageContainerInterface) {
            return $entry;
        }

        // set the container
        return $entry->setAppContainer($container);
    }

    /**
     * Check to see if the class uses the provided item
     *
     * @param $class
     * @param string $find
     * @return bool
     */
    public static function classUses($class, string $find): bool
    {
        $uses = class_uses($class);

        if (in_array($find, $uses)) {
            // found the item
            return true;
        }

        // add any parent classes to the search
        $uses = array_merge($uses, class_parents($class));

        foreach ($uses as $use) {
            if (static::classUses($use, $find)) {
                return true;
            }
        }

        return false;
    }
}