<?php

namespace App\Http\Modules\CaseValidation\Repositories\RepoInterface;

use App\Http\Modules\CaseValidation\Requests\FarmFormRequest;

interface FarmRepoInterface
{
    public function findByIdCached(int $farmId);
    public function findById(int $farmId);
    public function updateFarmDetails(FarmFormRequest $farmFormRequest,int $farmId);
}
