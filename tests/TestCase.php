<?php namespace Mizmoz\Container\Tests;

use Mockery;

class TestCase extends \PHPUnit\Framework\TestCase
{
    public function tearDown(): void
    {
        if ($container = Mockery::getContainer()) {
            $this->addToAssertionCount($container->mockery_getExpectationCount());
        }

        Mockery::close();
    }
}