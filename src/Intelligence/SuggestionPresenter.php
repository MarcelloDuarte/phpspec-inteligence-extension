<?php

namespace Md\PhpSpecIntelligenceExtension\Intelligence;

use PhpSpec\CodeGenerator\TemplateRenderer;
use Symfony\Component\Console\Output\OutputInterface;

class SuggestionPresenter
{

    public function __construct(private OutputInterface $output, private TemplateRenderer $renderer)
    {
        $this->renderer->setLocations([__DIR__ . '/../CodeGenerator/templates']);
    }

    public function present(Suggestion $suggestion): void
    {
        $code = $this->renderer->render('example', [
            '%name%' => $suggestion->getName(),
            '%arguments%' => $suggestion->getArguments(),
            '%body%' => $suggestion->getBody()
        ]);

        $this->output->writeln('');
        $this->output->writeln($code);
        $this->output->writeln('');
    }
}