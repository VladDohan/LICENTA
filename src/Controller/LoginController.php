<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login', methods: ['GET'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Redirectează utilizatorii autentificați
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('login/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    #[Route(path: '/login/check', name: 'app_login_check', methods: ['POST'])]
    public function loginCheck(): void
    {
        // Acest cod NU se va executa niciodată
        // Symfony interceptează request-ul înainte de a ajunge aici
        throw new \LogicException('Această rută este gestionată automat de sistemul de securitate.');
    }

    #[Route(path: '/logout', name: 'app_logout', methods: ['GET'])]
    public function logout(): void
    {
        throw new \LogicException('Această metodă va fi interceptată de sistemul de logout.');
    }
}