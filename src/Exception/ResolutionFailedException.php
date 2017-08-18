<?php

namespace Mizmoz\Container\Exception;

use RuntimeException;
use Psr\Container\NotFoundExceptionInterface;

class ResolutionFailedException extends RuntimeException implements NotFoundExceptionInterface
{
}