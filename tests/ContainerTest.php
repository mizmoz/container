<?php

namespace Mizmoz\Container\Tests;

use DateTime;
use Mizmoz\Container\Container;
use Mizmoz\Container\Contract\ContainerInterface;
use Mizmoz\Container\Exception\InvalidEntryException;
use Mizmoz\Container\Exception\NotFoundException;
use Mizmoz\Container\Exception\ServiceProviderException;
use Mizmoz\Container\ServiceProviderAbstract;

class ContainerTest extends TestCase
{
    /**
     * Create a call back
     *
     * @param $returnValue
     * @return \Closure
     */
    private function getCallable($returnValue): \Closure
    {
        return function () use ($returnValue) {
            return $returnValue;
        };
    }

    public function testContainerAddAndGet(): void
    {
        $container = new Container();
        $container->add('test', $this->getCallable('beep'));
        $this->assertEquals('beep', $container->get('test'));
    }

    public function testContainerClassNameResolution(): void
    {
        $container = new Container();
        $container->add('logger', ResolveLogger::class);
        $this->assertInstanceOf(ResolveLogger::class, $container->get('logger'));
    }

    public function testContainerAddNonCallable(): void
    {
        // should throw an exception when we try to add a non-callable item
        $this->expectException(InvalidEntryException::class);

        // get none set item
        (new Container())->add('test', 1);
    }

    public function testContainerGetNotFound(): void
    {
        // should throw an exception when we try to get an invalid item
        $this->expectException(NotFoundException::class);

        // get none set item
        (new Container())->get('test');
    }

    public function testContainerHas(): void
    {
        // get none set item
        $container = new Container();
        $container->add('a', $this->getCallable('hello'));
        $container->add('b', $this->getCallable('hello'));

        $this->assertTrue($container->has('a'));
        $this->assertTrue($container->has('b'));
        $this->assertFalse($container->has('A'));
        $this->assertFalse($container->has('c'));
        $this->assertFalse($container->has('d'));
    }

    public function testGetShared(): void
    {
        $container = new Container();
        $container->addShared('date', function () {
            return new DateTime();
        });

        $this->assertEquals($container->get('date'), $container->get('date'));
    }


    public function testAddServiceProvider(): void
    {
        $container = new Container();
        $container->addServiceProvider(new class extends ServiceProviderAbstract {
            /**
             * @inheritDoc
             */
            public function provides(): array
            {
                return [
                    'test',
                ];
            }

            /**
             * @inheritDoc
             */
            public function register(ContainerInterface $container): void
            {
                $container->add('test', function () {
                    return 'cheese';
                });
            }
        });

        $this->assertEquals('cheese', $container->get('test'));
    }

    /**
 * Adding a service provider that doesn't end up providing what it said it would should fail gracefully
 */
    public function testAddBrokenServiceProvider(): void
    {
        $container = new Container();
        $container->addServiceProvider(new class extends ServiceProviderAbstract {
            /**
             * @inheritDoc
             */
            public function provides(): array
            {
                return [
                    'test',
                ];
            }

            /**
             * @inheritDoc
             */
            public function register(ContainerInterface $container): void
            {
                $container->add('tests', function () {
                    return 'cheese';
                });
            }
        });

        // we should throw an exception if a service provider lies about what it actually is providing
        $this->expectException(ServiceProviderException::class);

        $this->assertEquals('cheese', $container->get('test'));
    }

    /**
     * Adding a service provider that doesn't end up providing what it said it would should fail gracefully
     * Same test as above but with the broken bits in the other way around
     */
    public function testAddBrokenServiceProviderPartDeux(): void
    {
        $container = new Container();
        $container->addServiceProvider(new class extends ServiceProviderAbstract {
            /**
             * @inheritDoc
             */
            public function provides(): array
            {
                return [
                    'tests',
                ];
            }

            /**
             * @inheritDoc
             */
            public function register(ContainerInterface $container): void
            {
                $container->add('test', function () {
                    return 'cheese';
                });
            }
        });

        // we should throw an exception if a service provider lies about what it actually is providing
        $this->expectException(NotFoundException::class);

        $this->assertEquals('cheese', $container->get('test'));
    }

    public function testAddAlias(): void
    {
        // get none set item
        $container = new Container();
        $container->add('helloMethod', $this->getCallable('hello'));
        $container->addAlias('helloMethod', 'sayIt');
        $this->assertEquals('hello', $container->get('sayIt'));
    }
    
    public function testAddValue(): void
    {
        $container = new Container();
        $container->addValue('name', 'bob');
        $this->assertEquals('bob', $container->get('name'));
    }

    public function testManageContainerTrait(): void
    {
        // container should be passed to any object being instantiated in the container
        $container = new Container();
        $container->add(ResolveNoConstructor::class, function () {
            return new ResolveNoConstructor();
        });

        $resolved = $container->get(ResolveNoConstructor::class);
        $this->assertInstanceOf(ResolveNoConstructor::class, $resolved);
        $this->assertInstanceOf(ContainerInterface::class, $resolved->getAppContainer());
    }
}