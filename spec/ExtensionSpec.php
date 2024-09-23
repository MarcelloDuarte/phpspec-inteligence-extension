<?php

namespace spec\Md\PhpSpecIntelligenceExtension;

use PhpSpec\ObjectBehavior;
use PhpSpec\ServiceContainer;
use Prophecy\Argument;

class ExtensionSpec extends ObjectBehavior
{
    function let(ServiceContainer $container)
    {
        $container->define(Argument::cetera());
    }

    function it_is_a_phpspec_extension()
    {
        $this->shouldHaveType('PhpSpec\Extension');
    }

    function it_registers_the_next_command(ServiceContainer $container)
    {
        $container->define(
            'console.commands.next',
            Argument::type('Closure'),
            Argument::type('array')
        )->shouldBeCalled();

        $this->load($container, []);
    }
}