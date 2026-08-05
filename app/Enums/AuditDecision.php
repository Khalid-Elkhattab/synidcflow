<?php

namespace App\Enums;

enum AuditDecision: string
{
    case Accepted = 'accepted';
    case Rejected = 'rejected';
    case Review = 'review';
}
