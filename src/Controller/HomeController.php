<?php

namespace App\Controller;


use App\Service\CryptoApiService;
use App\Service\StocksApiService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomeController extends AbstractController
{
    private $cryptoService;
    private $stocksService;

    public function __construct(
        CryptoApiService $cryptoService,
        StocksApiService $stocksService)
    {
        $this->cryptoService = $cryptoService;
        $this->stocksService = $stocksService;
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        // $cryptoSymbols = $this->cryptoService->getCryptoIconUrl('BTC');

        // dd($cryptoSymbols);die;

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            // 'crypto_symbols' => $cryptoSymbols,
        ]);
    }
}