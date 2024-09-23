<?php

namespace Md\PhpSpecIntelligenceExtension\Locator;

use PhpSpec\Locator\Resource;

class FileResource implements Resource
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function getName(): string
    {
        return basename($this->path);
    }

    public function getSpecName(): string
    {
        return substr($this->getName(), 0, -4);
    }

    public function getSrcFilename(): string
    {
        return $this->path;
    }

    public function getSrcNamespace(): string
    {
        return str_replace(DIRECTORY_SEPARATOR, "\\", substr($this->path, 0, -4));
    }

    public function getSrcClassname(): string
    {
        return basename($this->path, '.php');
    }

    public function getSpecFilename(): string
    {
        return $this->getSpecName().'.php';
    }

    public function getSpecNamespace(): string
    {
        return $this->getSrcNamespace();
    }

    public function getSpecClassname(): string
    {
        return $this->getSpecName();
    }
}