<?php

namespace Mizmoz\Container\Tests;

class ResolveBase
{
    public $logger;

    public function __construct(ResolveLogger $resolveLogger)
    {
        $this->logger = $resolveLogger;
        $resolveLogger->log('Hurray!');
    }
}