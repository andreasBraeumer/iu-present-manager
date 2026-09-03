<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:benachrichtigung:taeglich',
    description: 'Führt die Weihnachts- und Geburtstags-Benachrichtigungen in einem Aufruf aus.',
)]
class TaeglicheBenachrichtigungenCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $anwendung = $this->getApplication();

        $weihnachten = $anwendung->find('app:benachrichtigung:weihnachtsstatus')->run($input, $output);
        $geburtstage = $anwendung->find('app:benachrichtigung:geburtstage')->run($input, $output);

        return max($weihnachten, $geburtstage);
    }
}
