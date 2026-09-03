<?php

namespace App\Controller;

use App\Entity\Anhang;
use App\Entity\Geschenk;
use App\Entity\Person;
use App\Enum\AnhangTyp;
use App\Enum\GeschenkStatus;
use App\Form\AnhangType;
use App\Form\AufgabeType;
use App\Form\GeschenkType;
use App\Service\AnhangBildSpeicherer;
use App\Service\StandardAnlaesse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GeschenkController extends AbstractController
{
    #[Route('/personen/{person}/geschenke/neu', name: 'app_geschenk_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        Person $person,
        EntityManagerInterface $entityManager,
        StandardAnlaesse $standardAnlaesse,
        AnhangBildSpeicherer $anhangBildSpeicherer,
    ): Response {
        $this->denyAccessUnlessOwner($person);
        $standardAnlaesse->sicherstellen();

        $geschenk = new Geschenk();
        $geschenk->setPerson($person);
        $geschenk->setStatus(GeschenkStatus::Idee);
        $geschenk->setErstelltAm(new \DateTimeImmutable());

        if ($titel = $request->query->get('titel')) {
            $geschenk->setTitel((string) $titel);
        }
        if ($beschreibung = $request->query->get('beschreibung')) {
            $geschenk->setBeschreibung((string) $beschreibung);
        }
        $geschenk->setAutomatischGeneriert($request->query->getBoolean('automatisch'));

        $form = $this->createForm(GeschenkType::class, $geschenk);
        $form->handleRequest($request);

        $anhangForm = $this->createFormBuilder(null, ['csrf_protection' => false])
            ->add('typ', EnumType::class, [
                'class' => AnhangTyp::class,
                'label' => 'anhaenge.feld.typ',
                'choice_label' => fn (AnhangTyp $typ) => 'anhaenge.typ.'.$typ->value,
                'required' => false,
            ])
            ->add('inhalt', TextType::class, [
                'label' => 'anhaenge.feld.inhalt',
                'required' => false,
            ])
            ->add('bilddatei', FileType::class, [
                'label' => 'anhaenge.feld.bilddatei',
                'required' => false,
            ])
            ->getForm()
        ;
        $anhangForm->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($geschenk);

            $bilddatei = $anhangForm->get('bilddatei')->getData();
            $inhalt = $anhangForm->get('inhalt')->getData();

            if (null !== $bilddatei || $inhalt) {
                $anhang = new Anhang();
                $anhang->setGeschenk($geschenk);

                if (null !== $bilddatei) {
                    $anhang->setTyp(AnhangTyp::Bild);
                    $anhang->setInhalt($anhangBildSpeicherer->speichern($bilddatei));
                } else {
                    $anhang->setTyp($anhangForm->get('typ')->getData() ?? AnhangTyp::Text);
                    $anhang->setInhalt($inhalt);
                }

                $entityManager->persist($anhang);
            }

            $entityManager->flush();

            $this->addFlash('success', 'geschenke.gespeichert');

            return $this->redirectToRoute('app_geschenk_edit', ['id' => $geschenk->getId()]);
        }

        return $this->render('geschenk/new.html.twig', [
            'person' => $person,
            'form' => $form,
            'anhangForm' => $anhangForm,
        ]);
    }

    #[Route('/geschenke/{id}/bearbeiten', name: 'app_geschenk_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Geschenk $geschenk,
        EntityManagerInterface $entityManager,
    ): Response {
        $this->denyAccessUnlessOwner($geschenk);

        $form = $this->createForm(GeschenkType::class, $geschenk);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'geschenke.gespeichert');

            return $this->redirectToRoute('app_geschenk_edit', ['id' => $geschenk->getId()]);
        }

        $aufgabeForm = $this->createForm(AufgabeType::class);
        $anhangForm = $this->createForm(AnhangType::class);

        return $this->render('geschenk/edit.html.twig', [
            'geschenk' => $geschenk,
            'form' => $form,
            'aufgabeForm' => $aufgabeForm,
            'anhangForm' => $anhangForm,
        ]);
    }

    #[Route('/geschenke/{id}/loeschen', name: 'app_geschenk_delete', methods: ['POST'])]
    public function delete(Request $request, Geschenk $geschenk, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessOwner($geschenk);

        $personId = $geschenk->getPerson()->getId();

        if ($this->isCsrfTokenValid('submit', (string) $request->request->get('_token'))) {
            $entityManager->remove($geschenk);
            $entityManager->flush();

            $this->addFlash('success', 'geschenke.geloescht');
        }

        return $this->redirectToRoute('app_person_show', ['id' => $personId]);
    }

    private function denyAccessUnlessOwner(Geschenk|Person $subject): void
    {
        $person = $subject instanceof Person ? $subject : $subject->getPerson();

        if (null === $person || $person->getUser() !== $this->getUser()) {
            throw $this->createNotFoundException();
        }
    }
}
