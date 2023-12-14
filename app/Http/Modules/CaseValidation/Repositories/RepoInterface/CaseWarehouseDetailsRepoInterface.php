<?php

namespace App\Http\Modules\CaseValidation\Repositories\RepoInterface;

interface CaseWarehouseDetailsRepoInterface
{
    public function getWarehouseDocFormDetails(int $caseId);
}
