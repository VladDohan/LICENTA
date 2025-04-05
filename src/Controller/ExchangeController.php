<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ExchangeController extends AbstractController
{
    #[Route('/Exchange', name: 'app_Exchange')]
    public function index(): Response
    {
        return $this->render('Exchange/Exchange.html.twig', [
            'controller_name' => 'ExchangeController',
        ]);
    }
}
