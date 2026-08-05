<?php

namespace App\Enums;

enum ReclamationPriorite: string
{
    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';
    case Urgent = 'urgent';
}
