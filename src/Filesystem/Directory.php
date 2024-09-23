<?php

namespace Md\PhpSpecIntelligenceExtension\Filesystem;

use Symfony\Component\Finder\Finder;

class Directory
{

    public function __construct(private string $path)
    {
    }

    public function getContents(): string
    {
        $finder = new Finder();
        $finder->files()->in($this->path)->name('*.php');

        $files = [];
        foreach ($finder as $file) {
            $files[] = new File($file->getRealPath());
        }
        return implode("", array_map(fn ($spec)
            => "Specfile:" . $spec->getPath() . $spec->getContents() . "\n\n"
        , $files));
    }
}