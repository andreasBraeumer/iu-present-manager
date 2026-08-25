<?php

namespace App\Enum;

enum GeschenkStatus: string
{
    case Idee = 'idee';
    case Geplant = 'geplant';
    case Besorgt = 'besorgt';
    case Verschenkt = 'verschenkt';
}
