<?php

namespace Mizmoz\Container\Exception;

use RuntimeException;
use Psr\Container\ContainerExceptionInterface;

class AlreadyRegisteredException extends RuntimeException implements ContainerExceptionInterface
{
}