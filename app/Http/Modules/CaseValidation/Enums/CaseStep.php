<?php

namespace App\Http\Modules\CaseValidation\Enums;

enum CaseStep: string
{
    case BASIC_KYC = 'user_status';
    case LAND_KYC = 'farm_status';
    case WAREHOUSE_KYC = 'case_warehouse_status';
    case COMPANY_KYC = 'company_status';
    case BANK_ACCOUNT_KYC = 'bank_account_status';
    case GEO_TAG = 'geo_tag_status';
    case GEO_PLOT = 'geo_plot_status';

}
