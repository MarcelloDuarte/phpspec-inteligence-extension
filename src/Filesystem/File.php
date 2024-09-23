<?php

namespace Md\PhpSpecIntelligenceExtension\Filesystem;

class File
{
    public function __construct(private string $path)
    {
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getContents(): string
    {
        return file_get_contents($this->path);
    }
}