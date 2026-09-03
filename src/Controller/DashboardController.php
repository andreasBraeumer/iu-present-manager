<?php

namespace App\Controller;

use App\Entity\Geschenk;
use App\Entity\User;
use App\Enum\GeschenkStatus;
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

        $geburtstage = [];
        foreach ($personRepository->findGeburtstageImNaechstenMonat($user) as $person) {
            $ideen = array_filter(
                $person->getGeschenke()->toArray(),
                static fn (Geschenk $geschenk) => GeschenkStatus::Verschenkt !== $geschenk->getStatus(),
            );

            $geburtstage[] = [
                'person' => $person,
                'ideen' => array_values($ideen),
            ];
        }

        return $this->render('dashboard/index.html.twig', [
            'geburtstage' => $geburtstage,
            'benachrichtigungen' => $benachrichtigungRepository->findUngelesenForUser($user),
        ]);
    }
}
