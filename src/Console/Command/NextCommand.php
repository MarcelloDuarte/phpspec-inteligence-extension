<?php

namespace Md\PhpSpecIntelligenceExtension\Console\Command;

use Md\PhpSpecIntelligenceExtension\CodeGenerator\MethodGenerator;
use Md\PhpSpecIntelligenceExtension\Console\Question\AcceptSuggestion;
use Md\PhpSpecIntelligenceExtension\Filesystem\Directory;
use Md\PhpSpecIntelligenceExtension\Intelligence\InvalidSuggestionFormat;
use Md\PhpSpecIntelligenceExtension\Intelligence\Suggestion;
use Md\PhpSpecIntelligenceExtension\Intelligence\SuggestionGenerator;
use Md\PhpSpecIntelligenceExtension\Intelligence\SuggestionPresenter;
use Md\PhpSpecIntelligenceExtension\Locator\FileResource;
use PhpSpec\CodeGenerator\TemplateRenderer;
use PhpSpec\ServiceContainer\IndexedServiceContainer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NextCommand extends Command
{
    private array $config;
    private IndexedServiceContainer $container;
    private TemplateRenderer $renderer;

    public function __construct(array $config)
    {
        parent::__construct();
        $this->config = $config;
    }

    protected function configure(): void
    {
        $this->setName('next')
            ->setDescription('Suggests the next specification to implement using AI.')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command suggests the next specification to implement using AI.

  <info>php %command.full_name%</info>

It suggests the next specification to implement based on the existing specifications in the given file.
EOT);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->config['openai_api_key']) {
            $output->writeln('<error>No OpenAI API key provided.</error>');
            return Command::FAILURE;
        }

        try {
            $this->setupContainer();

            $suggestion = $this->getNextSuggestion();
            $this->showSuggestion($suggestion, $output);

            if($this->acceptSuggestion($input, $output)) {
                $this->generateExample($suggestion);
                $output->writeln('<info>Spec generated.</info>');
            }

            return Command::SUCCESS;
        } catch (InvalidSuggestionFormat $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        } catch (\Exception $e) {
            $output->writeln('<error>Failed to get a suggestion.</error>');
            return Command::FAILURE;
        }
    }

    private function acceptSuggestion(InputInterface $input, OutputInterface $output): bool
    {
        $acceptSuggestion = new AcceptSuggestion($input, $output);
        return $acceptSuggestion->ask();
    }

    private function generateExample(Suggestion $suggestion): void
    {
        $resource = new FileResource($suggestion->getPath());

        $generator = new MethodGenerator($this->renderer, $resource, $this->container->get('code_generator.writers.tokenized'));
        $generator->generate($suggestion);
    }

    private function getExistingSpecs(): string
    {
        $specs = new Directory('spec');
        return $specs->getContents();
    }

    /**
     * @throws InvalidSuggestionFormat
     */
    private function getNextSuggestion(): Suggestion
    {
        $existingSpecs = $this->getExistingSpecs();

        $prompt = $this->getPrompt($existingSpecs);

        $suggestionGenerator = new SuggestionGenerator(
            $this->config['openai_api_key'],
            $this->config['openai_api_model'],
            $this->config['openai_api_temperature'],
            $this->config['openai_api_max_tokens']
        );
        return $suggestionGenerator->generate($prompt);
    }

    private function getPrompt(string $existingSpecs): string
    {
        return <<<EOT
You are an assistant that helps with Test-Driven Development in PHP using PhpSpec. Given the existing specifications:

{$existingSpecs}
Respond in json format with the suggested single next spec code to implement. Do not include markdown.
New line characters should be replaced with __NEWLINE__.
Do not modify the path, just return what is given in the Specfile field.

if your suggestion is for the spec/Acme/MarkdownSpec.php and the code is:
    function it_converts_text_from_an_external_source(\Acme\Reader \$reader)
    {
        \$reader->getMarkdown()->willReturn('Hi, there');
        \$this->toHtml(\$reader)->shouldReturn('<p>Hi, there</p>');
    }

Your response will be:
{
    "suggestion": {
        "path": "spec/Acme/MarkdownSpec.php",
        "name": "it_converts_text_from_an_external_source",
        "arguments": "\\Acme\\Reader \$reader",
        "body": "        \$reader->getMarkdown()->willReturn('Hi, there');__NEWLINE__        \$this->toHtml(\$reader)->shouldReturn('<p>Hi, there</p>');"
    }
}

EOT;
    }

    private function setupContainer(): void
    {
        $this->container = $this->getApplication()->getContainer();
        $this->renderer = $this->container->get('code_generator.templates');
    }

    private function showSuggestion(Suggestion $suggestion, OutputInterface $output): void
    {
        $suggestionPresenter = new SuggestionPresenter($output, $this->renderer);
        $output->writeln('<info>Suggested Spec:</info>');
        $suggestionPresenter->present($suggestion);
    }
}
