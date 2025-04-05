<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WalletController extends AbstractController
{
    #[Route('/wallet', name: 'app_wallet')]
    public function index(): Response
    {
        return $this->render('wallet/wallet.html.twig', [
            'controller_name' => 'WalletController',
        ]);
    }
}
