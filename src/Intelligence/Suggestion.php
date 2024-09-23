<?php

namespace Md\PhpSpecIntelligenceExtension\Intelligence;

class Suggestion
{

    private string $name;
    private string $arguments;
    private string $body;
    private string $path;

    public function __construct(string $name, string $arguments, string $body, string $path)
    {
        $this->name = $name;
        $this->arguments = $arguments;
        $this->body = $body;
        $this->path = $path;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getArguments(): string
    {
        return $this->arguments;
    }

    public function getBody(): string
    {
        return str_replace("__NEWLINE__", "\n", $this->body);
    }

    public function getPath(): string
    {
        return $this->path;
    }
}