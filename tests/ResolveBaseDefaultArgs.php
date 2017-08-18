<?php

namespace Mizmoz\Container\Tests;

class ResolveBaseDefaultArgs
{
    public $name;

    public function __construct(string $name = 'Bob')
    {
        $this->name = $name;
    }
}