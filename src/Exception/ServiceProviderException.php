<?php

namespace Mizmoz\Container\Exception;

use RuntimeException;
use Psr\Container\NotFoundExceptionInterface;

class ServiceProviderException extends RuntimeException implements NotFoundExceptionInterface
{
}