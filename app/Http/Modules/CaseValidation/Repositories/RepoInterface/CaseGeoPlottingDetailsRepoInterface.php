<?php

namespace App\Http\Modules\CaseValidation\Repositories\RepoInterface;

interface CaseGeoPlottingDetailsRepoInterface
{
    public function getGeoPlottingDetails(int $caseId);
}
