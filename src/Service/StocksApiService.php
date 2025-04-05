<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Cache\CacheInterface;

class StocksApiService
{
    private $httpClient;
    private $cache;
    private $apiKey;

    public function __construct(
        HttpClientInterface $httpClient,
        CacheInterface $cache,
        // string $apiKey
    )
    {
        $this->httpClient = $httpClient;
        $this->cache = $cache;
        // $this->apiKey = $apiKey;
    }

    public function getAllStockSymbols(): array
    {
        // replace the "demo" apikey below with your own key from https://www.alphavantage.co/support/#api-key
$json = file_get_contents('https://www.alphavantage.co/query?function=TIME_SERIES_INTRADAY&symbol=IBM&interval=5min&apikey=SH6KFU7H58SQU1YW');

$data = json_decode($json,true);

print_r($data);
dd(1);
        return $this->cache->get('alpha_stock_symbols', function() {
            $response = $this->httpClient->request('GET', 'https://www.alphavantage.co/query', [
                'query' => [
                    'function' => 'LISTING_STATUS',
                    'apikey' => 'SH6KFU7H58SQU1YW',
                    'datatype' => 'json'
                ]
            ]);
            $csv = $response->toArray();
            dd($csv);
            $lines = explode("\n", $csv);
            $symbols = [];

            foreach ($lines as $line) {
                $data = str_getcsv($line);
                if ($data[0] !== 'symbol') {
                    $symbols[] = [
                        'symbol' => $data[0],
                        'name' => $data[1],
                        'type' => 'stock',
                        'image' => '/images/stock-icon.png'
                    ];
                }
            }

            return $symbols;
        });
    }

    public function getStockPriceWithChange(string $symbol): array
    {
        $response = $this->httpClient->request('GET', 'https://www.alphavantage.co/query', [
            'query' => [
                'function' => 'GLOBAL_QUOTE',
                'symbol' => $symbol,
                'apikey' => $this->apiKey
            ]
        ]);

        $data = $response->toArray();
        $quote = $data['Global Quote'];

        return [
            'price' => $quote['05. price'],
            'change' => (float)$quote['10. change percent'],
            'type' => 'stock'
        ];
    }
}