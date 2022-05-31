<?php

namespace App\Domain\Clippings;

enum Type: string
{
    case NOTE = 'Note';
    case HIGHLIGHT = 'Highlight';
}
