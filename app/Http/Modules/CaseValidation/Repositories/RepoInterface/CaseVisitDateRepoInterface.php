<?php

namespace App\Http\Modules\CaseValidation\Repositories\RepoInterface;

use App\Http\Modules\CaseValidation\Requests\CaseVisitDateReschedulingRequest;

interface CaseVisitDateRepoInterface
{
    public function rescheduling(CaseVisitDateReschedulingRequest $caseVisitDateReschedulingRequest,$caseVisitId);
}
