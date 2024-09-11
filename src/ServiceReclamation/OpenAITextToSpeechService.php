<?php
//
//namespace App\Service;
//
//use GuzzleHttp\Client;
//
//class OpenAITextToSpeechService
//{
//    private $apiKey;
//    private $client;
//
//    public function __construct(string $apiKey)
//    {
//        $this->apiKey = $apiKey;
//        $this->client = new Client([
//            'base_uri' => 'https://api.openai.com',
//            'headers' => [
//                'Authorization' => 'Bearer ' . $this->apiKey,
//                'Content-Type' => 'application/json',
//            ],
//        ]);
//    }
//
//    public function convertTextToSpeech(string $text): ?string
//    {
//
//            // Journal pour afficher le texte envoyé à l'API
//            var_dump($text);
//
//            $response = $this->client->post('/v1/engines/tts-1/completions', [
//                'json' => [
//                    'prompt' => $text,
//                    'max_tokens' => 150,
//                    'voice' => 'alloy',
//                ],
//            ]);
//
//            // Journal pour afficher la réponse de l'API
//            $data = json_decode($response->getBody(), true);
//            var_dump($data);
//
//            return $data['choices'][0]['audio'];
//
//    }
//}