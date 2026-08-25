<?php

namespace App\Controller;

use App\Entity\Anlass;
use App\Form\AnlassType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/anlaesse')]
final class AnlassController extends AbstractController
{
    #[Route('', name: 'app_anlass_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $anlaesse = $entityManager->getRepository(Anlass::class)->findBy([], ['bezeichnung' => 'ASC']);

        return $this->render('anlass/index.html.twig', [
            'anlaesse' => $anlaesse,
        ]);
    }

    #[Route('/neu', name: 'app_anlass_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $anlass = new Anlass();
        $anlass->setIstStandard(false);

        $form = $this->createForm(AnlassType::class, $anlass);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($anlass);
            $entityManager->flush();

            $this->addFlash('success', 'anlaesse.gespeichert');

            return $this->redirectToRoute('app_anlass_index');
        }

        return $this->render('anlass/new.html.twig', [
            'anlass' => $anlass,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/bearbeiten', name: 'app_anlass_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Anlass $anlass, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AnlassType::class, $anlass);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'anlaesse.gespeichert');

            return $this->redirectToRoute('app_anlass_index');
        }

        return $this->render('anlass/edit.html.twig', [
            'anlass' => $anlass,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/loeschen', name: 'app_anlass_delete', methods: ['POST'])]
    public function delete(Request $request, Anlass $anlass, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isCsrfTokenValid('submit', (string) $request->request->get('_token'))) {
            return $this->redirectToRoute('app_anlass_index');
        }

        if ($anlass->istStandard()) {
            $this->addFlash('danger', 'anlaesse.standardNichtLoeschbar');

            return $this->redirectToRoute('app_anlass_index');
        }

        $entityManager->remove($anlass);
        $entityManager->flush();

        $this->addFlash('success', 'anlaesse.geloescht');

        return $this->redirectToRoute('app_anlass_index');
    }
}
