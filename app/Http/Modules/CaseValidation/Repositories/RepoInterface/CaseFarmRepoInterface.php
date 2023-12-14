<?php

namespace App\Http\Modules\CaseValidation\Repositories\RepoInterface;

interface CaseFarmRepoInterface
{
    public function findByCaseIdCached(int $caseId);
    public function findByCaseId(int $caseId);
}
