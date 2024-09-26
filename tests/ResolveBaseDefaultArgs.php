<?php

namespace Mizmoz\Container\Tests;

class ResolveBaseDefaultArgs
{
    public string $name;

    public function __construct(string $name = 'Bob')
    {
        $this->name = $name;
    }
}