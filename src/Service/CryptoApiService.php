<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Psr\Log\LoggerInterface;

class CryptoApiService
{
    private $httpClient;
    private $cache;
    private $logger;

    public function __construct(
        HttpClientInterface $httpClient,
        CacheInterface $cache,
        LoggerInterface $logger
    ) {
        $this->httpClient = $httpClient;
        $this->cache = $cache;
        $this->logger = $logger;
    }

    public function getAllCryptoSymbols(): array
    {
        return $this->cache->get('binance_symbols', function() {
            $response = $this->httpClient->request('GET', 'https://api.binance.com/api/v3/exchangeInfo');
            $data = $response->toArray();

            return array_map(function($symbol) {
                $baseAsset = $symbol['baseAsset'];
                return [
                    'symbol' => $symbol['symbol'],
                    'name' => $baseAsset,
                    'type' => 'crypto',
                    'image' => $this->getCryptoIconUrl($baseAsset)
                ];
            }, $data['symbols']);
        });
    }

    public function getCryptoPriceWithChange(string $symbol): array
    {
        $response = $this->httpClient->request('GET', 'https://api.binance.com/api/v3/ticker/24hr', [
            'query' => ['symbol' => $symbol]
        ]);

        $data = $response->toArray();
        $baseAsset = preg_replace('/USDT|BTC|ETH|USD$/i', '', $symbol);

        return [
            'price' => $data['lastPrice'],
            'change' => $data['priceChangePercent'],
            'type' => 'crypto',
            'image' => $this->getCryptoIconUrl($baseAsset)
        ];
    }

    PUBLIC function getCryptoIconUrl(string $symbol): string
    {
        $symbol = strtolower($symbol);
        $cacheKey = 'crypto_icon_'.$symbol;

        return $this->cache->get($cacheKey, function() use ($symbol) {
            // Ordered by reliability (first successful source will be used)
            $sources = [
                "https://raw.githubusercontent.com/spothq/cryptocurrency-icons/master/128/color/{$symbol}.png",
            ];

            foreach ($sources as $url) {
                if ($this->isImageAvailable($url)) {
                    return $url;
                }
            }

            return '/images/crypto/generic.png';
        });
    }

    private function isImageAvailable(string $url): bool
    {
        // Skip check for local files
        if (str_starts_with($url, '/')) {
            return true;
        }

        try {
            $response = $this->httpClient->request('HEAD', $url);
            return $response->getStatusCode() === 200;
        } catch (\Exception $e) {
            $this->logger->debug("Image not available: {$url} - {$e->getMessage()}");
            return false;
        }
    }
}