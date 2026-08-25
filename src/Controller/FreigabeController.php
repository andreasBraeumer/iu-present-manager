<?php

namespace App\Controller;

use App\Entity\Freigabe;
use App\Entity\Person;
use App\Enum\GeschenkStatus;
use App\Repository\FreigabeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FreigabeController extends AbstractController
{
    #[Route('/personen/{person}/freigabe/erstellen', name: 'app_freigabe_create', methods: ['POST'])]
    public function create(Request $request, Person $person, EntityManagerInterface $entityManager): Response
    {
        if ($person->getUser() !== $this->getUser()) {
            throw $this->createNotFoundException();
        }

        if ($this->isCsrfTokenValid('submit', (string) $request->request->get('_token'))) {
            $freigabe = new Freigabe();
            $freigabe->setPerson($person);
            $freigabe->setToken(bin2hex(random_bytes(32)));
            $freigabe->setErstelltAm(new \DateTimeImmutable());
            $freigabe->setAblaufAm((new \DateTimeImmutable())->modify('+30 days'));

            $entityManager->persist($freigabe);
            $entityManager->flush();

            $this->addFlash('success', 'freigabe.erstellt');
        }

        return $this->redirectToRoute('app_person_show', ['id' => $person->getId()]);
    }

    #[Route('/freigabe/{token}', name: 'app_freigabe_show', methods: ['GET'])]
    public function show(string $token, FreigabeRepository $freigabeRepository): Response
    {
        $freigabe = $freigabeRepository->findValidByToken($token);

        if (null === $freigabe) {
            throw $this->createNotFoundException();
        }

        $person = $freigabe->getPerson();

        $ideen = array_filter(
            $person->getGeschenke()->toArray(),
            static fn ($geschenk) => GeschenkStatus::Verschenkt !== $geschenk->getStatus(),
        );

        return $this->render('freigabe/show.html.twig', [
            'person' => $person,
            'ideen' => $ideen,
        ]);
    }

    #[Route('/freigaben/{id}/loeschen', name: 'app_freigabe_delete', methods: ['POST'])]
    public function delete(Request $request, Freigabe $freigabe, EntityManagerInterface $entityManager): Response
    {
        if ($freigabe->getPerson()->getUser() !== $this->getUser()) {
            throw $this->createNotFoundException();
        }

        if ($this->isCsrfTokenValid('submit', (string) $request->request->get('_token'))) {
            $entityManager->remove($freigabe);
            $entityManager->flush();

            $this->addFlash('success', 'freigabe.widerrufen');
        }

        return $this->redirectToRoute('app_person_show', ['id' => $freigabe->getPerson()->getId()]);
    }
}
