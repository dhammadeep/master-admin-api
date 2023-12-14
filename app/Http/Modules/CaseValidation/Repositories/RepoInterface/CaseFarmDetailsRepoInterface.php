<?php

namespace App\Http\Modules\CaseValidation\Repositories\RepoInterface;

interface CaseFarmDetailsRepoInterface
{
    public function getLandDocFormDetails(int $caseId);
}
