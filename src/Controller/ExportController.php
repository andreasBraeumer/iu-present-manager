<?php

namespace App\Controller;

use App\Entity\User;
use App\Enum\GeschenkStatus;
use App\Repository\PersonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ExportController extends AbstractController
{
    #[Route('/export', name: 'app_export_html', methods: ['GET'])]
    public function html(PersonRepository $personRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $personen = [];
        foreach ($personRepository->findAllForUser($user) as $person) {
            $ideen = [];
            $vergangeneGeschenke = [];
            foreach ($person->getGeschenke() as $geschenk) {
                if (GeschenkStatus::Verschenkt === $geschenk->getStatus()) {
                    $vergangeneGeschenke[] = $geschenk;
                } else {
                    $ideen[] = $geschenk;
                }
            }

            $personen[] = [
                'person' => $person,
                'ideen' => $ideen,
                'vergangeneGeschenke' => $vergangeneGeschenke,
            ];
        }

        return $this->render('export/index.html.twig', [
            'personen' => $personen,
        ]);
    }
}
