<?php

namespace App\Http\Modules\CaseValidation\Enums;

enum DocWidget: int
{
    case BASIC_KYC_DOC = 1;
    case COMPANY_DOC = 2;
    case LAND_DOC = 3;
    case WAREHOUSE_DOC = 4;
    case BANK_ACCOUNT_DOC = 5;
}
