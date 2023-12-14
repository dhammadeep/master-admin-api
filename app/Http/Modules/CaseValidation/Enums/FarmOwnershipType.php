<?php

namespace App\Http\Modules\CaseValidation\Enums;

enum FarmOwnershipType: string
{
    case OWNED = 'OWNED';
    case LEASED = 'LEASED';

}
