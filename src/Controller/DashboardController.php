<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\BenachrichtigungRepository;
use App\Repository\PersonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->redirectToRoute('app_dashboard');
    }

    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(PersonRepository $personRepository, BenachrichtigungRepository $benachrichtigungRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->render('dashboard/index.html.twig', [
            'geburtstage' => $personRepository->findGeburtstageImNaechstenMonat($user),
            'benachrichtigungen' => $benachrichtigungRepository->findUngelesenForUser($user),
        ]);
    }
}
