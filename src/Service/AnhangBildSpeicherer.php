<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AnhangBildSpeicherer
{
    public function __construct(
        #[Autowire('%kernel.project_dir%/public/uploads/anhaenge')]
        private readonly string $zielverzeichnis,
    ) {
    }

    public function speichern(UploadedFile $datei): string
    {
        $dateiname = bin2hex(random_bytes(8)).'.'.($datei->guessExtension() ?? 'bin');
        $datei->move($this->zielverzeichnis, $dateiname);

        return '/uploads/anhaenge/'.$dateiname;
    }
}
