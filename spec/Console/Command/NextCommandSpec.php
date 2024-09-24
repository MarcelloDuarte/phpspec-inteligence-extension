<?php

namespace spec\Md\PhpSpecIntelligenceExtension\Console\Command;

use Md\PhpSpecIntelligenceExtension\Console\Command\NextCommand;
use PhpSpec\ObjectBehavior;
use PhpSpec\Wrapper\Collaborator;
use Prophecy\Argument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NextCommandSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith([
            'openai_api_key' => 'YOUR_OPENAI_API_KEY'
        ]);
    }
    function it_is_initializable()
    {
        $this->shouldHaveType(NextCommand::class);
    }

    function it_is_a_symfony_console_command()
    {
        $this->shouldHaveType('Symfony\Component\Console\Command\Command');
    }

    function it_errors_without_api_key(InputInterface $input, OutputInterface $output)
    {
        $this->stubInputSetup($input);

        $this->beConstructedWith([
            'openai_api_key' => null
        ]);
        $this->run($input, $output)->shouldReturn(Command::FAILURE);

        $output->writeln('<error>No OpenAI API key provided.</error>')
            ->shouldHaveBeenCalled();
    }

    private function stubInputSetup(Collaborator|InputInterface $input): void
    {
        $input->isInteractive()->willReturn(false);
        $input->hasArgument(Argument::any())->willReturn(false);
        $input->bind(Argument::any())->shouldBeCalled();
        $input->validate(Argument::any())->shouldBeCalled();
    }
}
