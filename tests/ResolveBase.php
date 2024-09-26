<?php

namespace Mizmoz\Container\Tests;

class ResolveBase
{
    public ResolveLogger $logger;

    public function __construct(ResolveLogger $resolveLogger)
    {
        $this->logger = $resolveLogger;
        $resolveLogger->log('Hurray!');
    }
}