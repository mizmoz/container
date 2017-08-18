<?php

namespace Mizmoz\Container\Exception;

use Psr\Container\NotFoundExceptionInterface;

class FatalNotFoundException extends NotFoundException implements NotFoundExceptionInterface
{
}