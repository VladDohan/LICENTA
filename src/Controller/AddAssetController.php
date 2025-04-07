<?php
// src/Controller/AddAssetController.php
namespace App\Controller;

use App\Service\CryptoApiService;
use App\Repository\AssetRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddAssetController extends AbstractController
{
    private $repoAsset;

    public function __construct(AssetRepository $repoAsset)
    {
        $this->repoAsset = $repoAsset;
    }

    #[Route('/add-asset', name: 'app_add_asset')]
    public function index(Request $request, CryptoApiService $cryptoApiSearch): Response
    {
        if ($request->isMethod('POST')) {
            $symbol = $request->request->get('symbol');
            $amount = $this->repoAsset->findOneBy(['symbol' => $symbol]);

            // Validate and process the form data here
            // For example, save the asset to the database

            // Redirect or render a success message
            return $this->redirectToRoute('app_add_asset');
        }
        return $this->render('exchange/exchange.html.twig', [
            'symbols' => $cryptoApiSearch->getAllCryptoSymbols(),
            'symbol' => $request->query->get('symbol', ''),

        ]);
    }
}