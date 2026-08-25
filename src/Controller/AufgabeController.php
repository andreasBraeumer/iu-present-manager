<?php

namespace App\Controller;

use App\Entity\Aufgabe;
use App\Entity\Geschenk;
use App\Form\AufgabeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AufgabeController extends AbstractController
{
    #[Route('/geschenke/{geschenk}/aufgaben/neu', name: 'app_aufgabe_new', methods: ['POST'])]
    public function new(Request $request, Geschenk $geschenk, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessOwner($geschenk);

        $aufgabe = new Aufgabe();
        $aufgabe->setGeschenk($geschenk);
        $aufgabe->setErledigt(false);

        $form = $this->createForm(AufgabeType::class, $aufgabe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($aufgabe);
            $entityManager->flush();
        } else {
            $this->addFlash('danger', 'aufgaben.ungueltig');
        }

        return $this->redirectToRoute('app_geschenk_edit', ['id' => $geschenk->getId()]);
    }

    #[Route('/aufgaben/{id}/erledigt', name: 'app_aufgabe_toggle', methods: ['POST'])]
    public function toggle(Request $request, Aufgabe $aufgabe, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessOwner($aufgabe->getGeschenk());

        if ($this->isCsrfTokenValid('submit', (string) $request->request->get('_token'))) {
            $aufgabe->setErledigt(!$aufgabe->isErledigt());
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_geschenk_edit', ['id' => $aufgabe->getGeschenk()->getId()]);
    }

    #[Route('/aufgaben/{id}/loeschen', name: 'app_aufgabe_delete', methods: ['POST'])]
    public function delete(Request $request, Aufgabe $aufgabe, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessOwner($aufgabe->getGeschenk());
        $geschenkId = $aufgabe->getGeschenk()->getId();

        if ($this->isCsrfTokenValid('submit', (string) $request->request->get('_token'))) {
            $entityManager->remove($aufgabe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_geschenk_edit', ['id' => $geschenkId]);
    }

    private function denyAccessUnlessOwner(?Geschenk $geschenk): void
    {
        if (null === $geschenk || $geschenk->getPerson()?->getUser() !== $this->getUser()) {
            throw $this->createNotFoundException();
        }
    }
}
