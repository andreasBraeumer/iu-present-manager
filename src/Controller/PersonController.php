<?php

namespace App\Controller;

use App\Entity\Person;
use App\Entity\User;
use App\Enum\GeschenkStatus;
use App\Form\PersonType;
use App\Repository\PersonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/personen')]
final class PersonController extends AbstractController
{
    #[Route('', name: 'app_person_index', methods: ['GET'])]
    public function index(PersonRepository $personRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->render('person/index.html.twig', [
            'personen' => $personRepository->findAllForUser($user),
        ]);
    }

    #[Route('/neu', name: 'app_person_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $person = new Person();
        $person->setUser($user);
        $person->setUsername($user->getUsername());
        $person->setErstelltAm(new \DateTimeImmutable());

        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($person);
            $entityManager->flush();

            $this->addFlash('success', 'personen.gespeichert');

            return $this->redirectToRoute('app_person_show', ['id' => $person->getId()]);
        }

        return $this->render('person/new.html.twig', [
            'person' => $person,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_person_show', methods: ['GET'])]
    public function show(Person $person): Response
    {
        $this->denyAccessUnlessOwner($person);

        $ideen = [];
        $vergangeneGeschenke = [];
        foreach ($person->getGeschenke() as $geschenk) {
            if (GeschenkStatus::Verschenkt === $geschenk->getStatus()) {
                $vergangeneGeschenke[] = $geschenk;
            } else {
                $ideen[] = $geschenk;
            }
        }

        return $this->render('person/show.html.twig', [
            'person' => $person,
            'ideen' => $ideen,
            'vergangeneGeschenke' => $vergangeneGeschenke,
        ]);
    }

    #[Route('/{id}/bearbeiten', name: 'app_person_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Person $person, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessOwner($person);

        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'personen.gespeichert');

            return $this->redirectToRoute('app_person_show', ['id' => $person->getId()]);
        }

        return $this->render('person/edit.html.twig', [
            'person' => $person,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/loeschen', name: 'app_person_delete', methods: ['POST'])]
    public function delete(Request $request, Person $person, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessOwner($person);

        if ($this->isCsrfTokenValid('submit', (string) $request->request->get('_token'))) {
            $entityManager->remove($person);
            $entityManager->flush();

            $this->addFlash('success', 'personen.geloescht');
        }

        return $this->redirectToRoute('app_person_index');
    }

    private function denyAccessUnlessOwner(Person $person): void
    {
        if ($person->getUser() !== $this->getUser()) {
            throw $this->createNotFoundException();
        }
    }
}
