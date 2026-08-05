<?php

namespace App\Enums;

enum ReclamationStatut: string
{
    case Submitted = 'submitted';
    case UnderReview = 'under_review';
    case Accepted = 'accepted';
    case Rejected = 'rejected';
    case Resolved = 'resolved';
    case Closed = 'closed';
}
