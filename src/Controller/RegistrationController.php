<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            // Send confirmation email
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('vladdohan9@gmail.com', 'Licenta Vlad'))
                ->to($user->getEmail())
                ->subject('Confirm Your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
                ->context([
                    'userId' => $user->getId(),
                ])
        );

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, EntityManagerInterface $entityManager): Response
    {
        try {

            $userId = $request->query->get('id');
            if (!$userId) {
                throw $this->createNotFoundException('Missing user ID');
            }

            $user = $entityManager->getRepository(User::class)->find($userId);
            if (!$user) {
                throw $this->createNotFoundException('User not found');
            }

            $this->emailVerifier->handleEmailConfirmation($request, $user);

            $this->addFlash('success', 'Email verified successfully!');
            return $this->redirectToRoute('app_home');

        } catch (VerifyEmailExceptionInterface $e) {
            $this->addFlash('error', sprintf(
                'Verification failed: %s (Code: %s)',
                $e->getReason(),
                $e->getCode()
            ));
            return $this->redirectToRoute('app_register');
        }
    }
}