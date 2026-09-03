<?php

namespace App\Service;

use App\Entity\Anlass;
use Doctrine\ORM\EntityManagerInterface;

class StandardAnlaesse
{
    private const BEZEICHNUNGEN = ['Geburtstag', 'Weihnachten'];

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function sicherstellen(): void
    {
        $repository = $this->entityManager->getRepository(Anlass::class);
        $angelegt = false;

        foreach (self::BEZEICHNUNGEN as $bezeichnung) {
            if (null !== $repository->findOneBy(['bezeichnung' => $bezeichnung])) {
                continue;
            }

            $anlass = new Anlass();
            $anlass->setBezeichnung($bezeichnung);
            $anlass->setIstStandard(true);
            $anlass->setWiederkehrend(true);

            $this->entityManager->persist($anlass);
            $angelegt = true;
        }

        if ($angelegt) {
            $this->entityManager->flush();
        }
    }
}
