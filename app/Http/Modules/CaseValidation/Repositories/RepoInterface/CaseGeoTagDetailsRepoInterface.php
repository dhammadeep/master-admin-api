<?php

namespace App\Http\Modules\CaseValidation\Repositories\RepoInterface;

interface CaseGeoTagDetailsRepoInterface
{
    public function getGeoTagDetails(int $caseId);
}
