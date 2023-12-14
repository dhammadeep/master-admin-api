<?php

namespace App\Http\Modules\CaseValidation\Enums;

enum CaseValidationStatusEnum: string
{
    case PENDING = 'PENDING';
    case DOCUMENT_SUBMITTED = 'DOCUMENT_SUBMITTED';
    case VALIDATION_DONE = 'VALIDATION_DONE';
    case VALIDATION_FAILED = 'VALIDATION_FAILED';
    case VERIFICATION_DONE = 'VERIFICATION_DONE';
    case VERIFICATION_FAILED = 'VERIFICATION_FAILED';
    case EXPIRED = 'EXPIRED';
}
