<?php

namespace Mizmoz\Container\Tests;

use Mizmoz\Container\Contract\ManageContainerInterface;
use Mizmoz\Container\ManageContainerTrait;

class ResolveNoConstructor implements ManageContainerInterface
{
    use ManageContainerTrait;
}