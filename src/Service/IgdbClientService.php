<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class IgdbClientService
{
    private HttpClientInterface $httpClient;
    private CacheInterface $cache;
    private string $clientId;
    private string $clientSecret;

    public function __construct(
        HttpClientInterface $httpClient,
        CacheInterface $cache,
        string $clientId,
        string $clientSecret
    ) {
        $this->httpClient = $httpClient;
        $this->cache = $cache;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    /**
     * Récupère un token IGDB en cache (ou le régénère si expiré)
     */
    private function getToken(): string
    {
        return $this->cache->get('igdbToken', function (ItemInterface $item) {
            $response = $this->httpClient->request('POST', 'https://id.twitch.tv/oauth2/token', [
                'query' => [
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'grant_type' => 'client_credentials',
                ],
            ]);
            $data = $response->toArray();
            $item->expiresAfter($data['expires_in']);

            return $data['access_token'];
        });
    }

    /**
     * Fait une requête vers IGDB
     */
    public function request(string $endpoint, string $body): array
    {
        $token = $this->getToken();

        $response = $this->httpClient->request('POST', "https://api.igdb.com/v4/{$endpoint}", [
            'headers' => [
                'Client-ID'     => $this->clientId,
                'Authorization' => "Bearer {$token}",
            ],
            'body' => $body,
        ]);

        return $response->toArray();
    }
}
