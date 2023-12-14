<?php

namespace App\Http\Modules\CaseValidation\Repositories\RepoInterface;

interface CaseGeoPlotRepoInterface
{
    public function findByCaseIdCached(int $caseId);
    public function findByCaseId(int $caseId);
}
