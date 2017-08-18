<?php

namespace Mizmoz\Container\Exception;

use RuntimeException;
use Psr\Container\NotFoundExceptionInterface;

class NotFoundException extends RuntimeException implements NotFoundExceptionInterface
{
}