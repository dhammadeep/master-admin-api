<?php

namespace App\Http\Modules\CaseValidation\Repositories\RepoInterface;

interface ScheduledVisitDetailsRepoInterface
{
    public function getScheduledVisitDetails(int $caseId);
}
