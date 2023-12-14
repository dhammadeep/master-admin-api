<?php

namespace App\Http\Modules\CaseValidation\Repositories\RepoInterface;

interface CaseGeoTagRepoInterface
{
    public function findByCaseIdCached(int $caseId);
    public function findByCaseId(int $caseId);
}
