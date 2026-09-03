<?php

namespace App\Controller;

use App\Entity\Anhang;
use App\Entity\Geschenk;
use App\Enum\AnhangTyp;
use App\Form\AnhangType;
use App\Service\AnhangBildSpeicherer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AnhangController extends AbstractController
{
    #[Route('/geschenke/{geschenk}/anhaenge/neu', name: 'app_anhang_new', methods: ['POST'])]
    public function new(Request $request, Geschenk $geschenk, EntityManagerInterface $entityManager, AnhangBildSpeicherer $anhangBildSpeicherer): Response
    {
        $this->denyAccessUnlessOwner($geschenk);

        $anhang = new Anhang();
        $anhang->setGeschenk($geschenk);

        $form = $this->createForm(AnhangType::class, $anhang);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bilddatei = $form->get('bilddatei')->getData();

            if (null !== $bilddatei) {
                $anhang->setTyp(AnhangTyp::Bild);
                $anhang->setInhalt($anhangBildSpeicherer->speichern($bilddatei));
            }

            if ($anhang->getInhalt()) {
                $entityManager->persist($anhang);
                $entityManager->flush();
            } else {
                $this->addFlash('danger', 'anhaenge.ungueltig');
            }
        } else {
            $this->addFlash('danger', 'anhaenge.ungueltig');
        }

        return $this->redirectToRoute('app_geschenk_edit', ['id' => $geschenk->getId()]);
    }

    #[Route('/anhaenge/{id}/loeschen', name: 'app_anhang_delete', methods: ['POST'])]
    public function delete(Request $request, Anhang $anhang, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessOwner($anhang->getGeschenk());
        $geschenkId = $anhang->getGeschenk()->getId();

        if ($this->isCsrfTokenValid('submit', (string) $request->request->get('_token'))) {
            $entityManager->remove($anhang);
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
