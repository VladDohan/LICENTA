<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AssetsController extends AbstractController
{
    #[Route('/assets', name: 'app_assets')]
    public function index(): Response
    {
        return $this->render('assets/assets.html.twig', [
            'controller_name' => 'assetsController',
        ]);
    }
}
