<?php

namespace App\Controller;

use App\Entity\Benachrichtigung;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BenachrichtigungController extends AbstractController
{
    #[Route('/benachrichtigungen/{id}/gelesen', name: 'app_benachrichtigung_gelesen', methods: ['POST'])]
    public function markGelesen(Request $request, Benachrichtigung $benachrichtigung, EntityManagerInterface $entityManager): Response
    {
        if ($benachrichtigung->getPerson()?->getUser() !== $this->getUser()) {
            throw $this->createNotFoundException();
        }

        if ($this->isCsrfTokenValid('submit', (string) $request->request->get('_token'))) {
            $benachrichtigung->setGelesen(true);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dashboard');
    }
}
