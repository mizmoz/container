<?php

namespace Mizmoz\Container\Tests;

class ResolveBaseInterface
{
    public $logger;

    public function __construct(ResolveLoggerInterface $resolveLogger)
    {
        $this->logger = $resolveLogger;
        $resolveLogger->log('Hurray!');
    }
}