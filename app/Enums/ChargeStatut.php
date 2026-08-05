<?php

namespace App\Enums;

enum ChargeStatut: string
{
    case Pending = 'pending';
    case Paid = 'paid';
    case Overdue = 'overdue';
}
