<?php

namespace Mizmoz\Container\Tests;

use Mizmoz\Container\Contract\ManageContainerInterface;
use Mizmoz\Container\ManageContainerTrait;

abstract class InjectBaseAbstract implements ManageContainerInterface
{
    use ManageContainerTrait;
}