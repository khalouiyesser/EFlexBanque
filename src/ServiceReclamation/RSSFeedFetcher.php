<?php
namespace App\ServiceReclamation;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class RSSFeedFetcher
{
    private $client;
    private $feedUrl;

    public function __construct(HttpClientInterface $client, string $feedUrl)
    {
        $this->client = $client;
        $this->feedUrl = $feedUrl;
    }

    public function fetchFeed(): array
    {
        $response = $this->client->request('GET', $this->feedUrl);
//        dd($response);
        $content = $response->toArray();

        return $content['items'];
    }
}
