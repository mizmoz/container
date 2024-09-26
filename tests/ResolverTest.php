<?php

namespace Mizmoz\Container\Tests;

use Mockery;
use Mizmoz\Container\Container;
use Mizmoz\Container\Exception\ResolutionFailedException;
use Mizmoz\Container\Resolver;

class ResolverTest extends TestCase
{
    public function testResolveByConcreteClassWithoutConstructor(): void
    {
        $container = new Container(new Resolver());
        $this->assertInstanceOf(ResolveNoConstructor::class, $container->get(ResolveNoConstructor::class));
    }

    public function testResolveByConcreteClass(): void
    {
        $container = new Container(new Resolver());
        $this->assertInstanceOf(ResolveLogger::class, $container->get(ResolveLogger::class));
    }

    public function testResolveByConcreteClassWithDefaultArgs(): void
    {
        $container = new Container(new Resolver());
        $resolved = $container->get(ResolveBaseDefaultArgs::class);
        $this->assertInstanceOf(ResolveBaseDefaultArgs::class, $resolved);
        $this->assertEquals('Bob', $resolved->name);
    }

    public function testResolveByConcreteClassWithConstructorClass(): void
    {
        $container = new Container(new Resolver());
        $resolveBase = $container->get(ResolveBase::class);
        $this->assertInstanceOf(ResolveBase::class, $resolveBase);
        $this->assertInstanceOf(ResolveLogger::class, $resolveBase->logger);
    }

    public function testResolveByInterfaceClassWithConstructorClass(): void
    {
        $container = new Container(new Resolver());
        $container->add(ResolveLoggerInterface::class, function () {
            return new ResolveLogger();
        });

        $resolveBase = $container->get(ResolveBaseInterface::class);
        $this->assertInstanceOf(ResolveBaseInterface::class, $resolveBase);
        $this->assertInstanceOf(ResolveLogger::class, $resolveBase->logger);
    }

    public function testResolveByInterfaceClassWithConstructorClassFail(): void
    {
        $this->expectException(ResolutionFailedException::class);
        (new Container(new Resolver()))->get(ResolveBaseInterface::class);
    }

    public function testResolveAddedClass(): void
    {
        $container = new Container(new Resolver());
        $container->add('log', ResolveBase::class);
        $this->assertInstanceOf(ResolveBase::class, $container->get('log'));
        $this->assertInstanceOf(ResolveLogger::class, $container->get('log')->logger);
    }

    public function testResolveIsCalledForEachGet(): void
    {
        $resolver = Mockery::mock(Resolver::class);
        $resolver->shouldReceive('resolve')
            ->times(3)
            ->andReturn(new ResolveNoConstructor);

        $container = new Container($resolver);
        $container->get(ResolveNoConstructor::class);
        $container->get(ResolveNoConstructor::class);
        $container->get(ResolveNoConstructor::class);
    }
}