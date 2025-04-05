<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TransactionController extends AbstractController
{
    #[Route('/transactions', name: 'app_transaction')]
    public function index(): Response
    {
        return $this->render('transaction/transaction.html.twig', [
            'controller_name' => 'transactionController',
        ]);
    }
}
