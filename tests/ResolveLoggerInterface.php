<?php

namespace Mizmoz\Container\Tests;

interface ResolveLoggerInterface
{
    /**
     * Log a message
     *
     * @param string $message
     * @return void
     */
    public function log(string $message): void;
}