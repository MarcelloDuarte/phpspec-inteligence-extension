<?php

namespace Md\PhpSpecIntelligenceExtension;

use Md\PhpSpecIntelligenceExtension\Console\Command\NextCommand;
use PhpSpec\Extension as PhpSpecExtension;
use PhpSpec\ServiceContainer;

class Extension implements PhpSpecExtension
{

    public function load(ServiceContainer $container, array $params)
    {
        $container->define('console.commands.next', function ($c) use ($params) {
            $apiKey = $params['openai_api_key'] ?? null;
            return new NextCommand($apiKey);
        }, ['console.commands']);
    }
}