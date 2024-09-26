<?php

namespace Mizmoz\Container\Tests;

class ResolveBaseInterface
{
    public ResolveLoggerInterface $logger;

    public function __construct(ResolveLoggerInterface $resolveLogger)
    {
        $this->logger = $resolveLogger;
        $resolveLogger->log('Hurray!');
    }
}