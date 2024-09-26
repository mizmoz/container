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
     * @param mixed $entry
     * @return mixed
     */
    public static function inject(ContainerInterface $container, mixed $entry): mixed
    {
        if ($entry instanceof ManageContainerInterface) {
            return $entry->setAppContainer($container);
        }

        return $entry;
    }

    /**
     * Check to see if the class uses the provided item
     *
     * @param object|string $class
     * @param string $find
     * @return bool
     */
    public static function classUses(object|string $class, string $find): bool
    {
        $uses = class_uses($class);

        if ($uses && in_array($find, $uses)) {
            // found the item
            return true;
        }

        // add any parent classes to the search
        $uses = array_merge($uses, class_parents($class) ?? []);

        foreach ($uses as $use) {
            if (static::classUses($use, $find)) {
                return true;
            }
        }

        return false;
    }
}