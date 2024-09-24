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
            $config = [
                'openai_api_key' => $params['openai_api_key'] ?? null,
                'openai_api_model' => $params['openai_api_model'] ?? 'gpt-3.5-turbo',
                'openai_api_temperature' => $params['openai_api_temperature'] ?? 0.7,
                'openai_api_max_tokens' => $params['openai_api_max_tokens'] ?? 256
            ];
            return new NextCommand($config);
        }, ['console.commands']);
    }
}