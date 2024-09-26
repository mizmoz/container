<?php

namespace Mizmoz\Container\Tests;

class ResolveLogger implements ResolveLoggerInterface
{
    public function __construct()
    {
        // so we can test resolving with constructor and no arguments
    }

    /**
     * @inheritdoc
     */
    public function log(string $message): void
    {
        // write the log to something!
    }
}