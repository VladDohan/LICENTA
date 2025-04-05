<?php

namespace App\Controller\Market;

use App\Service\CryptoApiService;
use App\Service\StocksApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MarketController extends AbstractController
{
    #[Route('/api/search', name: 'market_search')]
    public function search(
        Request $request,
        CryptoApiService $binance,
        StocksApiService $alpha
    ): JsonResponse {
        $query = strtoupper($request->query->get('q', ''));

        $cryptoResults = $this->filterResults($binance->getAllCryptoSymbols(), $query);
        $stockResults = $this->filterResults($alpha->getAllStockSymbols(), $query);

        $results = array_merge($cryptoResults, $stockResults);

        return $this->json(['results' => $results]);
    }

    #[Route('/api/price/{symbol}', name: 'market_price')]
    public function getPrice(
        string $symbol,
        CryptoApiService $binance,
        StocksApiService $alpha
    ): JsonResponse {
        if (str_contains($symbol, 'USD')) {
            $data = $binance->getCryptoPriceWithChange($symbol);
        } else {
            $data = $alpha->getStockPriceWithChange($symbol);
        }

        return $this->json($data);
    }

    private function filterResults(array $symbols, string $query): array
    {
        return array_filter($symbols, function($item) use ($query) {
            return strpos($item['symbol'], $query) === 0 ||
                   stripos($item['name'], $query) !== false;
        });
    }
}