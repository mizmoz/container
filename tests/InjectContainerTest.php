<?php

namespace Mizmoz\Container\Tests;

use Mizmoz\Container\Container;
use Mizmoz\Container\Contract\ContainerInterface;
use Mizmoz\Container\InjectContainer;
use Mizmoz\Container\ManageContainerTrait;

class InjectContainerTest extends TestCase
{
    private function getContainer(): ContainerInterface
    {
        return new Container();
    }

    public function testSimpleInjection()
    {
        $simple = new class ()
        {
            use ManageContainerTrait;
        };

        // inject the container
        $simple = InjectContainer::inject($this->getContainer(), $simple);

        // check we have the container now
        $this->assertInstanceOf(ContainerInterface::class, $simple->getAppContainer());
    }

    public function testInjectionAfterSetAppContainerIsCalled()
    {
        $simple = new class ()
        {
            use ManageContainerTrait;

            public $called = false;

            /**
             * @inheritDoc
             */
            public function afterSetAppContainer()
            {
                $this->called = true;
            }
        };

        // inject the container
        $simple = InjectContainer::inject($this->getContainer(), $simple);

        // make sure we called the method
        $this->assertTrue($simple->called);
    }

    /**
     * Check that we can resolve the container when it's included in some other part of the class (child trait etc).
     */
    public function testResolveInScope()
    {
        $simple = new class ()
        {
            use ManageTestTrait;
        };

        // inject the container
        $simple = InjectContainer::inject($this->getContainer(), $simple);

        // check we have the container now
        $this->assertInstanceOf(ContainerInterface::class, $simple->getAppContainer());
    }

    /**
     * Check that we can resolve the container when it's included in some other part of the class (child trait etc).
     */
    public function testResolveInExtended()
    {
        $simple = new class () extends InjectBaseAbstract
        {
        };

        // inject the container
        $simple = InjectContainer::inject($this->getContainer(), $simple);

        // check we have the container now
        $this->assertInstanceOf(ContainerInterface::class, $simple->getAppContainer());
    }
}