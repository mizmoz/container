<?php

namespace Mizmoz\Container\Exception;

use Psr\Container\ContainerExceptionInterface;
use RuntimeException;

class InvalidEntryException extends RuntimeException implements ContainerExceptionInterface
{
}