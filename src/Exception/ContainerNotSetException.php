<?php

namespace Mizmoz\Container\Exception;

use RuntimeException;
use Psr\Container\ContainerExceptionInterface;

class ContainerNotSetException extends RuntimeException implements ContainerExceptionInterface
{
}