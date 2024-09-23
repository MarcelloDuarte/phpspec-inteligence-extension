<?php

namespace Md\PhpSpecIntelligenceExtension\Intelligence;

use GuzzleHttp\Client;

class SuggestionGenerator
{

    public function __construct()
    {

    }

    public function generate($apiKey, $prompt): Suggestion
    {
        $client = new Client();
        $response = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $apiKey,
            ],
            'json' => [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
                'max_tokens' => 1000,
                'temperature' => 0.7,
            ],
        ]);

        $body = json_decode($response->getBody()->getContents(), true);

        $content = trim($body['choices'][0]['message']['content']);

        $response = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidSuggestionFormat('JSON Error: ' . json_last_error_msg());
        }

        return new Suggestion(
            $response['suggestion']['name'],
            $response['suggestion']['arguments'],
            $response['suggestion']['body'],
            $response['suggestion']['path']
        );
    }
}