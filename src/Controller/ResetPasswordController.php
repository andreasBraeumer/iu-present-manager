<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class ResetPasswordController extends AbstractController
{
    #[Route('/passwort-vergessen', name: 'app_forgot_password_request', methods: ['GET', 'POST'])]
    public function request(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $resetLink = null;
        $notFound = false;

        if ($request->isMethod('POST') && $this->isCsrfTokenValid('forgot_password', (string) $request->request->get('_token'))) {
            $username = (string) $request->request->get('username');
            $user = $userRepository->findOneBy(['username' => $username]);

            if (null === $user) {
                $notFound = true;
            } else {
                $user->setResetToken(bin2hex(random_bytes(32)));
                $user->setResetTokenExpiresAt((new \DateTimeImmutable())->modify('+1 hour'));

                $entityManager->flush();

                $resetLink = $this->generateUrl('app_reset_password', ['token' => $user->getResetToken()]);
            }
        }

        return $this->render('security/forgot_password_request.html.twig', [
            'reset_link' => $resetLink,
            'not_found' => $notFound,
        ]);
    }

    #[Route('/passwort-vergessen/{token}', name: 'app_reset_password', methods: ['GET', 'POST'])]
    public function reset(string $token, Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->findValidByResetToken($token);

        if (null === $user) {
            $this->addFlash('danger', 'resetPassword.linkUngueltig');

            return $this->redirectToRoute('app_forgot_password_request');
        }

        $error = null;

        if ($request->isMethod('POST') && $this->isCsrfTokenValid('reset_password', (string) $request->request->get('_token'))) {
            $password = (string) $request->request->get('password');
            $repeatPassword = (string) $request->request->get('repeat_password');

            if ('' === $password || mb_strlen($password) < 6) {
                $error = 'resetPassword.passwortZuKurz';
            } elseif ($password !== $repeatPassword) {
                $error = 'resetPassword.passwoerterStimmenNichtUeberein';
            } else {
                $user->setPassword($userPasswordHasher->hashPassword($user, $password));
                $user->setResetToken(null);
                $user->setResetTokenExpiresAt(null);

                $entityManager->flush();

                $this->addFlash('success', 'resetPassword.erfolgreich');

                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('security/reset_password.html.twig', [
            'token' => $token,
            'error' => $error,
        ]);
    }
}
