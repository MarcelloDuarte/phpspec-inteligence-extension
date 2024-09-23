<?php

namespace Md\PhpSpecIntelligenceExtension\CodeGenerator;

use Md\PhpSpecIntelligenceExtension\Intelligence\Suggestion;
use Md\PhpSpecIntelligenceExtension\Locator\FileResource;
use PhpSpec\CodeGenerator\TemplateRenderer;
use PhpSpec\CodeGenerator\Writer\CodeWriter;

class MethodGenerator
{
    private TemplateRenderer $renderer;
    private FileResource $resource;
    private CodeWriter $codeWriter;

    public function __construct(TemplateRenderer $renderer, FileResource $resource, CodeWriter $codeWriter)
    {
        $this->renderer = $renderer;
        $this->resource = $resource;
        $this->codeWriter = $codeWriter;
    }

    public function generate(Suggestion $suggestion): void
    {
        $class = $this->resource->getSrcClassname();

        $method = $this->renderer->render('example', [
            '%name%' => $suggestion->getName(),
            '%arguments%' => $suggestion->getArguments(),
            '%body%' => $suggestion->getBody()
        ]);

        $code = file_get_contents($this->resource->getSrcFilename());
        $new = $this->codeWriter->insertMethodLastInClass($code, $method);

        file_put_contents($this->resource->getSrcFilename(), $new);
    }
}