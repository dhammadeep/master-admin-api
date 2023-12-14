<?php

namespace App\Http\Modules\Cases\Repositories\RepoInterface;

interface CasesRepoInterface
{
    public function findById(int $caseId);
    public function rejectCase(int $rejectionReasonId,int $caseId);
}
