<?php

namespace Md\PhpSpecIntelligenceExtension\Console\Question;

use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class AcceptSuggestion
{

    public function __construct(private InputInterface $input, private OutputInterface $output)
    {
        $questionStyle = new OutputFormatterStyle('black', 'yellow');
        $this->output->getFormatter()->setStyle('question', $questionStyle);
    }

    public function ask(): bool
    {
        $this->output->writeln($this->getQuestionBox());
        $question = new QuestionHelper();
        $helper = new Question('');
        $answer = $question->ask($this->input, $this->output, $helper);

        return $answer === 'y' || $answer === 'Y' || $answer === null;
    }

    private function getQuestionBox(): array
    {
        $box = [];
        $box[] = '<question>' . str_repeat(' ', 60) . "</question>";
        $box[] = '<question>     Would you like me to generate this spec?               </question>';
        $box[] = '<question>' . str_repeat(' ', 55) . "</question> [Y/n]";
        return $box;
    }
}