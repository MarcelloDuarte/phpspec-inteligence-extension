<?php

namespace Md\PhpSpecIntelligenceExtension\Intelligence;

use GuzzleHttp\Client;

class SuggestionGenerator
{

    public function __construct(
        private string $apiKey,
        private string $model,
        private float $temperature,
        private int $maxTokens
    )
    {

    }

    public function generate(string $prompt): Suggestion
    {
        $client = new Client();
        $response = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->apiKey,
            ],
            'json' => [
                'model' => $this->model,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
                'max_tokens' => $this->maxTokens,
                'temperature' => $this->temperature,
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