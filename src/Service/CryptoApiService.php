<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\CacheItem;

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

    public function searchCryptoSymbols(string $query): array
    {
        $symbols = $this->getAllCryptoSymbols();
        
        if (empty(trim($query))) {
            return $symbols;
        }

        $query = strtoupper(trim($query));
        
        return array_filter($symbols, function($symbol) use ($query) {
            return str_starts_with($symbol['symbol'], $query) ||
                   str_contains(strtoupper($symbol['name']), $query);
        });
    }
    public function getAllCryptoSymbols(): array
    {
        return $this->cache->get('binance_symbols', function(CacheItem $item) {
            $item->expiresAfter(3600); // 1 hour cache

            try {
                $response = $this->httpClient->request('GET', 'https://api.binance.com/api/v3/exchangeInfo');
                $data = $response->toArray();

                $symbols = [];
                foreach ($data['symbols'] as $symbolData) {
                    if (str_ends_with($symbolData['symbol'], 'USDT')) {
                        $symbols[] = [
                            'symbol' => $symbolData['symbol'],
                            'name' => $symbolData['baseAsset'],
                            'type' => 'crypto',
                            // 'image' => $this->getCryptoIconUrl($symbolData['baseAsset'])
                        ];
                    }
                }

                return $symbols;

            } catch (\Exception $e) {
                $this->logger->error('Failed to fetch symbols: '.$e->getMessage());
                return [];
            }
        });
    }

    public function getCryptoPriceWithChange(string $symbol): array
    {
        return $this->cache->get('crypto_price_'.md5($symbol), function(CacheItem $item) use ($symbol) {
            $item->expiresAfter(60); // 1 minute cache

            try {
                $response = $this->httpClient->request('GET', 'https://api.binance.com/api/v3/ticker/24hr', [
                    'query' => ['symbol' => $symbol],
                    'timeout' => 5
                ]);

                $data = $response->toArray();

                if (!isset($data['lastPrice'])) {
                    throw new \RuntimeException('Invalid API response');
                }

                return [
                    'price' => (float)$data['lastPrice'],
                    'change' => (float)$data['priceChangePercent'],
                    'type' => 'crypto',
                    'image' => $this->getCryptoIconUrl(
                        $this->extractBaseAsset($symbol)
                    )
                ];

            } catch (\Exception $e) {
                $this->logger->error("Price fetch failed for {$symbol}: ".$e->getMessage());
                throw $e;
            }
        });
    }

    public function getCryptoIconUrl(string $symbol): string
    {
        $symbol = strtolower($symbol);
        $cacheKey = 'crypto_icon_'.$symbol;

        return $this->cache->get($cacheKey, function(CacheItem $item) use ($symbol) {
            $item->expiresAfter(86400); // 24 hours

            $sources = [
                "https://cdn.jsdelivr.net/gh/atomiclabs/cryptocurrency-icons@1.1.2/128/color/{$symbol}.png",
                "https://cryptoicon-api.vercel.app/api/icon/{$symbol}",
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

    private function extractBaseAsset(string $symbol): string
    {
        $symbols = $this->getAllCryptoSymbols();
        foreach ($symbols as $s) {
            if ($s['symbol'] === $symbol) {
                return $s['name'];
            }
        }
        return preg_replace('/USDT$/', '', $symbol);
    }

    private function isImageAvailable(string $url): bool
    {
        if (str_starts_with($url, '/')) {
            return true;
        }

        try {
            $response = $this->httpClient->request(
                'GET',
                $url,
                ['timeout' => 2]
            );
            return $response->getStatusCode() === 200;
        } catch (\Exception $e) {
            $this->logger->debug("Image unavailable: {$url}");
            return false;
        }
    }
}